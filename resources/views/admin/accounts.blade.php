@extends('layouts.app')
@section('title','Akun Siswa | POS-SARPRAS')
@section('content')
<div class="shell">
    @include('partials.sidebar', ['active' => 'admin.accounts'])

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
                <h1>Akun siswa / pelapor.</h1>
                <p>Siswa tidak bisa daftar sendiri, jadi akunnya dibuatkan di sini. Sampaikan email &amp; password ke siswanya langsung.</p>
            </div>
        </header>

        <div class="form-box account-form">
            <form method="POST" action="{{ route('admin.accounts.store') }}">
                @csrf
                <div class="field">
                    <label>Nama siswa</label>
                    <input name="name" required placeholder="Contoh: Ahmad Zaki Prasetyo" value="{{ old('name') }}">
                    @error('name') <small class="field-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Email / username</label>
                    <input name="email" type="email" required placeholder="Contoh: ahmad.zaki@smkn5surakarta.sch.id" value="{{ old('email') }}">
                    @error('email') <small class="field-error">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label>Password awal</label>
                    <div class="pw-wrap">
                        <input id="newAccountPassword" name="password" type="password" required minlength="6" placeholder="Minimal 6 karakter">
                        <button type="button" class="pw-toggle" data-target="newAccountPassword" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password') <small class="field-error">{{ $message }}</small> @enderror
                    <button type="button" class="btn soft pw-generate" data-target="newAccountPassword">Buatkan acak</button>
                </div>
                <button class="btn primary">Buat akun siswa</button>
            </form>
        </div>

        <div class="master-table">
            @forelse ($students as $student)
                <div class="account-row">
                    <div class="account-info">
                        <b>{{ $student->name }}</b>
                        <small>{{ $student->email }} &middot; {{ $student->reports_count }} laporan</small>
                    </div>

                    <form method="POST" action="{{ route('admin.accounts.reset-password', $student) }}" class="account-reset">
                        @csrf @method('PUT')
                        <div class="pw-wrap">
                            <input id="pw-{{ $student->id }}" name="password" type="password" minlength="6" required placeholder="Password baru">
                            <button type="button" class="pw-toggle" data-target="pw-{{ $student->id }}" aria-label="Tampilkan password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <button class="btn soft">Ganti password</button>
                    </form>

                    <form method="POST" action="{{ route('admin.accounts.destroy', $student) }}" onsubmit="return confirm('Hapus akun {{ $student->name }}? Laporan yang sudah dibuat tetap tersimpan.')">
                        @csrf @method('DELETE')
                        <button class="btn danger">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="muted">Belum ada akun siswa. Buat lewat form di atas.</p>
            @endforelse
        </div>
    </main>
</div>
@endsection
