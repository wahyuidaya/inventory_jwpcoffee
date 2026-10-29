@extends('layouts.app')
@section('page-title', 'Edit Bahan Baku')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ route('products.index') }}" style="color:#9A8070">Daftar Bahan</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Edit</span>
</div>
<div class="page-header"><h2>Edit Bahan Baku</h2><p>Perbarui informasi bahan baku</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('products.update', $product->id) }}">
    @csrf @method('PUT')
    <div class="form-row">
      <div class="form-group">
        <label>Kode Bahan <span style="color:#E53935">*</span></label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $product->code) }}" required>
      </div>
      <div class="form-group">
        <label>Kategori <span style="color:#E53935">*</span></label>
        <select name="category_id" class="form-control" required>
          <option value="">Pilih kategori...</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id)==$cat->id)?'selected':'' }}>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="form-group">
      <label>Nama Bahan Baku <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Satuan <span style="color:#E53935">*</span></label>
        <select name="unit" class="form-control" required>
          @foreach($units as $u)
          <option value="{{ $u }}" {{ (old('unit', $product->unit)==$u)?'selected':'' }}>{{ $u }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label>Stok Saat Ini <span style="color:#E53935">*</span></label>
        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" min="0" required>
      </div>
    </div>
    <div class="form-group">
      <label>Minimum Stok</label>
      <input type="number" name="minimum_stock" class="form-control" value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0">
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
      <a href="{{ route('products.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection