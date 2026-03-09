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
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Posisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td><strong>{{ $employee->name }}</strong></td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->phone ?? '-' }}</td>
                                <td><span class="badge badge-primary">{{ ucfirst($employee->role) }}</span></td>
                                <td>
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info">Belum ada karyawan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
