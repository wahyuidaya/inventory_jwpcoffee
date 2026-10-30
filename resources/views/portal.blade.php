<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Sistem Informasi Inventori — JWP Coffee</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FAF6F0;
            color: #2C1A0E;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        header {
            background-color: #241408;
            border-bottom: 1px solid rgba(200, 169, 126, 0.25);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background-color: rgba(200, 169, 126, 0.15);
            color: #E8C878;
            border: 1px solid rgba(200, 169, 126, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .brand-text h1 {
            color: #FEFCF7;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.1;
            letter-spacing: 0.5px;
        }
        .brand-text span {
            color: #D6C2AA;
            font-size: 11px;
            display: block;
            margin-top: 3px;
        }
        .btn-portfolio {
            color: #E8C878;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(200, 169, 126, 0.25);
            font-size: 12px;
            padding: 7px 14px;
            border-radius: 9999px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .btn-portfolio:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #FFF;
        }
        .main-container {
            max-width: 960px;
            margin: 0 auto;
            padding: 48px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
        }
        .hero-intro {
            text-align: center;
            max-width: 620px;
            margin: 0 auto 36px;
        }
        .badge-pill {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 9999px;
            background-color: rgba(139, 94, 60, 0.1);
            color: #8B5E3C;
            border: 1px solid #DFC9A8;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            color: #2C1A0E;
            margin-bottom: 12px;
            font-weight: 700;
        }
        .hero-subtitle {
            font-size: 14px;
            color: #6D5B4F;
            line-height: 1.6;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
            margin-bottom: 30px;
        }
        .portal-card {
            background: #FFFFFF;
            border: 1px solid #EADBCC;
            border-radius: 14px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 4px 16px rgba(44, 26, 14, 0.04);
            transition: all 0.2s ease;
        }
        .portal-card:hover {
            border-color: #8B5E3C;
            box-shadow: 0 8px 24px rgba(139, 94, 60, 0.12);
            transform: translateY(-2px);
        }
        .card-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #FAF4EB;
            color: #8B5E3C;
            border: 1px solid #EADBCC;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #2C1A0E;
            margin-bottom: 8px;
        }
        .card-desc {
            font-size: 13px;
            color: #6D5B4F;
            line-height: 1.5;
            margin-bottom: 18px;
        }
        .card-features {
            list-style: none;
            margin-bottom: 20px;
        }
        .card-features li {
            font-size: 12px;
            color: #554438;
            margin-bottom: 7px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-features li i {
            color: #8B5E3C;
            font-size: 11px;
        }
        .demo-box {
            background: #FAF7F2;
            border: 1px dashed #DFC9A8;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 16px;
            font-size: 12px;
        }
        .demo-box-title {
            font-weight: 700;
            font-size: 11px;
            color: #8B5E3C;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .demo-cred {
            font-family: monospace;
            color: #2C1A0E;
            font-size: 11.5px;
        }
        .btn-enter {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px;
            border-radius: 9px;
            background: #8B5E3C;
            color: #FFFFFF;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-enter:hover {
            background: #4A2C1A;
        }
        .footer {
            border-top: 1px solid #EADBCC;
            padding: 18px 24px;
            text-align: center;
            font-size: 12px;
            color: #8B7A6E;
            background: #FAF6F0;
        }
    </style>
</head>
<body>

    <header>
        <div class="header-brand">
            <div class="brand-icon">
                <i class="fa-solid fa-mug-hot"></i>
            </div>
            <div class="brand-text">
                <h1>JWP Coffee</h1>
                <span>Inventory & Supply Chain System</span>
            </div>
        </div>
        <a href="https://wahyuidaya.github.io" target="_blank" rel="noopener noreferrer" class="btn-portfolio">
            <span>&larr; Portofolio Wahyu Idaya</span>
            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 10px;"></i>
        </a>
    </header>

    <div class="main-container">
        <div class="hero-intro">
            <span class="badge-pill">☕ Laravel Fullstack Portfolio Project</span>
            <h2 class="hero-title">Sistem Inventori JWP Coffee</h2>
            <p class="hero-subtitle">
                Aplikasi manajemen stok bahan baku dan produk kopi terintegrasi. Dilengkapi kontrol transaksi masuk &amp; keluar, multi-level hak akses, dan ekspor laporan real-time.
            </p>
        </div>

        <div class="portal-grid">
            <!-- 1. Admin Role Card -->
            <div class="portal-card">
                <div>
                    <div class="card-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <h3 class="card-title">Portal Admin</h3>
                    <p class="card-desc">
                        Akses operasional penuh untuk inventori harian, katalog produk, kelola transaksi stok, dan otorisasi pengguna.
                    </p>
                    <ul class="card-features">
                        <li><i class="fa-solid fa-check"></i> Kelola data master produk &amp; kategori</li>
                        <li><i class="fa-solid fa-check"></i> Pencatatan stok masuk (pembelian/supplier)</li>
                        <li><i class="fa-solid fa-check"></i> Pencatatan stok keluar &amp; penyesuaian</li>
                        <li><i class="fa-solid fa-check"></i> Manajemen akun staf &amp; pimpinan</li>
                    </ul>
                </div>
                <div>
                    <div class="demo-box">
                        <div class="demo-box-title">
                            <i class="fa-solid fa-key"></i> Kredensial Demo Admin
                        </div>
                        <div class="demo-cred">
                            Email: <strong>admin@gmail.com</strong><br>
                            Password: <strong>admin123</strong>
                        </div>
                    </div>
                    <a href="{{ route('login') }}?demo=admin" class="btn-enter">
                        <span>Masuk sebagai Admin</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Pimpinan Role Card -->
            <div class="portal-card">
                <div>
                    <div class="card-icon" style="color: #690dac; background: #F4EDFA; border-color: #EDE4F5;">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="card-title">Portal Pimpinan</h3>
                    <p class="card-desc">
                        Akses analitik eksekutif untuk memantau performa pergudangan, perputaran stok, dan rekapitulasi laporan.
                    </p>
                    <ul class="card-features">
                        <li><i class="fa-solid fa-check"></i> Dashboard ringkasan stok &amp; grafik mutasi</li>
                        <li><i class="fa-solid fa-check"></i> Audit riwayat transaksi masuk &amp; keluar</li>
                        <li><i class="fa-solid fa-check"></i> Ekspor laporan inventori (PDF &amp; Excel)</li>
                        <li><i class="fa-solid fa-check"></i> Monitoring batas minimum persediaan</li>
                    </ul>
                </div>
                <div>
                    <div class="demo-box">
                        <div class="demo-box-title">
                            <i class="fa-solid fa-key"></i> Kredensial Demo Pimpinan
                        </div>
                        <div class="demo-cred">
                            Email: <strong>pimpinan@gmail.com</strong><br>
                            Password: <strong>pimpinan123</strong>
                        </div>
                    </div>
                    <a href="{{ route('login') }}?demo=pimpinan" class="btn-enter" style="background: #4A2C1A;">
                        <span>Masuk sebagai Pimpinan</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        &copy; 2026 JWP Coffee &mdash; Portofolio Sistem Informasi Inventori oleh <strong>Wahyu Idaya</strong>.
    </footer>

</body>
</html>