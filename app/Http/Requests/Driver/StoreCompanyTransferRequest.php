<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCompanyTransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->hasRole('driver');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_company_id' => ['required', 'exists:companies,id'],
            'surat_pengunduran_diri' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'new_company_id.required' => 'Perusahaan baru wajib dipilih.',
            'new_company_id.exists' => 'Perusahaan yang dipilih tidak valid.',
            'surat_pengunduran_diri.required' => 'Surat pengunduran diri wajib diupload.',
            'surat_pengunduran_diri.file' => 'Surat pengunduran diri harus berupa file.',
            'surat_pengunduran_diri.mimes' => 'Surat pengunduran diri harus berformat: PDF, JPG, JPEG, atau PNG.',
            'surat_pengunduran_diri.max' => 'Ukuran surat pengunduran diri maksimal 5MB.',
            'notes.max' => 'Catatan maksimal 1000 karakter.',
        ];
    }
}
