@extends('layouts.app')
@section('title','Denah Asli | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'map'])

    <main class="main">
        <header class="head">
            <div>
                <p class="eyebrow">DENAH ASLI SMKN 5 SURAKARTA</p>
                <h1>Cari tempatnya 📍</h1>
                <p>Klik ruangan pada denah asli untuk memilih lokasi masalah.</p>
            </div>
            <a class="btn soft" href="{{ route('reports.create') }}">Buat laporan →</a>
        </header>

        <div class="map-layout">
            <div class="map-board">
                <div class="map-frame">
                    <img id="schoolMap" src="{{ asset('images/'.$mapImage) }}" alt="Denah ruang SMK Negeri 5 Surakarta">
                    @foreach ($hotspots as $spot)
                        <button
                            type="button"
                            class="map-hotspot"
                            style="left:{{ $spot['left'] }}%;top:{{ $spot['top'] }}%;width:{{ $spot['width'] }}%;height:{{ $spot['height'] }}%"
                            data-location-id="{{ $spot['location_id'] }}"
                            data-location-name="{{ $spot['name'] }}"
                            title="{{ $spot['name'] }}"
                        ></button>
                    @endforeach
                </div>
            </div>
            <aside class="map-info">
                <small>LOKASI LAPORAN</small>
                <h2 id="roomTitle">Belum dipilih</h2>
                <p id="roomText">Klik ruangan pada denah asli, lalu lanjutkan ke form laporan.</p>
                <a class="btn" id="continueBtn" href="{{ route('reports.create') }}">Lanjut buat laporan ↗</a>
            </aside>
        </div>
    </main>
</div>
@endsection
