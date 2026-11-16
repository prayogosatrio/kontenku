@extends('layouts.app')

@section('title', 'Edit Campaign')

@section('content')
    <p><a href="{{ route('campaigns.index') }}">&larr; Kembali ke daftar campaign</a></p>

    <h1>Edit Campaign</h1>

    <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST" class="form-box">
        @csrf
        @method('PUT')

        @include('campaigns._form', ['campaign' => $campaign])

        <div class="actions">
            <button type="submit" class="btn">Perbarui</button>
            <a href="{{ route('campaigns.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection
