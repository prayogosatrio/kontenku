@extends('layouts.app')

@section('title', 'Tambah Platform')

@section('content')
    <p><a href="{{ route('platforms.index') }}">&larr; Kembali ke daftar platform</a></p>

    <h1>Tambah Platform</h1>

    <form action="{{ route('platforms.store') }}" method="POST" class="form-box">
        @csrf

        @include('platforms._form')

        <div class="actions">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('platforms.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
