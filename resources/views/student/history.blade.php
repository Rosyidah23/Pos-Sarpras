@extends('layouts.app')
@section('title','Riwayat Laporan | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'reports.history'])

    <main class="main">
        <header class="head">
            <div>
                <p class="eyebrow">RIWAYAT LAPORAN</p>
                <h1>Laporan kamu.</h1>
                <p>Semua laporan yang pernah kamu ajukan, beserta status penanganannya.</p>
            </div>
            <a class="btn primary" href="{{ route('reports.create') }}">Buat laporan baru ↗</a>
        </header>

        @if ($errors->any())
            <p class="alert-error">{{ $errors->first() }}</p>
        @endif

        <form method="GET" action="{{ route('reports.history') }}" class="date-filter">
            <div class="field">
                <label for="from">Dari tanggal</label>
                <input id="from" type="date" name="from" value="{{ $from }}">
            </div>
            <div class="field">
                <label for="to">Sampai tanggal</label>
                <input id="to" type="date" name="to" value="{{ $to }}">
            </div>
            <button class="btn primary" type="submit">Tampilkan</button>
            @if ($from || $to)
                <a class="btn soft" href="{{ route('reports.history') }}">Reset</a>
            @endif
        </form>
        <p class="filter-hint">
            @if ($from || $to)
                {{ $reports->total() }} laporan ditemukan. Kosongkan salah satu tanggal untuk menampilkan tanpa batas awal atau akhir.
            @else
                Menampilkan semua laporan. Isi tanggal untuk melihat riwayat pada periode tertentu.
            @endif
        </p>

        <div class="reports">
            @forelse ($reports as $report)
                <div class="report {{ $report->photo_path ? 'report-photo' : '' }}">
                    @if ($report->photo_path)
                        <button type="button" class="report-thumb-btn photo-zoom" data-full="{{ asset('storage/'.$report->photo_path) }}">
                            <img class="report-thumb" src="{{ asset('storage/'.$report->photo_path) }}" alt="Foto laporan">
                        </button>
                    @endif
                    <b>{{ $report->title }}<small>#{{ $report->code }} · {{ $report->created_at->translatedFormat('d M Y') }}</small>
                        <span class="report-desc" title="{{ $report->description }}">{{ $report->description }}</span>
                        @if ($report->follow_up_note)
                            <span class="verify-note">Catatan petugas: {{ $report->follow_up_note }}</span>
                        @endif
                    </b>
                    <span class="tag" title="{{ $report->category->name ?? '-' }}">{{ $report->category->name ?? '-' }}</span>
                    <span class="muted" title="{{ $report->location->name ?? '-' }}">{{ $report->location->name ?? '-' }}</span>
                    <span class="status">{{ ucfirst($report->status) }}</span>
                </div>
            @empty
                <div class="report report-empty">
                    @if ($from || $to)
                        <b>Tidak ada laporan pada tanggal tersebut.<small>Coba ubah rentang tanggalnya.</small></b>
                    @else
                        <b>Belum ada laporan.<small>Laporan yang kamu ajukan akan muncul di sini.</small></b>
                    @endif
                </div>
            @endforelse
        </div>

        @include('partials.pager', ['paginator' => $reports])
    </main>
</div>
@endsection
