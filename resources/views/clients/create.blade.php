@extends('layouts.app')

@section('title', 'Tambah Klien')

@section('content')
    <p><a href="{{ route('clients.index') }}">&larr; Kembali ke daftar klien</a></p>

    <h1>Tambah Klien</h1>

    <form action="{{ route('clients.store') }}" method="POST" class="form-box">
        @csrf

        @include('clients._form')

        <div class="actions">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
