@extends('layouts.app')

@section('title', 'Manajemen Grosir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-apms border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-primary-apms">
                            <i class="fas fa-boxes-packing mr-2"></i> Daftar Pesanan Grosir
                        </h3>
                        <div>
                            <a href="{{ route('wholesale.create') }}" class="btn btn-primary-apms">
                                <i class="fas fa-plus-circle mr-1"></i> Buat Pesanan Baru
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('wholesale.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Kode, Nama Penerima, No HP..." value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Admin)</option>
                                    <option value="on_progress" {{ request('status') == 'on_progress' ? 'selected' : '' }}>On Progress</option>
                                    <option value="ready_to_ship" {{ request('status') == 'ready_to_ship' ? 'selected' : '' }}>Siap Antar</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr class="text-nowrap">
                                    <th>Invoice</th>
                                    <th>Penerima</th>
                                    <th class="d-none d-sm-table-cell">Target</th>
                                    <th class="d-none d-md-table-cell">Total</th>
                                    <th>Status</th>
                                    <th class="d-none d-lg-table-cell">Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td class="font-weight-bold text-nowrap">{{ $order->invoice_number }}</td>
                                    <td>
                                        <div class="font-weight-bold truncate-text">{{ $order->recipient_name }}</div>
                                        <div class="d-sm-none text-xs-mobile text-muted">
                                            {{ $order->recipient_phone }} | T: Rp{{ number_format($order->package_target_amount, 0, ',', '.') }}
                                        </div>
                                        <small class="text-muted d-none d-sm-block">{{ $order->recipient_phone }}</small>
                                    </td>
                                    <td class="d-none d-sm-table-cell text-nowrap"><span class="text-primary font-weight-bold">Rp {{ number_format($order->package_target_amount, 0, ',', '.') }}</span></td>
                                    <td class="d-none d-md-table-cell text-nowrap">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="text-nowrap">
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning px-2 py-1">PENDING</span>
                                        @elseif($order->status == 'on_progress')
                                            <span class="badge badge-primary px-2 py-1">ON PROGRESS</span>
                                        @elseif($order->status == 'ready_to_ship')
                                            <span class="badge badge-info px-2 py-1">SIAP ANTAR</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge badge-success px-2 py-1">SELESAI</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1">{{ strtoupper($order->status) }}</span>
                                        @endif
                                        <div class="d-lg-none text-xs-mobile text-muted mt-1">
                                            {{ $order->created_at->format('d/m/y H:i') }}
                                        </div>
                                    </td>
                                    <td class="d-none d-lg-table-cell text-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('wholesale.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada pesanan grosir ditemukan.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
