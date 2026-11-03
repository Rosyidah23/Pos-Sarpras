@extends('layouts.app')
@section('title','Rekap & Cetak Laporan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.recap'])

    <main class="main">
        <div class="no-print">
            <header class="head">
                <div>
                    <p class="eyebrow">REKAP & CETAK</p>
                    <h1>Rekap laporan.</h1>
                    <p>Pilih rentang tanggal, lalu cetak atau download sebagai CSV.</p>
                </div>
                <div class="head-actions">
                    <button type="button" class="btn primary" data-print>🖨 Cetak</button>
                    <a class="btn soft" href="{{ route('admin.export', array_filter(['from' => $from, 'to' => $to, 'status' => $status])) }}">⇩ Download CSV</a>
                </div>
            </header>

            @if ($errors->any())
                <p class="alert-error">{{ $errors->first() }}</p>
            @endif

            <form method="GET" action="{{ route('admin.recap') }}" class="date-filter">
                <div class="field">
                    <label for="from">Dari tanggal</label>
                    <input id="from" type="date" name="from" value="{{ $from }}">
                </div>
                <div class="field">
                    <label for="to">Sampai tanggal</label>
                    <input id="to" type="date" name="to" value="{{ $to }}">
                </div>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua status</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn primary" type="submit">Tampilkan</button>
                @if ($from || $to || $status)
                    <a class="btn soft" href="{{ route('admin.recap') }}">Reset</a>
                @endif
            </form>

            <div class="filter-bar">
                @foreach ($presets as $label => $range)
                    <a class="chip {{ $from === $range['from'] && $to === $range['to'] ? 'chip-active' : '' }}"
                       href="{{ route('admin.recap', array_filter($range + ['status' => $status])) }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <section class="print-sheet">
            <div class="sheet-head">
                <img src="{{ asset('images/logo-smkn5.png') }}" alt="" class="sheet-logo">
                <div>
                    <h2>Rekap Laporan Sarana & Prasarana</h2>
                    <p>SMK Negeri 5 Surakarta</p>
                </div>
            </div>

            <dl class="sheet-meta">
                <div><dt>Periode</dt><dd>{{ $periodLabel }}</dd></div>
                <div><dt>Status</dt><dd>{{ $status ? ucfirst($status) : 'Semua status' }}</dd></div>
                <div><dt>Dicetak</dt><dd>{{ now()->translatedFormat('d F Y, H:i') }} oleh {{ auth()->user()->name }}</dd></div>
                <div>
                    <dt>Total</dt>
                    <dd>
                        {{ $reports->count() }} laporan
                        @if ($reports->isNotEmpty())
                            ({{ collect($statuses)->map(fn ($s) => ucfirst($s).' '.($summary[$s] ?? 0))->join(', ') }})
                        @endif
                    </dd>
                </div>
            </dl>

            <table class="recap-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Catatan petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="nowrap">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="nowrap">{{ $report->code }}</td>
                            <td>{{ $report->title }}</td>
                            <td>{{ $report->category->name ?? '-' }}</td>
                            <td>{{ $report->location->name ?? '-' }}</td>
                            <td>{{ $report->user->name ?? '-' }}</td>
                            <td>{{ ucfirst($report->status) }}</td>
                            <td>{{ $report->follow_up_note ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="recap-empty">Tidak ada laporan pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="sheet-sign">
                <p>Surakarta, {{ now()->translatedFormat('d F Y') }}</p>
                <p>Petugas Sarpras</p>
                <p class="sign-name">{{ auth()->user()->name }}</p>
            </div>
        </section>
    </main>
</div>
@endsection
