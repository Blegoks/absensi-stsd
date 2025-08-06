@extends('anggota.layout')

@section('content')
<div class="container">
    <h2>Daftarkan Wajah Anda</h2>
    <form method="POST" action="{{ route('anggota.daftarWajah') }}">
        @csrf
        <input type="hidden" name="id_anggota" value="{{ Auth::user()->profile_id }}">
        <div class="mb-3">
            <label for="nama">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" value="{{ Auth::user()->name }}" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Mulai Daftar Wajah</button>
    </form>
</div>
@endsection
