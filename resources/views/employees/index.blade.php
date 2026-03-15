@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Karyawan</h3>
                    <div class="card-tools">
                        <a href="{{ route('employees.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Karyawan Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($employees->count())
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th>Karyawan & Email</th>
                                <th class="d-none d-sm-table-cell">Telepon</th>
                                <th class="d-none d-md-table-cell">Posisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>
                                    <div class="font-weight-bold truncate-text">{{ $employee->name }}</div>
                                    <div class="text-xs-mobile text-muted">{{ $employee->email }}</div>
                                    <div class="d-sm-none text-xs-mobile text-muted mt-1">{{ $employee->phone ?? '-' }}</div>
                                    <div class="d-md-none mt-1">
                                        <span class="badge badge-primary text-xs-mobile">{{ ucfirst($employee->role) }}</span>
                                    </div>
                                </td>
                                <td class="d-none d-sm-table-cell text-nowrap">{{ $employee->phone ?? '-' }}</td>
                                <td class="d-none d-md-table-cell"><span class="badge badge-primary">{{ ucfirst($employee->role) }}</span></td>
                                <td>
                                    <form action="{{ route('employees.edit', $employee) }}" method="GET" style="display:inline;">
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                    </form>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">Belum ada karyawan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
