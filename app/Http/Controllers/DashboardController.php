<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Dashboard TIDAK query database langsung, tapi mengonsumsi REST API milik aplikasi ini sendiri.
     */
    public function index()
    {
        $stats = [
            'total_klien' => 0,
            'campaign_berjalan' => 0,
            'post_dijadwalkan' => 0,
            'post_tayang' => 0,
        ];
        $apiAktif = false;

        try {
            $response = Http::timeout(5)->get(config('services.internal_api.base_url').'/api/stats');

            if ($response->successful()) {
                $stats = array_merge($stats, $response->json());
                $apiAktif = true;
            }
        } catch (ConnectionException $e) {
            // Server API internal (port 8011) tidak bisa dihubungi: statistik tetap tampil nol.
        }

        return view('dashboard', compact('stats', 'apiAktif'));
    }
}
