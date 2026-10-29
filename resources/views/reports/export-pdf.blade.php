<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10.5px;
    color: #1a1a1a;
    background: #fff;
    padding: 22px 26px;
  }

  /* ── Header ── */
  .report-header {
    border-bottom: 2.5px solid #4A3728;
    padding-bottom: 10px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  .report-title {
    font-size: 16px;
    font-weight: bold;
    color: #4A3728;
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
    line-height: 1.7;
  }

  /* ── Summary cards ── */
  .summary-row {
    width: 100%;
    display: table;
    table-layout: fixed;
    margin-bottom: 12px;
    border-spacing: 0;
  }
  .summary-card {
    display: table-cell;
    border: 1px solid #E8DED8;
    background: #FBF8F6;
    padding: 7px 11px;
    vertical-align: top;
  }
  .summary-card + .summary-card { border-left: none; }
  .summary-card:first-child { border-radius: 6px 0 0 6px; }
  .summary-card:last-child  { border-radius: 0 6px 6px 0; }
  .summary-card .s-label {
    font-size: 8.5px;
    color: #9A8070;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .summary-card .s-value {
    font-size: 16px;
    font-weight: bold;
    color: #4A3728;
    margin-top: 2px;
  }
  .summary-card.in  .s-value { color: #16A34A; }
  .summary-card.out .s-value { color: #DC2626; }
  .summary-card.warn .s-value { color: #D97706; }

  /* ── Section title ── */
  .section-title {
    font-size: 11px;
    font-weight: bold;
    color: #4A3728;
    margin-bottom: 7px;
    padding-bottom: 4px;
    border-bottom: 1px solid #EDE6E0;
  }

  /* ── Two-column layout ── */
  .two-col {
    display: table;
    width: 100%;
    table-layout: fixed;
    margin-bottom: 12px;
    border-spacing: 8px 0;
  }
  .col-left  { display: table-cell; width: 60%; vertical-align: top; }
  .col-right { display: table-cell; width: 40%; vertical-align: top; }

  /* ── Tables ── */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 9.5px;
  }
  thead tr {
    background: #4A3728;
    color: #fff;
  }
  thead th {
    padding: 6px 7px;
    text-align: left;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 0.3px;
  }
  thead th.center { text-align: center; }

  tbody tr:nth-child(even) { background: #F9F5F2; }
  tbody tr:nth-child(odd)  { background: #fff; }
  tbody td {
    padding: 5px 7px;
    border-bottom: 1px solid #EDE6E0;
    vertical-align: middle;
  }
  tbody td.center { text-align: center; }
  tbody td.muted  { color: #9A8070; font-size: 9px; }

  /* ── Badge ── */
  .badge {
    display: inline-block;
    padding: 1px 6px;
    border-radius: 99px;
    font-size: 8.5px;
    font-weight: bold;
  }
  .badge-masuk  { background: #DCFCE7; color: #15803D; }
  .badge-keluar { background: #FEE2E2; color: #B91C1C; }
  .badge-low    { background: #FEF3C7; color: #92400E; }
  .badge-ok     { background: #DCFCE7; color: #15803D; }

  .qty-in  { color: #16A34A; font-weight: 600; }
  .qty-out { color: #DC2626; font-weight: 600; }

  /* ── Footer ── */
  .report-footer {
    margin-top: 12px;
    padding-top: 7px;
    border-top: 1px solid #E8DED8;
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
    <div class="report-title">Laporan Stok Bahan Baku</div>
    <div class="report-subtitle">
      Periode: {{ $fromDate }} s/d {{ $toDate }}
      &nbsp;·&nbsp; Kategori: {{ $categoryLabel }}
      &nbsp;·&nbsp; Tipe: {{ $typeLabel }}
    </div>
  </div>
  <div class="report-meta">
    <div>Dicetak: {{ $exportedAt }}</div>
    <div>{{ $transactions->count() }} transaksi ditemukan</div>
  </div>
</div>

{{-- ── Summary ── --}}
<div class="summary-row">
  <div class="summary-card">
    <div class="s-label">Total Transaksi</div>
    <div class="s-value">{{ $transactions->count() }}</div>
  </div>
  <div class="summary-card in">
    <div class="s-label">Total Qty Masuk</div>
    <div class="s-value">+{{ $totalMasuk }}</div>
  </div>
  <div class="summary-card out">
    <div class="s-label">Total Qty Keluar</div>
    <div class="s-value">-{{ $totalKeluar }}</div>
  </div>
  <div class="summary-card warn">
    <div class="s-label">Stok Rendah</div>
    <div class="s-value">{{ $lowStock->count() }}</div>
  </div>
  <div class="summary-card">
    <div class="s-label">Total Jenis Bahan</div>
    <div class="s-value">{{ $products->count() }}</div>
  </div>
</div>

{{-- ── Two-column: transaksi + status stok ── --}}
<div class="two-col">

  {{-- Kiri: Detail Transaksi ── --}}
  <div class="col-left">
    <div class="section-title">Detail Transaksi</div>
    <table>
      <thead>
        <tr>
          <th style="width:68px">Tanggal</th>
          <th>Bahan Baku</th>
          <th style="width:70px">Kategori</th>
          <th class="center" style="width:50px">Tipe</th>
          <th class="center" style="width:42px">Qty</th>
          <th class="center" style="width:55px">Stok Akhir</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transactions as $tx)
        <tr>
          <td class="muted">{{ $tx->transaction_date->format('Y-m-d') }}</td>
          <td style="font-weight:500">{{ $tx->product->name ?? '-' }}</td>
          <td class="muted">{{ $tx->product->category->name ?? '-' }}</td>
          <td class="center">
            <span class="badge badge-{{ $tx->type }}">
              {{ $tx->type === 'masuk' ? '↑ Masuk' : '↓ Keluar' }}
            </span>
          </td>
          <td class="center {{ $tx->type === 'masuk' ? 'qty-in' : 'qty-out' }}">
            {{ $tx->type === 'masuk' ? '+' : '-' }}{{ $tx->quantity }}
          </td>
          <td class="center">{{ $tx->stock_after }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="6" style="text-align:center;color:#9A8070;padding:12px">
            Tidak ada data transaksi
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Kanan: Status Stok Saat Ini ── --}}
  <div class="col-right">
    <div class="section-title">Status Stok Saat Ini</div>
    <table>
      <thead>
        <tr>
          <th>Bahan</th>
          <th class="center" style="width:38px">Stok</th>
          <th class="center" style="width:38px">Satuan</th>
          <th class="center" style="width:46px">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $p)
        <tr>
          <td style="font-weight:500">{{ $p->name }}</td>
          <td class="center">{{ $p->stock }}</td>
          <td class="center muted">{{ $p->unit }}</td>
          <td class="center">
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

{{-- ── Footer ── --}}
<div class="report-footer">
  <span>Dokumen ini digenerate secara otomatis oleh sistem.</span>
  <span>{{ $fromDate }} s/d {{ $toDate }}</span>
</div>

</body>
</html>