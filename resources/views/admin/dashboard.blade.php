@extends('layouts.app')
@section('title','Panel Petugas | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.dashboard'])

    <main class="main">
        <header class="head">
            <div>
                <p class="eyebrow">PUSAT KENDALI PETUGAS</p>
                <h1>Semua laporan terlihat.</h1>
                <p>Atur laporan sampai selesai.</p>
            </div>
            <a class="btn primary" href="{{ route('admin.recap') }}">Rekap & Cetak</a>
        </header>

        <div class="welcome">
            <div class="hello">
                <p>Total laporan</p>
                <h2>{{ $total }}</h2>
                <p>{{ $selesaiCount }} laporan sudah selesai ditangani.</p>
            </div>
            <div class="denah">
                <h3>Perlu dicek</h3>
                <h2 style="font-size:40px;margin-top:14px">{{ $perluDicek }}</h2>
                <p>Jangan sampai kelewat.</p>
            </div>
        </div>

        <div class="reports-head">
            <h3>Laporan terbaru</h3>
            <a class="see-all" href="{{ route('admin.reports') }}">Verifikasi semua laporan →</a>
        </div>
        <div class="reports">
            @forelse ($recentReports as $report)
                <div class="report">
                    <b>{{ $report->title }}<small>#{{ $report->code }} · {{ $report->user->name ?? 'Tanpa nama' }}</small>
                        <span class="report-desc" title="{{ $report->description }}">{{ $report->description }}</span>
                    </b>
                    <span class="tag" title="{{ $report->category->name ?? '-' }}">{{ $report->category->name ?? '-' }}</span>
                    <span class="muted" title="{{ $report->location->name ?? '-' }}">{{ $report->location->name ?? '-' }}</span>
                    <span class="status">{{ ucfirst($report->status) }}</span>
                </div>
            @empty
                <div class="report">
                    <b>Belum ada laporan masuk.<small>Laporan dari siswa akan muncul di sini.</small></b>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
