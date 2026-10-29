@extends('layouts.app')
@section('page-title', 'Edit Kategori')

@section('content')
<div class="breadcrumb">Master Data <i class="fa-solid fa-angle-right" style="font-size:9px"></i>
  <a href="{{ route('categories.index') }}" style="color:#9A8070">Kategori Bahan</a>
  <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Edit</span>
</div>
<div class="page-header"><h2>Edit Kategori</h2><p>Perbarui data kategori</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('categories.update', $category->id) }}">
    @csrf @method('PUT')
    <div class="form-group">
      <label>Nama Kategori <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
    </div>
    <div class="form-group">
      <label>Deskripsi</label>
      <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
      <a href="{{ route('categories.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection