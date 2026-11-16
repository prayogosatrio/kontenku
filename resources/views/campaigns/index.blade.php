@extends('layouts.app')

@section('title', 'Daftar Campaign')

@section('content')
    <h1>Daftar Campaign</h1>
    <p class="subtitle">Satu campaign milik satu klien dan berisi banyak post.</p>

    <div class="toolbar">
        <a href="{{ route('campaigns.create') }}" class="btn">+ Buat Campaign</a>

        <form action="{{ route('campaigns.index') }}" method="GET" class="filter">
            <select name="status">
                <option value="">Semua status</option>
                @foreach (['draft' => 'Draft', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') == $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Campaign</th>
                <th>Klien</th>
                <th>PIC</th>
                <th>Periode</th>
                <th>Post</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($campaigns as $campaign)
                <tr>
                    <td>{{ $campaigns->firstItem() + $loop->index }}</td>
                    <td><a href="{{ route('campaigns.show', $campaign->id) }}">{{ $campaign->nama_campaign }}</a></td>
                    <td>{{ $campaign->client->nama_brand }}</td>
                    <td>{{ $campaign->user->name }}</td>
                    <td>{{ $campaign->tanggal_mulai->translatedFormat('d M Y') }}<br><span class="muted">s.d. {{ $campaign->tanggal_selesai->translatedFormat('d M Y') }}</span></td>
                    <td>{{ $campaign->posts_count }}</td>
                    <td><span class="badge badge-{{ $campaign->status }}">{{ ucfirst($campaign->status) }}</span></td>
                    <td class="aksi">
                        <a href="{{ route('campaigns.show', $campaign->id) }}">Detail</a>
                        |
                        <a href="{{ route('campaigns.edit', $campaign->id) }}">Edit</a>
                        |
                        <form class="inline" action="{{ route('campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Hapus campaign ini beserta seluruh post-nya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-link">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data campaign.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $campaigns->appends(request()->query())->links() }}
@endsection
