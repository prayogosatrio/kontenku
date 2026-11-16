@extends('layouts.app')

@section('title', 'Daftar Platform')

@section('content')
    <h1>Daftar Platform</h1>
    <p class="subtitle">Platform media sosial tempat konten ditayangkan. Hanya admin yang bisa mengelola halaman ini.</p>

    <div class="toolbar">
        <a href="{{ route('platforms.create') }}" class="btn">+ Tambah Platform</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Platform</th>
                <th>Deskripsi</th>
                <th>Jumlah Post</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($platforms as $platform)
                <tr>
                    <td>{{ $platforms->firstItem() + $loop->index }}</td>
                    <td>{{ $platform->nama_platform }}</td>
                    <td>{{ $platform->deskripsi ?? '-' }}</td>
                    <td>{{ $platform->posts_count }}</td>
                    <td class="aksi">
                        <a href="{{ route('platforms.edit', $platform->id) }}">Edit</a>
                        |
                        <form class="inline" action="{{ route('platforms.destroy', $platform->id) }}" method="POST" onsubmit="return confirm('Hapus platform ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada data platform.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $platforms->links() }}
@endsection
