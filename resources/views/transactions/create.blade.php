@extends('layouts.app')

@section('title', 'Kasir - APMS')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Left Column: Product Selection -->
        <div class="col-md-8">
            <!-- Product Categories -->
            <div class="card card-apms mb-3">
                <div class="card-header">
                    <h3 class="card-title">Kategori Produk</h3>
                </div>
                <div class="card-body p-2">
                    <div class="category-scroll-wrapper">
                        <div class="d-flex flex-nowrap" style="overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 10px;">
                            <div class="mr-2">
                                <button class="btn btn-secondary shadow-sm" id="showAllProducts" style="min-width: 80px;">
                                    Semua
                                </button>
                            </div>
                            @foreach($categories as $category)
                            <div class="mr-2">
                                <button class="btn btn-category shadow-sm" 
                                        data-category="{{ $category->id }}"
                                        style="background-color: {{ $category->color }}; color: white; min-width: 100px;">
                                    {{ $category->name }}
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Grid -->
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Daftar Produk</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" id="productSearch" class="form-control" 
                                   placeholder="Cari produk atau scan barcode...">
                            <div class="input-group-append">
                                <button class="btn btn-primary-apms">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2 m-0 product-grid-container" style="max-height: 65vh; overflow-y: auto;">
                    <div class="row m-0" id="productGrid">
                        @foreach($products as $product)
                        @php
                            $inventory = $product->inventory;
                            $currentStock = $inventory ? $inventory->current_stock : 0;
                            $disabled = $currentStock == 0;
                        @endphp
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3 product-item" 
                             data-id="{{ $product->id }}"
                             data-category="{{ $product->category_id }}"
                             data-name="{{ $product->name }}"
                             data-price="{{ $product->selling_price }}"
                             data-wholesale="{{ $product->wholesale_price }}"
                             data-stock="{{ $currentStock }}"
                             data-barcode="{{ $product->barcode }}">
                            <div class="card product-card {{ $disabled ? 'bg-light disabled-product' : '' }}"
                                 style="user-select: none; cursor: pointer;">
                                <div class="card-body text-center p-2">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}"
                                         class="img-fluid mb-1" style="height: 60px; object-fit: cover;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center mb-1" 
                                         style="height: 60px;">
                                        <i class="fas fa-wine-bottle fa-2x text-muted"></i>
                                    </div>
                                    @endif
                                    
                                    <h6 class="mb-1 product-name">{{ $product->name }}</h6>
                                    <div class="product-meta">
                                        <small class="text-muted">{{ $product->size }}</small>
                                        <div class="mt-1">
                                            <strong class="text-primary product-price">
                                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                            </strong>
                                        </div>
                                        <div class="mt-1">
                                            @if($currentStock == 0)
                                                <span class="badge badge-danger">Habis</span>
                                            @elseif($currentStock < 10)
                                                <span class="badge badge-warning">{{ $currentStock }}</span>
                                            @else
                                                <span class="badge badge-success">Tersedia</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Cart & Payment -->
        <div class="col-md-4">
            <!-- Customer Info -->
            <div class="card card-apms mb-3">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pelanggan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipe Pelanggan</label>
                                <select class="form-control" id="customerType">
                                    <option value="retail">Retail</option>
                                    <option value="wholesale">Wholesale</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Pelanggan</label>
                                <select class="form-control select2" id="customerSelect">
                                    <option value="">Umum</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" 
                                            data-phone="{{ $customer->phone }}"
                                            data-email="{{ $customer->email }}"
                                            data-points="{{ $customer->points }}">
                                        {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="customer-details" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info p-2">
                                    <small id="customerInfoAlert">
                                        <i class="fas fa-phone mr-1"></i> <span id="customerPhone"></span><br>
                                        <i class="fas fa-envelope mr-1"></i> <span id="customerEmail"></span><br>
                                        <i class="fas fa-star text-warning mr-1"></i> Poin Member: <strong id="customerPoints">0</strong>
                                        <div id="aromaPrefContainer" style="display:none;" class="mt-1 pt-1 border-top">
                                            <i class="fas fa-magic text-purple mr-1"></i> <strong>Selera Aroma:</strong><br>
                                            <span id="customerAroma" class="text-dark italic"></span>
                                        </div>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-primary btn-block" id="newCustomerBtn">
                                <i class="fas fa-user-plus"></i> Pelanggan Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cart -->
            <div class="card card-apms mb-3">
                <div class="card-header">
                    <h3 class="card-title">Keranjang Belanja</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary" id="cartCount">0 item</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0" id="cartTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Produk & Ukuran</th>
                                    <th width="20%">Qty</th>
                                    <th width="25%">Harga</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="cartItems">
                                <!-- Cart items will be added here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Totals & Payment -->
            <div class="card card-apms">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <strong>Subtotal</strong>
                        </div>
                        <div class="col-6 text-right">
                            <span id="subtotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <button type="button" class="btn btn-outline-success btn-xs btn-block" onclick="openBonusModal(1, true)">
                            <i class="fas fa-plus"></i> Tambah Bonus Manual (20ml)
                        </button>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-6">
                            <strong>Diskon</strong>
                        </div>
                        <div class="col-6 text-right">
                            <div class="input-group input-group-sm">
                                <input type="number" id="discount" class="form-control text-right" 
                                       value="0" min="0" style="width: 80px;">
                                <div class="input-group-append">
                                    <select class="form-control form-control-sm" id="discountType" 
                                            style="width: 70px;">
                                        <option value="fixed">Rp</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="taxEnabled" checked>
                                <label class="custom-control-label font-weight-bold" for="taxEnabled">PPN (10%)</label>
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <span id="tax">Rp 0</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Total</h5>
                        </div>
                        <div class="col-6 text-right">
                            <h4 id="totalAmount" class="text-primary">Rp 0</h4>
                        </div>
                    </div>

                    <!-- Receipt Visibility -->
                    <div class="form-group">
                        <label><i class="fas fa-eye"></i> Tampilan Struk</label>
                        <select id="receiptVisibility" class="form-control form-control-sm">
                            <option value="public" selected>Pelanggan & Admin (Lengkap)</option>
                            <option value="private">Admin Saja (Khusus Internal)</option>
                        </select>
                        <small class="text-muted">Tentukan apakah detail bonus muncul di struk pelanggan.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="input-group btn-group-toggle w-100 mb-2" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="payment_method" value="cash" checked onchange="handlePaymentMethodChange()"> Cash
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="payment_method" value="qris" onchange="handlePaymentMethodChange()"> QRIS
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="payment_method" value="ewallet" onchange="handlePaymentMethodChange()"> E-Wallet
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="payment_method" value="transfer" onchange="handlePaymentMethodChange()"> Transfer
                            </label>
                        </div>
                        
                        <!-- E-Wallet Type Select -->
                        <div id="ewalletTypeContainer" style="display: none;">
                            <select id="ewalletType" class="form-control mb-2">
                                <option value="">-- Pilih E-Wallet --</option>
                                <option value="DANA">DANA</option>
                                <option value="OVO">OVO</option>
                                <option value="GOPAY">GoPay</option>
                                <option value="SHOPEEPAY">ShopeePay</option>
                                <option value="LINKAJA">LinkAja</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Amount Paid -->
                    <div class="form-group">
                        <label>Jumlah Bayar</label>
                        <input type="number" id="paidAmount" class="form-control form-control-lg" 
                               placeholder="0" min="0">
                    </div>
                    
                    <!-- Change -->
                    <div class="form-group">
                        <label>Kembalian</label>
                        <input type="text" id="changeAmount" class="form-control form-control-lg" 
                               readonly style="background-color: #f8f9fa; font-weight: bold;">
                    </div>
                    
                    <!-- Notes -->
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea id="transactionNotes" class="form-control" rows="2" 
                                  placeholder="Tambahkan catatan transaksi..."></textarea>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-danger btn-lg btn-block" onclick="clearCart()">
                                <i class="fas fa-trash"></i> Batal
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success btn-lg btn-block" onclick="processPayment()">
                                <i class="fas fa-check"></i> Bayar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Amounts -->
                    <div class="row mt-2">
                        @php
                            $quickAmounts = [50000, 100000, 150000, 200000, 250000, 300000];
                        @endphp
                        @foreach($quickAmounts as $amount)
                        <div class="col-4 mb-1">
                            <button class="btn btn-outline-secondary btn-sm btn-block quick-amount-btn" 
                                    data-amount="{{ $amount }}">
                                {{ number_format($amount, 0, ',', '.') }}
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Customer Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pelanggan Baru</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="newCustomerForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe Pelanggan *</label>
                        <select class="form-control" name="type" required>
                            <option value="retail">Retail</option>
                            <option value="wholesale">Wholesale</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" class="form-control" name="phone">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="address" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-apms">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bonus aroma Modal -->
<div class="modal fade" id="bonusAromaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-gift"></i> Pilih Aroma Bonus (Free 20ml)</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info py-2" id="bonusInstruction">
                    Silakan pilih aroma untuk bonus item. Sisa: <strong id="remainingBonus">0</strong>
                </div>
                <div class="input-group mb-3">
                    <input type="text" id="bonusSearch" class="form-control" placeholder="Cari nama parfum...">
                </div>
                <div id="bonusProductList" class="row" style="max-height: 400px; overflow-y: auto;">
                    <!-- Bonus items will be listed here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    cursor: pointer;
    transition: all 0.2s ease;
    height: 100%;
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.product-card.disabled-product {
    cursor: not-allowed;
    opacity: 0.6;
}
.product-name {
    font-size: 0.8rem;
    height: 2.2rem;
    line-height: 1.1;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.product-price {
    font-size: 0.85rem;
}
.product-img-mini {
    flex-shrink: 0;
}
#cartTable {
    font-size: 0.85rem;
}
#cartTable th, #cartTable td {
    padding: 0.5rem 0.25rem;
}
.category-scroll-wrapper::-webkit-scrollbar {
    height: 4px;
}
.category-scroll-wrapper::-webkit-scrollbar-thumb {
    background: #FF6B35;
    border-radius: 10px;
}
@media (max-width: 576px) {
    .product-item {
        padding-left: 5px;
        padding-right: 5px;
    }
    .card-title {
        font-size: 1rem;
    }
    .btn-lg {
        font-size: 1rem;
        padding: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
let cart = [];
let customerType = 'retail';
const allProducts = @json($products);
let bonusQueue = 0;

$(document).ready(function() {
    // Setup AJAX CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize Select2
    $('#customerSelect, #receiptVisibility, #customerType, select[name="type"]').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Load cart from localStorage
    loadCart();
    
    // Product search
    $('#productSearch').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.product-item').each(function() {
            const name = $(this).data('name').toLowerCase();
            const barcode = $(this).data('barcode');
            const matches = name.includes(searchTerm) || barcode.includes(searchTerm);
            $(this).toggle(matches);
        });
    });
    
    // Filter by category
    $('.btn-category').click(function() {
        $('.btn-category').removeClass('active');
        $(this).addClass('active');
        
        const categoryId = $(this).data('category');
        $('.product-item').each(function() {
            const itemCategory = $(this).data('category');
            $(this).toggle(categoryId === itemCategory);
        });
    });
    
    // Show all products
    $('#showAllProducts').click(function() {
        $('.btn-category').removeClass('active');
        $('.product-item').show();
    });
    
    // Customer type change
    $('#customerType').change(function() {
        customerType = $(this).val();
        updateCartPrices();
    });
    
    // Customer select change
    $('#customerSelect').change(function() {
        const customerId = $(this).val();
        if (customerId) {
            $.get(`/transactions/customer-info/${customerId}`, function(data) {
                $('#customerPhone').text(data.phone || '-');
                $('#customerEmail').text(data.email || '-');
                $('#customerPoints').text(data.points || 0);
                
                if (data.aroma_preferences) {
                    $('#customerAroma').text(data.aroma_preferences);
                    $('#aromaPrefContainer').show();
                } else {
                    $('#aromaPrefContainer').hide();
                }
                $('.customer-details').show();
            });
        } else {
            $('.customer-details').hide();
            $('#aromaPrefContainer').hide();
        }
    });
    
    // New customer button
    $('#newCustomerBtn').click(function() {
        $('#newCustomerModal').modal('show');
    });
    
    // New customer form
    $('#newCustomerForm').submit(function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        
        $.ajax({
            url: '/customers',
            method: 'POST',
            data: formData,
            success: function(response) {
                // Add new customer to select
                const option = new Option(response.name, response.id, false, true);
                $('#customerSelect').append(option).trigger('change');
                
                $('#newCustomerModal').modal('hide');
                $('#newCustomerForm')[0].reset();
                
                Swal.fire('Berhasil', 'Pelanggan berhasil ditambahkan', 'success');
            },
            error: function(xhr) {
                let message = 'Terjadi kesalahan';
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                Swal.fire('Gagal', message, 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan');
            }
        });
    });
    
    // Product selection via data attributes
    $(document).on('click', '.product-item .product-card:not(.disabled-product)', function() {
        const productId = $(this).closest('.product-item').data('id');
        addToCart(productId);
    });

    // Quick amount selection
    $(document).on('click', '.quick-amount-btn', function() {
        const amount = $(this).data('amount');
        setPaidAmount(amount);
    });

    // Barcode scanner simulation
    $(document).keypress(function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        
        if (e.which === 13) { // Enter key
            const barcode = $('#productSearch').val();
            if (barcode.length >= 8) {
                scanBarcode(barcode);
                $('#productSearch').val('');
            }
        }
    });

    $('#bonusSearch').on('input', renderBonusProducts);
}); // end $(document).ready

