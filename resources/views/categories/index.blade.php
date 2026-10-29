@extends('layouts.app')
@section('page-title', 'Kategori Bahan')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Kategori Bahan</span></div>
<div class="page-header">
  <h2>Kategori Bahan</h2>
  <p>Kelola kategori pengelompokan bahan baku</p>
</div>
<div class="card">
  <div class="toolbar">
    <form method="GET">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}">
      </div>
    </form>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Kategori
    </a>
    @endif
  </div>

  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Bahan</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @foreach($categories as $i => $cat)
      <tr>
        <td style="color:#9A8070">{{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}</td>
        <td style="font-weight:500">{{ $cat->name }}</td>
        <td style="color:#9A8070">{{ $cat->description ?: '-' }}</td>
        <td>
          <span style="background:#FDF3E7;color:#8B5E3C;padding:2px 8px;border-radius:20px;font-size:10px">
            {{ $cat->products_count }} bahan
          </span>
        </td>
        <td>
          <div class="actions">
            <button class="btn btn-info btn-sm"
              onclick="showKatDetail({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description) }}', {{ $cat->products_count }})">
              <i class="fa-solid fa-eye"></i>
            </button>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('categories.edit', $cat->id) }}" class="btn btn-outline btn-sm">
              <i class="fa-solid fa-pen"></i>
            </a>
            <form method="POST" action="{{ route('categories.destroy', $cat->id) }}" style="display:inline"
                  onsubmit="return confirm('Hapus kategori ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger-sm"><i class="fa-solid fa-trash"></i></button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
      @if($categories->isEmpty())
      <tr><td colspan="5" style="text-align:center;color:#9A8070;padding:20px">Belum ada kategori</td></tr>
      @endif
    </tbody>
  </table>

  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-top:1px solid #F0EAE5">
    <p style="font-size:12px;color:#9A8070;margin:0">
      @if($categories->total() > 0)
        Menampilkan {{ $categories->firstItem() }}–{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
      @else
        Tidak ada kategori
      @endif
    </p>
    <div style="display:flex;gap:4px">
      @if($categories->onFirstPage())
        <span class="page-btn disabled">‹</span>
      @else
        <a href="{{ $categories->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">‹</a>
      @endif

      @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
        @if($page == $categories->currentPage())
          <span class="page-btn active">{{ $page }}</span>
        @else
          <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">{{ $page }}</a>
        @endif
      @endforeach

      @if($categories->hasMorePages())
        <a href="{{ $categories->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">›</a>
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
function showKatDetail(id, name, desc, count) {
  document.getElementById('modalArea').innerHTML = `
<div class="modal-overlay" onclick="closeModal(event,this)">
  <div class="modal-box">
    <div class="modal-title">Detail Kategori</div>
    <div class="detail-card" style="background:#fff;border:1px solid #F2EDE4;margin-bottom:0">
      <div class="detail-row"><span class="detail-label">Nama Kategori</span><span class="detail-value">${name}</span></div>
      <div class="detail-row"><span class="detail-label">Deskripsi</span><span class="detail-value">${desc||'-'}</span></div>
      <div class="detail-row"><span class="detail-label">Jumlah Bahan</span><span class="detail-value">${count} bahan</span></div>
    </div>
    <div style="margin-top:14px;display:flex;gap:8px;justify-content:flex-end">
      <a href="/categories/${id}/edit" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen"></i> Edit</a>
      <button class="btn btn-outline" onclick="closeModal()">Tutup</button>
    </div>
  </div>
</div>`;
}
</script>
@endpush