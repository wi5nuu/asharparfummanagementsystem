<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>APMS - @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- PWA -->
    <meta name="theme-color" content="#FF6B35">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
    
    <style>
        :root {
            --primary-color: #2c7be5;
            --primary-light: #5e9cf2;
            --primary-dark: #1a5ab3;
            --secondary-color: #748194;
            --light-color: #f9fafd;
            --dark-color: #0b1727;
            --success-color: #00d27a;
            --info-color: #27bcfd;
            --warning-color: #f5803e;
            --danger-color: #e63757;
            --falcon-shadow: 0 7px 14px 0 rgba(11, 15, 60, 0.04);
            --falcon-border-color: #edf2f9;
        }
        
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
            position: relative;
        }
        
        body {
            font-family: 'Inter', 'Poppins', sans-serif;
            background-color: #f9fafd;
            color: #4d5969;
            font-size: 0.875rem;
        }
        
        * {
            box-sizing: border-box;
        }
        
        .wrapper {
            overflow-x: hidden;
        }
        
        .navbar-apms {
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--falcon-border-color) !important;
            box-shadow: var(--falcon-shadow);
        }
        
        .navbar-apms .nav-link, 
        .navbar-apms .navbar-brand {
            color: #5e6e82 !important;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        
        .navbar-apms .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: #f5f8ff;
            border-radius: 6px;
        }

        .navbar-apms .nav-link:focus,
        .navbar-apms .nav-link:active {
            color: #fff !important;
            background-color: rgba(0, 0, 0, 0.1) !important;
            border-radius: 6px;
        }
        
        .navbar-apms .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            border-radius: 10px;
            padding: 8px;
            margin-top: 10px;
        }

        .navbar-apms .dropdown-item {
            border-radius: 6px;
            padding: 8px 16px;
            color: var(--dark-color);
            transition: all 0.2s ease;
        }
        
        .navbar-apms .dropdown-item:hover {
            background-color: #fff5f2;
            color: var(--primary-color) !important;
        }
        
        .navbar-apms .dropdown-item i {
            color: var(--primary-color);
        }
        
        .sidebar-apms {
            background-color: #f9fafd;
            border-right: 1px solid var(--falcon-border-color);
        }
        
        .sidebar-apms .nav-link {
            color: #4a5568 !important;
            padding: 10px 15px;
            margin: 4px 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .sidebar-apms .nav-link i {
            color: #718096;
            margin-right: 10px;
            transition: all 0.2s ease;
        }
        
        .sidebar-apms .nav-link:hover {
            background-color: #fff5f2;
            color: var(--primary-color) !important;
        }

        .sidebar-apms .nav-link:hover i {
            color: var(--primary-color);
        }
        
        .sidebar-apms .nav-link.active {
            background-color: transparent !important;
            color: var(--primary-color) !important;
            font-weight: 700;
            position: relative;
        }

        .sidebar-apms .nav-link.active::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%);
            height: 20px;
            width: 4px;
            background-color: var(--primary-color);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-apms .nav-link.active i {
            color: var(--primary-color) !important;
        }
        
        .card-apms {
            border: 1px solid var(--falcon-border-color);
            border-radius: 0.625rem;
            box-shadow: var(--falcon-shadow);
            background-color: #fff;
        }
        
        .card-apms:hover {
            transform: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.06);
        }
        
        .btn-primary-apms {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(44, 123, 229, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }
        
        .btn-primary-apms:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 7px 14px rgba(44, 123, 229, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }
        
        .badge-premium {
            background-color: #fef1d8;
            color: #9d5228;
            border: 1px solid #fce1b6;
        }
        
        .badge-wholesale {
            background-color: #e5f1fd;
            color: #2c7be5;
            border: 1px solid #d2e5fc;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-color);
        }

        /* Global Table/Text Utilities */
        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .smaller {
            font-size: 0.75rem !important;
        }

        .text-xs-mobile {
            font-size: 0.65rem !important;
        }

        .text-nowrap {
            white-space: nowrap !important;
        }

        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        /* Faint Background Utilities */
        .bg-faint-primary { background-color: #f0f5ff !important; color: #2c7be5 !important; }
        .bg-faint-success { background-color: #e6fff5 !important; color: #00d27a !important; }
        .bg-faint-warning { background-color: #fff8f1 !important; color: #f5803e !important; }
        .bg-faint-danger { background-color: #fff5f7 !important; color: #e63757 !important; }
        .bg-faint-info { background-color: #f0faff !important; color: #27bcfd !important; }
        .bg-faint-teal { background-color: #e6fffa !important; color: #20c997 !important; }
        .bg-faint-indigo { background-color: #f0f0ff !important; color: #6610f2 !important; }

        /* Avatar Component */
        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
            background-color: #edf2f9;
            color: #5e6e82;
            text-transform: uppercase;
        }

        .avatar-sm { width: 24px; height: 24px; font-size: 0.65rem; }
        .avatar-md { width: 40px; height: 40px; font-size: 0.9rem; }

        .btn-ghost-primary {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        .btn-ghost-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 767.98px) {
            .truncate-text {
                max-width: 120px;
            }
        }

        /* =============================================
           MOBILE COMPACT LAYOUT (< 768px)
        ============================================= */
        @media (max-width: 767.98px) {

            /* Navbar */
            .main-header .navbar-nav .nav-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.8rem;
            }
            .main-header.navbar {
                min-height: 44px;
            }

            /* Page Content Padding */
            .content-wrapper {
                padding: 0 !important;
            }
            .content {
                padding: 8px !important;
            }
            .container-fluid {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }

            /* Cards */
            .card {
                margin-bottom: 8px !important;
                border-radius: 8px !important;
            }
            .card-apms {
                margin-bottom: 8px;
            }
            .card-header {
                padding: 8px 12px !important;
            }
            .card-header .card-title {
                font-size: 0.85rem !important;
                margin-bottom: 0 !important;
            }
            .card-body {
                padding: 10px 12px !important;
            }

            /* Info Boxes (stat cards) */
            .info-box {
                min-height: 60px !important;
                padding: 0 !important;
                margin-bottom: 8px !important;
            }
            .info-box-icon {
                width: 50px !important;
                line-height: 60px !important;
                font-size: 1.2rem !important;
            }
            .info-box-content {
                padding: 6px 10px !important;
            }
            .info-box-text {
                font-size: 0.7rem !important;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }
            .info-box-number {
                font-size: 1rem !important;
                font-weight: 700 !important;
            }

            /* Small Boxes (AdminLTE stat boxes) */
            .small-box {
                margin-bottom: 8px !important;
            }
            .small-box h3 {
                font-size: 1.4rem !important;
            }
            .small-box p {
                font-size: 0.75rem !important;
            }
            .small-box .icon {
                font-size: 50px !important;
                top: 5px !important;
                right: 10px !important;
            }

            /* Headings */
            h1, .h1 { font-size: 1.3rem !important; }
            h2, .h2 { font-size: 1.15rem !important; }
            h3, .h3 { font-size: 1rem !important; }
            h4, .h4 { font-size: 0.9rem !important; }
            h5, .h5 { font-size: 0.85rem !important; }

            /* Tables - favor horizontal scrolling on mobile */
            .table-responsive .table {
                min-width: 600px; /* Minimum width to trigger horizontal scroll when many columns */
            }
            .table th, .table td {
                font-size: 0.8rem !important;
                padding: 8px 10px !important;
            }
            .table th.text-nowrap, .table td.text-nowrap {
                white-space: nowrap !important;
            }
            .table-responsive {
                border-radius: 8px;
                border: 1px solid #eee;
            }

            /* Buttons */
            .btn {
                font-size: 0.78rem !important;
                padding: 5px 10px !important;
            }
            .btn-block {
                padding: 8px 10px !important;
            }

            /* Form Controls */
            .form-control, .form-group label {
                font-size: 0.8rem !important;
            }
            .form-group {
                margin-bottom: 8px !important;
            }

            /* Row gutters */
            .row {
                margin-left: -4px !important;
                margin-right: -4px !important;
                max-width: 100% !important;
            }
            .row > [class*="col-"] {
                padding-left: 4px !important;
                padding-right: 4px !important;
                max-width: 100%;
                overflow: hidden;
            }

            /* Prevent any element from exceeding screen */
            img, iframe, video, embed, canvas {
                max-width: 100% !important;
            }

            /* Charts - smaller height */
            canvas {
                max-height: 180px !important;
                width: 100% !important;
            }

            /* Fix NavBar width */
            .main-header {
                width: 100% !important;
                max-width: 100vw !important;
                overflow: hidden;
            }

            /* Fix sidebar overlay not blocking scroll */
            .content-wrapper, .main-footer {
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }

            /* Sidebar brand */
            .brand-text {
                font-size: 1.1rem !important;
            }

            /* Content header */
            .content-header {
                padding: 8px 12px !important;
            }
            .content-header h1 {
                font-size: 1.1rem !important;
            }
            .content-header .breadcrumb {
                font-size: 0.7rem !important;
                padding: 2px 0 !important;
                background: none;
            }

            /* Page-specific: Dashboard top stats */
            .stat-card {
                padding: 8px !important;
            }

            /* Badge */
            .badge {
                font-size: 0.65rem !important;
            }
        }

        /* Tablet (768px - 991px) - slightly compact */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .info-box-number {
                font-size: 1.2rem !important;
            }
            .small-box h3 {
                font-size: 1.8rem !important;
            }
            .card-body {
                padding: 12px !important;
            }
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-apms">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars text-white"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link text-white">
                    <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Wholesale Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-bell text-white"></i>
                    @if(count($urgentWholesaleOrders ?? []) > 0)
                        <span class="badge badge-warning navbar-badge shadow-sm">{{ count($urgentWholesaleOrders) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg">
                    <span class="dropdown-item dropdown-header font-weight-bold">{{ count($urgentWholesaleOrders ?? []) }} Pesanan Grosir Mendesak</span>
                    <div class="dropdown-divider"></div>
                    
                    @forelse($urgentWholesaleOrders ?? [] as $urgentOrder)
                        <a href="{{ route('wholesale.show', $urgentOrder->id) }}" class="dropdown-item">
                            <i class="fas fa-exclamation-circle text-warning mr-2"></i>
                            <span class="text-sm font-weight-bold">{{ $urgentOrder->invoice_number }}</span>
                            <div class="text-muted text-xs">Packing: {{ $urgentOrder->packing_days }} Hari | {{ $urgentOrder->recipient_name }}</div>
                        </a>
                        <div class="dropdown-divider"></div>
                    @empty
                        <div class="dropdown-item text-center text-muted py-3">
                            <i class="fas fa-check-circle text-success mb-2 d-block fa-2x"></i>
                            <span class="text-sm">Semua aman! Tidak ada paket mendesak hari ini.</span>
                        </div>
                    @endforelse
                    
                    <a href="{{ route('wholesale.index') }}" class="dropdown-item dropdown-footer">Lihat Semua Pesanan Grosir</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle text-white"></i>
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('settings.profile') }}" class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" class="dropdown-item" 
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-light-primary elevation-4 sidebar-apms">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link border-0 text-center py-2 d-flex align-items-center justify-content-center">
            <img src="{{ asset('logotoko.png') }}" alt="{{ $app_settings['store_name'] ?? 'APMS' }}" 
                 style="height: 32px; width: 32px; object-fit: contain; border-radius: 6px; margin-right: 8px;">
            <span class="brand-text font-weight-bold" style="color: var(--primary-color); font-size: 1.2rem; letter-spacing: -0.02rem;">
                {{ $app_settings['store_name'] ?? 'APMS' }}
            </span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    @can('manage_products')
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-wine-bottle"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('manage_inventory')
                    <li class="nav-item">
                        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventory</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('manage_transactions')
                    <li class="nav-item">
                        <a href="{{ route('transactions.create') }}" class="nav-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Kasir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('wholesale.index') }}" class="nav-link {{ request()->routeIs('wholesale.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes-packing"></i>
                            <p>Manajemen Grosir</p>
                        </a>
                    </li>
                    @endcan
                    
                    <li class="nav-item">
                        <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>
                    
                    @can('manage_customers')
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Pelanggan</p>
                        </a>
                    </li>
                    @endcan

                    @can('manage_expenses')
                    <li class="nav-item">
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>Pengeluaran</p>
                        </a>
                    </li>
                    @endcan

                    @can('manage_inventory')
                    <li class="nav-item">
                        <a href="{{ route('stock_audits.index') }}" class="nav-link {{ request()->routeIs('stock_audits.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Audit Stok</p>
                        </a>
                    </li>
                    @endcan

                    @can('manage_transactions')
                    <li class="nav-item">
                        <a href="{{ route('shifts.index') }}" class="nav-link {{ request()->routeIs('shifts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-badge"></i>
                            <p>Shift & Closing Kasir</p>
                        </a>
                    </li>
                    @endcan

                    @can('manage_transactions')
                    <li class="nav-item">
                        <a href="{{ route('debts.index') }}" class="nav-link {{ request()->routeIs('debts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>Manajemen Kas Bon</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('manage_coupons')
                    <li class="nav-item">
                        <a href="{{ route('coupons.index') }}" class="nav-link {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kupon & Loyalty</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('view_reports')
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>Laporan</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('manage_employees')
                    <li class="nav-item">
                        <a href="{{ route('attendances.index') }}" class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-user"></i>
                            <p>Absensi Kehadiran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Karyawan</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('manage_settings')
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>
                    @endcan
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#" style="color: var(--primary-color);">Ashar Parfum Management System</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- Scripts -->
{{-- Vite handles app.js now --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>

    <!-- AI Assistant FAB -->
    <div id="ai-assistant-fab" class="shadow-lg">
        <i class="fas fa-magic"></i>
    </div>

    <!-- AI Chat Window -->
    <div id="ai-chat-window" class="shadow-lg d-none">
        <div class="ai-chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="ai-status-dot mr-2"></div>
                <span class="font-weight-bold">APMS Assistant</span>
            </div>
            <button type="button" id="close-ai-chat" class="btn btn-sm text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="ai-chat-messages" class="p-3">
            <div class="ai-msg bot">
                Halo! Saya asisten pintar APMS. Ada yang bisa saya bantu hari ini?
            </div>
        </div>
        <div class="ai-chat-footer p-2">
            <div class="input-group">
                <input type="text" id="ai-chat-input" class="form-control form-control-sm border-0" placeholder="Tanya sesuatu...">
                <div class="input-group-append">
                    <button class="btn btn-primary-apms btn-sm" id="send-ai-msg">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        #ai-assistant-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            z-index: 9999;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(44, 123, 229, 0.3);
        }
        #ai-assistant-fab:hover {
            transform: scale(1.1);
            background: var(--primary-dark);
        }
        #ai-chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            max-width: calc(100vw - 60px);
            height: 450px;
            max-height: calc(100vh - 150px);
            background: white;
            border-radius: 15px;
            z-index: 9998;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .ai-chat-header {
            background: var(--primary-color);
            color: white;
            padding: 12px 15px;
        }
        .ai-status-dot {
            width: 8px;
            height: 8px;
            background: #00ff00;
            border-radius: 50%;
            box-shadow: 0 0 5px #00ff00;
        }
        #ai-chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            background: #f8faff;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .ai-msg {
            padding: 8px 12px;
            border-radius: 12px;
            max-width: 85%;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        .ai-msg.bot {
            background: white;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.02);
            border: 1px solid #eee;
        }
        .ai-msg.user {
            background: var(--primary-color);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }
        .ai-chat-footer {
            background: white;
            border-top: 1px solid #eee;
        }
        .ai-typing {
            font-style: italic;
            color: #999;
            font-size: 0.75rem;
        }
    </style>

@stack('scripts')
<script>
    $(document).ready(function() {
        const fab = $('#ai-assistant-fab');
        const window = $('#ai-chat-window');
        const closeBtn = $('#close-ai-chat');
        const sendBtn = $('#send-ai-msg');
        const input = $('#ai-chat-input');
        const messages = $('#ai-chat-messages');

        // Toggle chat window
        fab.on('click', function() {
            window.toggleClass('d-none');
            if (!window.hasClass('d-none')) {
                input.focus();
                messages.scrollTop(messages[0].scrollHeight);
            }
        });

        closeBtn.on('click', function() {
            window.addClass('d-none');
        });

        // Send function
        function sendMessage() {
            const text = input.val().trim();
            if (!text) return;

            // Add user message
            messages.append(`<div class="ai-msg user">${text}</div>`);
            input.val('');
            messages.scrollTop(messages[0].scrollHeight);

            // Typing indicator
            const typingId = 'typing-' + Date.now();
            messages.append(`<div id="${typingId}" class="ai-msg bot ai-typing">Menganalisis data...</div>`);
            messages.scrollTop(messages[0].scrollHeight);

            // AJAX to backend
            $.ajax({
                url: '/api/ai/ask',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    message: text
                },
                success: function(response) {
                    $(`#${typingId}`).remove();
                    messages.append(`<div class="ai-msg bot">${response.reply.replace(/\n/g, '<br>')}</div>`);
                    messages.scrollTop(messages[0].scrollHeight);
                },
                error: function() {
                    $(`#${typingId}`).remove();
                    messages.append(`<div class="ai-msg bot text-danger">Maaf, koneksi saya sedang terganggu. Coba lagi nanti ya.</div>`);
                    messages.scrollTop(messages[0].scrollHeight);
                }
            });
        }

        sendBtn.on('click', sendMessage);
        input.on('keypress', function(e) {
            if (e.which == 13) sendMessage();
        });
    });
</script>
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js');
        });
    }
</script>
</body>
</html>