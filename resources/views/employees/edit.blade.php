@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Karyawan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required>
                        </div>

                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="tel" name="phone" class="form-control" value="{{ $employee->phone ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label>Posisi</label>
                            <select name="role" class="form-control" required>
                                <option value="cashier" {{ $employee->role == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                <option value="packing" {{ $employee->role == 'packing' ? 'selected' : '' }}>Packing</option>
                                <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="supervisor" {{ $employee->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
