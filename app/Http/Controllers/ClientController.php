<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::withCount('campaigns')
            ->when(request('search'), fn ($query, $search) => $query->where('nama_brand', 'like', "%{$search}%"))
            ->orderBy('nama_brand')
            ->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.index')
            ->with('success', "Klien \"{$client->nama_brand}\" berhasil ditambahkan.");
    }

    public function show(string $id)
    {
        // Eager loading bersarang: campaign + PIC + jumlah post, tanpa N+1 query.
        $client = Client::with(['campaigns' => fn ($query) => $query->with('user')->withCount('posts')->latest('tanggal_mulai')])
            ->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    public function edit(string $id)
    {
        $client = Client::findOrFail($id);

        return view('clients.edit', compact('client'));
    }

    public function update(StoreClientRequest $request, string $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        return redirect()->route('clients.index')
            ->with('success', "Klien \"{$client->nama_brand}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $client = Client::withCount('campaigns')->findOrFail($id);

        if ($client->campaigns_count > 0) {
            return redirect()->route('clients.index')
                ->with('error', "Klien \"{$client->nama_brand}\" tidak bisa dihapus karena masih punya {$client->campaigns_count} campaign.");
        }

        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Klien berhasil dihapus.');
    }
}
