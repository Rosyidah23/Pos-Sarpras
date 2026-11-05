@extends('layouts.app')
@section('title','Kategori Kerusakan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.categories'])

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
                <h1>Kategori kerusakan.</h1>
                <p>Kategori ini muncul sebagai pilihan di form laporan (siswa &amp; guru).</p>
            </div>
        </header>

        <div class="form-box master-form">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="field">
                    <label>Nama kategori baru</label>
                    <input name="name" required placeholder="Contoh: Jaringan Internet" value="{{ old('name') }}">
                    @error('name') <small class="field-error">{{ $message }}</small> @enderror
                </div>
                <button class="btn primary">Tambah kategori</button>
            </form>
        </div>

        <div class="master-table">
            @forelse ($categories as $category)
                <div class="master-row">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="master-edit">
                        @csrf @method('PUT')
                        <input name="name" value="{{ $category->name }}">
                        <span class="muted">{{ $category->reports_count }} laporan</span>
                        <button class="btn soft">Simpan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn danger">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="muted">Belum ada kategori.</p>
            @endforelse
        </div>
    </main>
</div>
@endsection
