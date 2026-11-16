@extends('layouts.app')

@section('title', 'Tambah Post')

@section('content')
    <p><a href="{{ route('posts.index') }}">&larr; Kembali ke daftar post</a></p>

    <h1>Tambah Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" class="form-box">
        @csrf

        @include('posts._form')

        <div class="actions">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
