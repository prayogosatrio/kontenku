<?php

namespace App\Http\Requests;

class StorePlatformRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'nama_platform' => 'required|string|max:50|unique:platforms,nama_platform',
            'deskripsi' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return ['nama_platform' => 'Nama platform', 'deskripsi' => 'Deskripsi'];
    }
}
