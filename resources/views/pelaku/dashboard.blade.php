<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMKM Terpadu - Dashboard Usaha</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #3a7bd5;
            --primary-dark: #2d62aa;
            --secondary: #00d2ff;
            --dark: #333;
            --light: #f8f9fa;
            --success: #38b653;
            --warning: #ffc107;
            --danger: #dc3545;
            --gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 0.8rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            color: white;
        }

        .logo i {
            margin-right: 10px;
            font-size: 1.8rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            overflow: hidden;
        }

        .user-avatar i {
            font-size: 1.5rem;
        }

        .user-name {
            font-weight: 600;
        }

        .main-content {
            margin-top: 90px;
            padding: 20px 0;
            min-height: calc(100vh - 160px);
        }

        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .welcome-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .welcome-heading h1 {
            font-size: 1.7rem;
            color: var(--dark);
        }

        .today-date {
            color: var(--gray);
            font-size: 1rem;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.income {
            border-left: 4px solid var(--success);
        }

        .stat-card.orders {
            border-left: 4px solid var(--primary);
        }

        .stat-card.products {
            border-left: 4px solid var(--warning);
        }

        .stat-card.engagement {
            border-left: 4px solid var(--secondary);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 0.9rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .stat-icon.income {
            background-color: var(--success);
        }

        .stat-icon.orders {
            background-color: var(--primary);
        }

        .stat-icon.products {
            background-color: var(--warning);
        }

        .stat-icon.engagement {
            background-color: var(--secondary);
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-compare {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .stat-compare.positive {
            color: var(--success);
        }

        .stat-compare.negative {
            color: var(--danger);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.2rem;
            color: var(--dark);
            font-weight: 600;
        }

        .filter-dropdown {
            padding: 8px 12px;
            border: 1px solid #e1e5eb;
            border-radius: 5px;
            font-size: 0.9rem;
            background-color: white;
            cursor: pointer;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .placeholder-chart {
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #f5f7fa 0%, #e5e9f2 50%, #f5f7fa 100%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 5px;
        }

        @keyframes shimmer {
            0% {
                background-position: -100% 0;
            }
            100% {
                background-position: 100% 0;
            }
        }

        .activity-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f1f1f1;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .activity-icon.order {
            background-color: var(--primary);
        }

        .activity-icon.message {
            background-color: var(--success);
        }

        .activity-icon.alert {
            background-color: var(--warning);
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 30px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: white;
            border: 1px solid #e1e5eb;
            border-radius: 8px;
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }

        .action-btn i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(58, 123, 213, 0.2);
        }

        .footer {
            background: white;
            padding: 20px 0;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
            border-top: 1px solid #f1f1f1;
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-menu li {
            margin-right: 25px;
        }

        .nav-menu li:last-child {
            margin-right: 0;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            padding-bottom: 5px;
        }

        .nav-menu a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease;
        }

        .nav-menu a:hover:after {
            width: 100%;
        }

        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 30px;
            height: 25px;
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 10;
        }

        .menu-toggle div {
            width: 30px;
            height: 3px;
            background: white;
            border-radius: 10px;
            transition: all 0.3s linear;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: flex;
            }

            .nav-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 70%;
                height: 100vh;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                flex-direction: column;
                padding-top: 80px;
                transition: right 0.3s ease;
                z-index: 5;
            }

            .nav-menu.active {
                right: 0;
            }

            .nav-menu li {
                margin: 0;
                padding: 15px 30px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .quick-stats {
                grid-template-columns: 1fr;
            }
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            height: 180px;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details {
            padding: 20px;
        }

        .product-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .product-price {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .product-stock {
            font-size: 0.85rem;
            color: var(--success);
            margin-bottom: 15px;
        }

        .product-actions {
            display: flex;
            justify-content: space-between;
        }

        .edit-btn, .delete-btn {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .edit-btn {
            background-color: var(--light);
            color: var(--dark);
        }

        .delete-btn {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .edit-btn:hover {
            background-color: var(--primary);
            color: white;
        }

        .delete-btn:hover {
            background-color: var(--danger);
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <a href="#" class="logo">
                    <i class="fas fa-store"></i>
                    UMKM Terpadu
                </a>

                <ul class="nav-menu">
                    <li><a href="#dashboard">Dashboard</a></li>
                    <li><a href="#produk">Produk</a></li>
                    <li><a href="#pesanan">Pesanan</a></li>
                    <li><a href="#laporan">Laporan</a></li>
                    <li><a href="#bantuan">Bantuan</a></li>
                </ul>

                <button class="menu-toggle">
                    <div></div>
                    <div></div>
                    <div></div>
                </button>

                <div class="user-profile">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-name">Budi Santoso</div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <section class="welcome-section" id="dashboard">
                <div class="welcome-heading">
                    <h1>Selamat Datang, Budi Santoso!</h1>
                    <div class="today-date">Rabu, 16 April 2025</div>
                </div>
                <p>Pantau perkembangan usaha Anda secara real-time dan kelola produk dengan mudah.</p>
            </section>

            <section class="quick-stats">
                <div class="stat-card income">
                    <div class="stat-header">
                        <div class="stat-title">Pendapatan Bulan Ini</div>
                        <div class="stat-icon income">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="stat-value">Rp 8,5 juta</div>
                    <div class="stat-compare positive">
                        <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
                    </div>
                </div>

                <div class="stat-card orders">
                    <div class="stat-header">
                        <div class="stat-title">Pesanan Baru</div>
                        <div class="stat-icon orders">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="stat-value">42</div>
                    <div class="stat-compare positive">
                        <i class="fas fa-arrow-up"></i> 8% dari minggu lalu
                    </div>
                </div>

                <div class="stat-card products">
                    <div class="stat-header">
                        <div class="stat-title">Total Produk</div>
                        <div class="stat-icon products">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="stat-value">28</div>
                    <div class="stat-compare">
                        <i class="fas fa-plus"></i> 3 produk baru bulan ini
                    </div>
                </div>

                <div class="stat-card engagement">
                    <div class="stat-header">
                        <div class="stat-title">Kunjungan Toko</div>
                        <div class="stat-icon engagement">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value">1.254</div>
                    <div class="stat-compare positive">
                        <i class="fas fa-arrow-up"></i> 15% dari bulan lalu
                    </div>
                </div>
            </section>

            <div class="content-grid">
                <section class="chart-section">
                    <div class="section-header">
                        <h2 class="section-title">Grafik Penjualan</h2>
                        <select class="filter-dropdown">
                            <option>7 Hari Terakhir</option>
                            <option>30 Hari Terakhir</option>
                            <option>3 Bulan Terakhir</option>
                            <option>Tahun Ini</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <div class="placeholder-chart"></div>
                    </div>
                </section>

                <section class="activity-section">
                    <div class="section-header">
                        <h2 class="section-title">Aktivitas Terbaru</h2>
                    </div>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon order">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Pesanan baru #ID1234</div>
                                <div class="activity-time">15 menit yang lalu</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon message">
                                <i class="fas fa-comment"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Pesan baru dari pelanggan</div>
                                <div class="activity-time">1 jam yang lalu</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon order">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Pesanan #ID1233 selesai</div>
                                <div class="activity-time">3 jam yang lalu</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon alert">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">Stok produk hampir habis</div>
                                <div class="activity-time">5 jam yang lalu</div>
                            </div>
                        </li>
                    </ul>
                </section>
            </div>

            <section class="action-buttons">
                <a href="#" class="action-btn">
                    <i class="fas fa-plus-circle"></i> Tambah Produk
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-file-invoice"></i> Buat Laporan
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-tags"></i> Promo Baru
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-question-circle"></i> Bantuan
                </a>
            </section>

            <section id="produk" style="margin-top: 40px;">
                <div class="section-header">
                    <h2 class="section-title">Produk Populer</h2>
                    <a href="#" style="color: var(--primary); text-decoration: none; font-weight: 500;">Lihat Semua</a>
                </div>

                <div class="product-list">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="/api/placeholder/500/300" alt="Produk 1">
                        </div>
                        <div class="product-details">
                            <div class="product-title">Tas Anyaman Premium</div>
                            <div class="product-price">Rp 159.000</div>
                            <div class="product-stock">Stok: 15 tersisa</div>
                            <div class="product-actions">
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image">
                            <img src="/api/placeholder/500/300" alt="Produk 2">
                        </div>
                        <div class="product-details">
                            <div class="product-title">Batik Tulis Motif Parang</div>
                            <div class="product-price">Rp 275.000</div>
                            <div class="product-stock">Stok: 8 tersisa</div>
                            <div class="product-actions">
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Hapus</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card">
                        <div class="product-image">
                            <img src="/api/placeholder/500/300" alt="Produk 3">
                        </div>
                        <div class="product-details">
                            <div class="product-title">Kerajinan Kayu Jati</div>
                            <div class="product-price">Rp 325.000</div>
                            <div class="product-stock">Stok: 4 tersisa</div>
                            <div class="product-actions">
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 UMKM Terpadu - Platform Manajemen UMKM Indonesia</p>
        </div>
    </footer>

    <script>
        // Fungsi toggle menu untuk tampilan mobile
        const menuToggle = document.querySelector('.menu-toggle');
        const navMenu = document.querySelector('.nav-menu');

        menuToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });

        // Tutup menu saat klik link
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });
    </script>
</body>
</html>