@php
    $navUser = auth()->user();
    $isPetugas = $navUser && $navUser->isPetugas();
    $initials = $navUser
        ? mb_strtoupper(collect(explode(' ', $navUser->name))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->join(''))
        : '?';
    $active = $active ?? null;
@endphp
<aside class="side" id="sidebar">
    <div class="side-role">
        <img src="{{ asset('images/logo-smkn5.png') }}?v={{ filemtime(public_path('images/logo-smkn5.png')) }}" alt="Logo SMKN 5 Surakarta" class="side-logo">
        <span class="side-role-badge">{{ $isPetugas ? 'Petugas Sarpras' : $navUser->roleName().' / Pelapor' }}</span>
    </div>

    <div class="nav-group">
        <div class="label">Menu utama</div>

        @if ($isPetugas)
            <a class="nav {{ $active === 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">⌂ &nbsp; Ringkasan</a>
            <a class="nav {{ $active === 'admin.reports' ? 'active' : '' }}" href="{{ route('admin.reports') }}">✓ &nbsp; Verifikasi Laporan</a>
            <a class="nav {{ $active === 'admin.recap' ? 'active' : '' }}" href="{{ route('admin.recap') }}">🖨 &nbsp; Rekap & Cetak</a>
        @else
            <a class="nav {{ $active === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">⌂ &nbsp; Beranda</a>
            <a class="nav {{ $active === 'reports.create' ? 'active' : '' }}" href="{{ route('reports.create') }}">＋ &nbsp; Buat laporan</a>
            <a class="nav {{ $active === 'map' ? 'active' : '' }}" href="{{ route('map') }}">⌖ &nbsp; Denah asli</a>
            <a class="nav {{ $active === 'reports.history' ? 'active' : '' }}" href="{{ route('reports.history') }}">▣ &nbsp; Riwayat laporan</a>
        @endif
    </div>

    @if ($isPetugas)
        <div class="nav-group">
            <div class="label">Master data</div>
            <a class="nav {{ $active === 'admin.categories' ? 'active' : '' }}" href="{{ route('admin.categories') }}">☰ &nbsp; Kategori kerusakan</a>
            <a class="nav {{ $active === 'admin.locations' ? 'active' : '' }}" href="{{ route('admin.locations') }}">📍 &nbsp; Lokasi / ruangan</a>
            <a class="nav {{ $active === 'admin.accounts' ? 'active' : '' }}" href="{{ route('admin.accounts') }}">👤 &nbsp; Akun siswa &amp; guru</a>
        </div>
    @endif

    <a class="nav nav-logout" href="{{ route('logout') }}">↪ &nbsp; Keluar</a>

    @if ($navUser)
        <div class="user">
            <div class="avatar">{{ $initials }}</div>
            <div>
                <b>{{ $navUser->name }}</b>
                <small>{{ $isPetugas ? 'Petugas Sarpras' : $navUser->roleName() }}</small>
            </div>
        </div>
    @endif
</aside>
