<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Client;
use App\Models\Post;

class StatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_klien' => Client::count(),
            'campaign_berjalan' => Campaign::where('status', 'berjalan')->count(),
            'post_dijadwalkan' => Post::where('status', 'dijadwalkan')->count(),
            'post_tayang' => Post::where('status', 'tayang')->count(),
        ]);
    }
}