function scanBarcode(barcode) {
    const product = $('.product-item').filter(function() {
        return $(this).data('barcode') === barcode;
    }).first();
    
    if (product.length) {
        const productId = product.data('id');
        addToCart(productId);
    } else {
        Swal.fire('Produk Tidak Ditemukan', 'Barcode tidak valid', 'warning');
    }
}

function addToCart(productId) {
    const product = allProducts.find(p => p.id === productId);
    const stock = product.inventory ? product.inventory.current_stock : 0;
    
    if (stock === 0) {
        Swal.fire('Stok Habis', 'Produk ini tidak tersedia', 'warning');
        return;
    }
    
    const categoryId = parseInt(product.category_id);
    const isPremium = categoryId === 1 || categoryId === 5;
    
    // Check if already in cart
    const existingIndex = cart.findIndex(item => item.id === productId && item.size === '30ml');
    
    if (existingIndex >= 0) {
        if (cart[existingIndex].quantity >= stock) {
            Swal.fire('Stok Tidak Cukup', 'Jumlah melebihi stok tersedia', 'warning');
            return;
        }
        cart[existingIndex].quantity++;
    } else {
        const basePrice = customerType === 'wholesale' 
            ? parseFloat(product.wholesale_price) || parseFloat(product.selling_price)
            : parseFloat(product.selling_price);
        
        cart.push({
            id: productId,
            name: product.name,
            price: basePrice,
            base_price: basePrice,
            quantity: 1,
            stock: stock,
            size: '30ml',
            is_premium: isPremium
        });
    }

    // Auto-add Bonus for Premium
    if (isPremium) {
        // Rules: 30ml -> 1, 50ml -> 2, 100ml -> 1
        // Default size is 30ml
        openBonusModal(1);
    }
    
    saveCart();
    updateCartDisplay();
    autoFillPayment();
}

