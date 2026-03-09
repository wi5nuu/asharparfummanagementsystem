@extends('layouts.app')

@section('title', 'Pelanggan Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Form Pelanggan Baru</h3>
                </div>
                <form action="{{ route('customers.store') }}" method="POST" id="customerForm">
                    @csrf
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Pelanggan *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Nomor Telepon *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="type">Tipe Pelanggan *</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">Pilih Tipe</option>
                                <option value="retail" {{ old('type') == 'retail' ? 'selected' : '' }}>Retail</option>
                                <option value="wholesale" {{ old('type') == 'wholesale' ? 'selected' : '' }}>Grosir</option>
                                <option value="vip" {{ old('type') == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                            @error('type')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#customerForm').submit(function(e) {
        // Basic validation
        if (!$('#name').val() || !$('#phone').val() || !$('#type').val()) {
            e.preventDefault();
            Swal.fire('Error', 'Harap isi semua field yang diperlukan', 'error');
            return false;
        }
    });
});
</script>
@endpush
