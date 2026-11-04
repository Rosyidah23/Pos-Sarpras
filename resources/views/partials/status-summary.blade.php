{{-- Ringkasan laporan: total, belum/sudah ditangani, dan per status.
     Variabel: $counts (status => jumlah), $route (nama route tujuan filter), $params (opsional), $active (status terpilih, opsional) --}}
@php
    $belum = $counts['menunggu'] + $counts['diproses'];
    $sudah = $counts['selesai'] + $counts['ditolak'];
    $params = $params ?? [];
    $active = $active ?? null;
    $labels = ['menunggu' => 'Menunggu dicek', 'diproses' => 'Sedang diproses', 'selesai' => 'Sudah selesai', 'ditolak' => 'Ditolak'];
    $tones = ['menunggu' => 'yellow', 'diproses' => 'blue', 'selesai' => 'mint', 'ditolak' => 'coral'];
@endphp
<div class="sum-grid">
    <div class="sum sum-total">
        <small>Total laporan</small>
        <strong>{{ $counts->sum() }}</strong>
        <span>Semua laporan yang masuk</span>
    </div>
    <div class="sum sum-pending">
        <small>Belum ditangani</small>
        <strong>{{ $belum }}</strong>
        <span>{{ $counts['menunggu'] }} menunggu · {{ $counts['diproses'] }} diproses</span>
    </div>
    <div class="sum sum-done">
        <small>Sudah ditangani</small>
        <strong>{{ $sudah }}</strong>
        <span>{{ $counts['selesai'] }} selesai · {{ $counts['ditolak'] }} ditolak</span>
    </div>
</div>
<div class="stats">
    @foreach ($labels as $status => $label)
        <a class="stat {{ $active === $status ? 'stat-active' : '' }}" style="--tone:var(--{{ $tones[$status] }})"
           href="{{ route($route, array_merge($params, ['status' => $status])) }}">
            <i></i><strong>{{ $counts[$status] }}</strong><small>{{ $label }}</small>
        </a>
    @endforeach
</div>
