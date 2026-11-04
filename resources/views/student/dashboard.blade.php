@extends('layouts.app')
@section('title','Beranda Siswa | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'dashboard'])

    <main class="main">
        @if (session('status'))
            <p class="alert-success">{{ session('status') }}</p>
        @endif

        <header class="head">
            <div>
                <p class="eyebrow">{{ ucfirst(now()->translatedFormat('l, d F Y')) }}</p>
                <h1>Hai, {{ auth()->user()->name }}! 👋</h1>
                <p>Kalau ada fasilitas bermasalah, lapor biar cepat dibenerin.</p>
            </div>
        </header>

        <div class="welcome">
            <div class="hello">
                <p>Bikin sekolah makin nyaman</p>
                <h2>Ada yang rusak?</h2>
                <p>Foto kondisinya, pilih ruangannya, jelaskan singkat. Beres.</p>
                <a class="btn primary" href="{{ route('reports.create') }}">Buat laporan baru ↗</a>
            </div>
            <div class="denah">
                <h3>📍 Denah asli sekolah</h3>
                <p>Denah SMKN 5 Surakarta</p>
                <img src="{{ asset('images/denah-smkn5.png') }}" alt="Denah SMKN 5 Surakarta">
                <a class="btn" href="{{ route('map') }}">Buka denah asli →</a>
            </div>
        </div>

        @include('partials.status-summary', ['counts' => $counts, 'route' => 'reports.history'])

        <div class="reports-head">
            <h3>Laporan terbaru</h3>
            <a class="see-all" href="{{ route('reports.history') }}">Lihat semua riwayat →</a>
        </div>
        <div class="reports">
            @forelse ($recentReports as $report)
                <div class="report">
                    <b>{{ $report->title }}<small>#{{ $report->code }}</small>
                        <span class="report-desc" title="{{ $report->description }}">{{ $report->description }}</span>
                    </b>
                    <span class="tag" title="{{ $report->category->name ?? '-' }}">{{ $report->category->name ?? '-' }}</span>
                    <span class="muted" title="{{ $report->location->name ?? '-' }}">{{ $report->location->name ?? '-' }}</span>
                    <span class="status status-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                </div>
            @empty
                <div class="report">
                    <b>Belum ada laporan.<small>Yuk buat laporan pertamamu.</small></b>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
