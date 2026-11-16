@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>
    <p class="subtitle">Ringkasan konten, diambil dari <code>GET /api/stats</code>.</p>

    @unless ($apiAktif)
        @include('partials.api-mati')
    @endunless

    <div class="stats-grid">
        <div class="stat-card">
            <div class="value">{{ $stats['total_klien'] }}</div>
            <div class="label">Total Klien</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $stats['campaign_berjalan'] }}</div>
            <div class="label">Campaign Berjalan</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $stats['post_dijadwalkan'] }}</div>
            <div class="label">Post Dijadwalkan</div>
        </div>
        <div class="stat-card">
            <div class="value">{{ $stats['post_tayang'] }}</div>
            <div class="label">Post Sudah Tayang</div>
        </div>
    </div>

    <p style="margin-top: 24px;">
        <a href="{{ route('posts.jadwal') }}" class="btn">Lihat Jadwal Konten</a>
        <a href="{{ route('campaigns.create') }}" class="btn btn-secondary">+ Buat Campaign</a>
    </p>
@endsection
