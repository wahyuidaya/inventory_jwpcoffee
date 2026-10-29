@extends('layouts.app')
@section('page-title', 'Bahan Keluar')

@section('content')
<div class="breadcrumb">Persediaan Bahan <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Bahan Keluar</span></div>
<div class="page-header">
  <h2>Bahan Keluar</h2>
  <p>Pencatatan bahan baku yang digunakan atau dikeluarkan</p>
</div>

<div class="card">
  <div class="toolbar">
    <div class="toolbar-left">
      <form method="GET" style="display:flex;gap:8px;align-items:center">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" name="search" placeholder="Cari bahan..." value="{{ request('search') }}">
        </div>
        <input type="date" name="date" class="date-input" value="{{ request('date', date('Y-m-d')) }}">
        <button type="submit" class="btn btn-outline" style="padding:6px 12px">
          <i class="fa-solid fa-filter"></i>
        </button>
      </form>
    </div>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('persediaan.create', 'keluar') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Bahan Keluar
    </a>
    @endif
  </div>

  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Tanggal</th><th>Bahan Baku</th><th>Jumlah</th><th>Satuan</th><th>Stok Setelah</th><th>Dicatat Oleh</th><th>Keterangan</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @foreach($transactions as $i => $tx)
      <tr>
        <td style="color:#9A8070">{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $i + 1 }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->transaction_date->format('Y-m-d') }}</td>
        <td style="font-weight:500">{{ $tx->product->name ?? '-' }}</td>
        <td class="qty-out">-{{ $tx->quantity }}</td>
        <td style="color:#9A8070">{{ $tx->product->unit ?? '-' }}</td>
        <td>{{ $tx->stock_after }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->user->name ?? '-' }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->note ?: '-' }}</td>
        <td>
          <div class="actions">
            <button class="btn btn-info btn-sm" onclick="showTxDetail(
              '{{ addslashes($tx->product->name ?? '-') }}',
              '{{ addslashes($tx->product->code ?? '-') }}',
              'keluar',
              '{{ $tx->quantity }}',
              '{{ addslashes($tx->product->unit ?? '') }}',
              '{{ $tx->stock_before ?? '-' }}',
              '{{ $tx->stock_after ?? '-' }}',
              '{{ $tx->transaction_date->format('d M Y') }}',
              '{{ addslashes($tx->user->name ?? '-') }}',
              '{{ addslashes($tx->note ?: '-') }}'
            )"><i class="fa-solid fa-eye"></i></button>
          </div>
        </td>
      </tr>
      @endforeach
      @if($transactions->isEmpty())
      <tr><td colspan="9" style="text-align:center;color:#9A8070;padding:20px">Belum ada data bahan keluar</td></tr>
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

<div id="modalArea"></div>
@endsection

@push('scripts')
<script>
function showTxDetail(name, code, type, qty, unit, stockBefore, stockAfter, date, user, note) {
  const isIn = type === 'masuk';
  document.getElementById('modalArea').innerHTML = `
<div class="modal-overlay" onclick="closeModal(event,this)">
  <div class="modal-box">
    <div class="modal-title">Detail Transaksi</div>
    <div class="detail-card" style="background:#fff;border:1px solid #F2EDE4;margin-bottom:0">
      <div class="detail-row"><span class="detail-label">Bahan Baku</span><span class="detail-value">${name}</span></div>
      <div class="detail-row"><span class="detail-label">Kode</span><span class="detail-value"><code>${code}</code></span></div>
      <div class="detail-row"><span class="detail-label">Tipe</span><span class="detail-value"><span class="badge badge-${type}">${isIn ? 'Stok Masuk' : 'Stok Keluar'}</span></span></div>
      <div class="detail-row"><span class="detail-label">Jumlah</span><span class="detail-value ${isIn ? 'qty-in' : 'qty-out'}">${isIn ? '+' : '-'}${qty} ${unit}</span></div>
      <div class="detail-row"><span class="detail-label">Stok Sebelum</span><span class="detail-value">${stockBefore}</span></div>
      <div class="detail-row"><span class="detail-label">Stok Setelah</span><span class="detail-value">${stockAfter}</span></div>
      <div class="detail-row"><span class="detail-label">Tanggal</span><span class="detail-value">${date}</span></div>
      <div class="detail-row"><span class="detail-label">Dicatat Oleh</span><span class="detail-value">${user}</span></div>
      <div class="detail-row"><span class="detail-label">Keterangan</span><span class="detail-value">${note}</span></div>
    </div>
    <div style="margin-top:14px;display:flex;justify-content:flex-end">
      <button class="btn btn-outline" onclick="closeModal()">Tutup</button>
    </div>
  </div>
</div>`;
}
</script>
@endpush