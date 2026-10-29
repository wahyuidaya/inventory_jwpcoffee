@extends('layouts.app')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="page-header"><h2>Edit Pengguna</h2><p>Ubah role akun pengguna</p></div>
<div class="card form-wrap">
  @if($errors->any())
    <div class="alert-error">@foreach($errors->all() as $e){{ $e }}<br>@endforeach</div>
  @endif
  <form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf @method('PUT')
    <div class="form-group">
      <label>Nama Lengkap</label>
      <input type="text" class="form-control" value="{{ $user->name }}" disabled>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" value="{{ $user->email }}" disabled>
    </div>
    <div class="form-group">
      <label>Role <span style="color:#E53935">*</span></label>
      <select name="role" class="form-control" required>
        <option value="admin" {{ (old('role',$user->role)=='admin')?'selected':'' }}>Admin</option>
        <option value="pimpinan" {{ (old('role',$user->role)=='pimpinan')?'selected':'' }}>Pimpinan</option>
      </select>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
      <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
    </div>
  </form>
</div>
@endsection