function openBonusModal(count, isManual = false) {
    bonusQueue = count;
    $('#remainingBonus').text(bonusQueue);
    $('#bonusSearch').val('');
    renderBonusProducts();
    $('#bonusAromaModal').modal('show');
}

function renderBonusProducts() {
    const searchTerm = $('#bonusSearch').val().toLowerCase();
    const container = $('#bonusProductList');
    container.empty();
    
    const filtered = allProducts.filter(p => p.name.toLowerCase().includes(searchTerm));
    
    filtered.slice(0, 24).forEach(product => {
        container.append(`
            <div class="col-md-4 mb-2">
                <button class="btn btn-outline-success btn-sm btn-block text-left p-2" 
                        onclick="addSelectedBonus(${product.id})">
                    <div class="font-weight-bold" style="font-size: 0.75rem;">${product.name}</div>
                    <small>20ml - Rp 0</small>
                </button>
            </div>
        `);
    });
}

function addSelectedBonus(productId) {
    const product = allProducts.find(p => p.id === productId);
    const bonusId = product.id; 
    
    // Check if bonus entry for this aroma already exists
    const existingIndex = cart.findIndex(item => item.id === bonusId && item.is_bonus);
    
    if (existingIndex >= 0) {
        cart[existingIndex].quantity++;
    } else {
        cart.push({
            id: bonusId,
            name: product.name + ' (Bonus 20ml)',
            price: 0,
            base_price: 0,
            real_value: 43333,
            quantity: 1,
            stock: 999,
            size: '20ml',
            is_bonus: true
        });
    }
    
    bonusQueue--;
    $('#remainingBonus').text(bonusQueue);
    
    if (bonusQueue <= 0) {
        $('#bonusAromaModal').modal('hide');
    }
    
    saveCart();
    updateCartDisplay();
    calculateTotals();
    autoFillPayment();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
    updateCartDisplay();
}

