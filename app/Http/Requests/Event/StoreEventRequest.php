<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'status' => ['required', 'string', 'in:active,inactive'],
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
            'name.required' => 'Nama event wajib diisi.',
            'name.max' => 'Nama event maksimal 255 karakter.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'image.image' => 'Gambar harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat: jpeg, jpg, png, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus salah satu dari: active, inactive.',
        ];
    }
}

