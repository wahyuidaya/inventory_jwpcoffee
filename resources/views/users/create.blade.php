@extends('layouts.app')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="page-header"><h2>Tambah Pengguna</h2><p>Daftarkan akun pengguna baru</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="form-group">
      <label>Nama Lengkap <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" placeholder="Nama lengkap pengguna" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label>Email <span style="color:#E53935">*</span></label>
      <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
      <label>Password <span style="color:#E53935">*</span></label>
      <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
      <span class="form-hint">Kombinasi huruf dan angka</span>
    </div>
    <div class="form-group">
      <label>Konfirmasi Password <span style="color:#E53935">*</span></label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
    </div>
    <div class="form-group">
      <label>Role <span style="color:#E53935">*</span></label>
      <select name="role" class="form-control" required>
        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
        <option value="pimpinan" {{ old('role')=='pimpinan'?'selected':'' }}>Pimpinan</option>
      </select>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Pengguna</button>
      <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection