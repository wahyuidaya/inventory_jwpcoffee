@extends('layouts.app')
@section('page-title', 'Riwayat Transaksi')

@section('content')
<div class="breadcrumb">Persediaan Bahan <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Riwayat Transaksi</span></div>
<div class="page-header">
  <h2>Riwayat Transaksi</h2>
  <p>Semua history pergerakan stok bahan baku</p>
</div>
<div class="card">
  <div class="toolbar">
    <div class="toolbar-left">
      <form method="GET" style="display:flex;gap:8px;align-items:center">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" name="search" placeholder="Cari bahan..." value="{{ request('search') }}">
        </div>
        <select name="type" class="filter-select" onchange="this.form.submit()">
          <option value="">Semua Tipe</option>
          <option value="masuk" {{ request('type')=='masuk'?'selected':'' }}>Masuk</option>
          <option value="keluar" {{ request('type')=='keluar'?'selected':'' }}>Keluar</option>
        </select>
        <input type="date" name="date_from" class="date-input" value="{{ request('date_from') }}">
        <span style="color:#9A8070;font-size:12px">s/d</span>
        <input type="date" name="date_to" class="date-input" value="{{ request('date_to') }}">
        <button type="submit" class="btn btn-outline" style="padding:6px 12px">
          <i class="fa-solid fa-filter"></i>
        </button>
      </form>
    </div>

    {{-- Export buttons --}}
    <div style="display:flex;gap:8px">
      <a href="{{ route('persediaan.riwayat.export', request()->query()) }}"
         class="btn btn-outline"
         title="Export ke Excel">
        <i class="fa-solid fa-file-excel"></i> Excel
      </a>
      <a href="{{ route('persediaan.riwayat.export-pdf', request()->query()) }}"
         class="btn btn-outline btn-pdf"
         title="Export ke PDF">
        <i class="fa-solid fa-file-pdf"></i> PDF
      </a>
    </div>
  </div>

  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Tanggal</th><th>Bahan Baku</th><th>Tipe</th><th>Jumlah</th><th>Stok Setelah</th><th>Oleh</th><th>Keterangan</th></tr>
    </thead>
    <tbody>
      @foreach($transactions as $i => $tx)
      <tr>
        <td style="color:#9A8070">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $i + 1 }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->transaction_date->format('Y-m-d') }}</td>
        <td style="font-weight:500">{{ $tx->product->name ?? '-' }}</td>
        <td><span class="badge badge-{{ $tx->type }}">{{ $tx->type === 'masuk' ? '↑ Masuk' : '↓ Keluar' }}</span></td>
        <td class="{{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
          {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
        </td>
        <td>{{ $tx->stock_after }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->user->name ?? '-' }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->note ?: '-' }}</td>
      </tr>
      @endforeach
      @if($transactions->isEmpty())
      <tr><td colspan="8" style="text-align:center;color:#9A8070;padding:20px">Belum ada riwayat transaksi</td></tr>
      @endif
    </tbody>
  </table>

  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-top:1px solid #F0EAE5">
    <p style="font-size:12px;color:#9A8070;margin:0">
      @if($transactions->total() > 0)
        Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
      @else
        Tidak ada transaksi
      @endif
    </p>
    <div style="display:flex;gap:4px">
      @if($transactions->onFirstPage())
        <span class="page-btn disabled">‹</span>
      @else
        <a href="{{ $transactions->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">‹</a>
      @endif

      @foreach($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
        @if($page == $transactions->currentPage())
          <span class="page-btn active">{{ $page }}</span>
        @else
          <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">{{ $page }}</a>
        @endif
      @endforeach

      @if($transactions->hasMorePages())
        <a href="{{ $transactions->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">›</a>
      @else
        <span class="page-btn disabled">›</span>
      @endif
    </div>
  </div>

</div>

<style>
/* Tombol PDF pakai warna merah agar berbeda dari Excel */
.btn-pdf {
  color: #DC2626 !important;
  border-color: #FECACA !important;
}
.btn-pdf:hover {
  background: #FEF2F2 !important;
}
</style>
@endsection