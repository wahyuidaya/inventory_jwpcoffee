<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 11px;
    color: #1a1a1a;
    background: #fff;
    padding: 24px 28px;
  }

  /* ── Header ── */
  .report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2.5px solid #4A3728;
    padding-bottom: 10px;
    margin-bottom: 14px;
  }
  .report-title {
    font-size: 16px;
    font-weight: bold;
    color: #4A3728;
    letter-spacing: 0.3px;
  }
  .report-subtitle {
    font-size: 10px;
    color: #9A8070;
    margin-top: 3px;
  }
  .report-meta {
    text-align: right;
    font-size: 9.5px;
    color: #9A8070;
    line-height: 1.6;
  }

  /* ── Summary badges ── */
  .summary-row {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
  }
  .summary-card {
    flex: 1;
    border: 1px solid #E8DED8;
    border-radius: 6px;
    padding: 8px 12px;
    background: #FBF8F6;
  }
  .summary-card .label {
    font-size: 9px;
    color: #9A8070;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .summary-card .value {
    font-size: 15px;
    font-weight: bold;
    margin-top: 2px;
    color: #4A3728;
  }
  .summary-card.in  .value { color: #16A34A; }
  .summary-card.out .value { color: #DC2626; }

  /* ── Table ── */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
  }
  thead tr {
    background: #4A3728;
    color: #fff;
  }
  thead th {
    padding: 7px 8px;
    text-align: left;
    font-weight: bold;
    font-size: 9.5px;
    letter-spacing: 0.3px;
  }
  thead th.center { text-align: center; }

  tbody tr:nth-child(even) { background: #F9F5F2; }
  tbody tr:nth-child(odd)  { background: #fff; }

  tbody td {
    padding: 6px 8px;
    border-bottom: 1px solid #EDE6E0;
    vertical-align: middle;
  }
  tbody td.center { text-align: center; }
  tbody td.muted  { color: #9A8070; }

  /* ── Badge ── */
  .badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 99px;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 0.3px;
  }
  .badge-masuk  { background: #DCFCE7; color: #15803D; }
  .badge-keluar { background: #FEE2E2; color: #B91C1C; }

  .qty-in  { color: #16A34A; font-weight: 600; }
  .qty-out { color: #DC2626; font-weight: 600; }

  /* ── Footer ── */
  .report-footer {
    margin-top: 14px;
    border-top: 1px solid #E8DED8;
    padding-top: 8px;
    font-size: 9px;
    color: #B0A098;
    display: flex;
    justify-content: space-between;
  }
</style>
</head>
<body>

{{-- ── Header ── --}}
<div class="report-header">
  <div>
    <div class="report-title">Riwayat Transaksi Stok</div>
    <div class="report-subtitle">Periode: {{ $dateRange }}</div>
  </div>
  <div class="report-meta">
    <div>Dicetak pada: {{ $exportedAt }}</div>
    <div>Total transaksi: {{ $transactions->count() }}</div>
  </div>
</div>

{{-- ── Summary ── --}}
@php
  $totalMasuk  = $transactions->where('type', 'masuk')->count();
  $totalKeluar = $transactions->where('type', 'keluar')->count();
  $totalQtyIn  = $transactions->where('type', 'masuk')->sum('quantity');
  $totalQtyOut = $transactions->where('type', 'keluar')->sum('quantity');
@endphp

<div class="summary-row">
  <div class="summary-card">
    <div class="label">Total Transaksi</div>
    <div class="value">{{ $transactions->count() }}</div>
  </div>
  <div class="summary-card in">
    <div class="label">Stok Masuk</div>
    <div class="value">+{{ $totalQtyIn }}</div>
  </div>
  <div class="summary-card out">
    <div class="label">Stok Keluar</div>
    <div class="value">-{{ $totalQtyOut }}</div>
  </div>
  <div class="summary-card">
    <div class="label">Transaksi Masuk</div>
    <div class="value">{{ $totalMasuk }}x</div>
  </div>
  <div class="summary-card">
    <div class="label">Transaksi Keluar</div>
    <div class="value">{{ $totalKeluar }}x</div>
  </div>
</div>

{{-- ── Table ── --}}
<table>
  <thead>
    <tr>
      <th class="center" style="width:28px">#</th>
      <th style="width:72px">Tanggal</th>
      <th>Bahan Baku</th>
      <th class="center" style="width:60px">Tipe</th>
      <th class="center" style="width:52px">Jumlah</th>
      <th class="center" style="width:70px">Stok Setelah</th>
      <th style="width:100px">Oleh</th>
      <th>Keterangan</th>
    </tr>
  </thead>
  <tbody>
    @forelse($transactions as $i => $tx)
    <tr>
      <td class="center muted">{{ $i + 1 }}</td>
      <td class="muted">{{ $tx->transaction_date->format('Y-m-d') }}</td>
      <td style="font-weight:500">{{ $tx->product->name ?? '-' }}</td>
      <td class="center">
        <span class="badge badge-{{ $tx->type }}">
          {{ $tx->type === 'masuk' ? '↑ Masuk' : '↓ Keluar' }}
        </span>
      </td>
      <td class="center {{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
        {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
      </td>
      <td class="center">{{ $tx->stock_after }}</td>
      <td class="muted">{{ $tx->user->name ?? '-' }}</td>
      <td class="muted">{{ $tx->note ?: '-' }}</td>
    </tr>
    @empty
    <tr>
      <td colspan="8" style="text-align:center;color:#9A8070;padding:16px">
        Belum ada riwayat transaksi
      </td>
    </tr>
    @endforelse
  </tbody>
</table>

{{-- ── Footer ── --}}
<div class="report-footer">
  <span>Dokumen ini digenerate secara otomatis oleh sistem.</span>
  <span>Halaman 1</span>
</div>

</body>
</html>