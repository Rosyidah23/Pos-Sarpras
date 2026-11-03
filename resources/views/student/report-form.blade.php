@extends('layouts.app')
@section('title','Buat Laporan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'reports.create'])

    <main class="main">
        <header class="head">
            <div>
                <p class="eyebrow">LAPORAN BARU</p>
                <h1>Ceritakan masalahnya.</h1>
                <p>Nggak perlu bahasa resmi, tulis seperti menjelaskan ke guru.</p>
            </div>
            <a class="btn soft" href="{{ route('dashboard') }}">← Kembali</a>
        </header>

        <div class="form-box">
            <form id="reportForm" method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="field">
                    <label>Yang rusak apa? *</label>
                    <select name="category_id" class="fancy-select" required>
                        <option value="">Pilih kategori...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Rusaknya di mana? *</label>
                    @if ($selectedLocationId)
                        <p class="field-hint">📍 Lokasi sudah keisi otomatis dari denah, tinggal diganti kalau salah.</p>
                    @endif
                    <select name="location_id" class="fancy-select" required>
                        <option value="">Pilih ruangan...</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" @selected(old('location_id', $selectedLocationId) == $location->id)>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Judul laporan *</label>
                    <input name="title" required placeholder="Contoh: Lampu kelas mati tiga hari">
                </div>
                <div class="field">
                    <label>Jelaskan masalahnya *</label>
                    <textarea name="description" required placeholder="Contoh: Lampu baris depan mati sejak Senin."></textarea>
                </div>
                <div class="field">
                    <label>Foto bukti *</label>
                    <input name="photo" type="file" accept="image/*" required>
                </div>
                <button class="btn primary">Kirim laporan ↗</button>
            </form>
        </div>
    </main>
</div>
@endsection
