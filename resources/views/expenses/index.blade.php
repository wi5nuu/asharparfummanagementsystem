@extends('layouts.app')

@section('title', 'Daftar Pengeluaran')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pengeluaran Toko</h3>
                    <div class="card-tools">
                        <a href="{{ route('expenses.create') }}" class="btn btn-primary-apms btn-sm">
                            <i class="fas fa-plus"></i> Catat Pengeluaran Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pengeluaran</span>
                                    <span class="info-box-number text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Vendor</th>
                                    <th>Jumlah (Rp)</th>
                                    <th>Bukti</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                    <td><span class="badge badge-info">{{ $expense->category->name }}</span></td>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ $expense->vendor ?? '-' }}</td>
                                    <td class="text-right font-weight-bold">{{ number_format($expense->amount, 0, ',', '.') }}</td>
                                    <td>
                                        @if($expense->proof_image)
                                            <a href="{{ asset('storage/' . $expense->proof_image) }}" target="_blank" class="btn btn-xs btn-default">
                                                <i class="fas fa-image"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-muted small">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data pengeluaran.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
