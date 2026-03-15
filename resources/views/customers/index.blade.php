@extends('layouts.app')

@section('title', 'Manajemen Pelanggan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pelanggan</h3>
                    <div class="card-tools">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary-apms">
                            <i class="fas fa-user-plus"></i> Pelanggan Baru
                        </a>
                        <button class="btn btn-success ml-2" onclick="exportCustomers()">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" id="searchCustomers" class="form-control" 
                                   placeholder="Cari pelanggan...">
                        </div>
                        <div class="col-md-3">
                            <select id="customerTypeFilter" class="form-control">
                                <option value="">Semua Tipe</option>
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                                <option value="vip">VIP</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="customerStatusFilter" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-secondary btn-block" onclick="resetFilters()">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="d-none d-md-table-cell text-nowrap">ID</th>
                                    <th class="text-nowrap">Pelanggan</th>
                                    <th class="text-nowrap">Kontak</th>
                                    <th class="d-none d-lg-table-cell text-nowrap">Tipe</th>
                                    <th class="d-none d-xl-table-cell text-center text-nowrap">Transaksi</th>
                                    <th class="d-none d-sm-table-cell text-nowrap">Total Belanja</th>
                                    <th class="d-none d-lg-table-cell text-center text-nowrap">Poin</th>
                                    <th class="d-none d-md-table-cell text-nowrap">Status</th>
                                    <th class="text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                @php
                                    $totalTransactions = $customer->transactions->count();
                                    $totalSpent = $customer->transactions->sum('total_amount');
                                    $points = floor($totalSpent / 10000); // 1 point per 10k
                                @endphp
                                <tr>
                                    <td class="d-none d-md-table-cell">
                                        <span class="badge badge-light">{{ $customer->customer_code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="img-circle img-size-32 bg-primary d-flex align-items-center justify-content-center mr-2 flex-shrink-0">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <div class="font-weight-bold truncate-text">{{ $customer->name }}</div>
                                                <div class="d-sm-none text-xs-mobile text-muted">
                                                    {{ ucfirst($customer->type) }} | {{ $points }} Poin | {{ $customer->phone ?? 'No Phone' }}
                                                </div>
                                                <small class="text-muted d-none d-md-block">Joined: {{ $customer->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><i class="fas fa-phone text-muted mr-1"></i> {{ $customer->phone ?? '-' }}</div>
                                            <div class="d-none d-md-block"><i class="fas fa-envelope text-muted mr-1"></i> {{ $customer->email ?? '-' }}</div>
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        @if($customer->type == 'wholesale')
                                            <span class="badge badge-info">Wholesale</span>
                                        @elseif($customer->type == 'vip')
                                            <span class="badge badge-success">VIP</span>
                                        @else
                                            <span class="badge badge-secondary">Retail</span>
                                        @endif
                                    </td>
                                    <td class="d-none d-xl-table-cell">
                                        <div class="text-center">
                                            <div class="font-weight-bold">{{ $totalTransactions }}</div>
                                            <small class="text-muted">trns</small>
                                            <div class="font-weight-bold text-nowrap">{{ $totalTransactions }}</div>
                                            <small class="text-muted text-nowrap">trns</small>
                                        </div>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <div class="text-success font-weight-bold text-nowrap">
                                            Rp {{ number_format($totalSpent, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell">
                                        <div class="text-center">
                                            <div class="font-weight-bold text-nowrap">{{ $points }}</div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        @if($customer->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-primary" onclick="viewTransactions({{ $customer->id }})">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="deleteCustomer({{ $customer->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $customers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="small text-center text-sm-left">
                                Total: <strong>{{ $customers->total() }}</strong> |
                                Aktif: <strong>{{ $activeCustomers }}</strong>
                            </div>
                        </div>
                        <div class="col-sm-6 text-center text-sm-right mt-2 mt-sm-0">
                            <div class="small">
                                Rata-rata: <strong>Rp {{ number_format($averageSpent, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Customer Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="customerForm" action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" class="form-control" name="birth_date">
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control" name="gender">
                                    <option value="">Pilih</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Telepon *</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="address" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tipe Pelanggan</label>
                                        <select class="form-control" name="type">
                                            <option value="retail">Retail</option>
                                            <option value="wholesale">Wholesale</option>
                                            <option value="vip">VIP</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="is_active">
                                            <option value="1">Aktif</option>
                                            <option value="0">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Catatan / Selera Aroma</label>
                                <textarea class="form-control" name="aroma_preferences" rows="2" placeholder="Contoh: Suka aroma manis, Baccarat, atau tidak suka aroma kayu"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Catatan Internal</label>
                                <textarea class="form-control" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-apms">Simpan Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="customerDetailContent">
                <!-- Customer details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewCustomer(id) {
    $.ajax({
        url: `/api/customers/${id}`,
        method: 'GET',
        success: function(response) {
            $('#customerDetailContent').html(response.html);
            $('#viewCustomerModal').modal('show');
        }
    });
}

function editCustomer(id) {
    window.location.href = `/customers/${id}/edit`;
}

function viewTransactions(id) {
    window.location.href = `/transactions?customer_id=${id}`;
}

function deleteCustomer(id) {
    Swal.fire({
        title: 'Hapus Pelanggan?',
        text: "Data pelanggan akan dihapus permanen",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/customers/${id}`,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    Swal.fire('Terhapus!', 'Pelanggan berhasil dihapus.', 'success')
                        .then(() => location.reload());
                }
            });
        }
    });
}

function exportCustomers() {
    window.open('/customers/export', '_blank');
}

function resetFilters() {
    $('#searchCustomers').val('');
    $('#customerTypeFilter').val('');
    $('#customerStatusFilter').val('');
    
    // Reset table display
    $('tbody tr').show();
}

$(function() {
    // Search filter
    $('#searchCustomers').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Type filter
    $('#customerTypeFilter').change(function() {
        const value = $(this).val();
        if (!value) {
            $('tbody tr').show();
            return;
        }
        
        $('tbody tr').each(function() {
            const type = $(this).find('td:eq(3) .badge').text().toLowerCase();
            $(this).toggle(type.indexOf(value) > -1);
        });
    });
    
    // Status filter
    $('#customerStatusFilter').change(function() {
        const value = $(this).val();
        if (!value) {
            $('tbody tr').show();
            return;
        }
        
        $('tbody tr').each(function() {
            const status = $(this).find('td:eq(7) .badge').hasClass('badge-success') ? 'active' : 'inactive';
            $(this).toggle(status === value);
        });
    });
    
    // Customer form submission
    $('#customerForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire('Berhasil', 'Pelanggan berhasil ditambahkan', 'success')
                    .then(() => {
                        $('#newCustomerModal').modal('hide');
                        $('#customerForm')[0].reset();
                        location.reload();
                    });
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            }
        });
    });
});
</script>
@endpush