<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return ($user && $user->isAdmin()) || Session::get('role') === 'admin';
    }

    public function rules(): array
    {
        $targetUser = $this->route('user');
        $userId = $targetUser ? $targetUser->id : null;
        $passwordRule = $this->isMethod('post') ? ['required', 'string', 'min:6', 'confirmed'] : ['nullable', 'string', 'min:6', 'confirmed'];

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'name')->ignore($userId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'role' => ['required', Rule::enum(UserRole::class)],
            'password' => $passwordRule,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Username wajib diisi.',
            'name.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.Illuminate\Validation\Rules\Enum' => 'Role akun tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
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
