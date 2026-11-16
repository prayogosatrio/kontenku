@extends('layouts.app')

@section('title', 'Jadwal Konten')

@section('content')
    <h1>Jadwal Konten</h1>
    <p class="subtitle">Post yang sudah dijadwalkan, urut dari tanggal tayang terdekat. Data diambil dari <code>GET /api/posts?status=dijadwalkan</code>, bukan query database langsung.</p>

    @unless ($apiAktif)
        @include('partials.api-mati')
    @endunless

    <table>
        <thead>
            <tr>
                <th>Tanggal Tayang</th>
                <th>Judul</th>
                <th>Klien</th>
                <th>Campaign</th>
                <th>Platform</th>
                <th>Format</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- $posts di sini array hasil decode JSON, bukan Collection Eloquent: diakses dengan notasi array --}}
            @forelse ($posts as $post)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($post['tanggal_tayang'])->translatedFormat('d M Y') }}</td>
                    <td><a href="{{ route('posts.show', $post['id']) }}">{{ $post['judul'] }}</a></td>
                    <td>{{ $post['klien']['nama_brand'] }}</td>
                    <td>{{ $post['campaign']['nama_campaign'] }}</td>
                    <td>{{ $post['platform']['nama_platform'] }}</td>
                    <td>{{ ucfirst($post['format']) }}</td>
                    <td class="aksi">
                        <form class="inline" action="{{ route('posts.tayang', $post['id']) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-small">Tandai Tayang</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada post yang sedang dijadwalkan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination dibangun manual dari "meta" response API, karena $posts bukan objek Paginator --}}
    @if ($meta && $meta['last_page'] > 1)
        <nav class="pagination">
            @if ($meta['current_page'] > 1)
                <a href="{{ route('posts.jadwal', ['page' => $meta['current_page'] - 1]) }}">&laquo; Sebelumnya</a>
            @else
                <span class="pagination-disabled">&laquo; Sebelumnya</span>
            @endif
            <span class="pagination-current">Halaman {{ $meta['current_page'] }} dari {{ $meta['last_page'] }}</span>
            @if ($meta['current_page'] < $meta['last_page'])
                <a href="{{ route('posts.jadwal', ['page' => $meta['current_page'] + 1]) }}">Berikutnya &raquo;</a>
            @else
                <span class="pagination-disabled">Berikutnya &raquo;</span>
            @endif
        </nav>
    @endif
@endsection
