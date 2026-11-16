<?php

namespace App\Http\Requests;

class StoreCampaignRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'nama_campaign' => 'required|string|max:150',
            'tujuan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:draft,berjalan,selesai',
        ];
    }

    public function attributes(): array
    {
        return [
            'client_id' => 'Klien',
            'nama_campaign' => 'Nama campaign',
            'tujuan' => 'Tujuan',
            'tanggal_mulai' => 'Tanggal mulai',
            'tanggal_selesai' => 'Tanggal selesai',
            'status' => 'Status',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);
    }
}
