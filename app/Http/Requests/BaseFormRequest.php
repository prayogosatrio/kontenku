<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Induk semua Form Request: pesan error berbahasa Indonesia ditulis sekali di sini
 * (pakai placeholder :attribute), lalu tiap Form Request cukup mengisi rules() dan attributes().
 */
abstract class BaseFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa angka.',
            'email' => 'Format :attribute tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after_or_equal' => ':attribute tidak boleh sebelum :date.',
        ];
    }
}
