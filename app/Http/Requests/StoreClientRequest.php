<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreClientRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'nama_brand' => 'required|string|max:100',
            'industri' => 'required|string|max:100',
            'nama_kontak' => 'required|string|max:100',
            // Dipakai untuk store DAN update: saat update, email milik klien ini sendiri dikecualikan.
            'email' => ['required', 'email', 'max:100', Rule::unique('clients', 'email')->ignore($this->route('client'))],
            'nomor_telepon' => 'required|string|max:15',
            'status' => 'required|in:aktif,nonaktif',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_brand' => 'Nama brand',
            'industri' => 'Industri',
            'nama_kontak' => 'Nama kontak',
            'email' => 'Email',
            'nomor_telepon' => 'Nomor telepon',
            'status' => 'Status',
        ];
    }
}
