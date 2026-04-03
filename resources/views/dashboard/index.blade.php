@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    @media (max-width: 576px) {
        .info-box-mini {
            flex-direction: column;
            text-align: center;
            padding: 10px 5px !important;
            min-height: 80px !important;
        }
        .info-box-mini .info-box-icon {
            display: none !important;
        }
        .info-box-mini .info-box-content {
            padding: 0 !important;
            margin: 0 !important;
        }
        .info-box-mini .info-box-text {
            font-size: 0.65rem !important;
            margin-bottom: 2px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .info-box-mini .info-box-number {
            font-size: 0.75rem !important;
            display: block !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid p-0 p-md-2">
    <!-- Mandatory Attendance Modal -->
    @if(session('needs_attendance'))
    <div class="modal fade" id="attendanceModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary-apms text-white border-0">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-clipboard-user mr-2"></i> Absensi Kehadiran Kasir</h5>
                </div>
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-clock fa-3x text-primary-apms mb-3"></i>
                            <p class="lead">Silakan catat kehadiran Anda untuk shift ini.</p>
                            <p class="text-muted text-sm">Karena aplikasi digunakan bersama, pastikan nama Anda (dan/atau tim Anda) tercatat akurat.</p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Siapa yang bertugas/memegang aplikasi saat ini? *</label>
                            <input type="text" name="cashier_names" class="form-control form-control-lg" placeholder="Contoh: Bu Rini, Siti" required autofocus autocomplete="off">
                            <small class="form-text text-muted">Bisa diisi lebih dari 1 nama jika bertugas bersama (pisahkan dengan koma).</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4">
                        <button type="submit" class="btn btn-primary-apms btn-lg btn-block" style="font-weight: 600; font-size: 1.1rem;">
                            <i class="fas fa-check-circle mr-2"></i> Hadir & Lanjutkan ke Sistem
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- LEVEL 0: Strategic Command (Combined Highlights) -->
    <div class="row no-gutters m-0 p-0 row-mobile-tight mb-3">
        <div class="col-12 col-md-12 p-0 p-md-1">
            <div class="card card-apms border-0 bg-gradient-dark text-white shadow-lg overflow-hidden position-relative" style="background: linear-gradient(135deg, #0b1727 0%, #1a5ab3 100%);">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase font-weight-bold mb-0 smaller text-white-50" style="letter-spacing: 0.1rem;">Total Omzet Gabungan (Eceran + Grosir)</p>
                        <h3 class="font-weight-bold mb-0 text-white display-5">Rp {{ number_format($totalCombinedRevenue, 0, ',', '.') }}</h3>
                        <span class="smaller text-white-50"><i class="fas fa-calendar-alt mr-1"></i> Data Periode: {{ $periodLabel }}</span>
                    </div>
                    <div class="d-none d-lg-block text-right">
                        <div class="badge badge-wholesale px-3 py-2 text-uppercase" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2);">
                            Command Center Mode: Active
                        </div>
                    </div>
                </div>
                <!-- Subtle Icon Background -->
                <i class="fas fa-chart-line position-absolute" style="right: -20px; bottom: -20px; font-size: 8rem; opacity: 0.05; transform: rotate(-15deg);"></i>
            </div>
        </div>
    </div>

    <!-- LEVEL 1: Executive KPI Strip (Ultra Compact) -->
    <div class="row no-gutters m-0 p-0 row-mobile-tight">
        <div class="col-6 col-md-3 p-0 p-md-1">
            <div class="card card-apms border-left-primary shadow-sm overflow-hidden mb-1 mb-md-3" style="border-left-width: 3px !important;">
                <div class="card-body p-2 p-md-3 px-2">
                    <p class="text-uppercase text-muted font-weight-bold mb-0 smaller text-truncate" style="letter-spacing: 0.02rem;">Eceran</p>
                    <h6 class="font-weight-bold mb-0 text-dark text-truncate">Rp {{ number_format($periodSales, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0 p-md-1">
            <div class="card card-apms border-left-success shadow-sm overflow-hidden mb-1 mb-md-3" style="border-left-width: 3px !important;">
                <div class="card-body p-2 p-md-3 px-2">
                    <p class="text-uppercase text-muted font-weight-bold mb-0 smaller text-truncate" style="letter-spacing: 0.02rem;">Grosir</p>
                    <h6 class="font-weight-bold mb-0 text-dark text-truncate">Rp {{ number_format($wholesaleSalesPeriod, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0 p-md-1">
            <div class="card card-apms border-left-info shadow-sm overflow-hidden mb-1 mb-md-3" style="border-left-width: 3px !important;">
                <div class="card-body p-2 p-md-3 px-2">
                    <p class="text-uppercase text-muted font-weight-bold mb-0 smaller text-truncate" style="letter-spacing: 0.02rem;">Est Profit</p>
                    <h6 class="font-weight-bold mb-0 text-success text-truncate">Rp {{ number_format($periodProfit, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 p-0 p-md-1">
            <div class="card card-apms border-left-warning shadow-sm overflow-hidden mb-1 mb-md-3" style="border-left-width: 3px !important;">
                <div class="card-body p-2 p-md-3 px-2">
                    <p class="text-uppercase text-muted font-weight-bold mb-0 smaller text-truncate" style="letter-spacing: 0.02rem;">Pengeluaran</p>
                    <h6 class="font-weight-bold mb-0 text-danger text-truncate">Rp {{ number_format($periodExpenses, 0, ',', '.') }}</h6>
                </div>
            </div>
        </div>
    </div>

    <!-- LEVEL 2: Combined Ultra-Compact Command Center (One-Line Mastery) -->
    @if(in_array(auth()->user()->role, ['admin', 'cashier', 'supervisor']))
    <div class="row no-gutters mb-1 mx-0 row-mobile-tight bg-white border shadow-sm align-items-center">
        <!-- Part 1: Quick Stats (Low Stock, Customer, Period) -->
        <div class="col-7 col-md-7 d-flex align-items-center px-1 border-right" style="height: 30px;">
            <div class="d-flex align-items-center mr-auto">
                <span class="font-weight-bold text-muted mr-1" style="font-size: 0.55rem;">S:</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.55rem;">{{ $lowStockProductsCount }}</span>
            </div>
            <div class="d-flex align-items-center border-left border-right px-1 mx-1">
                <span class="font-weight-bold text-muted mr-1" style="font-size: 0.55rem;">C:</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.55rem;">{{ $totalCustomers }}</span>
            </div>
            <div class="d-flex align-items-center ml-auto">
                <span class="font-weight-bold text-muted d-none d-sm-inline" style="font-size: 0.55rem;">P:</span>
                <span class="font-weight-bold text-dark text-uppercase text-truncate" style="max-width: 45px; font-size: 0.55rem;">{{ $period }}</span>
            </div>
        </div>

        <!-- Part 2: Filter & POS Action -->
        <div class="col-5 col-md-5 d-flex align-items-center p-0" style="height: 30px;">
            <!-- Filter Dropdown -->
            <div class="flex-grow-1 px-1 border-right">
                <form action="{{ route('dashboard') }}" method="GET" id="periodFilterForm" class="m-0">
                    <select name="period" class="form-control form-control-sm border-0 bg-transparent font-weight-bold p-0 h-auto text-primary" style="font-size: 0.65rem;" onchange="document.getElementById('periodFilterForm').submit()">
                        <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Harian</option>
                        <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Mingguan</option>
                        <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </form>
            </div>
            <!-- Shift Status Button -->
            <div class="px-1">
                @if($activeShift)
                <a href="{{ route('shifts.index') }}" class="btn btn-outline-danger btn-xs py-0 px-1 font-weight-bold" style="font-size: 0.55rem; min-height: 18px; border-radius: 2px;">CLOSE</a>
                @else
                <a href="{{ route('shifts.index') }}" class="btn btn-primary-apms btn-xs py-0 px-1" style="font-size: 0.55rem; min-height: 18px; border-radius: 2px;">OPEN</a>
                @endif
            </div>
        </div>
    </div>
    @else
    <!-- Period Filter Only for Owner/Others -->
    <div class="bg-white border shadow-sm mb-1 px-2 py-1 row-mobile-tight row mx-0 no-gutters">
        <div class="col-6 d-flex align-items-center">
            <span class="font-weight-bold text-muted smaller mr-2">PERIODE LAPORAN:</span>
            <span class="badge badge-primary-apms px-2">{{ $periodLabel }}</span>
        </div>
        <div class="col-6">
            <form action="{{ route('dashboard') }}" method="GET" id="ownerPeriodFilterForm" class="m-0">
                <select name="period" class="form-control form-control-sm border-0 font-weight-bold p-0 h-auto text-right" style="font-size: 0.7rem;" onchange="document.getElementById('ownerPeriodFilterForm').submit()">
                    <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </form>
        </div>
    </div>
    @endif
    
    <!-- Section: Activity & Reports -->
    <div class="d-flex align-items-center mb-1 mt-1 px-2">
        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.6rem; letter-spacing: 0.05rem;">Aktivitas & Laporan Utama</h6>
        <div class="flex-grow-1 ml-2 border-bottom" style="opacity: 0.05;"></div>
    </div>
    
    <!-- Main Content Row -->
    <div class="row row-mobile-tight m-md-0">
        <!-- Left Column -->
        <div class="col-lg-8 col-mobile-tight">
            <!-- Sales Chart Area and Side Panels -->
            <div class="row m-0 p-0">
                <div class="col-xl-8 pr-xl-3 col-mobile-tight">
                    <!-- Sales Chart -->
                    <div class="card card-apms border-0 shadow-sm card-mobile-flush h-100">
                        <div class="card-header border-0 pb-0">
                            <h3 class="card-title font-weight-bold" style="font-size: 0.9rem;">Grafik Penjualan {{ date('Y') }}</h3>
                        </div>
                        <div class="card-body pt-0 mobile-tight-p">
                            <div class="position-relative" style="height: 300px;">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 mt-3 mt-xl-0">
                    <!-- Ringkasan Keuangan (Moved here) -->
                    <div class="card card-apms bg-gradient-primary text-white border-0 card-mobile-flush shadow-sm">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h3 class="card-title text-white" style="font-size: 0.8rem;">Ringkasan Keuangan {{ $periodLabel }}</h3>
                        </div>
                        <div class="card-body py-1 py-md-2">
                            <div class="row text-center m-0">
                                <div class="col-6 p-0 border-right border-white-50">
                                    <small class="d-block opacity-75">Revenue</small>
                                    <h6 class="font-weight-bold mb-0" id="summaryRevenueText">Rp {{ number_format($periodSales, 0, ',', '.') }}</h6>
                                </div>
                                <div class="col-6 p-0">
                                    <small class="d-block opacity-75">Expense</small>
                                    <h6 class="font-weight-bold mb-0" id="summaryExpenseText">Rp {{ number_format($periodExpenses, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                            <div class="border-top border-white-50 mt-1 pt-1 text-center">
                                <small class="d-block opacity-75">Net Profit</small>
                                <h6 class="font-weight-bold mb-0 text-warning" id="summaryProfitText">Rp {{ number_format($periodProfit, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- Distribusi Pembayaran (Moved here) -->
                    <div class="card card-apms border-0 shadow-sm card-mobile-flush mt-3 mt-md-0">
                        <div class="card-header border-0 pb-0">
                            <h3 class="card-title" style="font-size: 0.8rem;">Distribusi Pembayaran</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="position-relative" id="paymentChartContainer" style="height: 140px;">
                                <canvas id="paymentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card card-apms shadow-sm card-mobile-flush mt-3">
                <div class="card-header border-0">
                    <h3 class="card-title">Transaksi Terbaru</h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.index') }}" class="btn btn-primary-apms btn-sm">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-borderless table-centered table-nowrap mb-0">
                        <thead class="bg-faint-primary">
                            <tr class="text-nowrap">
                                <th class="py-2 text-uppercase text-muted font-weight-bold" style="font-size: 0.65rem;">Invoice</th>
                                <th class="py-2 text-uppercase text-muted font-weight-bold" style="font-size: 0.65rem;">Pelanggan</th>
                                <th class="py-2 text-uppercase text-muted font-weight-bold d-none d-sm-table-cell" style="font-size: 0.65rem;">Total</th>
                                <th class="py-2 text-uppercase text-muted font-weight-bold d-none d-md-table-cell" style="font-size: 0.65rem;">Metode</th>
                                <th class="py-2 text-uppercase text-muted font-weight-bold d-none d-lg-table-cell" style="font-size: 0.65rem;">Kasir</th>
                                <th class="py-2 text-uppercase text-muted font-weight-bold" style="font-size: 0.65rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="text-primary font-weight-bold" style="font-size: 0.75rem;">
                                        #{{ substr($transaction->invoice_number, -6) }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle avatar-sm bg-faint-primary mr-2" style="width: 20px; height: 20px; font-size: 0.6rem;">
                                            {{ strtoupper(substr($transaction->customer->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="font-weight-bold truncate-text" style="max-width: 80px; font-size: 0.75rem;">{{ $transaction->customer->name ?? 'Umum' }}</div>
                                    </div>
                                    <div class="d-sm-none text-muted" style="font-size: 0.65rem;">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="d-none d-sm-table-cell">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge badge-wholesale">{{ strtoupper($transaction->payment_method) }}</span>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle avatar-sm bg-faint-secondary mr-2" style="font-size: 0.5rem;">
                                            {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $transaction->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($transaction->paid_amount >= $transaction->total_amount)
                                        <span class="badge badge-success">Lunas</span>
                                    @else
                                        <span class="badge badge-warning">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- NEW: Live Personnel (Active Staff) -->
            <div class="card card-apms shadow-sm border-0 mb-3 overflow-hidden">
                <div class="card-header bg-faint-teal py-2 px-3 border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users-viewfinder text-teal mr-2"></i>
                            <h6 class="mb-0 font-weight-bold text-teal" style="font-size: 0.8rem;">Personel Aktif Saat Ini</h6>
                        </div>
                        <span class="badge badge-pill bg-teal text-white">{{ count($activeStaff) }}</span>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="d-flex flex-wrap">
                        @forelse($activeStaff as $attendance)
                            <div class="text-center mr-2 mb-2" title="{{ $attendance->user->name }} ({{ ucfirst($attendance->user->role) }})">
                                <div class="avatar-circle avatar-md border {{ $attendance->user->role == 'admin' ? 'border-primary' : ($attendance->user->role == 'supervisor' ? 'border-success' : 'border-secondary') }}" style="border-width: 2px !important;">
                                    {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                                </div>
                                <small class="d-block text-truncate mt-1" style="max-width: 50px; font-size: 0.6rem;">{{ explode(' ', $attendance->user->name)[0] }}</small>
                            </div>
                        @empty
                            <div class="text-center py-3 w-100">
                                <i class="fas fa-user-slash text-muted mb-2"></i>
                                <p class="text-muted smaller mb-0">Belum ada staf yang masuk.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- NEW: Wholesale Logistics Monitor -->
            <div class="card card-apms shadow-sm border-0 mb-3">
                <div class="card-header bg-faint-indigo py-2 px-3 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-truck-ramp-box text-indigo mr-2"></i>
                        <h6 class="mb-0 font-weight-bold text-indigo" style="font-size: 0.8rem;">Monitor Logistik Grosir</h6>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row no-gutters text-center">
                        <div class="col-4 border-right">
                            <h5 class="font-weight-bold mb-0 text-warning">{{ $wholesaleSummary['pending'] ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller font-weight-bold">Pending</small>
                        </div>
                        <div class="col-4 border-right">
                            <h5 class="font-weight-bold mb-0 text-info">{{ $wholesaleSummary['packing'] ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller font-weight-bold">Packing</small>
                        </div>
                        <div class="col-4">
                            <h5 class="font-weight-bold mb-0 text-success">{{ $wholesaleSummary['sent'] ?? 0 }}</h5>
                            <small class="text-muted text-uppercase smaller font-weight-bold">Dikirim</small>
                        </div>
                    </div>
                    <div class="mt-2 pt-2 border-top">
                        <a href="{{ route('wholesale.index') }}" class="btn btn-ghost-primary btn-xs btn-block py-1">
                            Lacak Semua Pesanan <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stock Alerts -->
            <div class="card card-apms">
                <div class="card-header border-0">
                    <h3 class="card-title">Peringatan Stok</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockAlerts as $alert)
                        <li class="list-group-item px-3 py-2 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-faint-warning rounded p-2 mr-3 text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('inventory.index') }}" class="text-dark font-weight-bold d-block text-sm">{{ $alert->name }}</a>
                                    <span class="text-muted smaller">Min: {{ $alert->minimum_stock }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-warning">{{ $alert->current_stock }} stok</span>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-4 border-0">
                            <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                            <p class="text-muted mb-0">Semua stok terpantau aman.</p>
                        </li>
                        @endforelse
                        
                        @foreach($expiringAlerts as $alert)
                        <li class="item">
                            <div class="product-img">
                                <i class="fas fa-calendar-times fa-2x text-danger"></i>
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    {{ $alert->product->name }}
                                    <span class="badge badge-danger float-right">
                                        {{ \Carbon\Carbon::parse($alert->expiration_date)->diffForHumans() }}
                                    </span>
                                </a>
                                <span class="product-description">
                                    Exp: {{ \Carbon\Carbon::parse($alert->expiration_date)->format('d/m/Y') }}
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('inventory.index') }}" class="uppercase">Lihat Semua Peringatan</a>
                </div>
            </div>
            
            <!-- Top Products -->
            <div class="card card-apms">
                <div class="card-header border-0">
                    <h3 class="card-title">Produk Terlaris</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($topProducts as $index => $product)
                        <li class="list-group-item px-3 py-2 border-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-faint-primary rounded p-2 mr-3 text-primary-apms font-weight-bold" style="width: 35px; text-align: center;">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold text-sm">{{ $product->name }}</h6>
                                    <span class="text-muted smaller">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-wholesale">{{ $product->total_sold }} terjual</span>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="card card-apms mb-3">
                <div class="card-header border-0 pb-0">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body p-2">
                    <div class="row no-gutters">
                        <div class="col-12 mb-1">
                            <a href="{{ route('transactions.create') }}" class="btn btn-ghost-primary btn-sm btn-block text-left py-2 px-3 border-faint">
                                <i class="fas fa-cash-register mr-2"></i> Kasir POS
                            </a>
                        </div>
                        <div class="col-12 mb-1">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-sm btn-block text-left py-2 px-3 border-faint">
                                <i class="fas fa-plus mr-2"></i> Tambah Produk
                            </a>
                        </div>
                        <div class="col-12 mb-1">
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-warning btn-sm btn-block text-left py-2 px-3 border-faint">
                                <i class="fas fa-boxes mr-2"></i> Manajemen Stok
                            </a>
                        </div>
                        <div class="col-12 mb-1">
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-info btn-sm btn-block text-left py-2 px-3 border-faint">
                                <i class="fas fa-chart-line mr-2"></i> Analitik & Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Operational Insights (Intelligence Hub - Relocated to Sidebar) -->
            <div class="card card-apms border-0 shadow-sm overflow-hidden mb-3">
                <div class="card-header bg-faint-primary py-2 px-3 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-magic text-primary-apms mr-2"></i>
                        <h6 class="mb-0 font-weight-bold text-primary-apms" style="font-size: 0.8rem;">Smart AI Logic</h6>
                    </div>
                </div>
                <div class="card-body py-2 px-2">
                    <div id="smartInsightsContainer">
                        @forelse($smartInsights ?? [] as $insight)
                        <div class="d-flex align-items-start p-2 rounded border-faint bg-light-apms mb-2">
                            <i class="fas {{ $insight['icon'] }} {{ $insight['color'] }} mt-1 mr-2" style="font-size: 0.75rem;"></i>
                            <div>
                                <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.6rem;">{{ $insight['title'] }}</small>
                                <p class="mb-0 text-sm" style="line-height: 1.2; font-size: 0.75rem !important;">{!! $insight['text'] !!}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-2">
                            <p class="text-muted mb-0 small">Analisis...</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script>
$(function() {
    // Show Mandatory Attendance Modal if needed
    @if(session('needs_attendance'))
        $('#attendanceModal').modal('show');
    @endif

    @if(session('show_welcome_popup') && session('welcome_user_name'))
    Swal.fire({
        title: 'Selamat Datang, {{ session('welcome_user_name') }}!',
        text: 'Semoga hari ini kerjanya makin semangat dan tokonya makin laris ya!',
        icon: 'success',
        confirmButtonText: 'Terima Kasih, Siap Kerja!',
        confirmButtonColor: '#FF6B35',
        backdrop: `rgba(0,0,123,0.4) url("https://sweetalert2.github.io/images/nyan-cat.gif") left top no-repeat`,
        allowOutsideClick: false
    });
    @php
        // Clear it so it only shows once right after login
        session()->forget('show_welcome_popup');
        session()->forget('welcome_user_name');
    @endphp
    @endif

    // Sales Chart
    // Create JS primitives from Blade securely
    var labelsRaw = JSON.parse('{!! json_encode(collect($salesData)->pluck("month")) !!}');
    var dataRaw = JSON.parse('{!! json_encode(collect($salesData)->pluck("sales")) !!}');

    var salesChartData = {
        labels: labelsRaw,
        datasets: [{
            label: 'Penjualan',
            backgroundColor: 'rgba(44, 123, 229, 0.1)',
            borderColor: 'rgba(44, 123, 229, 1)',
            pointBackgroundColor: 'rgba(44, 123, 229, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(44, 123, 229, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            data: dataRaw
        }]
    };
    
    var salesChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += 'Rp ' + context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    display: true
                },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }
            }
        }
    };
    
    new Chart(salesChartCanvas, {
        type: 'line',
        data: salesChartData,
        options: salesChartOptions
    });
    
    // Auto-Pulse: Refresh stats every 30 seconds
    var currentPeriod = '{{ $period }}';
    
    function refreshStats() {
        $.ajax({
            url: '/api/dashboard/stats',
            method: 'GET',
            data: { period: currentPeriod },
            success: function(response) {
                // Today Stats (Static labels)
                $('#todaySalesText').fadeOut(300, function() { $(this).text(response.todaySales).fadeIn(300); });
                $('#wholesaleTodayText').fadeOut(300, function() { $(this).text(response.wholesaleToday).fadeIn(300); });
                
                // Period Stats (Dynamic labels)
                $('#periodSalesText').fadeOut(300, function() { $(this).text(response.periodSales).fadeIn(300); });
                $('#wholesalePeriodText').fadeOut(300, function() { $(this).text(response.wholesalePeriod).fadeIn(300); });
                
                // Financial Summary
                $('#summaryRevenueText').fadeOut(300, function() { $(this).text(response.periodSales).fadeIn(300); });
                $('#summaryExpenseText').fadeOut(300, function() { $(this).text(response.periodExpenses).fadeIn(300); });
                $('#netProfitText').fadeOut(300, function() { $(this).text(response.netProfit).fadeIn(300); });
                $('#summaryProfitText').fadeOut(300, function() { $(this).text(response.netProfit).fadeIn(300); });
                
                $('#lowStockCountText').fadeOut(300, function() { $(this).text(response.lowStockCount + ' Produk').fadeIn(300); });
                $('#totalCustomersText').fadeOut(300, function() { $(this).text(response.totalCustomers).fadeIn(300); });
                
                // Refresh Smart Insights
                if (response.smartInsights && response.smartInsights.length > 0) {
                    let insightsHtml = '';
                    response.smartInsights.forEach(insight => {
                        let borderColor = '#00d2ff';
                        if (insight.color.includes('danger')) borderColor = '#ff4b2b';
                        if (insight.color.includes('success')) borderColor = '#1d976c';
                        if (insight.color.includes('warning')) borderColor = '#ffb347';

                        insightsHtml += `
                        <div class="d-flex align-items-start p-2 rounded border-faint bg-light-apms mb-2">
                            <i class="fas ${insight.icon} ${insight.color} mt-1 mr-2" style="font-size: 0.75rem;"></i>
                            <div>
                                <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.6rem;">${insight.title}</small>
                                <p class="mb-0 text-sm" style="line-height: 1.2; font-size: 0.75rem !important;">${insight.text}</p>
                            </div>
                        </div>`;
                    });
                    $('#smartInsightsContainer').fadeOut(300, function() {
                        $(this).html(insightsHtml).fadeIn(300);
                    });
                }
            },
            error: function(err) {
                console.error('Auto-Pulse error:', err);
            }
        });
    }
    
    // Initial call after 30s, then repeat
    setInterval(refreshStats, 30000);
    
    // Payment Method Chart
    var paymentChartCanvas = $('#paymentChart').get(0).getContext('2d');
    var paymentChart = new Chart(paymentChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'QRIS', 'Transfer', 'Kartu'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: ['#2c7be5', '#27bcfd', '#00d27a', '#748194'],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush