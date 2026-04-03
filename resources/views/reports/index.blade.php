@extends('layouts.app')

@section('title', 'Laporan & Analitik')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- LEVEL 0: Strategic Command (Combined Highlights) -->
        <div class="col-12 mb-3">
            <div class="card card-apms border-0 bg-gradient-dark text-white shadow-lg overflow-hidden position-relative" style="background: linear-gradient(135deg, #0b1727 0%, #1a5ab3 100%);">
                <div class="card-body p-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-uppercase font-weight-bold mb-0 smaller text-white-50" style="letter-spacing: 0.1rem;">Total Omzet Gabungan (Eceran + Grosir)</p>
                        <h3 class="font-weight-bold mb-0 text-white display-5">Rp {{ number_format($monthlyStats['revenue'], 0, ',', '.') }}</h3>
                        <span class="smaller text-white-50"><i class="fas fa-calendar-alt mr-1"></i> Bulan Ini: {{ date('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Cards -->
        <div class="col-12">
            <div class="row">
                @foreach($reportCards as $card)
                <div class="col-lg-3 col-6 mb-3">
                    <div class="card card-apms h-100 border-0 shadow-sm overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-faint-{{ $card['color'] }} rounded p-2 mr-2">
                                    <i class="{{ $card['icon'] }} text-{{ $card['color'] }}"></i>
                                </div>
                                <span class="text-uppercase text-muted font-weight-bold smaller">{{ $card['title'] }}</span>
                            </div>
                            <h4 class="font-weight-bold mb-0 text-dark">{{ $card['value'] }}</h4>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Quick Reports -->
        <div class="col-md-8">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Laporan Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Sales Report -->
                        <div class="col-md-6">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Laporan Penjualan</h3>
                                </div>
                                <div class="card-body">
                                    <form id="salesReportForm">
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Periode</label>
                                            <select class="form-control form-control-sm" id="salesPeriod">
                                                <option value="today">Hari Ini</option>
                                                <option value="yesterday">Kemarin</option>
                                                <option value="this_week">Minggu Ini</option>
                                                <option value="last_week">Minggu Lalu</option>
                                                <option value="this_month">Bulan Ini</option>
                                                <option value="last_month">Bulan Lalu</option>
                                                <option value="custom">Custom</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-2 custom-date" style="display: none;">
                                            <label class="small font-weight-bold">Tanggal Mulai</label>
                                            <input type="date" class="form-control form-control-sm" id="startDate">
                                        </div>
                                        <div class="form-group mb-2 custom-date" style="display: none;">
                                            <label class="small font-weight-bold">Tanggal Akhir</label>
                                            <input type="date" class="form-control form-control-sm" id="endDate">
                                        </div>
                                        <button type="submit" class="btn btn-primary-apms btn-sm btn-block font-weight-bold">
                                            <i class="fas fa-file-pdf mr-1"></i> Generate PDF
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Inventory Report -->
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Laporan Inventory</h3>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="{{ route('reports.inventory') }}" class="list-group-item list-group-item-action py-2 px-1 text-sm border-0">
                                            <i class="fas fa-boxes mr-2 text-success"></i> Stok Saat Ini
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action py-2 px-1 text-sm border-0" 
                                           onclick="generateLowStockReport()">
                                            <i class="fas fa-exclamation-triangle mr-2 text-warning"></i> Stok Rendah
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action py-2 px-1 text-sm border-0"
                                           onclick="generateExpiryReport()">
                                            <i class="fas fa-calendar-times mr-2 text-danger"></i> Akan Kadaluarsa
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action py-2 px-1 text-sm border-0"
                                           onclick="generateStockMovementReport()">
                                            <i class="fas fa-exchange-alt mr-2 text-primary"></i> Perpindahan Stok
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Reports -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Laporan Lanjutan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row no-gutters">
                                        <div class="col-md-3 p-1">
                                            <a href="{{ route('reports.profit-loss') }}" class="btn btn-outline-info btn-sm btn-block mb-1 font-weight-bold">
                                                <i class="fas fa-chart-line mr-1"></i> Profit & Loss
                                            </a>
                                        </div>
                                        <div class="col-md-3 p-1">
                                            <a href="{{ route('reports.customers') }}" class="btn btn-outline-warning btn-sm btn-block mb-1 font-weight-bold">
                                                <i class="fas fa-users mr-1"></i> Pelanggan
                                            </a>
                                        </div>
                                        <div class="col-md-3 p-1">
                                            <a href="#" class="btn btn-outline-success btn-sm btn-block mb-1 font-weight-bold" 
                                               onclick="generateProductPerformance()">
                                                <i class="fas fa-chart-bar mr-1"></i> Produk
                                            </a>
                                        </div>
                                        <div class="col-md-3 p-1">
                                            <a href="#" class="btn btn-outline-primary btn-sm btn-block mb-1 font-weight-bold" 
                                               onclick="generateEmployeeReport()">
                                                <i class="fas fa-user-tie mr-1"></i> Karyawan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Reports -->
        <div class="col-md-4">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Laporan Terbaru</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Laporan</th>
                                    <th>Tanggal</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReports as $report)
                                <tr>
                                    <td>{{ $report->name }}</td>
                                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ $report->file_url }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card card-apms mt-3">
                <div class="card-header">
                    <h3 class="card-title">Statistik Cepat</h3>
                </div>
                <div class="card-body">
                    <canvas id="quickStatsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Detailed Reports -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Performa Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <canvas id="monthlyPerformanceChart" height="300"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box mb-3 bg-faint-primary border">
                                <span class="info-box-icon bg-primary text-white"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold text-muted text-uppercase smaller">Revenue (Gabungan)</span>
                                    <span class="info-box-number text-dark h5 mb-0">Rp {{ number_format($monthlyStats['revenue'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="info-box mb-3 bg-faint-warning border">
                                <span class="info-box-icon bg-warning text-white"><i class="fas fa-shopping-cart"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold text-muted text-uppercase smaller">Expenses</span>
                                    <span class="info-box-number text-dark h5 mb-0">Rp {{ number_format($monthlyStats['expenses'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="info-box mb-3 bg-faint-info border">
                                <span class="info-box-icon bg-info text-white"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold text-muted text-uppercase smaller">Net Profit</span>
                                    <span class="info-box-number text-dark h5 mb-0">Rp {{ number_format($monthlyStats['profit'], 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- NEW: Housing Logistics Summary -->
                            <div class="card card-outline card-warning mt-4 shadow-none border">
                                <div class="card-header py-2">
                                    <h3 class="card-title smaller text-uppercase"><i class="fas fa-home mr-1"></i> Logistik Tempat Tinggal</h3>
                                </div>
                                <div class="card-body p-2">
                                    <div class="row no-gutters text-center">
                                        <div class="col-6 border-right">
                                            <div class="h6 mb-0 font-weight-bold">{{ $housingStats['mess'] }}</div>
                                            <div class="smaller text-muted text-uppercase">Di Mes</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="h6 mb-0 font-weight-bold">{{ $housingStats['house'] }}</div>
                                            <div class="smaller text-muted text-uppercase">Rumah</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('employees.index') }}" class="btn btn-xs btn-outline-warning btn-block mt-2 font-weight-bold">
                                        Lihat Daftar Alamat <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
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
    // Toggle custom date range
    $('#salesPeriod').change(function() {
        if ($(this).val() === 'custom') {
            $('.custom-date').show();
        } else {
            $('.custom-date').hide();
        }
    });
    
    // Sales report form
    $('#salesReportForm').submit(function(e) {
        e.preventDefault();
        
        const period = $('#salesPeriod').val();
        let url = '/reports/sales/pdf?period=' + period;
        
        if (period === 'custom') {
            const start = $('#startDate').val();
            const end = $('#endDate').val();
            if (!start || !end) {
                Swal.fire('Error', 'Harap pilih rentang tanggal', 'error');
                return;
            }
            url += `&start_date=${start}&end_date=${end}`;
        }
        
        window.open(url, '_blank');
    });
    
    // Quick Stats Chart
    const quickStatsCtx = document.getElementById('quickStatsChart').getContext('2d');
    const quickStatsChart = new Chart(quickStatsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Penjualan', 'Produk Terjual', 'Pelanggan Baru', 'Transaksi'],
            datasets: [{
                data: [65, 15, 10, 10],
                backgroundColor: ['#FF6B35', '#3498db', '#2ecc71', '#f39c12']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Monthly Performance Chart
    const monthlyCtx = document.getElementById('monthlyPerformanceChart').getContext('2d');
    
    // Parse blade arrays to raw JS objects
    const labelsRaw = JSON.parse('{!! json_encode($monthlyChartData["labels"]) !!}');
    const revRaw = JSON.parse('{!! json_encode($monthlyChartData["revenue"]) !!}');
    const expRaw = JSON.parse('{!! json_encode($monthlyChartData["expenses"]) !!}');
    const profRaw = JSON.parse('{!! json_encode($monthlyChartData["profit"]) !!}');

    const monthlyChart = new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: labelsRaw,
            datasets: [{
                label: 'Revenue',
                data: revRaw,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Expenses',
                data: expRaw,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }, {
                label: 'Profit',
                data: profRaw,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + 
                                   context.parsed.y.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            }
        }
    });
});

function generateLowStockReport() {
    window.open('/reports/inventory/low-stock/pdf', '_blank');
}

function generateExpiryReport() {
    window.open('/reports/inventory/expiry/pdf', '_blank');
}

function generateStockMovementReport() {
    const month = prompt('Masukkan bulan (1-12):', new Date().getMonth() + 1);
    if (month) {
        window.open(`/reports/inventory/movement/pdf?month=${month}`, '_blank');
    }
}

function generateProductPerformance() {
    const year = prompt('Masukkan tahun:', new Date().getFullYear());
    if (year) {
        window.open(`/reports/products/performance/pdf?year=${year}`, '_blank');
    }
}

function generateEmployeeReport() {
    window.open('/reports/employees/performance/pdf', '_blank');
}
</script>
@endpush