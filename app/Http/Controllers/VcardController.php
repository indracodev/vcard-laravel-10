<?php

namespace App\Http\Controllers;

use App\Enums\VcardStatus;
use App\Helpers\VcardHelper;
use App\Http\Requests\VcardRequest;
use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;

class VcardController extends Controller
{
    public function index()
    {
        $vcards = Vcard::query()
            ->latest()
            ->get();

        return view('dashboard.index', compact('vcards'));
    }

    public function show(Request $request, string $link_name)
    {
        $vcard = Vcard::where('link_name', $link_name)->first();

        if (! $vcard || $vcard->status === VcardStatus::Inactive) {
            return redirect()->away('https://indracoglobal.com');
        }

        $ip = $request->ip();
        $sessionKey = 'viewed_vcard_'.$vcard->id.'_'.md5((string) $ip);

        if (! Session::has($sessionKey)) {
            $vcard->increment('views');
            Session::put($sessionKey, true);
        }

        return view('vcards.public', compact('vcard'));
    }

    public function downloadVcf(string $link_name)
    {
        $vcard = Vcard::where('link_name', $link_name)->first();

        if (! $vcard || $vcard->status === VcardStatus::Inactive) {
            return redirect()->away('https://indracoglobal.com');
        }

        $fileName = VcardHelper::getVcfFileName($vcard->full_name);
        $content = VcardHelper::buildVcardContent($vcard);

        return response($content, 200, [
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.addslashes($fileName).'"',
            'Cache-Control' => 'no-cache, must-revalidate',
        ]);
    }

    public function create(VcardRequest $request)
    {
        $uploadedPhoto = null;

        try {
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $uploadedPhoto = $this->uploadPhoto($request->file('photo'));
                $data['photo'] = $uploadedPhoto;
            }

            DB::transaction(function () use ($data): void {
                $vcard = Vcard::create($data);
                VcardHelper::generateVcard($vcard);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan dan file vCard dibuat!',
            ]);
        } catch (\Throwable $e) {
            $this->deletePhotoFile($uploadedPhoto);

            Log::error('Error creating vcard: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat menyimpan data vCard.']],
            ], 500);
        }
    }

    public function update(VcardRequest $request, Vcard $vcard)
    {
        $newPhoto = null;

        try {
            $data = $request->validated();
            $oldFilePath = public_path('storage/contact/'.VcardHelper::getVcfFileName($vcard->full_name));
            $oldPhoto = $vcard->photo;

            if ($request->hasFile('photo')) {
                $newPhoto = $this->uploadPhoto($request->file('photo'));
                $data['photo'] = $newPhoto;
            }

            DB::transaction(function () use ($vcard, $data, $oldFilePath): void {
                $vcard->update($data);

                $newFilePath = public_path('storage/contact/'.VcardHelper::getVcfFileName($vcard->full_name));
                if ($oldFilePath !== $newFilePath && is_file($oldFilePath)) {
                    @unlink($oldFilePath);
                }

                VcardHelper::generateVcard($vcard);
            });

            if ($newPhoto && $oldPhoto) {
                $this->deletePhotoFile($oldPhoto);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data dan file vCard berhasil diperbarui!',
            ]);
        } catch (\Throwable $e) {
            $this->deletePhotoFile($newPhoto);

            Log::error('Error updating vcard: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat memperbarui data vCard.']],
            ], 500);
        }
    }

    public function destroy(Vcard $vcard)
    {
        try {
            $photo = $vcard->photo;
            $vcfPath = public_path('storage/contact/'.VcardHelper::getVcfFileName($vcard->full_name));

            DB::transaction(function () use ($vcard): void {
                $vcard->delete();
            });

            $this->deletePhotoFile($photo);

            if (is_file($vcfPath)) {
                @unlink($vcfPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data dan file vCard berhasil dihapus!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error deleting vcard: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat menghapus data vCard.']],
            ], 500);
        }
    }

    private function uploadPhoto($image): string
    {
        $photoDir = public_path('storage/photos');
        if (! is_dir($photoDir)) {
            mkdir($photoDir, 0755, true);
        }

        $filename = uniqid('', true).'.webp';
        ImageManager::gd()
            ->read($image->getRealPath())
            ->scaleDown(600, 600)
            ->toWebp(85)
            ->save($photoDir.'/'.$filename);

        return $filename;
    }

    private function deletePhotoFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $photoPath = public_path('storage/photos/'.$filename);
        if (is_file($photoPath)) {
            @unlink($photoPath);
        }
    }
}
