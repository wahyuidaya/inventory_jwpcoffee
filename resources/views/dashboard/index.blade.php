@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="page-header">
  <h2>Dashboard</h2>
  <p>Ringkasan inventori bahan baku JWP Coffee</p>
</div>

<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon" style="background:#FDF3E7">☕</div>
    <div class="stat-label">Total Bahan Baku</div>
    <div class="stat-value">{{ $products->count() }}</div>
    <div class="stat-sub">jenis bahan</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#E8F5E9">📦</div>
    <div class="stat-label">Stok Masuk Bulan Ini</div>
    <div class="stat-value">{{ $txMasukCount }}</div>
    <div class="stat-sub">transaksi masuk</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#FFF3E0">🚚</div>
    <div class="stat-label">Stok Keluar Bulan Ini</div>
    <div class="stat-value">{{ $txKeluarCount }}</div>
    <div class="stat-sub">transaksi keluar</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon" style="background:#FFEBEE">⚠️</div>
    <div class="stat-label">Stok Rendah</div>
    <div class="stat-value" style="color:#E53935">{{ $lowStock->count() }}</div>
    <div class="stat-sub">butuh restock</div>
  </div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="section-title">
      Grafik Stok 30 Hari Terakhir
      <span style="font-size:10px;color:#9A8070;font-family:'DM Sans'">Masuk vs Keluar</span>
    </div>
    <canvas id="cDash" height="110"></canvas>
  </div>
  <div class="card">
    <div class="section-title">
      Stok Terendah
      <a href="{{ route('products.index') }}">Lihat semua</a>
    </div>
    <table class="simple">
      <thead><tr><th>Bahan</th><th>Stok</th><th>Status</th></tr></thead>
      <tbody>
        @foreach($lowStock as $item)
        <tr>
          <td>{{ $item->name }}</td>
          <td>{{ $item->stock }}</td>
          <td><span class="badge badge-low">Rendah</span></td>
        </tr>
        @endforeach
        @if($lowStock->isEmpty())
        <tr><td colspan="3" style="text-align:center;color:#9A8070;padding:12px">Semua stok aman</td></tr>
        @endif
      </tbody>
    </table>
  </div>
</div>

<div class="grid-2b">
  <div class="card">
    <div class="section-title">5 Stok Tertinggi</div>
    @foreach($top5 as $item)
    <div style="margin-bottom:10px">
      <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
        <span>{{ $item->name }}</span>
        <span style="color:#9A8070">{{ $item->stock }} {{ $item->unit }}</span>
      </div>
      <div class="stok-bar-bg">
        <div class="stok-bar-fill" style="width:{{ min(100, ($item->stock/500)*100) }}%"></div>
      </div>
    </div>
    @endforeach
  </div>
  <div class="card">
    <div class="section-title">
      Transaksi Terbaru
      <a href="{{ route('persediaan.riwayat') }}">Lihat semua</a>
    </div>
    <table class="simple">
      <thead><tr><th>Bahan</th><th>Tipe</th><th>Qty</th></tr></thead>
      <tbody>
        @foreach($recentTx as $tx)
        <tr>
          <td>{{ $tx->product->name ?? '-' }}</td>
          <td><span class="badge badge-{{ $tx->type }}">{{ $tx->type === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
          <td class="{{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
            {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('cDash');
if (ctx) {
  new Chart(ctx.getContext('2d'), {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [
        { label: 'Masuk', data: {!! json_encode($chartMasuk) !!}, borderColor: '#4CAF50', backgroundColor: 'rgba(76,175,80,0.08)', tension: 0.4, fill: true, pointRadius: 3 },
        { label: 'Keluar', data: {!! json_encode($chartKeluar) !!}, borderColor: '#8B5E3C', backgroundColor: 'rgba(139,94,60,0.08)', tension: 0.4, fill: true, pointRadius: 3 }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top', labels: { font: { family: 'DM Sans', size: 11 }, boxWidth: 10 } } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 10 } } },
        y: { grid: { color: '#F2EDE4' }, ticks: { font: { family: 'DM Sans', size: 10 } } }
      }
    }
  });
}
</script>
@endpush