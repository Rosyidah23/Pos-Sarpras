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

        @include('partials.status-summary', ['counts' => $counts, 'route' => 'admin.reports'])

        <div class="reports-head">
            <h3>Laporan terbaru</h3>
            <a class="see-all" href="{{ route('admin.reports') }}">Verifikasi semua laporan →</a>
        </div>
        <div class="reports">
            @forelse ($recentReports as $report)
                <a class="report report-link" href="{{ route('admin.reports', ['status' => $report->status]) }}#laporan-{{ $report->id }}" title="Buka di Verifikasi Laporan">
                    <b>{{ $report->title }}<small>#{{ $report->code }} · {{ $report->user->name ?? 'Tanpa nama' }}</small>
                        <span class="report-desc">{{ $report->description }}</span>
                    </b>
                    <span class="tag" title="{{ $report->category->name ?? '-' }}">{{ $report->category->name ?? '-' }}</span>
                    <span class="muted" title="{{ $report->location->name ?? '-' }}">{{ $report->location->name ?? '-' }}</span>
                    <span class="status status-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                </a>
            @empty
                <div class="report">
                    <b>Belum ada laporan masuk.<small>Laporan dari siswa &amp; guru akan muncul di sini.</small></b>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
