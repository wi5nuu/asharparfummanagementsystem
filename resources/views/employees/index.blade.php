@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-mobile-tight">
            <div class="card card-apms card-mobile-flush border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-primary-apms">
                        <i class="fas fa-users mr-2"></i> Daftar Karyawan Aktif
                    </h5>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary-apms btn-sm shadow-sm ml-auto">
                        <i class="fas fa-plus mr-1"></i> Baru
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($employees->count())
                    <div class="table-responsive">
                        <table class="table table-hover table-compact mb-0">
                            <thead class="bg-light">
                                <tr class="text-nowrap">
                                    <th style="width: 40px;" class="text-center">#</th>
                                    <th>KARYAWAN & INFO</th>
                                    <th class="d-none d-md-table-cell">TELEPON / ASAL</th>
                                    <th>POSISI</th>
                                    <th class="text-center" style="width: 100px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $index => $employee)
                                <tr>
                                    <td class="text-center align-middle text-muted" style="font-size: 0.75rem;">
                                        {{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle avatar-sm mr-2 bg-faint-primary font-weight-bold">
                                                {{ substr($employee->nickname ?? $employee->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark text-truncate" style="max-width: 200px; font-size: 0.85rem;">
                                                    {{ $employee->full_name ?? $employee->name }}
                                                    @if($employee->nickname)
                                                        <span class="text-primary-apms">({{ $employee->nickname }})</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted smaller truncate-text" style="max-width: 180px;">{{ $employee->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle d-none d-md-table-cell">
                                        <div style="font-size: 0.8rem;">{{ $employee->phone ?? '-' }}</div>
                                        <div class="text-muted smaller"><i class="fas fa-map-marker-alt mr-1"></i>{{ $employee->origin ?? 'Asal -' }}</div>
                                    </td>
                                    <td class="align-middle text-nowrap">
                                        @php
                                            $badgeClass = 'badge-secondary';
                                            if($employee->role == 'admin') $badgeClass = 'bg-faint-indigo';
                                            elseif($employee->role == 'supervisor') $badgeClass = 'bg-faint-teal';
                                            elseif($employee->role == 'cashier') $badgeClass = 'bg-faint-primary';
                                            elseif($employee->role == 'packing') $badgeClass = 'bg-faint-warning';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.65rem;">
                                            {{ strtoupper($employee->role) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center" style="gap: 5px;">
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-faint-primary p-1" title="Edit">
                                                <i class="fas fa-edit fa-fw"></i>
                                            </a>
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-faint-danger p-1" onclick="return confirm('Hapus data karyawan ini?')" title="Hapus">
                                                    <i class="fas fa-trash fa-fw"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($employees->hasPages())
                    <div class="p-3 bg-light border-top">
                        {{ $employees->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data karyawan terdaftar.</p>
                        <a href="{{ route('employees.create') }}" class="btn btn-primary-apms btn-sm">Tambah Karyawan Pertama</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
