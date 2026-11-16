@extends('layouts.app')

@section('title', 'Detail Klien')

@section('content')
    <p><a href="{{ route('clients.index') }}">&larr; Kembali ke daftar klien</a></p>

    <h1>{{ $client->nama_brand }}</h1>
    <p class="subtitle">Detail klien dan seluruh campaign-nya.</p>

    <table class="info">
        <tr>
            <th>Industri</th>
            <td>{{ $client->industri }}</td>
        </tr>
        <tr>
            <th>Nama Kontak</th>
            <td>{{ $client->nama_kontak }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $client->email }}</td>
        </tr>
        <tr>
            <th>Nomor Telepon</th>
            <td>{{ $client->nomor_telepon }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge badge-{{ $client->status }}">{{ ucfirst($client->status) }}</span></td>
        </tr>
    </table>

    <p style="margin-top: 14px;"><a href="{{ route('clients.edit', $client->id) }}" class="btn btn-secondary">Edit Klien</a></p>

    <h2>Campaign</h2>
    <p class="muted">Diambil lewat relasi <code>$client->campaigns</code> (satu klien punya banyak campaign).</p>

    <table>
        <thead>
            <tr>
                <th>Campaign</th>
                <th>Periode</th>
                <th>PIC</th>
                <th>Jumlah Post</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($client->campaigns as $campaign)
                <tr>
                    <td><a href="{{ route('campaigns.show', $campaign->id) }}">{{ $campaign->nama_campaign }}</a></td>
                    <td>{{ $campaign->tanggal_mulai->translatedFormat('d M Y') }} - {{ $campaign->tanggal_selesai->translatedFormat('d M Y') }}</td>
                    <td>{{ $campaign->user->name }}</td>
                    <td>{{ $campaign->posts_count }}</td>
                    <td><span class="badge badge-{{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Klien ini belum punya campaign.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
