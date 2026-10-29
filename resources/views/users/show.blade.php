@extends('layouts.app')
@section('page-title', $user->name)

@section('content')
<div class="page-header" style="display:flex;justify-content:space-between;align-items:flex-start">
  <div><h2>{{ $user->name }}</h2><p>{{ $user->email }}</p></div>
  <div style="display:flex;gap:8px">
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary"><i class="fa-solid fa-pen"></i> Edit</a>
    <a href="{{ route('users.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
  </div>
</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
  <div class="card">
    <div class="section-title">Informasi Pengguna</div>
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid #F2EDE4">
      <div style="width:54px;height:54px;border-radius:50%;background:{{ $user->role==='admin'?'#EDE7F6':'#E3F2FD' }};display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:500;color:{{ $user->role==='admin'?'#4527A0':'#0D47A1' }}">
        {{ strtoupper(substr($user->name,0,1)) }}{{ strtoupper(substr(strstr($user->name,' '),1,1)) }}
      </div>
      <div>
        <div style="font-weight:500;font-size:15px">{{ $user->name }}</div>
        <span class="role-badge role-{{ $user->role }}">{{ $user->role === 'admin' ? 'Admin' : 'Pimpinan' }}</span>
      </div>
    </div>
    <div class="detail-row"><span class="detail-label">Nama Lengkap</span><span class="detail-value">{{ $user->name }}</span></div>
    <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">{{ $user->email }}</span></div>
    <div class="detail-row"><span class="detail-label">Role</span><span class="detail-value"><span class="role-badge role-{{ $user->role }}">{{ $user->role === 'admin' ? 'Admin' : 'Pimpinan' }}</span></span></div>
    <div class="detail-row"><span class="detail-label">Tanggal Bergabung</span><span class="detail-value">{{ \Carbon\Carbon::parse($user->creat_at)->format('Y-m-d') }}</span></div>
  </div>
  <div class="card">
    <div class="section-title">Aktivitas</div>
    <div style="text-align:center;padding:20px 0;color:#9A8070">
      <i class="fa-solid fa-clock-rotate-left" style="font-size:28px;margin-bottom:10px;display:block;color:#C8A97E"></i>
      <div style="font-size:12px">Total transaksi dicatat: {{ $txCount }}</div>
    </div>
  </div>
</div>
@endsection