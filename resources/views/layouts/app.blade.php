<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>APMS - @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
    
    <style>
        :root {
            --primary-color: #FF6B35;
            --primary-light: #FF8B5C;
            --primary-dark: #E55A2B;
            --secondary-color: #2D3047;
            --light-color: #F8F9FA;
            --dark-color: #343A40;
        }
        
        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            font-size: 14px;
        }
        
        * {
            box-sizing: border-box;
        }
        
        .wrapper {
            overflow-x: hidden;
        }
        
        .navbar-apms {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            color: white !important;
        }
        
        .navbar-apms .nav-link, .navbar-apms .navbar-brand {
            color: white !important;
        }
        
        .navbar-apms .nav-link:hover, .navbar-apms .nav-link:focus {
            color: rgba(255,255,255,0.8) !important;
            background-color: rgba(0,0,0,0.05);
        }
        
        .navbar-apms .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white !important;
        }
        
        .sidebar-apms {
            background-color: white;
            border-right: 1px solid #eaeaea;
        }
        
        .sidebar-apms .nav-link {
            color: var(--dark-color);
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar-apms .nav-link:hover {
            background-color: var(--primary-color);
            color: white !important;
        }
        
        .sidebar-apms .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .card-apms {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        
        .card-apms:hover {
            transform: translateY(-2px);
        }
        
        .btn-primary-apms {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary-apms:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }
        
        .badge-premium {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            color: #000;
        }
        
        .badge-wholesale {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }
        
        .stat-card {
            border-left: 4px solid var(--primary-color);
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

            /* Tables - no nowrap to prevent overflow */
            .table th, .table td {
                font-size: 0.72rem !important;
                padding: 4px 6px !important;
                white-space: normal !important;
                word-break: break-word;
            }
            .table-responsive {
                border-radius: 6px;
                max-width: 100%;
                overflow-x: auto;
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
<body class="hold-transition sidebar-mini">
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
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle text-white"></i>
                    {{ Auth::user()->name }}
                </a>
<div class="dropdown-menu dropdown-menu-right">
    <a href="#" class="dropdown-item">
        <i class="fas fa-user mr-2"></i> Profile
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
    <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-apms">
        <!-- Brand Logo -->
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <span class="brand-text font-weight-bold" style="color: var(--primary-color); font-size: 1.5rem;">
                <i class="fas fa-spray-can"></i> APMS
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

@stack('scripts')
</body>
</html>