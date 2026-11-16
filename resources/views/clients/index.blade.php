@extends('layouts.app')

@section('title', 'Daftar Klien')

@section('content')
    <h1>Daftar Klien</h1>
    <p class="subtitle">Brand yang media sosialnya dikelola.</p>

    <div class="toolbar">
        <a href="{{ route('clients.create') }}" class="btn">+ Tambah Klien</a>

        <form action="{{ route('clients.index') }}" method="GET" class="filter">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama brand...">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if (request('search'))
                <a href="{{ route('clients.index') }}">Reset</a>
            @endif
        </form>
    </div>

    @if (request('search'))
        <p>Hasil pencarian "<strong>{{ request('search') }}</strong>": {{ $clients->total() }} klien ditemukan.</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Brand</th>
                <th>Industri</th>
                <th>Kontak</th>
                <th>Campaign</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clients as $client)
                <tr>
                    <td>{{ $clients->firstItem() + $loop->index }}</td>
                    <td><a href="{{ route('clients.show', $client->id) }}">{{ $client->nama_brand }}</a></td>
                    <td>{{ $client->industri }}</td>
                    <td>{{ $client->nama_kontak }}<br><span class="muted">{{ $client->email }}</span></td>
                    <td>{{ $client->campaigns_count }}</td>
                    <td><span class="badge badge-{{ $client->status }}">{{ ucfirst($client->status) }}</span></td>
                    <td class="aksi">
                        <a href="{{ route('clients.show', $client->id) }}">Detail</a>
                        |
                        <a href="{{ route('clients.edit', $client->id) }}">Edit</a>
                        |
                        <form class="inline" action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirm('Hapus klien ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        @if (request('search'))
                            Tidak ada klien dengan nama "{{ request('search') }}".
                        @else
                            Belum ada data klien.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- appends(): parameter ?search= ikut terbawa saat pindah halaman --}}
    {{ $clients->appends(request()->query())->links() }}
@endsection
