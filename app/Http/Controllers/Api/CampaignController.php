<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::with(['client', 'user', 'posts.platform'])
            ->when($request->query('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest('tanggal_mulai')
            ->paginate(10);

        return CampaignResource::collection($campaigns);
    }

    public function show(string $id)
    {
        return new CampaignResource(Campaign::with(['client', 'user', 'posts.platform'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        // API tidak memakai session login, jadi user_id (PIC) wajib dikirim di body request.
        $validated = $request->validate([
            'client_id' => 'required|integer|exists:clients,id',
            'user_id' => 'required|integer|exists:users,id',
            'nama_campaign' => 'required|string|max:150',
            'tujuan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'client_id.required' => 'Klien (client_id) wajib diisi.',
            'client_id.exists' => 'Klien yang dipilih tidak valid.',
            'user_id.required' => 'PIC (user_id) wajib diisi.',
            'user_id.exists' => 'PIC yang dipilih tidak valid.',
            'nama_campaign.required' => 'Nama campaign wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $campaign = Campaign::create($validated);

        // refresh(): ambil ulang dari database supaya nilai default kolom status ("draft") ikut terbaca.
        $campaign->refresh()->load(['client', 'user', 'posts.platform']);

        return (new CampaignResource($campaign))->response()->setStatusCode(201);
    }
}
