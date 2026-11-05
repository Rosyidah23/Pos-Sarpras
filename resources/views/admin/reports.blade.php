@extends('layouts.app')
@section('title','Verifikasi Laporan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.reports'])

    <main class="main">
        @if (session('status'))
            <p class="alert-success">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p class="alert-error">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <p class="alert-error">{{ $errors->first() }}</p>
        @endif

        <header class="head">
            <div>
                <p class="eyebrow">VERIFIKASI LAPORAN</p>
                <h1>Tindak lanjuti laporan masuk.</h1>
                <p>Ubah status dan tambahkan catatan supaya pelapor tahu perkembangannya.</p>
            </div>
            <a class="btn soft" href="{{ route('admin.recap', array_filter(['from' => $from, 'to' => $to, 'status' => $statusFilter])) }}">🖨 Cetak / download rekap</a>
        </header>

        <form method="GET" action="{{ route('admin.reports') }}" class="date-filter">
            <div class="field">
                <label for="from">Dari tanggal</label>
                <input id="from" type="date" name="from" value="{{ $from }}">
            </div>
            <div class="field">
                <label for="to">Sampai tanggal</label>
                <input id="to" type="date" name="to" value="{{ $to }}">
            </div>
            @if ($statusFilter)
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <button class="btn primary" type="submit">Terapkan</button>
            @if ($from || $to)
                <a class="btn soft" href="{{ route('admin.reports', array_filter(['status' => $statusFilter])) }}">Reset tanggal</a>
            @endif
        </form>

        <div class="filter-bar">
            <a class="chip {{ !$statusFilter ? 'chip-active' : '' }}" href="{{ route('admin.reports', array_filter(['from' => $from, 'to' => $to])) }}">Semua</a>
            @foreach ($statuses as $s)
                <a class="chip {{ $statusFilter === $s ? 'chip-active' : '' }}" href="{{ route('admin.reports', array_filter(['from' => $from, 'to' => $to, 'status' => $s])) }}">{{ ucfirst($s) }}</a>
            @endforeach
        </div>

        <div class="verify-list">
            @forelse ($reports as $report)
                <div class="verify-card" id="laporan-{{ $report->id }}">
                    <div class="verify-info">
                        @if ($report->photo_path)
                            <button type="button" class="verify-photo-btn photo-zoom" data-full="{{ asset('storage/'.$report->photo_path) }}">
                                <img class="verify-photo" src="{{ asset('storage/'.$report->photo_path) }}" alt="Foto bukti">
                            </button>
                        @endif
                        <div>
                            <b>{{ $report->title }}</b>
                            <small>#{{ $report->code }} · Dilaporkan oleh {{ $report->user->name ?? 'Tanpa nama' }} · {{ $report->created_at->translatedFormat('d M Y, H:i') }}</small>
                            <p class="verify-desc">{{ $report->description }}</p>
                            <div class="verify-tags">
                                <span class="tag">{{ $report->category->name ?? '-' }}</span>
                                <span class="muted">📍 {{ $report->location->name ?? '-' }}</span>
                                <span class="status status-{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                            </div>
                            @if ($report->follow_up_note)
                                <p class="verify-note">Catatan: {{ $report->follow_up_note }}</p>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.reports.update', $report) }}" class="verify-form">
                        @csrf
                        <select name="status" required>
                            @foreach ($statuses as $s)
                                <option value="{{ $s }}" @selected($report->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <input name="follow_up_note" value="{{ $report->follow_up_note }}" placeholder="Catatan tindak lanjut (opsional)">
                        <button class="btn primary">Simpan</button>
                    </form>
                </div>
            @empty
                <div class="report">
                    <b>{{ $from || $to || $statusFilter ? 'Tidak ada laporan yang cocok dengan filter.' : 'Belum ada laporan.' }}<small>Laporan dari siswa &amp; guru akan muncul di sini.</small></b>
                </div>
            @endforelse
        </div>

        @include('partials.pager', ['paginator' => $reports])
    </main>
</div>
@endsection
