@extends('layouts.app')

@section('title', 'Manajemen Inventory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Inventory Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary-apms" data-toggle="modal" data-target="#adjustModal">
                            <i class="fas fa-exchange-alt"></i> Stock Adjustment
                        </button>
                        <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#stockInModal">
                            <i class="fas fa-arrow-down"></i> Stok Masuk
                        </button>
                        <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#auditModal">
                            <i class="fas fa-clipboard-check"></i> Stock Audit
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">
                                <i class="fas fa-boxes"></i> Semua Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="low-tab" data-toggle="tab" href="#low" role="tab">
                                <i class="fas fa-exclamation-triangle text-warning"></i> Stok Rendah
                                <span class="badge badge-warning">{{ $lowStock->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="out-tab" data-toggle="tab" href="#out" role="tab">
                                <i class="fas fa-times-circle text-danger"></i> Stok Habis
                                <span class="badge badge-danger">{{ $outOfStock->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="expiring-tab" data-toggle="tab" href="#expiring" role="tab">
                                <i class="fas fa-calendar-times text-danger"></i> Akan Kadaluarsa
                                <span class="badge badge-danger">{{ $expiringSoon->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="batch-tab" data-toggle="tab" href="#batch" role="tab">
                                <i class="fas fa-layer-group"></i> Batch Management
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Tabs Content -->
                    <div class="tab-content mt-3" id="inventoryTabsContent">
                        <!-- All Inventory Tab -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="inventoryTable">
                                    <thead class="text-nowrap bg-light">
                                        <tr>
                                            <th>Produk & Batch</th>
                                            <th class="d-none d-md-table-cell">Batch</th>
                                            <th class="d-none d-lg-table-cell text-center">Awal</th>
                                            <th class="d-none d-sm-table-cell text-center">Masuk</th>
                                            <th class="d-none d-sm-table-cell text-center">Keluar</th>
                                            <th class="text-center">Stok</th>
                                            <th class="d-none d-xl-table-cell text-center">Min</th>
                                            <th class="d-none d-lg-table-cell">Harga</th>
                                            <th class="d-none d-md-table-cell">Exp</th>
                                            <th class="d-none d-sm-table-cell">Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventories as $inventory)
                                        <tr>
                                            <td class="text-nowrap">
                                                <div class="d-flex align-items-center">
                                                    @if($inventory->product->image)
                                                    <img src="{{ asset('storage/' . $inventory->product->image) }}" 
                                                         class="img-circle img-size-32 mr-2 d-none d-sm-block">
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold truncate-text" style="max-width: 150px;">{{ $inventory->product->name }}</div>
                                                        <div class="text-xs-mobile text-muted">
                                                            {{ $inventory->product->size }} 
                                                            <span class="d-md-none">| B: {{ $inventory->batch_number ?? '-' }}</span>
                                                        </div>
                                                        <div class="d-sm-none mt-1">
                                                            @if($inventory->expiration_date)
                                                                <span class="badge badge-light border text-xs-mobile">Exp: {{ \Carbon\Carbon::parse($inventory->expiration_date)->format('d/m/y') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{ $inventory->batch_number ?? '-' }}</td>
                                            <td class="d-none d-lg-table-cell text-center">{{ $inventory->initial_stock }}</td>
                                            <td class="text-success d-none d-sm-table-cell text-center">+{{ $inventory->stock_in }}</td>
                                            <td class="text-danger d-none d-sm-table-cell text-center">-{{ $inventory->stock_out }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-{{ $inventory->current_stock == 0 ? 'danger' : ($inventory->current_stock <= $inventory->minimum_stock ? 'warning' : 'success') }} px-2">
                                                    {{ $inventory->current_stock }}
                                                </span>
                                                <div class="d-sm-none mt-1 text-xs-mobile">
                                                    <span class="text-success">+{{ $inventory->stock_in }}</span> / <span class="text-danger">-{{ $inventory->stock_out }}</span>
                                                </div>
                                            </td>
                                            <td class="d-none d-xl-table-cell">{{ $inventory->minimum_stock }}</td>
                                            <td class="d-none d-lg-table-cell text-nowrap">Rp {{ number_format($inventory->cost_per_unit, 0, ',', '.') }}</td>
                                            <td class="d-none d-md-table-cell">
                                                @if($inventory->expiration_date)
                                                    @php
                                                        $expDate = \Carbon\Carbon::parse($inventory->expiration_date);
                                                        $now = \Carbon\Carbon::now();
                                                        $diffDays = $now->diffInDays($expDate, false);
                                                    @endphp
                                                    
                                                    @if($diffDays < 0)
                                                        <span class="badge badge-danger">{{ $expDate->format('d/m/Y') }}</span>
                                                    @elseif($diffDays <= 30)
                                                        <span class="badge badge-warning">{{ $expDate->format('d/m/Y') }}</span>
                                                    @else
                                                        <span class="badge badge-success">{{ $expDate->format('d/m/Y') }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="d-none d-sm-table-cell text-nowrap">
                                                @if($inventory->status == 'in_stock')
                                                    <span class="badge badge-success">Tersedia</span>
                                                @elseif($inventory->status == 'low_stock')
                                                    <span class="badge badge-warning">Rendah</span>
                                                @elseif($inventory->status == 'out_of_stock')
                                                    <span class="badge badge-danger">Habis</span>
                                                @elseif($inventory->status == 'expired')
                                                    <span class="badge badge-dark">Expired</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-info" 
                                                            onclick="viewInventory({{ $inventory->id }})" 
                                                            title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning" 
                                                            onclick="editInventory({{ $inventory->id }})" 
                                                            title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" 
                                                            onclick="adjustStock({{ $inventory->id }})" 
                                                            title="Adjust">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    {{ $inventories->links() }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Low Stock Tab -->
                        <div class="tab-pane fade" id="low" role="tabpanel">
                            @if($lowStock->count() > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Terdapat {{ $lowStock->count() }} produk dengan stok rendah
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-nowrap bg-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Stok</th>
                                            <th class="d-none d-sm-table-cell">Min</th>
                                            <th class="d-none d-md-table-cell">Kurang</th>
                                            <th class="d-none d-sm-table-cell">Estimasi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lowStock as $item)
                                        @php
                                            $dailyAvg = $item->daily_average_sales ?? 1;
                                            $daysLeft = $dailyAvg > 0 ? floor($item->current_stock / $dailyAvg) : 0;
                                        @endphp
                                        <tr class="text-nowrap">
                                            <td>
                                                <div class="font-weight-bold truncate-text">{{ $item->product->name }}</div>
                                                <div class="text-xs-mobile text-muted">{{ $item->product->size }}</div>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning px-2">{{ $item->current_stock }}</span>
                                                <div class="d-sm-none mt-1">
                                                    @if($daysLeft <= 3)
                                                        <span class="text-danger text-xs-mobile font-weight-bold">{{ $daysLeft }} hr lagi</span>
                                                    @else
                                                        <span class="text-warning text-xs-mobile">{{ $daysLeft }} hr lagi</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="d-none d-sm-table-cell">{{ $item->minimum_stock }}</td>
                                            <td class="d-none d-md-table-cell">{{ $item->minimum_stock - $item->current_stock }}</td>
                                            <td class="d-none d-sm-table-cell">
                                                @if($daysLeft <= 3)
                                                    <span class="badge badge-danger">{{ $daysLeft }} hr</span>
                                                @elseif($daysLeft <= 7)
                                                    <span class="badge badge-warning">{{ $daysLeft }} hr</span>
                                                @else
                                                    <span class="badge badge-success">{{ $daysLeft }} hr</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm" 
                                                        onclick="quickReorder({{ $item->product_id }})">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Tidak ada produk dengan stok rendah
                            </div>
                            @endif
                        </div>
                        
                        <!-- Out of Stock Tab -->
                        <div class="tab-pane fade" id="out" role="tabpanel">
                            @if($outOfStock->count() > 0)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                Terdapat {{ $outOfStock->count() }} produk yang stoknya habis
                            </div>
                            <div class="row">
                                @foreach($outOfStock as $item)
                                <div class="col-md-4 mb-3">
                                    <div class="card card-outline card-danger">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $item->product->name }}</h5>
                                            <p class="card-text">
                                                <i class="fas fa-box text-danger"></i> Stok: <strong>0</strong><br>
                                                <i class="fas fa-tag text-muted"></i> Kategori: {{ $item->product->category->name }}<br>
                                                <i class="fas fa-money-bill-wave text-success"></i> Harga: Rp {{ number_format($item->product->selling_price, 0, ',', '.') }}
                                            </p>
                                            <button class="btn btn-primary btn-block" 
                                                    onclick="quickReorder({{ $item->product_id }})">
                                                <i class="fas fa-redo"></i> Restock
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Tidak ada produk yang stoknya habis
                            </div>
                            @endif
                        </div>
                        
                        <!-- Expiring Soon Tab -->
                        <div class="tab-pane fade" id="expiring" role="tabpanel">
                            @if($expiringSoon->count() > 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-calendar-times"></i>
                                Terdapat {{ $expiringSoon->count() }} produk yang akan kadaluarsa dalam 30 hari
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="text-nowrap bg-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th class="d-none d-md-table-cell">Batch</th>
                                            <th>Stok</th>
                                            <th>Expired</th>
                                            <th class="d-none d-sm-table-cell">Sisa (Hari)</th>
                                            <th class="d-none d-lg-table-cell">Nilai</th>
                                            <th class="d-none d-md-table-cell">Info</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiringSoon as $item)
                                        @php
                                            $expDate = \Carbon\Carbon::parse($item->expiration_date);
                                            $now = \Carbon\Carbon::now();
                                            $diffDays = $now->diffInDays($expDate, false);
                                            $stockValue = $item->current_stock * $item->cost_per_unit;
                                        @endphp
                                        <tr class="text-nowrap">
                                            <td>
                                                <div class="font-weight-bold truncate-text" style="max-width: 150px;">{{ $item->product->name }}</div>
                                                <small class="text-muted d-block d-md-none">B: {{ $item->batch_number ?? '-' }}</small>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{ $item->batch_number ?? '-' }}</td>
                                            <td>{{ $item->current_stock }}</td>
                                            <td><span class="badge badge-{{ $diffDays < 7 ? 'danger' : 'warning' }}">{{ $expDate->format('d/m/y') }}</span></td>
                                            <td class="d-none d-sm-table-cell">
                                                @if($diffDays < 0)
                                                    <span class="text-danger">EXPIRED</span>
                                                @else
                                                    {{ $diffDays }} hr
                                                @endif
                                            </td>
                                            <td class="d-none d-lg-table-cell">Rp {{ number_format($stockValue, 0, ',', '.') }}</td>
                                            <td class="d-none d-md-table-cell">
                                                @if($diffDays <= 7)
                                                    <span class="badge badge-danger">Diskon Cepat!</span>
                                                @elseif($diffDays <= 15)
                                                    <span class="badge badge-warning">Promo</span>
                                                @else
                                                    <span class="badge badge-info">Monitor</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                Tidak ada produk yang akan kadaluarsa dalam 30 hari
                            </div>
                            @endif
                        </div>
                        
                        <!-- Batch Management Tab -->
                        <div class="tab-pane fade" id="batch" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Kelola Batch Produk</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Pilih Produk</label>
                                                <select class="form-control select2" id="batchProductSelect">
                                                    <option value="">Pilih Produk</option>
                                                    @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nomor Batch</label>
                                                <input type="text" class="form-control" id="batchNumber" 
                                                       placeholder="Contoh: BATCH-2024-001">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="batchDetails" class="mt-3" style="display: none;">
                                        <!-- Batch details will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="small text-muted">
                                Total Nilai Inventory: <strong>Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="small">
                                Update Terakhir: {{ now()->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('inventory.modals.adjust')
@include('inventory.modals.stock-in')
@include('inventory.modals.audit')

@endsection

@push('styles')
<style>
.card-outline {
    border-top: 3px solid;
}
.img-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
}
.img-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.nav-tabs .nav-link.active {
    border-bottom: 3px solid #FF6B35;
    font-weight: bold;
}
.truncate-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.text-xs {
    font-size: 0.65rem;
}
.smaller {
    font-size: 0.75rem;
}
@media (max-width: 576px) {
    .card-tools {
        margin-top: 10px;
        float: none !important;
        display: flex;
        overflow-x: auto;
        padding-bottom: 5px;
    }
    .card-tools .btn {
        flex: 0 0 auto;
        margin-right: 5px;
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
$(function() {
    // Initialize DataTable
    $('#inventoryTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25
    });
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    // Product select change for batch management
    $('#batchProductSelect').change(function() {
        const productId = $(this).val();
        if (productId) {
            loadBatchDetails(productId);
        } else {
            $('#batchDetails').hide();
        }
    });
});

function loadBatchDetails(productId) {
    $.ajax({
        url: `/api/inventory/batch/${productId}`,
        method: 'GET',
        success: function(response) {
            $('#batchDetails').html(response.html).show();
        }
    });
}

function adjustStock(inventoryId) {
    $('#adjustInventoryId').val(inventoryId);
    $('#adjustModal').modal('show');
}

function quickReorder(productId) {
    Swal.fire({
        title: 'Pesan Ulang Produk',
        html: `
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" id="reorderQuantity" class="form-control" min="1" value="10">
            </div>
            <div class="form-group">
                <label>Supplier</label>
                <select id="reorderSupplier" class="form-control">
                    <option value="">Pilih Supplier</option>
                    <option value="1">Supplier Utama</option>
                    <option value="2">Supplier Alternatif</option>
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Pesan',
        preConfirm: () => {
            const quantity = $('#reorderQuantity').val();
            const supplier = $('#reorderSupplier').val();
            
            if (!quantity || quantity < 1) {
                Swal.showValidationMessage('Masukkan jumlah yang valid');
                return false;
            }
            
            return { quantity, supplier };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request to create purchase order
            Swal.fire('Berhasil', 'Pesanan telah dibuat', 'success');
        }
    });
}

function viewInventory(id) {
    window.location.href = `/inventory/${id}`;
}

function editInventory(id) {
    window.location.href = `/inventory/${id}/edit`;
}
</script>
@endpush