@extends('layouts.app')
@section('title','Lokasi / Ruangan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.locations'])

    <main class="main">
        @if (session('status'))
            <p class="alert-success">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p class="alert-error">{{ session('error') }}</p>
        @endif

        <header class="head">
            <div>
                <p class="eyebrow">MASTER DATA</p>
                <h1>Lokasi / ruangan.</h1>
                <p>Daftar ruangan sesuai denah sekolah. Tambahkan kalau ada ruangan yang belum masuk daftar.</p>
            </div>
            <a class="btn soft" href="{{ route('map') }}">Lihat denah asli →</a>
        </header>

        <div class="form-box master-form">
            <form method="POST" action="{{ route('admin.locations.store') }}">
                @csrf
                <div class="field">
                    <label>Nama ruangan baru</label>
                    <input name="name" required placeholder="Contoh: Rg 2.19" value="{{ old('name') }}">
                    @error('name') <small class="field-error">{{ $message }}</small> @enderror
                </div>
                <button class="btn primary">Tambah lokasi</button>
            </form>
        </div>

        <div class="master-table">
            @forelse ($locations as $location)
                <div class="master-row">
                    <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="master-edit">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $location->name }}">
                        <span class="muted">{{ $location->reports_count }} laporan</span>
                        <button class="btn soft">Simpan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.locations.destroy', $location) }}" onsubmit="return confirm('Hapus lokasi {{ $location->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn danger">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="muted">Belum ada lokasi.</p>
            @endforelse
        </div>
    </main>
</div>
@endsection
