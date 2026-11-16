@extends('layouts.app')

@section('title', 'Daftar Post')

@section('content')
    <h1>Daftar Post</h1>
    <p class="subtitle">Semua konten dari seluruh campaign.</p>

    <div class="toolbar">
        <a href="{{ route('posts.create') }}" class="btn">+ Tambah Post</a>

        <form action="{{ route('posts.index') }}" method="GET" class="filter">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul...">
            <select name="platform_id">
                <option value="">Semua platform</option>
                @foreach ($platforms as $platform)
                    <option value="{{ $platform->id }}" @selected(request('platform_id') == $platform->id)>{{ $platform->nama_platform }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Semua status</option>
                @foreach (\App\Models\Post::STATUSES as $status)
                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            @if (request()->hasAny(['search', 'platform_id', 'status']))
                <a href="{{ route('posts.index') }}">Reset</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Klien / Campaign</th>
                <th>Platform</th>
                <th>Tanggal Tayang</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($posts as $post)
                <tr>
                    <td>{{ $posts->firstItem() + $loop->index }}</td>
                    <td><a href="{{ route('posts.show', $post->id) }}">{{ $post->judul }}</a><br><span class="muted">{{ ucfirst($post->format) }}</span></td>
                    <td>{{ $post->campaign->client->nama_brand }}<br><span class="muted">{{ $post->campaign->nama_campaign }}</span></td>
                    <td>{{ $post->platform->nama_platform }}</td>
                    <td>{{ $post->tanggal_tayang->translatedFormat('d M Y') }}</td>
                    <td><span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                    <td class="aksi">
                        @if ($post->status === 'dijadwalkan')
                            <form class="inline" action="{{ route('posts.tayang', $post->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-small">Tandai Tayang</button>
                            </form>
                            |
                        @endif
                        @if ($post->status !== 'tayang')
                            <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
                            |
                        @endif
                        <form class="inline" action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Hapus post ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada post yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $posts->appends(request()->query())->links() }}
@endsection
