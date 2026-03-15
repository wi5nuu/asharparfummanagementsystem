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
            width: 35px !important;
            height: 35px !important;
            line-height: 35px !important;
            font-size: 1rem !important;
            margin: 0 auto 5px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
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
<div class="container-fluid">
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

    <!-- Active Shift Alert (Premium Style) -->
    <div class="row">
        <div class="col-12">
            @if($activeShift)
            <div class="card card-apms mb-3 border-left-success" style="border-left-width: 4px !important;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-faint-success rounded-circle p-2 mr-3 text-success d-none d-sm-block">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 font-weight-bold text-dark">Shift Kasir Aktif</h6>
                            <p class="mb-0 text-muted smaller">Mulai: {{ $activeShift->start_time->format('d M/H:i') }} | Modal: Rp {{ number_format($activeShift->initial_cash, 0, ',', '.') }}</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('shifts.index') }}" class="btn btn-outline-danger btn-sm font-weight-bold">
                                <i class="fas fa-times-circle mr-1"></i> Tutup Shift
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card card-apms mb-3 border-left-warning" style="border-left-width: 4px !important;">
                <div class="card-body py-2 px-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-faint-warning rounded-circle p-2 mr-3 text-warning d-none d-sm-block">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 font-weight-bold text-dark">Shift Belum Dibuka</h6>
                            <p class="mb-0 text-muted smaller">Buka shift kasir untuk mulai transaksi POS.</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('shifts.index') }}" class="btn btn-primary-apms btn-sm shadow-none">
                                <i class="fas fa-play-circle mr-1"></i> Buka Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Period Filter Row -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-apms border-0 shadow-sm">
                <div class="card-body p-2 p-sm-3">
                    <form action="{{ route('dashboard') }}" method="GET" id="periodFilterForm">
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center">
                            <label class="mr-sm-3 mb-2 mb-sm-0 font-weight-bold text-muted text-sm"><i class="fas fa-filter mr-1"></i> Filter Statistik Utama:</label>
                            <select name="period" class="form-control form-control-sm h-auto py-2" style="width: 100%; max-width: 300px;" onchange="document.getElementById('periodFilterForm').submit()">
                                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>Harian (Hari Ini)</option>
                                <option value="this_week" {{ $period == 'this_week' ? 'selected' : '' }}>Mingguan (Minggu Ini)</option>
                                <option value="this_month" {{ $period == 'this_month' ? 'selected' : '' }}>Bulanan ({{ date('F') }})</option>
                                <option value="this_year" {{ $period == 'this_year' ? 'selected' : '' }}>Tahunan ({{ date('Y') }})</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Operational Insights -->
    <div class="d-flex align-items-center mb-3 mt-2">
        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.05rem;">Sintesis Operasional</h6>
        <div class="flex-grow-1 ml-3 border-bottom" style="opacity: 0.1;"></div>
    </div>

    <!-- Smart AI Insights Section (Falcon Card) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-apms border-0 overflow-hidden">
                <div class="card-header bg-faint-primary py-2 px-3 border-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-magic text-primary-apms mr-2"></i>
                        <h6 class="mb-0 font-weight-bold text-primary-apms" style="font-size: 0.8rem;">Smart Insights AI Logic</h6>
                    </div>
                </div>
                <div class="card-body py-3 px-3">
                    
                    <div class="row" id="smartInsightsContainer">
                        @forelse($smartInsights ?? [] as $insight)
                        <div class="col-md-6 col-lg-3 mb-2 mb-lg-0">
                            <div class="d-flex align-items-start p-2 rounded border">
                                <i class="fas {{ $insight['icon'] }} {{ $insight['color'] }} mt-1 mr-2"></i>
                                <div>
                                    <small class="text-muted d-block font-weight-bold" style="text-transform: uppercase; font-size: 0.65rem;">{{ $insight['title'] }}</small>
                                    <p class="mb-0 text-sm" style="line-height: 1.2;">{!! $insight['text'] !!}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-2">
                            <p class="text-muted mb-0 small">Menganalisis data... Tambahkan lebih banyak transaksi untuk mendapatkan insight cerdas!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Financial Analytics -->
    <div class="d-flex align-items-center mb-3 mt-4">
        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.05rem;">Analitik Finansial</h6>
        <div class="flex-grow-1 ml-3 border-bottom" style="opacity: 0.1;"></div>
    </div>

    <!-- Quick Stats Row (Falcon Cards) -->
    <div class="row px-1 px-sm-2 mb-3">
        <div class="col-4 col-sm-6 col-md-3 px-1 mb-2">
            <div class="card card-apms h-100 border-left-primary">
                <div class="card-body p-2 p-sm-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.05rem;">Eceran Aktif</h6>
                        <span class="badge badge-wholesale smaller">POS</span>
                    </div>
                    <div class="d-flex align-items-end flex-wrap">
                        <h5 class="font-weight-bold mb-0 mr-2 text-dark" id="todaySalesText" style="font-size: 0.95rem;">Rp {{ number_format($todaySales, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 col-sm-6 col-md-3 px-1 mb-2">
            <div class="card card-apms h-100 mb-0 border-0">
                <div class="card-body p-2 p-sm-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.05rem;">Grosir Aktif</h6>
                        <span class="badge badge-premium smaller">Bulk</span>
                    </div>
                    <div class="d-flex align-items-end flex-wrap">
                        <h5 class="font-weight-bold mb-0 mr-2 text-dark" id="wholesaleTodayText" style="font-size: 0.95rem;">Rp {{ number_format($wholesaleSalesToday, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-4 col-sm-6 col-md-3 px-1 mb-2">
            <div class="card card-apms h-100 mb-0 border-0">
                <div class="card-body p-2 p-sm-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.05rem;">Rev Period</h6>
                    </div>
                    <div class="d-flex align-items-end flex-wrap">
                        <h5 class="font-weight-bold mb-0 mr-2 text-primary-apms" id="periodSalesText" style="font-size: 0.95rem;">Rp {{ number_format($periodSales, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4 col-sm-6 col-md-3 px-1 mb-2">
            <div class="card card-apms h-100 mb-0 border-0">
                <div class="card-body p-2 p-sm-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.65rem; letter-spacing: 0.05rem;">Gro Period</h6>
                    </div>
                    <div class="d-flex align-items-end flex-wrap">
                        <h5 class="font-weight-bold mb-0 mr-2 text-info" id="wholesalePeriodText" style="font-size: 0.95rem;">Rp {{ number_format($wholesaleSalesPeriod, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row for Other Stats -->
    <div class="row mb-4">
        <div class="col-4 px-1">
            <div class="card card-apms border-0 mb-0">
                <div class="card-body p-2 p-sm-3 text-center">
                    <div class="text-warning mb-1"><i class="fas fa-box-open"></i></div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 0.6rem;">Low Stok</div>
                    <div class="h6 mb-0 font-weight-bold text-dark" id="lowStockCountText">{{ $lowStockProductsCount }}</div>
                </div>
            </div>
        </div>
        
        <div class="col-4 px-1">
            <div class="card card-apms border-0 mb-0">
                <div class="card-body p-2 p-sm-3 text-center">
                    <div class="text-teal mb-1"><i class="fas fa-users"></i></div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 0.6rem;">Customer</div>
                    <div class="h6 mb-0 font-weight-bold text-dark" id="totalCustomersText">{{ $totalCustomers }}</div>
                </div>
            </div>
        </div>

        <div class="col-4 px-1">
            <div class="card card-apms border-0 mb-0">
                <div class="card-body p-2 p-sm-3 text-center">
                    <div class="text-indigo mb-1"><i class="fas fa-wallet"></i></div>
                    <div class="text-uppercase text-muted font-weight-bold" style="font-size: 0.6rem;">Est Profit</div>
                    <div class="h6 mb-0 font-weight-bold text-success" id="netProfitText">Rp {{ number_format($periodProfit, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    
    <!-- Section: Activity & Reports -->
    <div class="d-flex align-items-center mb-3 mt-4">
        <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.05rem;">Aktivitas & Laporan Utama</h6>
        <div class="flex-grow-1 ml-3 border-bottom" style="opacity: 0.1;"></div>
    </div>
    
    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Sales Chart -->
            <div class="card card-apms">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">Grafik Penjualan Tahunan {{ date('Y') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary-apms btn-sm" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="salesChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="card card-apms">
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
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="text-primary">
                                        {{ $transaction->invoice_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle avatar-sm bg-faint-primary mr-2">
                                            {{ strtoupper(substr($transaction->customer->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="font-weight-bold truncate-text">{{ $transaction->customer->name ?? 'Umum' }}</div>
                                    </div>
                                    <div class="d-sm-none text-xs-mobile text-muted">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
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
            <div class="card card-apms">
                <div class="card-header border-0">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('transactions.create') }}" class="btn btn-ghost-primary btn-block mb-2 text-sm font-weight-bold">
                                <i class="fas fa-cash-register mr-1"></i> Kasir
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-block mb-2 text-sm font-weight-bold">
                                <i class="fas fa-plus mr-1"></i> Produk
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-warning btn-block mb-2 text-sm font-weight-bold">
                                <i class="fas fa-boxes mr-1"></i> Stok
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-info btn-block mb-2 text-sm font-weight-bold">
                                <i class="fas fa-chart-bar mr-1"></i> Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Row -->
    <div class="row mt-3">
        <!-- Profit Summary -->
        <div class="col-md-6">
            <div class="card card-apms bg-gradient-success">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Keuangan {{ $periodLabel }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <h4 id="summaryRevenueText">Rp {{ number_format($periodSales, 0, ',', '.') }}</h4>
                            <p class="mb-0">Revenue</p>
                        </div>
                        <div class="col-4 text-center border-left border-right">
                            <h4 id="summaryExpenseText">Rp {{ number_format($periodExpenses, 0, ',', '.') }}</h4>
                            <p class="mb-0">Expense</p>
                        </div>
                        <div class="col-4 text-center">
                            <h4 id="summaryProfitText">Rp {{ number_format($periodProfit, 0, ',', '.') }}</h4>
                            <p class="mb-0">Profit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Methods -->
        <div class="col-md-6">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Distribusi Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="paymentChart" height="150"></canvas>
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
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    var salesChartData = {
        labels: @json(collect($salesData)->pluck('month')),
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
            data: @json(collect($salesData)->pluck('sales'))
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
                        <div class="col-md-6 col-lg-3 mb-2 mb-lg-0">
                            <div class="d-flex align-items-start p-2 rounded border">
                                <i class="fas ${insight.icon} ${insight.color} mt-1 mr-2"></i>
                                <div>
                                    <small class="text-muted d-block font-weight-bold" style="text-transform: uppercase; font-size: 0.65rem;">${insight.title}</small>
                                    <p class="mb-0 text-sm" style="line-height: 1.2;">${insight.text}</p>
                                </div>
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