@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Edit Produk
                    </h3>
                </div>
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <!-- Basic Information -->
                                <div class="card card-outline card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Informasi Dasar</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama Produk *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                            @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="product_category_id">Kategori *</label>
                                                    <select class="form-control select2 @error('product_category_id') is-invalid @enderror" 
                                                            id="product_category_id" name="product_category_id" required>
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" 
                                                                {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error('product_category_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="brand">Brand</label>
                                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                                           id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
                                                    @error('brand')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="size">Ukuran *</label>
                                                    <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                                           id="size" name="size" value="{{ old('size', $product->size) }}" required>
                                                    @error('size')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="unit">Satuan *</label>
                                                    <select class="form-control @error('unit') is-invalid @enderror" 
                                                            id="unit" name="unit" required>
                                                        <option value="">Pilih Satuan</option>
                                                        <option value="ml" {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>ML</option>
                                                        <option value="gr" {{ old('unit', $product->unit) == 'gr' ? 'selected' : '' }}>Gram</option>
                                                        <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                                        <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                                                    </select>
                                                    @error('unit')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Stock Information -->
                                <div class="card card-outline card-warning mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Informasi Stok</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="initial_stock">Stok Awal</label>
                                                    <input type="number" class="form-control" 
                                                           id="initial_stock" name="initial_stock" 
                                                           value="{{ old('initial_stock', $product->initial_stock) }}" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="minimum_stock">Minimal Stok</label>
                                                    <input type="number" class="form-control" 
                                                           id="minimum_stock" name="minimum_stock" 
                                                           value="{{ old('minimum_stock', 10) }}" min="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <!-- Pricing Information -->
                                <div class="card card-outline card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Informasi Harga</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="purchase_price">Harga Beli (Cost) *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control @error('purchase_price') is-invalid @enderror" 
                                                       id="purchase_price" name="purchase_price" 
                                                       value="{{ old('purchase_price', $product->purchase_price) }}" 
                                                       step="0.01" min="0" required>
                                            </div>
                                            @error('purchase_price')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="selling_price">Harga Jual (Retail) *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control @error('selling_price') is-invalid @enderror" 
                                                       id="selling_price" name="selling_price" 
                                                       value="{{ old('selling_price', $product->selling_price) }}" 
                                                       step="0.01" min="0" required>
                                            </div>
                                            @error('selling_price')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="wholesale_price">Harga Grosir (Wholesale)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" 
                                                       id="wholesale_price" name="wholesale_price" 
                                                       value="{{ old('wholesale_price', $product->wholesale_price) }}" 
                                                       step="0.01" min="0">
                                            </div>
                                            @error('wholesale_price')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <div class="info-box bg-light">
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Markup Keuntungan</span>
                                                        <span class="info-box-number text-success" id="marginInfo">
                                                            @if($product->purchase_price > 0)
                                                                {{ round((($product->selling_price - $product->purchase_price) / $product->purchase_price) * 100) }}%
                                                            @else
                                                                0%
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="card card-outline card-info mt-3">
                                    <div class="card-header">
                                        <h3 class="card-title">Foto Produk</h3>
                                    </div>
                                    <div class="card-body image-upload-container">
                                        <div class="image-preview" id="imagePreview">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="Preview">
                                            @else
                                                <span class="image-preview-default">
                                                    <i class="fas fa-image fa-3x"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="custom-file mt-3">
                                            <input type="file" class="custom-file-input" id="image" name="image" 
                                                   accept="image/*" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="image">
                                                @if($product->image)
                                                    Ubah Foto
                                                @else
                                                    Pilih Foto
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-default">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.image-upload-container {
    text-align: center;
}
.image-preview {
    width: 200px;
    height: 200px;
    border: 2px dashed #ddd;
    border-radius: 10px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview-default {
    color: #999;
}
.card-outline {
    border-top: 3px solid;
}
</style>
@endpush

@push('scripts')
<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid">`;
        }
        
        reader.readAsDataURL(input.files[0]);
        
        const fileName = input.files[0].name;
        input.nextElementSibling.innerText = fileName;
    }
}

$(function() {
    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    // Update margin calculation
    $('#purchase_price').on('change', function() {
        calculateMargin();
    });
    
    $('#selling_price').on('change', function() {
        calculateMargin();
    });
});

function calculateMargin() {
    const purchasePrice = parseFloat($('#purchase_price').val()) || 0;
    const sellingPrice = parseFloat($('#selling_price').val()) || 0;
    
    if (purchasePrice > 0) {
        const margin = ((sellingPrice - purchasePrice) / purchasePrice) * 100;
        $('#marginInfo').text(Math.round(margin) + '%');
    }
}
</script>
@endpush