function updateBonus(index, value) {
    cart[index].bonus_quantity = Math.max(0, parseInt(value) || 0);
    saveCart();
    // Don't re-render to avoid losing focus; just update the value in cart
}

function updateCartDisplay() {
    const cartItems = $('#cartItems');
    cartItems.empty();
    
    if (cart.length === 0) {
        cartItems.html(`
            <tr>
                <td colspan="5" class="text-center text-muted py-3">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                    Keranjang kosong
                </td>
            </tr>
        `);
        $('#cartCount').text('0 item');
        return;
    }
    
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        const isPremium = item.is_premium || false;
        const row = `
            <tr class="${isPremium ? 'table-warning' : ''}">
                <td>${index + 1}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="mr-1">
                            <i class="fas ${item.is_bonus ? 'fa-gift text-success' : 'fa-wine-bottle'}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <small class="d-block font-weight-bold" style="font-size: 0.85rem; line-height: 1.2;">${item.name}</small>
                            ${item.is_bonus ? '<span class="badge badge-success mt-1" style="font-size:0.65rem;">BONUS WAJIB 20ML</span>' : `
                            <div class="mt-1">
                                <select class="form-control form-control-sm p-1" style="font-size: 0.75rem; height: 28px;" 
                                        onchange="updateSize(${index}, this.value)">
                                    <option value="30ml" ${item.size === '30ml' ? 'selected' : ''}>30ml</option>
                                    <option value="50ml" ${item.size === '50ml' ? 'selected' : ''}>50ml</option>
                                    <option value="100ml" ${item.size === '100ml' ? 'selected' : ''}>100ml</option>
                                </select>
                            </div>
                            `}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm flex-nowrap" style="width: 100px;">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                        </div>
                        <input type="text" class="form-control text-center px-1" 
                               value="${item.quantity}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" 
                                    onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="text-right">
                        <div style="font-size: 0.9rem;">Rp ${item.price.toLocaleString('id-ID')}</div>
                        <small class="text-muted" style="font-size: 0.75rem;">Total: Rp ${itemTotal.toLocaleString('id-ID')}</small>
                    </div>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm" 
                            onclick="removeFromCart(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        cartItems.append(row);
    });
    
    $('#cartCount').text(`${cart.length} item${cart.length > 1 ? 's' : ''}`);
    $('#subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
    calculateTotals();
}

function updateQuantity(index, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (newQuantity < 1) newQuantity = 1;
    if (newQuantity > cart[index].stock) {
        Swal.fire('Stok Tidak Cukup', 'Jumlah melebihi stok tersedia', 'warning');
        newQuantity = cart[index].stock;
    }
    
    cart[index].quantity = newQuantity;
    saveCart();
    updateCartDisplay();
}

function updateCartPrices() {
    cart.forEach(item => {
        // Update price based on customer type
        const product = $(`.product-item[data-id="${item.id}"]`);
        item.price = customerType === 'wholesale' 
            ? parseFloat(product.data('wholesale')) || parseFloat(product.data('price'))
            : parseFloat(product.data('price'));
    });
    saveCart();
    updateCartDisplay();
}

function handlePaymentMethodChange() {
    const method = $('input[name="payment_method"]:checked').val();
    if (method === 'ewallet') {
        $('#ewalletTypeContainer').slideDown();
    } else {
        $('#ewalletTypeContainer').slideUp();
        $('#ewalletType').val('');
    }
    autoFillPayment();
}

$(document).on('change', '#taxEnabled', function() {
    calculateTotals();
});

function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountInput = $('#discount').val();
    const discountType = $('#discountType').val();
    
    let discountValue = 0;
    if (discountType === 'percent') {
        discountValue = subtotal * (parseFloat(discountInput) || 0) / 100;
    } else {
        discountValue = parseFloat(discountInput) || 0;
    }
    
    const taxEnabled = $('#taxEnabled').is(':checked');
    const tax = taxEnabled ? Math.round((subtotal - discountValue) * 0.1) : 0; // 10% PPN
    const total = Math.max(0, subtotal - discountValue + tax);
    
    $('#tax').text('Rp ' + tax.toLocaleString('id-ID'));
    $('#totalAmount').text('Rp ' + total.toLocaleString('id-ID'));
    
    // Calculate change
    const paid = parseFloat($('#paidAmount').val()) || 0;
    const change = paid - total;
    
    if (change >= 0) {
        $('#changeAmount').val('Rp ' + change.toLocaleString('id-ID'));
        $('#changeAmount').removeClass('text-danger').addClass('text-success');
    } else {
        $('#changeAmount').val('Kurang: Rp ' + Math.abs(change).toLocaleString('id-ID'));
        $('#changeAmount').removeClass('text-success').addClass('text-danger');
    }
}

function setPaidAmount(amount) {
    $('#paidAmount').val(amount).trigger('input');
}

function updateSize(index, newSize) {
    const item = cart[index];
    const oldSize = item.size;
    item.size = newSize;
    
    // Update price based on size (Image ratios: 1x, 2x, 4x)
    // Refined based on user image:
    // Premium: 63k -> 125k -> 250k
    // Sedang: 50k -> 100k -> 200k
    // Standar: 35k -> 70k -> 140k
    if (newSize === '30ml') {
        item.price = item.base_price;
    } else if (newSize === '50ml') {
        if (item.is_premium) {
            item.price = 125000;
        } else {
            item.price = item.base_price * 2.0; 
        }
    } else if (newSize === '100ml') {
        if (item.is_premium) {
            item.price = 250000;
        } else {
            item.price = item.base_price * 4.0;
        }
    }
    
    // Scaling Bonus Rules: 30ml -> 1, 50ml -> 2, 100ml -> 1
    if (item.is_premium) {
        if (newSize === '50ml' && (oldSize === '30ml' || oldSize === '100ml')) {
            openBonusModal(1); // Add 1 more if upgrading to 50ml or switching from 100ml
        } else if (newSize === '30ml' && oldSize === '100ml') {
            // Already had 1, still 1. No action needed or maybe confirm if they want another?
            // Usually 100ml gives 1, 30ml gives 1.
        }
    }
    
    saveCart();
    updateCartDisplay();
    calculateTotals();
    autoFillPayment();
}

function autoFillPayment() {
    const totalText = $('#totalAmount').text().replace(/[^0-9]/g, '');
    const total = Math.round(parseFloat(totalText) || 0);
    
    const paymentMethod = $('input[name="payment_method"]:checked').val();
    
    // Auto fill for cash/qris/transfer if currently empty or just updated total
    $('#paidAmount').val(total).trigger('input');
}

function processPayment() {
    if (cart.length === 0) {
        Swal.fire('Keranjang Kosong', 'Tambahkan produk terlebih dahulu', 'warning');
        return;
    }
    
    const total = Math.round(parseFloat($('#totalAmount').text().replace(/[^0-9]/g, '')) || 0);
    const paid = parseFloat($('#paidAmount').val()) || 0;
    
    // Check if debt (with small tolerance for floats)
    if (paid < total - 0.9) {
        const customerId = $('#customerSelect').val();
        if (!customerId) {
            Swal.fire('Pelanggan Umum Tidak Bisa Hutang', 'Pilih pelanggan terdaftar untuk mencatat piutang / Kas Bon.', 'warning');
            return;
        }
        
        const debtAmount = total - paid;
        const confirmMsg = paid > 0 
            ? `Pembayaran kurang Rp ${debtAmount.toLocaleString('id-ID')}. Catat sebagai Cicilan/Hutang?`
            : `Total Rp ${total.toLocaleString('id-ID')} akan dicatat sebagai HUTANG penuh. Lanjutkan?`;

        Swal.fire({
            title: 'Konfirmasi Kas Bon',
            text: confirmMsg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Catat Hutang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitTransaction(total, paid);
            }
        });
        return;
    }
    
    submitTransaction(total, paid);
}

function submitTransaction(total, paid) {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discountInput = parseFloat($('#discount').val()) || 0;
        const discountType = $('#discountType').val();
        let discountAmount = 0;
        let discountPercent = 0;

        if (discountType === 'percent') {
            discountPercent = discountInput;
            discountAmount = (subtotal * discountPercent) / 100;
        } else {
            discountAmount = discountInput;
            discountPercent = 0;
        }

        const transactionData = {
            customer_id: $('#customerSelect').val(),
            customer_type: $('#customerType').val(),
            receipt_visibility: $('#receiptVisibility').val(),
            items: cart.map(item => ({
                product_id:      item.id,
                quantity:        item.quantity,
                size:            item.size || null,
                price:           item.price,
                bonus_quantity:  0,
                bonus_note:      item.is_bonus ? 'Bonus Wajib' : null,
            })),
            discount_amount: discountAmount,
            discount_type: discountType,
            discount_percent: discountPercent,
            tax_enabled: $('#taxEnabled').is(':checked'),
            payment_method: $('input[name="payment_method"]:checked').val(),
            ewallet_type: $('#ewalletType').val() || null,
            paid_amount: paid,
            notes: $('#transactionNotes').val(),
            _token: '{{ csrf_token() }}'
        };
    
    // Send to server
    $.ajax({
        url: '{{ route("transactions.store") }}',
        method: 'POST',
        data: JSON.stringify(transactionData),
        contentType: 'application/json',
        success: function(response) {
            // Print receipt
            if (response.transaction_id) {
                printReceipt(response.transaction_id);
            }
            
            const change = Math.max(0, paid - total);
            const isDebt = paid < total;
            
            // Show success message
            Swal.fire({
                title: isDebt ? 'Piutang Dicatat!' : 'Transaksi Berhasil!',
                html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>Invoice: ${response.invoice_number}</h5>
                        <p>Total: Rp ${total.toLocaleString('id-ID')}</p>
                        ${isDebt ? `<p class="text-danger font-weight-bold">Sisa Hutang: Rp ${(total - paid).toLocaleString('id-ID')}</p>` : `<p>Kembalian: Rp ${change.toLocaleString('id-ID')}</p>`}
                        <hr>
                        <a href="https://wa.me/?text=${response.whatsapp_message}" target="_blank" class="btn btn-success btn-lg btn-block mb-3">
                            <i class="fab fa-whatsapp mr-1"></i> Kirim Invoice via WA
                        </a>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-print mr-1"></i> Cetak Ulang',
                cancelButtonText: 'Transaksi Baru',
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#2D3047'
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt(response.transaction_id);
                }
                clearCart();
            });
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses transaksi', 'error');
        }
    });
}

function printReceipt(transactionId) {
    const printWindow = window.open(`/transactions/${transactionId}/receipt`, '_blank');
    setTimeout(() => {
        printWindow.print();
    }, 500);
}

function clearCart() {
    cart = [];
    saveCart();
    updateCartDisplay();
    $('#paidAmount').val('');
    $('#discount').val(0);
    $('#transactionNotes').val('');
}

function saveCart() {
    localStorage.setItem('apms_cart', JSON.stringify(cart));
}

function loadCart() {
    const savedCart = localStorage.getItem('apms_cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCartDisplay();
    }
}
</script>
@endpush