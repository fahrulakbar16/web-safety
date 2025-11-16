<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'durasi' => ['required', 'integer', 'min:1'],
            'jumlah_soal' => ['required', 'integer', 'min:1'],
            'minimal_score' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama ujian wajib diisi.',
            'name.max' => 'Nama ujian maksimal 255 karakter.',
            'durasi.required' => 'Durasi wajib diisi.',
            'durasi.integer' => 'Durasi harus berupa angka.',
            'durasi.min' => 'Durasi minimal 1 menit.',
            'jumlah_soal.required' => 'Jumlah soal wajib diisi.',
            'jumlah_soal.integer' => 'Jumlah soal harus berupa angka.',
            'jumlah_soal.min' => 'Jumlah soal minimal 1.',
            'minimal_score.required' => 'Minimal score wajib diisi.',
            'minimal_score.integer' => 'Minimal score harus berupa angka.',
            'minimal_score.min' => 'Minimal score minimal 0.',
            'minimal_score.max' => 'Minimal score maksimal 100.',
        ];
    }
}

