@extends('layouts.app')

@section('title', 'Tambah Campaign')

@section('content')
    <p><a href="{{ route('campaigns.index') }}">&larr; Kembali ke daftar campaign</a></p>

    <h1>Tambah Campaign</h1>

    <form action="{{ route('campaigns.store') }}" method="POST" class="form-box">
        @csrf

        @include('campaigns._form')

        <div class="actions">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
