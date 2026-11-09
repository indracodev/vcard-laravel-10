<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->latest()->get();

        return view('users.index', compact('users'));
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data): void {
                User::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Akun pengguna baru berhasil ditambahkan!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error creating user: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat menyimpan akun pengguna.']],
            ], 500);
        }
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            $currentUserId = Auth::id() ?? session('user_id');

            if ((int) $user->id === (int) $currentUserId && $data['role'] !== UserRole::Admin->value) {
                return response()->json([
                    'success' => false,
                    'errors' => ['role' => ['Anda tidak dapat menurunkan role Administrator pada akun Anda sendiri yang sedang aktif.']],
                ], 422);
            }

            if (empty($data['password'])) {
                unset($data['password']);
            }

            DB::transaction(function () use ($user, $data): void {
                $user->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data akun pengguna berhasil diperbarui!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error updating user: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat memperbarui akun pengguna.']],
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $currentUserId = Auth::id() ?? session('user_id');

            if ((int) $user->id === (int) $currentUserId) {
                return response()->json([
                    'success' => false,
                    'errors' => ['general' => ['Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.']],
                ], 422);
            }

            DB::transaction(function () use ($user): void {
                $user->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Akun pengguna berhasil dihapus!',
            ]);
        } catch (\Throwable $e) {
            Log::error('Error deleting user: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'errors' => ['general' => ['Terjadi kesalahan sistem saat menghapus akun pengguna.']],
            ], 500);
        }
    }
}
