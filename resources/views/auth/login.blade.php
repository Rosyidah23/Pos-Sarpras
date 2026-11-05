@extends('layouts.app')
@section('title','Masuk | POS-SARPRAS')
@section('content')
<section class="page">
    <div class="login">
        <div class="hero">
            <div class="hero-inner">
                <div class="hero-brand">
                    <img src="{{ asset('images/logo-smkn5.png') }}?v={{ filemtime(public_path('images/logo-smkn5.png')) }}" alt="Logo SMKN 5 Surakarta">
                    <span><b>POS-SARPRAS</b><small>SMKN 5 Surakarta</small></span>
                </div>
                <h1>Ada yang rusak? <span>Lapor aja.</span></h1>
                <p>Foto kondisinya, pilih ruangannya, lalu ceritakan singkat. Laporanmu bisa dipantau sampai selesai.</p>
                <div class="facts">
                    <div><strong>{{ $monthTotal }}</strong><small>laporan bulan ini</small></div>
                    <div><strong>{{ $doneTotal }}</strong><small>sudah selesai</small></div>
                    <div><strong>24/7</strong><small>bisa melapor</small></div>
                </div>
            </div>
        </div>

        <div class="login-panel">
            <div class="login-box">
                <div class="login-brand">
                    <img src="{{ asset('images/logo-smkn5.png') }}?v={{ filemtime(public_path('images/logo-smkn5.png')) }}" alt="Logo SMKN 5 Surakarta">
                    <span><b>POS-SARPRAS</b><small>SMKN 5 Surakarta</small></span>
                </div>
                <h2>Masuk sebagai siapa?</h2>
                <p>Masuk dengan akun siswa, guru, atau petugas.</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if ($errors->any())
                        <p style="color:#c0392b;margin-bottom:12px">{{ $errors->first() }}</p>
                    @endif
                    <div class="field">
                        <label>Email / Username</label>
                        <div class="field-icon">
                            <input name="email" value="{{ old('email') }}" required placeholder="Contoh: siswa@smkn5surakarta.sch.id">
                            <span class="icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label>Password</label>
                        <div class="pw-wrap">
                            <input id="loginPassword" name="password" type="password" required placeholder="Masukkan password">
                            <button type="button" class="pw-toggle" data-target="loginPassword" aria-label="Tampilkan password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <button class="btn primary full">Masuk ke akun →</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
