<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','POS-SARPRAS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pos-sarpras.css') }}?v={{ filemtime(public_path('css/pos-sarpras.css')) }}">
</head>
<body>
    <nav class="top">
        @auth
            <button type="button" class="nav-toggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        @endauth
        @guest
            <img src="{{ asset('images/logo-smkn5.png') }}?v={{ filemtime(public_path('images/logo-smkn5.png')) }}" alt="Logo SMKN 5 Surakarta" class="top-logo">
        @endguest
        <b>POS-SARPRAS <span class="top-sep">·</span> SMKN 5 Surakarta</b>
        @auth
            <span class="top-role">{{ auth()->user()->roleName() }}</span>
            <a href="{{ route('logout') }}" class="top-logout">Keluar</a>
        @else
            <a href="{{ route('login') }}">Masuk</a>
        @endauth
    </nav>

    @yield('content')

    @auth
        <div class="side-backdrop"></div>
    @endauth

    <div id="toast" class="toast"></div>
    <script src="{{ asset('js/pos-sarpras.js') }}?v={{ filemtime(public_path('js/pos-sarpras.js')) }}"></script>
</body>
</html>
