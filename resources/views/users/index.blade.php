@extends('layouts.app')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="page-header"><h2>Manajemen Pengguna</h2><p>Kelola akun pengguna sistem inventori</p></div>

<div class="card">
  <div class="toolbar">
    <form method="GET">
      <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" placeholder="Cari pengguna..." value="{{ request('search') }}">
      </div>
    </form>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus"></i> Tambah Pengguna
    </a>
    @endif
  </div>

  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @foreach($users as $i => $u)
      <tr>
        <td style="color:#9A8070">{{ ($users->currentPage() - 1) * $users->perPage() + $i + 1 }}</td>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div style="width:28px;height:28px;border-radius:50%;background:{{ $u->role==='admin'?'#EDE7F6':'#E3F2FD' }};display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:500;color:{{ $u->role==='admin'?'#4527A0':'#0D47A1' }}">
              {{ strtoupper(substr($u->name,0,1)) }}{{ strtoupper(substr(strstr($u->name,' '),1,1)) }}
            </div>
            <span style="font-weight:500">{{ $u->name }}</span>
          </div>
        </td>
        <td style="color:#9A8070;font-size:12px">{{ $u->email }}</td>
        <td>
          <span class="role-badge role-{{ $u->role }}">
            {{ $u->role === 'admin' ? 'Admin' : 'Pimpinan' }}
          </span>
        </td>
          <td style="font-size:11px;color:#9A8070">
            {{ $u->created_at ? $u->created_at->format('Y-m-d') : '-' }}
          </td>
        <td>
          <div class="actions">
            <a href="{{ route('users.show', $u->id) }}" class="btn btn-info btn-sm">
              <i class="fa-solid fa-eye"></i>
            </a>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-outline btn-sm">
              <i class="fa-solid fa-pen"></i>
            </a>
            <form method="POST" action="{{ route('users.destroy', $u->id) }}" style="display:inline"
                  onsubmit="return confirm('Hapus pengguna ini?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-danger-sm">
                <i class="fa-solid fa-trash"></i>
              </button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @endforeach
      @if($users->isEmpty())
      <tr><td colspan="6" style="text-align:center;color:#9A8070;padding:20px">Belum ada pengguna</td></tr>
      @endif
    </tbody>
  </table>

  <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-top:1px solid #F0EAE5">
    <p style="font-size:12px;color:#9A8070;margin:0">
      @if($users->total() > 0)
        Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
      @else
        Tidak ada pengguna
      @endif
    </p>
    <div style="display:flex;gap:4px">
      @if($users->onFirstPage())
        <span class="page-btn disabled">‹</span>
      @else
        <a href="{{ $users->previousPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">‹</a>
      @endif

      @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
        @if($page == $users->currentPage())
          <span class="page-btn active">{{ $page }}</span>
        @else
          <a href="{{ $url }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">{{ $page }}</a>
        @endif
      @endforeach

      @if($users->hasMorePages())
        <a href="{{ $users->nextPageUrl() }}&{{ http_build_query(request()->except('page')) }}" class="page-btn">›</a>
      @else
        <span class="page-btn disabled">›</span>
      @endif
    </div>
  </div>
</div>
@endsection