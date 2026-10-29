@extends('layouts.app')
@section('page-title', 'Tambah Bahan Baku')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ route('products.index') }}" style="color:#9A8070">Daftar Bahan</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Tambah</span>
</div>
<div class="page-header"><h2>Tambah Bahan Baku</h2><p>Daftarkan bahan baku baru</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('products.store') }}">
    @csrf
    <div class="form-row">
      <div class="form-group">
        <label>Kode Bahan <span style="color:#E53935">*</span></label>
        <input type="text" name="code" class="form-control" placeholder="cth: KB-001" value="{{ old('code') }}" required>
        <span class="form-hint">Format: KB-001, KB-002, dst.</span>
      </div>
      <div class="form-group">
        <label>Kategori <span style="color:#E53935">*</span></label>
        <select name="category_id" class="form-control" required>
          <option value="">Pilih kategori...</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group">
      <label>Nama Bahan Baku <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" placeholder="cth: Kopi Arabica, Susu UHT..." value="{{ old('name') }}" required>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Satuan <span style="color:#E53935">*</span></label>
        <select name="unit" class="form-control" required>
          <option value="">Pilih satuan...</option>
          @foreach($units as $u)
          <option value="{{ $u }}" {{ old('unit')==$u?'selected':'' }}>{{ $u }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Stok Awal <span style="color:#E53935">*</span></label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
      </div>
    </div>
    <div class="form-group">
      <label>Minimum Stok</label>
      <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', 10) }}" min="0">
      <span class="form-hint">Peringatan jika stok di bawah nilai ini</span>
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea name="description" class="form-control" placeholder="Deskripsi singkat (opsional)...">{{ old('description') }}</textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Bahan</button>
      <a href="{{ route('products.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection