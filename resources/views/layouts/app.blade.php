<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>JWP Coffee â€” Inventory System</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<style>
:root{--cream:#FAF7F2;--cream-2:#F2EDE4;--brown-lt:#C8A97E;--brown:#8B5E3C;--brown-dk:#4A2C1A;--espresso:#2C1A0E;--white:#FFFFFF;--text-muted:#9A8070;--sidebar-w:230px}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--espresso);display:flex;min-height:100vh;font-size:14px}
.sidebar{width:var(--sidebar-w);background:var(--espresso);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:100;overflow-y:auto}
.sidebar-brand{padding:22px 18px 16px;border-bottom:1px solid rgba(255,255,255,0.07);flex-shrink:0}
.sidebar-brand h1{font-family:'Playfair Display',serif;font-size:17px;color:var(--brown-lt)}
.sidebar-brand span{font-size:10px;color:var(--text-muted);letter-spacing:1.5px;text-transform:uppercase}
.sidebar-nav{flex:1;padding:12px 8px}
.nav-label{font-size:10px;font-weight:500;letter-spacing:1.8px;text-transform-uppercase;color:var(--text-muted);padding:10px 10px 4px}
.nav-item{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;color:rgba(255,255,255,0.6);font-size:13px;cursor:pointer;transition:all 0.2s;margin-bottom:1px;border:none;background:none;width:100%;text-align:left;text-decoration:none}
.nav-item i{width:16px;text-align:center;font-size:13px}
.nav-item:hover{background:rgba(255,255,255,0.07);color:#fff}
.nav-item.active{background:var(--brown);color:#fff;font-weight:500}
.nav-item.active-parent{background:rgba(200,169,126,0.12);color:var(--brown-lt)}
.nav-sub{padding-left:26px;margin-top:3px}
.nav-sub .nav-item{font-size:12px;padding:6px 10px;margin-bottom:2px}
.nav-sub .nav-item.active{background:rgba(139,94,60,0.7)}
.nav-section{margin-bottom:8px}
.sidebar-footer{padding:12px 8px;border-top:1px solid rgba(255,255,255,0.07);flex-shrink:0}
.main-wrap{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
.topbar{background:var(--white);border-bottom:1px solid var(--cream-2);padding:0 22px;height:54px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50}
.topbar-title{font-family:'Playfair Display',serif;font-size:16px;color:var(--brown-dk)}
.topbar-right{display:flex;align-items:center;gap:12px}
.avatar{width:32px;height:32px;border-radius:50%;background:var(--brown);color:#fff;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:500}
.badge-notif{background:var(--brown-lt);color:var(--brown-dk);font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500}
.page-content{flex:1;padding:22px}
.page-header{margin-bottom:18px}
.page-header h2{font-family:'Playfair Display',serif;font-size:20px;color:var(--brown-dk)}
.page-header p{font-size:12px;color:var(--text-muted);margin-top:3px}
.breadcrumb{font-size:11px;color:var(--text-muted);margin-bottom:6px}
.breadcrumb span{color:var(--brown)}
.card{background:var(--white);border-radius:12px;border:1px solid var(--cream-2);padding:16px 20px}
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 15px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:500;cursor:pointer;border:none;transition:all 0.2s;text-decoration:none}
.btn-primary{background:var(--brown);color:#fff}
.btn-primary:hover{background:var(--brown-dk)}
.btn-outline{background:transparent;border:1px solid var(--brown-lt);color:var(--brown)}
.btn-outline:hover{background:var(--cream-2)}
.btn-info{background:#E3F2FD;color:#1565C0;border:1px solid #BBDEFB}
.btn-sm{padding:4px 10px;font-size:11px}
.btn-danger-sm{background:#FFF0EE;color:#E53935;border:1px solid #FFCCBB;padding:4px 10px;font-size:11px;border-radius:7px;cursor:pointer;display:inline-flex;align-items:center;gap:4px}
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
.stat-card{background:#fff;border-radius:12px;border:1px solid #F2EDE4;padding:16px;display:flex;flex-direction:column;gap:6px}
.stat-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-label{font-size:11px;color:#9A8070}
.stat-value{font-family:'Playfair Display',serif;font-size:24px;color:#4A2C1A}
.stat-sub{font-size:10px;color:#9A8070}
.grid-2{display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:16px}
.grid-2b{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.section-title{font-family:'Playfair Display',serif;font-size:14px;color:#4A2C1A;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between}
.section-title a{font-family:'DM Sans',sans-serif;font-size:11px;color:#8B5E3C;cursor:pointer}
table.simple{width:100%;border-collapse:collapse}
table.simple th{font-size:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:#9A8070;padding:6px 0;border-bottom:1px solid #F2EDE4;text-align:left}
table.simple td{font-size:12px;color:#2C1A0E;padding:8px 0;border-bottom:1px solid #FAF7F2}
.badge{display:inline-block;padding:2px 8px;border-radius:20px;font-size:10px;font-weight:500}
.badge-low{background:#FFF3E0;color:#E65100}
.badge-ok{background:#E8F5E9;color:#2E7D32}
.badge-masuk{background:#E8F5E9;color:#2E7D32}
.badge-keluar{background:#FFF3E0;color:#E65100}
.stok-bar-bg{flex:1;height:5px;background:#F2EDE4;border-radius:4px}
.stok-bar-fill{height:5px;border-radius:4px;background:#8B5E3C}
.toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:10px;flex-wrap:wrap}
.toolbar-left{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.search-wrap{position:relative}
.search-wrap i{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#C8A97E;font-size:12px}
.search-wrap input{padding:7px 12px 7px 30px;border:1.5px solid #F2EDE4;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2C1A0E;background:#FAF7F2;outline:none;width:190px}
.search-wrap input:focus{border-color:#C8A97E;background:#fff}
select.filter-select,input.date-input{padding:7px 12px;border:1.5px solid #F2EDE4;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2C1A0E;background:#FAF7F2;outline:none}
table.data-table{width:100%;border-collapse:collapse}
table.data-table thead tr{background:#FAF7F2}
table.data-table th{font-size:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:#9A8070;padding:9px 12px;text-align:left;border-bottom:1px solid #F2EDE4}
table.data-table td{padding:10px 12px;font-size:12px;color:#2C1A0E;border-bottom:1px solid #FAF7F2;vertical-align:middle}
table.data-table tr:hover td{background:#FFFDF9}
.actions{display:flex;gap:5px;flex-wrap:wrap}
.qty-in{color:#2E7D32;font-weight:500}
.qty-out{color:#E65100;font-weight:500}
.stok-warn{color:#E65100;font-weight:500}
.form-wrap{max-width:600px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:18px}
.form-group label{display:block;font-size:11px;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:#8B5E3C;margin-bottom:6px}
.form-control{width:100%;padding:9px 12px;border:1.5px solid #F2EDE4;border-radius:9px;font-family:'DM Sans',sans-serif;font-size:13px;color:#2C1A0E;background:#FAF7F2;outline:none;transition:border-color 0.2s}
.form-control:focus{border-color:#C8A97E;background:#fff}
textarea.form-control{resize:vertical;min-height:80px}
.form-hint{color:#9A8070;font-size:10px;margin-top:3px}
.form-actions{display:flex;gap:10px;margin-top:6px}
.stok-info{background:#FAF7F2;border:1px solid #F2EDE4;border-radius:10px;padding:12px 16px;margin-bottom:18px;display:flex;gap:20px;align-items:center}
.stok-info-item label{font-size:10px;color:#9A8070;display:block;margin-bottom:2px}
.stok-info-item span{font-size:16px;font-family:'Playfair Display',serif;color:#4A2C1A}
.filter-card{background:#fff;border-radius:12px;border:1px solid #F2EDE4;padding:14px 18px;margin-bottom:16px;display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap}
.filter-group{display:flex;flex-direction:column;gap:4px}
.filter-group label{font-size:10px;font-weight:500;text-transform:uppercase;letter-spacing:0.8px;color:#8B5E3C}
.filter-control{padding:7px 12px;border:1.5px solid #F2EDE4;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:12px;color:#2C1A0E;background:#FAF7F2;outline:none}
.summary-row{display:flex;gap:10px;margin-bottom:16px}
.summary-box{flex:1;background:#fff;border:1px solid #F2EDE4;border-radius:10px;padding:12px 16px}
.summary-box .s-label{font-size:11px;color:#9A8070;margin-bottom:3px}
.summary-box .s-value{font-family:'Playfair Display',serif;font-size:21px;color:#4A2C1A}
.report-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
.detail-card{background:#FAF7F2;border-radius:10px;padding:14px 18px;margin-bottom:12px}
.detail-row{display:flex;padding:7px 0;border-bottom:1px solid #F2EDE4;font-size:12px}
.detail-row:last-child{border-bottom:none}
.detail-label{width:160px;color:#9A8070;flex-shrink:0}
.detail-value{color:#2C1A0E;font-weight:500}
code{background:#F2EDE4;padding:2px 6px;border-radius:4px;font-size:11px}
.alert-success{background:#E8F5E9;border:1px solid #C8E6C9;color:#2E7D32;border-radius:8px;padding:8px 14px;font-size:12px;margin-bottom:14px}
.alert-error{background:#FFF0EE;border:1px solid #FFCCBB;color:#E53935;border-radius:8px;padding:8px 14px;font-size:12px;margin-bottom:14px}
.role-badge{padding:2px 9px;border-radius:20px;font-size:10px;font-weight:500}
.role-admin{background:#EDE7F6;color:#4527A0}
.role-pimpinan{background:#E3F2FD;color:#0D47A1}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;display:flex;align-items:center;justify-content:center}
.modal-box{background:#fff;border-radius:14px;padding:24px;width:420px;max-width:90%}
.modal-title{font-family:'Playfair Display',serif;font-size:16px;color:var(--brown-dk);margin-bottom:16px}
.page-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  border-radius: 6px;
  font-size: 12px;
  color: #4A3728;
  border: 1px solid #E8DDD6;
  text-decoration: none;
  transition: all .15s;
}
.page-btn:hover { background: #F0EAE5; }
.page-btn.active { background: #4A3728; color: #fff; border-color: #4A3728; }
.page-btn.disabled { color: #C5B5AA; cursor: default; border-color: #F0EAE5; }
.menu-toggle {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 4px;
  width: 32px;
  height: 32px;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  margin-right: 10px;
}
.menu-toggle span {
  display: block;
  width: 20px;
  height: 2px;
  background: var(--brown-dk);
  border-radius: 2px;
}
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 95;
}
.sidebar-overlay.show { display: block; }

@media (max-width: 768px) {
  .menu-toggle { display: flex; }
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.22s ease;
    top: 54px;
    height: calc(100vh - 54px);
    z-index: 100;
  }
  .sidebar.open {
    transform: translateX(0);
  }
  .main-wrap {
    margin-left: 0;
  }
  .stat-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
  .grid-2, .grid-2b {
    grid-template-columns: 1fr !important;
  }
}
@media (max-width: 480px) {
  .stat-grid {
    grid-template-columns: 1fr !important;
  }
  .page-content {
    padding: 14px;
  }
}
</style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleInventorySidebar()"></div>

<aside class="sidebar" id="inventorySidebar">
  <div class="sidebar-brand">
    <h1>â˜• JWP Coffee</h1>
    <span>Inventory System</span>
  </div>

  <nav class="sidebar-nav">

    <div class="nav-section">
      <div class="nav-label">MENU UTAMA</div>
      <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-house"></i> Dashboard
      </a>
    </div>

    <div class="nav-section">
      <div class="nav-label">INVENTORI</div>
      <a href="{{ route('persediaan.masuk') }}" class="nav-item {{ request()->routeIs('persediaan.*') ? 'active-parent' : '' }}">
        <i class="fa-solid fa-boxes-stacked"></i> Persediaan Bahan
      </a>
      @if(request()->routeIs('persediaan.*'))
      <div class="nav-sub">
        <a href="{{ route('persediaan.masuk') }}" class="nav-item {{ request()->routeIs('persediaan.masuk') ? 'active' : '' }}">
          <i class="fa-solid fa-download"></i> Bahan Masuk
        </a>
        <a href="{{ route('persediaan.keluar') }}" class="nav-item {{ request()->routeIs('persediaan.keluar') ? 'active' : '' }}">
          <i class="fa-solid fa-arrow-up-from-bracket"></i> Bahan Keluar
        </a>
        <a href="{{ route('persediaan.riwayat') }}" class="nav-item {{ request()->routeIs('persediaan.riwayat') ? 'active' : '' }}">
          <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Transaksi
        </a>
      </div>
      @endif
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="nav-section">
      <div class="nav-label">MASTER DATA</div>
      <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') || request()->routeIs('products.*') || request()->routeIs('users.*') ? 'active-parent' : '' }}">
        <i class="fa-solid fa-database"></i> Master Data
      </a>
      @if(request()->routeIs('categories.*') || request()->routeIs('products.*') || request()->routeIs('users.*'))
      <div class="nav-sub">
        <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
          <i class="fa-solid fa-tags"></i> Kategori Bahan
        </a>
        <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
          <i class="fa-solid fa-box-open"></i> Daftar Bahan
        </a>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
          <i class="fa-solid fa-users"></i> Manajemen Pengguna
        </a>
      </div>
      @endif
    </div>
    @endif

    <div class="nav-section">
      <div class="nav-label">LAINNYA</div>
      <a href="{{ route('laporan') }}" class="nav-item {{ request()->routeIs('laporan') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-bar"></i> Laporan
      </a>
      <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active-parent' : '' }}">
        <i class="fa-solid fa-user"></i> Profil
      </a>
      @if(request()->routeIs('profile.*'))
      <div class="nav-sub">
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
          <i class="fa-solid fa-pen-to-square"></i> Edit Profil
        </a>
        <a href="{{ route('profile.password') }}" class="nav-item {{ request()->routeIs('profile.password') ? 'active' : '' }}">
          <i class="fa-solid fa-key"></i> Ubah Password
        </a>
      </div>
      @endif
    </div>

  </nav>

  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="nav-item" style="color:rgba(255,255,255,0.5)">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </button>
    </form>
  </div>
</aside>

<div class="main-wrap">
  <header class="topbar">
    <div style="display:flex;align-items:center;">
      <button type="button" class="menu-toggle" onclick="toggleInventorySidebar()" aria-label="Toggle Menu"><span></span><span></span><span></span></button>
      <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
      @php $lowCount = \App\Models\Product::whereColumn('stock', '<=', 'minimum_stock')->count(); @endphp
      <span class="badge-notif"><i class="fa-solid fa-triangle-exclamation"></i> {{ $lowCount }} stok rendah</span>
      <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name, ' '), 1, 1)) }}</div>
    </div>
  </header>

  <main class="page-content">
    @if(session('success'))
      <div class="alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert-error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
  </main>
</div>

<div id="modalArea"></div>
<script>
function toggleInventorySidebar() { var sb = document.getElementById('inventorySidebar'); var ov = document.getElementById('sidebarOverlay'); if(sb) sb.classList.toggle('open'); if(ov) ov.classList.toggle('show'); }
function closeModal(e, overlay) {
  if (!e || e.target === overlay) document.getElementById('modalArea').innerHTML = '';
}
</script>

@stack('scripts')
</body>
</html>



