@extends('layouts.app')
@section('page-title', 'Detail Transaksi')

@section('content')
<div class="breadcrumb">
  Persediaan Bahan <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ $transaction->type === 'masuk' ? route('persediaan.masuk') : route('persediaan.keluar') }}"
     style="color:#9A8070">{{ $transaction->type === 'masuk' ? 'Bahan Masuk' : 'Bahan Keluar' }}</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <span>Detail</span>
</div>
<div class="page-header" style="display:flex;justify-content:space-between;align-items:flex-start">
  <div>
    <h2>Detail Transaksi</h2>
    <p>{{ $transaction->product->name ?? '-' }}</p>
  </div>
  <a href="{{ $transaction->type === 'masuk' ? route('persediaan.masuk') : route('persediaan.keluar') }}"
     class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card form-wrap">
  <div class="detail-card" style="background:#fff;border:1px solid #F2EDE4;margin-bottom:0">
    <div class="detail-row">
      <span class="detail-label">Bahan Baku</span>
      <span class="detail-value">{{ $transaction->product->name ?? '-' }}</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Kode</span>
      <span class="detail-value"><code>{{ $transaction->product->code ?? '-' }}</code></span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Tipe</span>
      <span class="detail-value">
        <span class="badge badge-{{ $transaction->type }}">
          {{ $transaction->type === 'masuk' ? 'Stok Masuk' : 'Stok Keluar' }}
        </span>
      </span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Jumlah</span>
      <span class="detail-value {{ $transaction->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
        {{ $transaction->type === 'masuk' ? '+' : '-' }}{{ $transaction->quantity }} {{ $transaction->product->unit ?? '' }}
      </span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Stok Sebelum</span>
      <span class="detail-value">{{ $transaction->stock_before ?? '-' }}</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Stok Setelah</span>
      <span class="detail-value">{{ $transaction->stock_after ?? '-' }}</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Tanggal</span>
      <span class="detail-value">{{ $transaction->transaction_date ? $transaction->transaction_date->format('d M Y') : '-' }}</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Dicatat Oleh</span>
      <span class="detail-value">{{ $transaction->user->name ?? '-' }}</span>
    </div>
    <div class="detail-row">
      <span class="detail-label">Keterangan</span>
      <span class="detail-value">{{ $transaction->note ?: '-' }}</span>
    </div>
  </div>
</div>
@endsection