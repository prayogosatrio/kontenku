<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return ClientResource::collection(Client::withCount('campaigns')->orderBy('nama_brand')->paginate(10));
    }

    public function show(string $id)
    {
        return new ClientResource(Client::withCount('campaigns')->findOrFail($id));
    }
}
