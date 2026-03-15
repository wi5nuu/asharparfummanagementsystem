@extends('layouts.app')

@section('title', 'Manajemen Kupon')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Kupon</h3>
                    <div class="card-tools">
                        <a href="{{ route('coupons.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Kupon Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($coupons->count())
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Kode & Tipe</th>
                                <th class="text-nowrap">Nilai</th>
                                <th class="d-none d-sm-table-cell text-nowrap">Berlaku</th>
                                <th class="d-none d-md-table-cell text-nowrap">Penggunaan</th>
                                <th class="text-nowrap text-center">Status</th>
                                <th class="text-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-primary">{{ $coupon->code }}</div>
                                    <div class="text-xs-mobile text-muted">
                                        {{ ucfirst($coupon->type) }} | Exp: {{ $coupon->expiration_date->format('d/m/y') }}
                                    </div>
                                </td>
                                <td class="font-weight-bold text-nowrap">
                                    @if($coupon->is_percentage)
                                        {{ $coupon->value }}%
                                    @else
                                        Rp{{ number_format($coupon->value, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell text-nowrap">{{ $coupon->expiration_date->format('d/m/Y') }}</td>
                                <td class="d-none d-md-table-cell text-nowrap">{{ $coupon->used_count }} / {{ $coupon->max_usage }}</td>
                                <td class="text-center">
                                    @if($coupon->is_active)
                                        <span class="badge badge-success text-xs-mobile">Aktif</span>
                                    @else
                                        <span class="badge badge-danger text-xs-mobile">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('coupons.destroy', $coupon) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $coupons->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">Belum ada kupon</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
