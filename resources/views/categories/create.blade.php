@extends('layouts.app')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ route('categories.index') }}" style="color:#9A8070">Kategori Bahan</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Tambah</span>
</div>
<div class="page-header"><h2>Tambah Kategori</h2><p>Buat kategori baru</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('categories.store') }}">
    @csrf
    <div class="form-group">
      <label>Nama Kategori <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" placeholder="cth: Kopi, Susu..." value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control" placeholder="Keterangan singkat (opsional)...">{{ old('description') }}</textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Kategori</button>
      <a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection