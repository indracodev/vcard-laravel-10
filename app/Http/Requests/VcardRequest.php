<?php

namespace App\Http\Requests;

use App\Enums\VcardStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VcardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() || (bool) session('login');
    }

    public function rules(): array
    {
        $vcard = $this->route('vcard');
        $vcardId = $vcard ? $vcard->id : null;
        $photoRule = $this->isMethod('post') ? 'required' : 'nullable';
        $reservedSlugs = ['login', 'logout', 'register', 'dashboard', 'vcards', 'rgstr-user', 'api', 'storage', 'assets', 'up'];

        return [
            'photo' => [$photoRule, 'image', 'mimes:webp,jpg,jpeg,png', 'max:4096'],
            'name' => ['required', 'string', 'max:255'],
            'full_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vcards', 'full_name')->ignore($vcardId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'mobile_phone' => ['required', 'string', 'regex:/^[0-9\s\+]+$/', 'min:10', 'max:30'],
            'link_name' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::notIn($reservedSlugs),
                Rule::unique('vcards', 'link_name')->ignore($vcardId),
            ],
            'status' => ['required', Rule::enum(VcardStatus::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Foto wajib diisi.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.mimes' => 'Format gambar hanya boleh webp, jpg, jpeg, atau png.',
            'photo.max' => 'Ukuran gambar maksimal 4MB.',
            'name.required' => 'Nama wajib diisi.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'full_name.unique' => 'Nama lengkap sudah digunakan.',
            'title.required' => 'Jabatan wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'mobile_phone.required' => 'Nomor HP wajib diisi.',
            'mobile_phone.regex' => 'Nomor HP hanya boleh mengandung angka, spasi, dan tanda tambah (+).',
            'mobile_phone.min' => 'Nomor HP minimal 10 karakter.',
            'link_name.required' => 'Link name wajib diisi.',
            'link_name.alpha_dash' => 'Link name hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'link_name.not_in' => 'Link name ini tidak dapat digunakan karena merupakan jalur sistem.',
            'link_name.unique' => 'Link name sudah digunakan.',
            'status.required' => 'Status wajib dipilih.',
            'status.Illuminate\Validation\Rules\Enum' => 'Status tidak valid.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422));
    }
}
