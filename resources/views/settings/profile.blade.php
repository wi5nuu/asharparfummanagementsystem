@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card card-apms shadow-sm border-0">
                <div class="card-header bg-primary-apms text-white text-center">
                    <div class="my-3">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h5 class="font-weight-bold mb-0">{{ $user->name }}</h5>
                    <p class="small mb-2">{{ ucfirst($user->role) }}</p>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills">
                        <a class="nav-link p-3 border-bottom rounded-0" href="{{ route('settings.index') }}">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Profile Info Card -->
            <div class="card card-apms shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Informasi Dasar</h3>
                </div>
                <form action="{{ route('settings.profile.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button type="submit" class="btn btn-primary-apms px-4">
                            <i class="fas fa-save mr-1"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Card -->
            <div class="card card-apms shadow-sm border-0">
                <div class="card-header bg-warning">
                    <h3 class="card-title font-weight-bold text-white">Keamanan (Ganti Password)</h3>
                </div>
                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">
                            <i class="fas fa-info-circle mr-1"></i> Pastikan password baru Anda kuat dan sulit ditebak.
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button type="submit" class="btn btn-warning px-4 font-weight-bold text-white">
                            <i class="fas fa-key mr-1"></i> Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
