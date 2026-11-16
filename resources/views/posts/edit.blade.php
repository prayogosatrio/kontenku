@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <p><a href="{{ route('posts.index') }}">&larr; Kembali ke daftar post</a></p>

    <h1>Edit Post</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" class="form-box">
        @csrf
        @method('PUT')

        @include('posts._form', ['post' => $post])

        <div class="actions">
            <button type="submit" class="btn">Perbarui</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
