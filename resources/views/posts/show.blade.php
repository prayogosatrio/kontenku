@extends('layouts.app')

@section('title', 'Detail Post')

@section('content')
    <p><a href="{{ route('posts.index') }}">&larr; Kembali ke daftar post</a></p>

    <h1>{{ $post->judul }}</h1>
    <p class="subtitle">Detail post.</p>

    <table class="info">
        <tr>
            <th>Klien</th>
            <td>{{ $post->campaign->client->nama_brand }}</td>
        </tr>
        <tr>
            <th>Campaign</th>
            <td><a href="{{ route('campaigns.show', $post->campaign->id) }}">{{ $post->campaign->nama_campaign }}</a></td>
        </tr>
        <tr>
            <th>PIC</th>
            <td>{{ $post->campaign->user->name }}</td>
        </tr>
        <tr>
            <th>Platform</th>
            <td>{{ $post->platform->nama_platform }}</td>
        </tr>
        <tr>
            <th>Format</th>
            <td>{{ ucfirst($post->format) }}</td>
        </tr>
        <tr>
            <th>Caption</th>
            {{-- {{ }} meng-escape isi caption (aman dari XSS); nl2br + e() mempertahankan baris baru --}}
            <td>{!! nl2br(e($post->caption)) !!}</td>
        </tr>
        <tr>
            <th>Tanggal Tayang (rencana)</th>
            <td>{{ $post->tanggal_tayang->translatedFormat('d M Y') }}</td>
        </tr>
        <tr>
            <th>Tanggal Ditayangkan</th>
            <td>{{ $post->tanggal_ditayangkan?->translatedFormat('d M Y') ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td><span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
        </tr>
    </table>

    <p style="margin-top: 14px;">
        @if ($post->status === 'dijadwalkan')
            <form class="inline" action="{{ route('posts.tayang', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit" class="btn">Tandai Tayang</button>
            </form>
        @endif
        @if ($post->status !== 'tayang')
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-secondary">Edit Post</a>
        @endif
    </p>
@endsection
