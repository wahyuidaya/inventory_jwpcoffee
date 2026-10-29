@extends('layouts.app')
@section('page-title', 'Ubah Password')

@section('content')
<div class="breadcrumb">Profil <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Ubah Password</span></div>
<div class="page-header"><h2>Ubah Password</h2><p>Perbarui password login Anda</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif

  <form method="POST" action="{{ route('profile.password.update') }}">
    @csrf @method('PUT')
    <div class="form-group">
      <label>Password Saat Ini <span style="color:#E53935">*</span></label>
      <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
    </div>
    <div class="form-group">
      <label>Password Baru <span style="color:#E53935">*</span></label>
      <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      <span class="form-hint">Minimal 8 karakter, kombinasi huruf dan angka</span>
    </div>
    <div class="form-group">
      <label>Konfirmasi Password Baru <span style="color:#E53935">*</span></label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-key"></i> Ubah Password</button>
    </div>
  </form>
</div>
@endsection