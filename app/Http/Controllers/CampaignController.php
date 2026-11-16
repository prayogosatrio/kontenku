<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampaignRequest;
use App\Models\Campaign;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with(['client', 'user'])
            ->withCount('posts')
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->latest('tanggal_mulai')
            ->paginate(10);

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $clients = Client::where('status', 'aktif')->orderBy('nama_brand')->get();

        return view('campaigns.create', compact('clients'));
    }

    public function store(StoreCampaignRequest $request)
    {
        // user_id (PIC) tidak datang dari form, tapi dari akun yang sedang login.
        $campaign = Campaign::create($request->validated() + ['user_id' => auth()->id()]);

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', "Campaign \"{$campaign->nama_campaign}\" berhasil dibuat. Sekarang tambahkan post-nya.");
    }

    public function show(string $id)
    {
        $campaign = Campaign::with(['client', 'user', 'posts' => fn ($query) => $query->with('platform')->orderBy('tanggal_tayang')])
            ->findOrFail($id);

        return view('campaigns.show', compact('campaign'));
    }

    public function edit(string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $clients = Client::where('status', 'aktif')->orWhere('id', $campaign->client_id)->orderBy('nama_brand')->get();

        return view('campaigns.edit', compact('campaign', 'clients'));
    }

    public function update(StoreCampaignRequest $request, string $id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update($request->validated());

        return redirect()->route('campaigns.show', $campaign->id)
            ->with('success', "Campaign \"{$campaign->nama_campaign}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $campaign = Campaign::findOrFail($id);

        // Post harus dihapus dulu (foreign key), dan keduanya dibungkus transaction:
        // kalau salah satu gagal, tidak ada yang terhapus setengah-setengah.
        DB::transaction(function () use ($campaign) {
            $campaign->posts()->delete();
            $campaign->delete();
        });

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign beserta seluruh post-nya berhasil dihapus.');
    }
}
