@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-lg-8 mx-auto">
            <div class="card card-apms border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-primary-apms">
                        <i class="fas fa-user-plus mr-2"></i> Form Karyawan Baru
                    </h5>
                    <p class="text-muted smaller mb-0">Lengkapi data personal dan profesional karyawan.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Nama Panggilan / User ID</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: wisnu_apms" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Nama Lengkap</label>
                                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" placeholder="Sesuai KTP">
                                    @error('full_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Panggilan Akrab</label>
                                    <input type="text" name="nickname" class="form-control @error('nickname') is-invalid @enderror" value="{{ old('nickname') }}" placeholder="Nama sapaan">
                                    @error('nickname') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Asal / Domisili</label>
                                    <input type="text" name="origin" class="form-control @error('origin') is-invalid @enderror" value="{{ old('origin') }}" placeholder="Contoh: Bekasi Timur">
                                    @error('origin') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Email Login</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Nomor Telepon (WhatsApp)</label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold smaller text-muted text-uppercase">Posisi / Role</label>
                            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                                <option value="">Pilih Posisi</option>
                                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>KASIR (Operational)</option>
                                <option value="packing" {{ old('role') == 'packing' ? 'selected' : '' }}>PACKING (Warehouse)</option>
                                <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>SUPERVISOR (Control)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ADMIN (Full Access)</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>MANAGER</option>
                            </select>
                            @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold smaller text-muted text-uppercase">Skills / Keahlian</label>
                            <textarea name="skills" class="form-control @error('skills') is-invalid @enderror" rows="2" placeholder="Contoh: Racik Parfum, Packing Cepat, Marketing">{{ old('skills') }}</textarea>
                            @error('skills') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <hr class="my-3 text-faint">
                        
                        <div class="bg-light p-3 rounded mb-4">
                            <h6 class="font-weight-bold text-dark smaller text-uppercase mb-3">
                                <i class="fas fa-home mr-1 text-primary-apms"></i> Informasi Tempat Tinggal
                            </h6>
                            <div class="custom-control custom-switch mb-3">
                                <input type="checkbox" name="is_staying_in_mess" class="custom-control-input" id="messSwitch" value="1" {{ old('is_staying_in_mess') ? 'checked' : '' }} onclick="toggleAddress(this.checked)">
                                <label class="custom-control-label font-weight-bold text-dark" for="messSwitch">Tinggal di Mes / Kos Karyawan?</label>
                            </div>
                            
                            <div id="addressGroup" class="{{ old('is_staying_in_mess') ? 'd-none' : '' }}">
                                <label class="font-weight-bold smaller text-muted text-uppercase">Alamat Tinggal Saat Ini</label>
                                <textarea name="living_address" class="form-control @error('living_address') is-invalid @enderror" rows="2" placeholder="Masukkan alamat lengkap rumah/tempat tinggal sekarang">{{ old('living_address') }}</textarea>
                                <small class="text-muted italic">Wajib jika tidak tinggal di Mes.</small>
                                @error('living_address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Password Initial</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold smaller text-muted text-uppercase">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                            <a href="{{ route('employees.index') }}" class="btn btn-light px-4">
                                <i class="fas fa-arrow-left mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary-apms px-5 shadow-sm">
                                <i class="fas fa-save mr-1"></i> SIMPAN KARYAWAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function toggleAddress(isMess) {
        const addressGroup = document.getElementById('addressGroup');
        if (isMess) {
            addressGroup.classList.add('d-none');
        } else {
            addressGroup.classList.remove('d-none');
        }
    }
</script>
@endpush
@endsection
