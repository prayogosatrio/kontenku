@extends('layouts.app')

@section('title', 'Edit Klien')

@section('content')
    <p><a href="{{ route('clients.index') }}">&larr; Kembali ke daftar klien</a></p>

    <h1>Edit Klien</h1>

    <form action="{{ route('clients.update', $client->id) }}" method="POST" class="form-box">
        @csrf
        @method('PUT')

        @include('clients._form', ['client' => $client])

        <div class="actions">
            <button type="submit" class="btn">Perbarui</button>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
