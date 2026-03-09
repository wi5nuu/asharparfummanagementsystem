@extends('layouts.app')

@section('title', 'Manajemen Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Daftar Produk</h3>
                    <div class="card-tools">
                        <a href="{{ route('products.create') }}" class="btn btn-primary-apms">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                        <div class="btn-group ml-2">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">PDF</a>
                                <a class="dropdown-item" href="#">Excel</a>
                                <a class="dropdown-item" href="#">CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari produk...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select id="categoryFilter" class="form-control">
                                <option value="">Semua Kategori</option>
                                <option value="premium">Premium</option>
                                <option value="regular">Regular</option>
                                <option value="standard">Standard</option>
                                <option value="refill">Refill</option>
                                <option value="wholesale">Wholesale</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="stockFilter" class="form-control">
                                <option value="">Semua Stok</option>
                                <option value="low">Stok Rendah</option>
                                <option value="out">Stok Habis</option>
                                <option value="available">Tersedia</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="statusFilter" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary" onclick="resetFilters()">
                                <i class="fas fa-redo"></i> Reset Filter
                            </button>
                        </div>
                    </div>
                    
                    <!-- Products Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="productsTable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>Kode</th>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                @php
                                    $inventory = $product->inventories->first();
                                    $currentStock = $inventory ? $inventory->current_stock : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $product->internal_id }}</span><br>
                                        <small class="text-muted">{{ $product->barcode }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="img-circle img-size-32 mr-2">
                                            @else
                                            <div class="img-circle img-size-32 bg-light d-flex align-items-center justify-content-center mr-2">
                                                <i class="fas fa-wine-bottle text-muted"></i>
                                            </div>
                                            @endif
                                            <div>
                                                <strong>{{ $product->name }}</strong><br>
                                                <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $product->category->color ?? '#FF6B35' }}; color: white;">
                                            {{ $product->category->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $product->size }} {{ $product->unit }}</td>
                                    <td>
                                        <div class="price-info">
                                            <div class="text-success font-weight-bold">
                                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                            </div>
                                            @if($product->wholesale_price)
                                            <div class="text-primary small">
                                                Grosir: Rp {{ number_format($product->wholesale_price, 0, ',', '.') }}
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($currentStock == 0)
                                            <span class="badge badge-danger">Habis</span>
                                        @elseif($currentStock < 10)
                                            <span class="badge badge-warning">{{ $currentStock }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $currentStock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('products.show', $product->id) }}" 
                                               class="btn btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->id) }}" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('products.barcode', $product->id) }}" 
                                               class="btn btn-primary" title="Barcode" target="_blank">
                                                <i class="fas fa-barcode"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                    onclick="deleteProduct({{ $product->id }})" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                                Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari {{ $products->total() }} produk
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bulk Actions -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default" onclick="bulkAction('activate')">
                                    <i class="fas fa-check-circle"></i> Aktifkan
                                </button>
                                <button type="button" class="btn btn-default" onclick="bulkAction('deactivate')">
                                    <i class="fas fa-ban"></i> Nonaktifkan
                                </button>
                                <button type="button" class="btn btn-default" onclick="bulkAction('export')">
                                    <i class="fas fa-file-export"></i> Export
                                </button>
                                <button type="button" class="btn btn-danger" onclick="bulkAction('delete')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <span id="selectedCount">0 produk terpilih</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.price-info {
    min-width: 120px;
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
</style>
@endpush

@push('scripts')
<script>
let selectedProducts = [];

function deleteProduct(id) {
    $('#deleteForm').attr('action', `/products/${id}`);
    $('#deleteModal').modal('show');
}

function bulkAction(action) {
    const selected = $('.product-checkbox:checked');
    if (selected.length === 0) {
        Swal.fire('Peringatan', 'Pilih produk terlebih dahulu', 'warning');
        return;
    }
    
    const ids = selected.map(function() {
        return $(this).val();
    }).get();
    
    switch(action) {
        case 'delete':
            if (confirm(`Hapus ${ids.length} produk?`)) {
                // AJAX delete
                $.ajax({
                    url: '/products/bulk-delete',
                    method: 'POST',
                    data: { ids: ids, _token: '{{ csrf_token() }}' },
                    success: function() {
                        location.reload();
                    }
                });
            }
            break;
        case 'activate':
            // AJAX activate
            break;
        case 'deactivate':
            // AJAX deactivate
            break;
        case 'export':
            window.open(`/products/export?ids=${ids.join(',')}`, '_blank');
            break;
    }
}

function resetFilters() {
    $('#searchInput').val('');
    $('#categoryFilter').val('');
    $('#stockFilter').val('');
    $('#statusFilter').val('');
    $('#productsTable tbody tr').show();
}

$(function() {
    // Select All
    $('#selectAll').change(function() {
        const isChecked = $(this).prop('checked');
        $('.product-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });
    
    // Update selected count
    $('.product-checkbox').change(function() {
        updateSelectedCount();
    });
    
    // Search filter
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#productsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Category filter
    $('#categoryFilter').change(function() {
        const value = $(this).val();
        if (value === '') {
            $('#productsTable tbody tr').show();
        } else {
            $('#productsTable tbody tr').each(function() {
                const category = $(this).find('td:eq(3)').text().toLowerCase();
                $(this).toggle(category.indexOf(value) > -1);
            });
        }
    });
    
    // Stock filter
    $('#stockFilter').change(function() {
        const value = $(this).val();
        $('#productsTable tbody tr').each(function() {
            const stockBadge = $(this).find('td:eq(6) .badge');
            let shouldShow = false;
            
            if (value === '') {
                shouldShow = true;
            } else if (value === 'low') {
                shouldShow = stockBadge.hasClass('badge-warning');
            } else if (value === 'out') {
                shouldShow = stockBadge.hasClass('badge-danger');
            } else if (value === 'available') {
                shouldShow = stockBadge.hasClass('badge-success');
            }
            
            $(this).toggle(shouldShow);
        });
    });
});

function updateSelectedCount() {
    const count = $('.product-checkbox:checked').length;
    $('#selectedCount').text(`${count} produk terpilih`);
}
</script>
@endpush