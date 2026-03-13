@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Active Shift Alert -->
    <div class="row">
        <div class="col-12">
            @if($activeShift)
            <div class="alert alert-success alert-dismissible card-apms border-left-success">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-cash-register fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold">Shift Aktif: Sedang Berjalan</h5>
                        <p class="mb-0">Dimulai pada {{ $activeShift->start_time->format('d M Y H:i') }} | Modal Awal: Rp {{ number_format($activeShift->initial_cash, 0, ',', '.') }}</p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('shifts.index') }}" class="btn btn-light font-weight-bold">
                            <i class="fas fa-times-circle mr-1"></i> Tutup Shift
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-warning alert-dismissible card-apms border-left-warning">
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold">Shift Belum Dibuka!</h5>
                        <p class="mb-0">Anda harus membuka shift kasir terlebih dahulu untuk dapat melakukan transaksi di POS.</p>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('shifts.index') }}" class="btn btn-primary-apms font-weight-bold">
                            <i class="fas fa-play-circle mr-1"></i> Buka Shift Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 card-apms">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-money-bill-wave"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penjualan Hari Ini</span>
                    <span class="info-box-number">Rp {{ number_format($todaySales, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 card-apms">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Penjualan Bulan Ini</span>
                    <span class="info-box-number">Rp {{ number_format($monthSales, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 card-apms">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-box-open"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Stok Rendah</span>
                    <span class="info-box-number">{{ $lowStockProductsCount }} Produk</span>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3 card-apms">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-wallet"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Laba Bersih (Bulan Ini)</span>
                    <span class="info-box-number">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
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
                    <table class="table table-striped table-valign-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Kasir</th>
                                <th>Status</th>
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
                                <td>{{ $transaction->customer->name ?? 'Umum' }}</td>
                                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge badge-light">{{ strtoupper($transaction->payment_method) }}</span>
                                </td>
                                <td>{{ $transaction->user->name }}</td>
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
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse($lowStockAlerts as $alert)
                        <li class="item">
                            <div class="product-img text-center">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div class="product-info">
                                <a href="{{ route('inventory.index') }}" class="product-title">
                                    {{ $alert->name }}
                                    <span class="badge badge-warning float-right">{{ $alert->current_stock }} stok</span>
                                </a>
                                <span class="product-description">
                                    Segera lakukan pengisian (Min: {{ $alert->minimum_stock }})
                                </span>
                            </div>
                        </li>
                        @empty
                        <li class="item text-center py-3">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="text-muted">Semua stok terpantau aman.</p>
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
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @foreach($topProducts as $index => $product)
                        <li class="item">
                            <div class="product-img">
                                <span class="badge bg-primary">{{ $index + 1 }}</span>
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">
                                    {{ $product->name }}
                                    <span class="badge badge-primary float-right">{{ $product->total_sold }} pcs</span>
                                </a>
                                <span class="product-description">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </span>
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
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary-apms btn-block mb-2">
                                <i class="fas fa-cash-register mr-1"></i> Kasir
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('products.create') }}" class="btn btn-success btn-block mb-2">
                                <i class="fas fa-plus mr-1"></i> Produk
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('inventory.index') }}" class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-boxes mr-1"></i> Stok
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('reports.index') }}" class="btn btn-info btn-block mb-2">
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
                    <h3 class="card-title">Ringkasan Keuangan Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 text-center">
                            <h4>Rp {{ number_format($monthSales, 0, ',', '.') }}</h4>
                            <p class="mb-0">Revenue</p>
                        </div>
                        <div class="col-4 text-center border-left border-right">
                            <h4>Rp {{ number_format($monthExpenses, 0, ',', '.') }}</h4>
                            <p class="mb-0">Expense</p>
                        </div>
                        <div class="col-4 text-center">
                            <h4>Rp {{ number_format($profit, 0, ',', '.') }}</h4>
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
    // Sales Chart
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    var salesChartData = {
        labels: @json(collect($salesData)->pluck('month')),
        datasets: [{
            label: 'Penjualan',
            backgroundColor: 'rgba(255, 107, 53, 0.2)',
            borderColor: 'rgba(255, 107, 53, 1)',
            pointBackgroundColor: 'rgba(255, 107, 53, 1)',
            pointBorderColor: '#fff',
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: 'rgba(255, 107, 53, 1)',
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
    function refreshStats() {
        $.ajax({
            url: '/api/dashboard/stats',
            method: 'GET',
            success: function(response) {
                $('.info-box-number:eq(0)').text(response.todaySales);
                $('.info-box-number:eq(1)').text(response.monthSales);
                $('.info-box-number:eq(2)').text(response.lowStockCount + ' Produk');
                $('.info-box-number:eq(3)').text(response.netProfit);
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
                backgroundColor: ['#FF6B35', '#3498db', '#2ecc71', '#f39c12']
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