<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;

class StoreDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in controller via Gate
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'name'       => ['required', 'string', 'max:255'],
            'alamat'    => ['required', 'string'],
            'no_hp'     => ['required', 'string', 'max:20'],
            'no_ktp'    => ['required', 'string', 'max:20', 'unique:drivers,no_ktp'],
            'no_sim'    => ['nullable', 'string', 'max:20'],
            'foto_ktp'  => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'foto_sim'  => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'foto_diri' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'status'    => ['required', 'string', 'in:pending,active,inactive'],
            // User fields
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'username'  => ['required', 'string', 'max:255', 'unique:users,username'],
            'password'  => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_id.required' => 'Perusahaan wajib dipilih.',
            'company_id.exists'  => 'Perusahaan yang dipilih tidak valid.',
            'name.required'      => 'Nama wajib diisi.',
            'name.max'           => 'Nama maksimal 255 karakter.',
            'alamat.required'    => 'Alamat wajib diisi.',
            'no_hp.required'    => 'Nomor HP wajib diisi.',
            'no_hp.max'          => 'Nomor HP maksimal 20 karakter.',
            'no_ktp.required'   => 'Nomor KTP wajib diisi.',
            'no_ktp.max'         => 'Nomor KTP maksimal 20 karakter.',
            'no_ktp.unique'     => 'Nomor KTP sudah terdaftar.',
            'no_sim.max'         => 'Nomor SIM maksimal 20 karakter.',
            'foto_ktp.image'     => 'Foto KTP harus berupa gambar.',
            'foto_ktp.mimes'     => 'Foto KTP harus berformat: jpeg, jpg, png, gif, atau webp.',
            'foto_ktp.max'       => 'Ukuran foto KTP maksimal 2MB.',
            'foto_sim.image'     => 'Foto SIM harus berupa gambar.',
            'foto_sim.mimes'     => 'Foto SIM harus berformat: jpeg, jpg, png, gif, atau webp.',
            'foto_sim.max'       => 'Ukuran foto SIM maksimal 2MB.',
            'foto_diri.image'    => 'Foto diri harus berupa gambar.',
            'foto_diri.mimes'    => 'Foto diri harus berformat: jpeg, jpg, png, gif, atau webp.',
            'foto_diri.max'      => 'Ukuran foto diri maksimal 2MB.',
            'status.required'    => 'Status wajib dipilih.',
            'status.in'          => 'Status harus salah satu dari: pending, active, inactive.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Email harus berupa format email yang valid.',
            'email.unique'       => 'Email sudah digunakan.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
        ];
    }
}

