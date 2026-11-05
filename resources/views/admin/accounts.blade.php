@extends('layouts.app')
@section('title','Akun Siswa & Guru | POS-SARPRAS')
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
                <h1>Akun siswa &amp; guru.</h1>
                <p>Siswa dan guru tidak bisa daftar sendiri, jadi akunnya dibuatkan di sini. Sampaikan email &amp; password ke yang bersangkutan langsung.</p>
            </div>
        </header>

        @php
            $importResult = session('import_result');
            $bulkOpen = $importResult || $errors->hasAny(['file', 'paste', 'import', 'common_password']);
        @endphp

        @if ($importResult)
            <div class="import-result" id="importResult">
                <div class="import-result-head">
                    <div>
                        <h3>Hasil impor: {{ count($importResult['created']) }} akun dibuat{{ count($importResult['skipped']) ? ', '.count($importResult['skipped']).' dilewati' : '' }}</h3>
                        <p>Password hanya ditampilkan di sini sekali ini saja. Unduh daftarnya sekarang, lalu bagikan ke siswa/guru.</p>
                    </div>
                    @if (count($importResult['created']))
                        <button type="button" class="btn primary" id="downloadCredentials">Unduh daftar akun</button>
                    @endif
                </div>

                @if (count($importResult['created']))
                    <div class="table-scroll">
                        <table class="mini-table" id="credentialsTable">
                            <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Password</th></tr></thead>
                            <tbody>
                                @foreach ($importResult['created'] as [$cName, $cEmail, $cRole, $cPassword])
                                    <tr><td>{{ $cName }}</td><td>{{ $cEmail }}</td><td>{{ $cRole }}</td><td><code>{{ $cPassword }}</code></td></tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (count($importResult['skipped']))
                    <details class="import-skipped" open>
                        <summary>{{ count($importResult['skipped']) }} baris dilewati (tidak dibuat)</summary>
                        <div class="table-scroll">
                            <table class="mini-table">
                                <thead><tr><th>Baris</th><th>Data</th><th>Alasan</th></tr></thead>
                                <tbody>
                                    @foreach ($importResult['skipped'] as [$sLine, $sWho, $sReason])
                                        <tr><td>{{ $sLine }}</td><td>{{ $sWho }}</td><td>{{ $sReason }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endif
            </div>
        @endif

        <div class="form-box account-form" id="accountTabs" data-open="{{ $bulkOpen ? 'bulk' : 'single' }}">
            <div class="form-tabs" role="tablist">
                <button type="button" class="chip is-active" role="tab" data-tab="single">Buat satu akun</button>
                <button type="button" class="chip" role="tab" data-tab="bulk">Impor banyak akun</button>
            </div>

            <div class="tab-panel" data-panel="single">
                <form method="POST" action="{{ route('admin.accounts.store') }}">
                    @csrf
                    <div class="field">
                        <label>Nama lengkap</label>
                        <input name="name" required autocomplete="off" placeholder="Contoh: Ahmad Zaki Prasetyo" value="{{ old('name') }}">
                        @error('name') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="field">
                        <label>Email / username</label>
                        <input name="email" type="email" required autocomplete="off" placeholder="Contoh: ahmad.zaki@smkn5surakarta.sch.id" value="{{ old('email') }}">
                        @error('email') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="field">
                        <label>Peran akun</label>
                        <div class="role-pick">
                            <label>
                                <input type="radio" name="role" value="siswa" @checked(old('role', 'siswa') === 'siswa')>
                                <span>Siswa</span>
                            </label>
                            <label>
                                <input type="radio" name="role" value="guru" @checked(old('role') === 'guru')>
                                <span>Guru</span>
                            </label>
                        </div>
                        @error('role') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="field">
                        <label>Password awal</label>
                        <div class="pw-row">
                            <div class="pw-wrap">
                                <input id="newAccountPassword" name="password" type="password" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter">
                                <button type="button" class="pw-toggle" data-target="newAccountPassword" aria-label="Tampilkan password">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <button type="button" class="btn soft pw-generate" data-target="newAccountPassword">Buatkan acak</button>
                        </div>
                        @error('password') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-actions">
                        <button class="btn primary">Buat akun</button>
                    </div>
                </form>
            </div>

            <div class="tab-panel" data-panel="bulk" hidden>
                <form method="POST" action="{{ route('admin.accounts.import') }}" enctype="multipart/form-data" class="bulk-form">
                    @csrf
                    @error('import') <p class="alert-error">{{ $message }}</p> @enderror

                    <div class="bulk-intro">
                        Upload file <b>Excel (.xlsx)</b> atau <b>Word (.docx)</b> berisi daftar akun, kolomnya urut: nama, email, peran (siswa atau guru), password.
                        <details class="bulk-example">
                            <summary>Lihat contoh</summary>
                            <div class="table-scroll">
                                <table class="mini-table">
                                    <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Password</th></tr></thead>
                                    <tbody>
                                        <tr><td>Ahmad Zaki Prasetyo</td><td>ahmad.zaki@smkn5surakarta.sch.id</td><td>siswa</td><td></td></tr>
                                        <tr><td>Sri Wahyuni</td><td>sri.wahyuni@smkn5surakarta.sch.id</td><td>guru</td><td></td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <small>Kolom peran wajib diisi siswa atau guru. Password boleh dikosongkan, nanti ikut pilihan di bawah. Baris judul boleh ada, boleh tidak.</small>
                        </details>
                    </div>

                    <div class="field bulk-wide bulk-pw">
                        <label>Password <span class="label-note">(kosongkan = diacak, tiap akun beda)</span></label>
                        <div class="pw-row">
                            <div class="pw-wrap">
                                <input id="bulkCommonPassword" name="common_password" type="password" minlength="6" autocomplete="new-password" placeholder="Ketik sendiri untuk semua akun" value="{{ old('common_password') }}">
                                <button type="button" class="pw-toggle" data-target="bulkCommonPassword" aria-label="Tampilkan password">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            <button type="button" class="btn soft pw-generate" data-target="bulkCommonPassword">Buatkan acak</button>
                        </div>
                        @error('common_password') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="field bulk-wide">
                        <label>File daftar akun</label>
                        <input type="file" name="file" accept=".xlsx,.xlsm,.docx,.csv,.txt">
                        @error('file') <small class="field-error">{{ $message }}</small> @enderror
                        <p class="bulk-note">Maksimal 5 MB dan 1000 akun per upload. Password yang tertulis di file lebih diutamakan.</p>
                    </div>

                    <details class="bulk-paste bulk-wide" @if (old('paste')) open @endif>
                        <summary>Atau tempel langsung dari Excel / Word</summary>
                        <textarea name="paste" rows="5" placeholder="Nama, email, peran (siswa/guru), password. Satu akun per baris">{{ old('paste') }}</textarea>
                        @error('paste') <small class="field-error">{{ $message }}</small> @enderror
                    </details>

                    <div class="form-actions">
                        <button class="btn primary">Impor akun</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pencarian akun: memfilter daftar di bawah secara langsung (tanpa reload) --}}
        <div class="account-search" id="accountSearchBox">
            <div class="search-field">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input id="accountSearch" type="search" autocomplete="off" placeholder="Cari nama atau email akun..." aria-label="Cari akun siswa atau guru">
                <button type="button" class="search-clear" id="accountSearchClear" aria-label="Hapus pencarian" hidden>✕</button>
            </div>
            <div class="role-filter" role="group" aria-label="Filter peran">
                <button type="button" class="chip is-active" data-role="semua">Semua <b>{{ $roleCounts['semua'] }}</b></button>
                <button type="button" class="chip" data-role="siswa">Siswa <b>{{ $roleCounts['siswa'] }}</b></button>
                <button type="button" class="chip" data-role="guru">Guru <b>{{ $roleCounts['guru'] }}</b></button>
            </div>
        </div>
        <p class="filter-hint" id="accountCount" aria-live="polite">Menampilkan {{ $roleCounts['semua'] }} akun.</p>

        <div class="master-table" id="accountList">
            @foreach ($accounts as $account)
                <div class="account-row"
                     data-role="{{ $account->role }}"
                     data-search="{{ mb_strtolower($account->name.' '.$account->email.' '.$account->roleName()) }}">
                    <div class="account-info">
                        <b>{{ $account->name }} <span class="role-tag role-{{ $account->role }}">{{ $account->roleName() }}</span></b>
                        <small>{{ $account->email }} &middot; {{ $account->reports_count }} laporan</small>
                    </div>

                    <form method="POST" action="{{ route('admin.accounts.reset-password', $account) }}" class="account-reset">
                        @csrf @method('PUT')
                        <div class="pw-wrap">
                            <input id="pw-{{ $account->id }}" name="password" type="password" minlength="6" required placeholder="Password baru">
                            <button type="button" class="pw-toggle" data-target="pw-{{ $account->id }}" aria-label="Tampilkan password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        <button class="btn soft">Ganti password</button>
                    </form>

                    <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" onsubmit="return confirm('Hapus akun {{ addslashes($account->name) }}? Laporan yang sudah dibuat tetap tersimpan.')">
                        @csrf @method('DELETE')
                        <button class="btn danger">Hapus</button>
                    </form>
                </div>
            @endforeach

            <p class="muted" id="accountEmpty" @if ($accounts->isNotEmpty()) hidden @endif>
                {{ $accounts->isEmpty() ? 'Belum ada akun siswa atau guru. Buat lewat form di atas.' : 'Tidak ada akun yang cocok dengan pencarian.' }}
            </p>
        </div>
    </main>
</div>
@endsection
