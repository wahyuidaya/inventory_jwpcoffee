@extends('layouts.app')
@section('page-title', $product->name)

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ route('products.index') }}" style="color:#9A8070">Daftar Bahan</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Detail</span>
</div>
<div class="page-header" style="display:flex;justify-content:space-between;align-items:flex-start">
  <div>
    <h2>{{ $product->name }}</h2>
    <p><code>{{ $product->code }}</code> · {{ $product->category->name ?? '-' }}</p>
  </div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
      <i class="fa-solid fa-pen"></i> Edit
    </a>
    <a href="{{ route('products.index') }}" class="btn btn-outline">
      <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
  <div class="card">
    <div class="section-title">Informasi Bahan</div>
    <div class="detail-row"><span class="detail-label">Kode</span><span class="detail-value"><code>{{ $product->code }}</code></span></div>
    <div class="detail-row"><span class="detail-label">Nama Bahan</span><span class="detail-value">{{ $product->name }}</span></div>
    <div class="detail-row"><span class="detail-label">Kategori</span><span class="detail-value">{{ $product->category->name ?? '-' }}</span></div>
    <div class="detail-row"><span class="detail-label">Satuan</span><span class="detail-value">{{ $product->unit }}</span></div>
    <div class="detail-row"><span class="detail-label">Deskripsi</span><span class="detail-value">{{ $product->description ?: '-' }}</span></div>
  </div>
  <div class="card">
    <div class="section-title">Status Stok</div>
    <div style="text-align:center;padding:16px 0">
      <div style="font-size:42px;font-family:'Playfair Display',serif;color:{{ $product->isLowStock() ? '#E53935' : '#4A2C1A' }}">
        {{ $product->stock }}
      </div>
      <div style="font-size:12px;color:#9A8070;margin-bottom:12px">{{ $product->unit }}</div>
      @if($product->isLowStock())
        <span style="background:#FFF3E0;color:#E65100;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500">⚠ Stok Rendah</span>
      @else
        <span style="background:#E8F5E9;color:#2E7D32;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:500">✓ Stok Aman</span>
      @endif
    </div>
    <div class="detail-row">
      <span class="detail-label">Minimum Stok</span>
      <span class="detail-value">{{ $product->minimum_stock }} {{ $product->unit }}</span>
    </div>
  </div>
</div>

<div class="card">
  <div class="section-title">Riwayat Transaksi Terakhir</div>
  <table class="data-table">
    <thead>
      <tr><th>Tanggal</th><th>Tipe</th><th>Jumlah</th><th>Stok Setelah</th><th>Keterangan</th></tr>
    </thead>
    <tbody>
      @forelse($recentTx as $tx)
      <tr>
        <td style="font-size:11px;color:#9A8070">{{ $tx->transaction_date->format('Y-m-d') }}</td>
        <td><span class="badge badge-{{ $tx->type }}">{{ $tx->type === 'masuk' ? 'Masuk' : 'Keluar' }}</span></td>
        <td class="{{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
          {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
        </td>
        <td>{{ $tx->stock_after }}</td>
        <td style="font-size:11px;color:#9A8070">{{ $tx->note ?: '-' }}</td>
      </tr>
      @empty
      <tr><td colspan="5" style="text-align:center;color:#9A8070;padding:20px">Belum ada transaksi</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection