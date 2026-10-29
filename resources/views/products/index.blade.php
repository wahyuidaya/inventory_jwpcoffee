@extends('layouts.app')
@section('page-title', 'Daftar Bahan')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Daftar Bahan</span></div>
<div class="page-header"><h2>Daftar Bahan</h2><p>Daftar dan kelola seluruh bahan baku coffee shop</p></div>
<div class="card">
  <div class="toolbar">
    <div class="toolbar-left">
      <form method="GET" style="display:flex;gap:8px;align-items:center">
        <div class="search-wrap">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="text" name="search" placeholder="Cari bahan..." value="{{ request('search') }}">
        </div>
        <select name="category_id" class="filter-select" onchange="this.form.submit()">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </form>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Bahan
    </a>
  </div>

  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Kode</th><th>Nama Bahan</th><th>Kategori</th><th>Satuan</th><th>Stok</th><th>Min.</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @foreach($products as $i => $p)
      <tr>
        <td style="color:#9A8070">{{ ($products->currentPage() - 1) * $products->perPage() + $i + 1 }}</td>
        <td><code>{{ $p->code }}</code></td>
        <td style="font-weight:500">{{ $p->name }}</td>
        <td style="color:#9A8070">{{ $p->category->name ?? '-' }}</td>
        <td style="color:#9A8070">{{ $p->unit }}</td>
        <td class="{{ $p->isLowStock() ? 'stok-warn' : '' }}">{{ $p->stock }}</td>
        <td style="color:#9A8070">{{ $p->minimum_stock }}</td>
        <td>
          @if($p->isLowStock())
            <span style="background:#FFF3E0;color:#E65100;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:500">⚠ Rendah</span>
          @else
            <span style="background:#E8F5E9;color:#2E7D32;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:500">✓ Aman</span>
          @endif
        </td>
        <td>
          <div class="actions">
            <a href="{{ route('products.show', $p->id) }}" class="btn btn-info btn-sm"><i class="fa-solid fa-eye"></i></a>
            <a href="{{ route('products.edit', $p->id) }}" class="btn btn-outline btn-sm"><i class="fa-solid fa-pen"></i></a>
            <form method="POST" action="{{ route('products.destroy', $p->id) }}" style="display:inline"
                  onsubmit="return confirm('Hapus bahan baku ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger-sm"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
      @if($products->isEmpty())
      <tr><td colspan="9" style="text-align:center;color:#9A8070;padding:20px">Belum ada bahan baku</td></tr>
      @endif
    </tbody>
  </table>

  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-top:1px solid #F0EAE5">
    <p style="font-size:12px;color:#9A8070;margin:0">
      @if($products->total() > 0)
        Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} bahan
      @else
        Tidak ada bahan baku
      @endif
    </p>
    <div style="display:flex;gap:4px">
      @if($products->onFirstPage())
        <span class="page-btn disabled">‹</span>
      @else
        <a href="{{ $products->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">‹</a>
      @endif

      @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
        @if($page == $products->currentPage())
          <span class="page-btn active">{{ $page }}</span>
        @else
          <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">{{ $page }}</a>
        @endif
      @endforeach

      @if($products->hasMorePages())
        <a href="{{ $products->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">›</a>
      @else
        <span class="page-btn disabled">›</span>
      @endif
    </div>
  </div>

</div>
@endsection