@extends('layouts.app')
@section('page-title', 'Laporan Stok')

@section('content')
<div class="page-header">
  <h2>Laporan Stok</h2>
  <p>Rekap dan analisis pergerakan stok bahan baku</p>
</div>

<div class="filter-card">
  <form method="GET" id="filter-form" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
    <div class="filter-group">
      <label>Dari Tanggal</label>
      <input type="date" name="from_date" class="filter-control" value="{{ $fromDate }}">
    </div>
    <div class="filter-group">
      <label>Sampai Tanggal</label>
      <input type="date" name="to_date" class="filter-control" value="{{ $toDate }}">
    </div>
    <div class="filter-group">
      <label>Kategori</label>
      <select name="category_id" class="filter-control">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="filter-group">
      <label>Tipe</label>
      <select name="type" class="filter-control">
        <option value="">Semua</option>
        <option value="masuk"  {{ request('type')=='masuk'?'selected':'' }}>Masuk</option>
        <option value="keluar" {{ request('type')=='keluar'?'selected':'' }}>Keluar</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="fa-solid fa-filter"></i> Filter
    </button>

    {{-- Export Excel --}}
    <button type="button" class="btn btn-outline" onclick="exportForm('excel')" title="Export ke Excel">
      <i class="fa-solid fa-file-excel"></i> Excel
    </button>

    {{-- Export PDF --}}
    <button type="button" class="btn btn-outline btn-pdf" onclick="exportForm('pdf')" title="Export ke PDF">
      <i class="fa-solid fa-file-pdf"></i> PDF
    </button>
  </form>
</div>

<div class="summary-row">
  <div class="summary-box">
    <div class="s-label">Total Qty Masuk</div>
    <div class="s-value" style="color:#2E7D32">{{ $totalMasuk }}</div>
  </div>
  <div class="summary-box">
    <div class="s-label">Total Qty Keluar</div>
    <div class="s-value" style="color:#E65100">{{ $totalKeluar }}</div>
  </div>
  <div class="summary-box">
    <div class="s-label">Bahan Stok Rendah</div>
    <div class="s-value" style="color:#E53935">{{ $lowStock->count() }}</div>
  </div>
  <div class="summary-box">
    <div class="s-label">Total Jenis Bahan</div>
    <div class="s-value">{{ $products->count() }}</div>
  </div>
</div>

<div class="report-grid">
  <div class="card">
    <div class="section-title">Grafik Transaksi Bulanan</div>
    <canvas id="rChart" height="140"></canvas>
  </div>
  <div class="card">
    <div class="section-title">Status Stok Saat Ini</div>
    <div style="max-height:240px;overflow-y:auto">
      <table class="data-table">
        <thead><tr><th>Bahan</th><th>Stok</th><th>Satuan</th><th>Status</th></tr></thead>
        <tbody>
          @foreach($products as $p)
          <tr>
            <td style="font-weight:500">{{ $p->name }}</td>
            <td>{{ $p->stock }}</td>
            <td style="color:#9A8070">{{ $p->unit }}</td>
            <td>
              @if($p->isLowStock())
                <span class="badge badge-low">Rendah</span>
              @else
                <span class="badge badge-ok">Aman</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card">
  <div class="section-title">
    Detail Transaksi
    <span style="font-family:'DM Sans';font-size:11px;color:#9A8070">{{ $transactions->count() }} transaksi</span>
  </div>
  <table class="data-table">
    <thead>
      <tr><th>Tanggal</th><th>Bahan Baku</th><th>Kategori</th><th>Tipe</th><th>Jumlah</th><th>Stok Setelah</th><th>Keterangan</th></tr>
    </thead>
    <tbody>
      @foreach($transactions as $tx)
      <tr>
        <td style="font-size:11px;color:#9A8070">{{ $tx->transaction_date->format('Y-m-d') }}</td>
        <td style="font-weight:500">{{ $tx->product->name ?? '-' }}</td>
        <td style="color:#9A8070">{{ $tx->product->category->name ?? '-' }}</td>
        <td><span class="badge badge-{{ $tx->type }}">{{ $tx->type === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
        <td class="{{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
          {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
        </td>
        <td>{{ $tx->stock_after }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->note ?: '-' }}</td>
      </tr>
      @endforeach
      @if($transactions->isEmpty())
      <tr><td colspan="7" style="text-align:center;color:#9A8070;padding:20px">Tidak ada data</td></tr>
      @endif
    </tbody>
  </table>
</div>

<style>
.btn-pdf {
  color: #DC2626 !important;
  border-color: #FECACA !important;
}
.btn-pdf:hover {
  background: #FEF2F2 !important;
}
</style>
@endsection

@push('scripts')
<script>
// Export dengan membawa semua nilai filter saat ini
function exportForm(format) {
  const form   = document.getElementById('filter-form');
  const params = new URLSearchParams(new FormData(form)).toString();
  const base   = format === 'pdf'
    ? '{{ route('laporan.export-pdf') }}'
    : '{{ route('laporan.export') }}';
  window.location.href = base + '?' + params;
}

const rCtx = document.getElementById('rChart');
if (rCtx) {
  new Chart(rCtx.getContext('2d'), {
    type: 'bar',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [
        { label: 'Masuk',  data: {!! json_encode($chartMasuk) !!},  backgroundColor: 'rgba(76,175,80,0.7)',   borderRadius: 4 },
        { label: 'Keluar', data: {!! json_encode($chartKeluar) !!}, backgroundColor: 'rgba(139,94,60,0.6)',   borderRadius: 4 }
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { font: { family: 'DM Sans', size: 11 }, boxWidth: 10 } } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { family: 'DM Sans', size: 10 } } },
        y: { grid: { color: '#F2EDE4' }, ticks: { font: { family: 'DM Sans', size: 10 } } }
      }
    }
  });
}
</script>
@endpush