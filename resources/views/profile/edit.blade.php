@extends('layouts.app')
@section('page-title', 'Edit Profil')

@section('content')
<div class="breadcrumb">Profil <i class="fa-solid fa-angle-right" style="font-size:9px"></i> <span>Edit Profil</span></div>
<div class="page-header"><h2>Edit Profil</h2><p>Perbarui informasi akun Anda</p></div>
<div class="card form-wrap">
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px;padding-bottom:18px;border-bottom:1px solid #F2EDE4">
    <div style="width:54px;height:54px;border-radius:50%;background:#8B5E3C;color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:600">
      {{ strtoupper(substr($user->name,0,1)) }}{{ strtoupper(substr(strstr($user->name,' '),1,1)) }}
    </div>
    <div>
      <div style="font-weight:500;font-size:15px">{{ $user->name }}</div>
      <div style="font-size:11px;color:#9A8070;margin-top:2px">{{ $user->role === 'admin' ? 'Admin' : 'Pimpinan' }}</div>
    </div>
  </div>

  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif

  <form method="POST" action="{{ route('profile.update') }}">
    @csrf @method('PUT')
    <div class="form-group">
      <label>Nama Lengkap <span style="color:#E53935">*</span></label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>
    <div class="form-group">
      <label>Email <span style="color:#E53935">*</span></label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>
    <div class="form-group">
      <label>Role</label>
      <input type="text" class="form-control" value="{{ $user->role === 'admin' ? 'Admin' : 'Pimpinan' }}"
             disabled style="background:#F2EDE4;color:#9A8070">
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection