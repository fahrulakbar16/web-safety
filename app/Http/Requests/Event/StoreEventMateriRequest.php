<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventMateriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'urutan' => ['nullable', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,mp4,avi,mov', 'max:10240'],
            'type' => ['required', 'string', 'in:text,video'],
            'description' => ['nullable', 'string'],
            'attributes' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 1.',
            'name.required' => 'Nama materi wajib diisi.',
            'name.max' => 'Nama materi maksimal 255 karakter.',
            'file.file' => 'File harus berupa file.',
            'file.mimes' => 'File harus berformat: pdf, doc, docx, mp4, avi, atau mov.',
            'file.max' => 'Ukuran file maksimal 10MB.',
            'type.required' => 'Tipe materi wajib dipilih.',
            'type.in' => 'Tipe harus salah satu dari: text, video.',
        ];
    }
}

