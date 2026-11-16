@extends('layouts.app')

@section('title', 'Detail Campaign')

@section('content')
    <p><a href="{{ route('campaigns.index') }}">&larr; Kembali ke daftar campaign</a></p>

    <h1>{{ $campaign->nama_campaign }}</h1>
    <p class="subtitle">Detail campaign dan seluruh post di dalamnya.</p>

    <table class="info">
        <tr>
            <th>Klien</th>
            <td><a href="{{ route('clients.show', $campaign->client->id) }}">{{ $campaign->client->nama_brand }}</a></td>
        </tr>
        <tr>
            <th>PIC</th>
            <td>{{ $campaign->user->name }}</td>
        </tr>
        <tr>
            <th>Tujuan</th>
            <td>{{ $campaign->tujuan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Periode</th>
            <td>{{ $campaign->tanggal_mulai->translatedFormat('d M Y') }} s.d. {{ $campaign->tanggal_selesai->translatedFormat('d M Y') }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge badge-{{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span></td>
        </tr>
    </table>

    <p style="margin-top: 14px;"><a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-secondary">Edit Campaign</a></p>

    <h2>Post dalam Campaign Ini ({{ $campaign->posts->count() }})</h2>

    <div class="toolbar">
        <a href="{{ route('posts.create', ['campaign_id' => $campaign->id]) }}" class="btn">+ Tambah Post</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Platform</th>
                <th>Format</th>
                <th>Tanggal Tayang</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($campaign->posts as $post)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('posts.show', $post->id) }}">{{ $post->judul }}</a></td>
                    <td>{{ $post->platform->nama_platform }}</td>
                    <td>{{ ucfirst($post->format) }}</td>
                    <td>{{ $post->tanggal_tayang->translatedFormat('d M Y') }}</td>
                    <td><span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                    <td class="aksi">
                        @if ($post->status === 'dijadwalkan')
                            <form class="inline" action="{{ route('posts.tayang', $post->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-small">Tandai Tayang</button>
                            </form>
                        @endif
                        @if ($post->status !== 'tayang')
                            <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Campaign ini belum punya post.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
