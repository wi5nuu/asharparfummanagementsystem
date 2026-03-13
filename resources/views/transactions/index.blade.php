@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Daftar Transaksi</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 280px;">
                            <input type="text" id="dateRange" class="form-control float-right" 
                                   placeholder="Filter tanggal" 
                                   value="{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : '' }}">
                            <div class="input-group-append" style="cursor: pointer" onclick="$('#dateRange').click()">
                                <button class="btn btn-default">
                                    <i class="fas fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select id="paymentFilter" name="payment_method" class="form-control" onchange="filterTransactions()">
                                <option value="">Semua Pembayaran</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="customerFilter" name="customer_type" class="form-control" onchange="filterTransactions()">
                                <option value="">Semua Pelanggan</option>
                                <option value="retail" {{ request('customer_type') == 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="wholesale" {{ request('customer_type') == 'wholesale' ? 'selected' : '' }}>Wholesale</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Tipe</th>
                                    <th>Total</th>
                                    <th>Pembayaran</th>
                                    <th>Kasir</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>
                                        <a href="{{ route('transactions.show', $transaction->id) }}">
                                            {{ $transaction->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->customer->name ?? 'Umum' }}</td>
                                    <td>
                                        @if($transaction->customer_type == 'wholesale')
                                            <span class="badge badge-info">Grosir</span>
                                        @else
                                            <span class="badge badge-secondary">Retail</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-light">{{ strtoupper($transaction->payment_method) }}</span>
                                    </td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('transactions.show', $transaction->id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transactions.print', $transaction->id) }}" 
                                               class="btn btn-primary btn-sm" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @can('manage_transactions')
                                            <form id="delete-form-{{ $transaction->id }}" action="{{ route('transactions.destroy', $transaction->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="confirmDelete({{ $transaction->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(function() {
    // Date range picker
    $('#dateRange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD',
            cancelLabel: 'Clear',
            applyLabel: 'Pilih',
            customRangeLabel: 'Pilih Sendiri',
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
        },
        ranges: {
           'Hari Ini': [moment(), moment()],
           'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
           '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
           'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
           'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });

    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        filterTransactions();
    });

    $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        filterTransactions();
    });
    
    window.filterTransactions = function() {
        const payment = $('#paymentFilter').val();
        const customer = $('#customerFilter').val();
        const dateRange = $('#dateRange').val();
        
        let url = `{{ route('transactions.index') }}?`;
        if (payment) url += `payment_method=${payment}&`;
        if (customer) url += `customer_type=${customer}&`;
        
        if (dateRange && dateRange.includes(' - ')) {
            const dates = dateRange.split(' - ');
            url += `start_date=${dates[0]}&end_date=${dates[1]}&`;
        }
        
        window.location.href = url;
    }

    window.confirmDelete = function(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Tindakan ini akan mengembalikan stok dan mencabut poin pelanggan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
});
</script>
@endpush