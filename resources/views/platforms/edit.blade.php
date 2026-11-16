@extends('layouts.app')

@section('title', 'Edit Platform')

@section('content')
    <p><a href="{{ route('platforms.index') }}">&larr; Kembali ke daftar platform</a></p>

    <h1>Edit Platform</h1>

    <form action="{{ route('platforms.update', $platform->id) }}" method="POST" class="form-box">
        @csrf
        @method('PUT')

        @include('platforms._form', ['platform' => $platform])

        <div class="actions">
            <button type="submit" class="btn">Perbarui</button>
            <a href="{{ route('platforms.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
