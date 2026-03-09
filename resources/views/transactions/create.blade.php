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
                <div class="card-body">
                    <div class="row">
                        @foreach($categories as $category)
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-block btn-category" 
                                    data-category="{{ $category->id }}"
                                    style="background-color: {{ $category->color }}; color: white;">
                                {{ $category->name }}
                            </button>
                        </div>
                        @endforeach
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-block btn-secondary" id="showAllProducts">
                                Semua
                            </button>
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
                <div class="card-body">
                    <div class="row" id="productGrid">
                        @foreach($products as $product)
                        @php
                            $inventory = $product->inventories->first();
                            $currentStock = $inventory ? $inventory->current_stock : 0;
                            $disabled = $currentStock == 0;
                        @endphp
                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3 product-item" 
                             data-id="{{ $product->id }}"
                             data-category="{{ $product->category_id }}"
                             data-name="{{ $product->name }}"
                             data-price="{{ $product->selling_price }}"
                             data-wholesale="{{ $product->wholesale_price }}"
                             data-stock="{{ $currentStock }}"
                             data-barcode="{{ $product->barcode }}">
                            <div class="card product-card {{ $disabled ? 'bg-light' : '' }}"
                                 onclick="{{ !$disabled ? 'addToCart(' . $product->id . ')' : '' }}">
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
                                            data-email="{{ $customer->email }}">
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
                                    <small>
                                        <i class="fas fa-phone"></i> <span id="customerPhone"></span><br>
                                        <i class="fas fa-envelope"></i> <span id="customerEmail"></span>
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
                                <th>Produk</th>
                                <th width="15%">Qty</th>
                                <th width="20%">Harga</th>
                                <th width="10%">Bonus</th>
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
                                        <option value="amount">Rp</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-6">
                            <strong>PPN (10%)</strong>
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
                    
                    <!-- Payment Method -->
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="payment_method" value="cash" checked> Cash
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="payment_method" value="qris"> QRIS
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="payment_method" value="transfer"> Transfer
                            </label>
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
                            <button class="btn btn-outline-secondary btn-sm btn-block" 
                                    onclick="setPaidAmount({{ $amount }})">
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
.product-card.bg-light {
    cursor: not-allowed;
    opacity: 0.6;
}
.product-name {
    font-size: 0.85rem;
    height: 2.5rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.product-price {
    font-size: 0.9rem;
}
#cartTable tbody tr {
    border-bottom: 1px solid #dee2e6;
}
#cartTable tbody tr:last-child {
    border-bottom: none;
}
.btn-category.active {
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.5);
}
</style>
@endpush

@push('scripts')
<script>
let cart = [];
let customerType = 'retail';

$(function() {
    // Initialize Select2
    $('#customerSelect').select2({
        theme: 'bootstrap4'
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
        const selected = $(this).find('option:selected');
        const phone = selected.data('phone');
        const email = selected.data('email');
        
        if (phone || email) {
            $('#customerPhone').text(phone || '-');
            $('#customerEmail').text(email || '-');
            $('.customer-details').show();
        } else {
            $('.customer-details').hide();
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
        
        $.ajax({
            url: '/api/customers',
            method: 'POST',
            data: formData,
            success: function(response) {
                // Add new customer to select
                const option = new Option(response.name, response.id, false, true);
                $('#customerSelect').append(option).trigger('change');
                
                $('#newCustomerModal').modal('hide');
                $('#newCustomerForm')[0].reset();
                
                Swal.fire('Berhasil', 'Pelanggan berhasil ditambahkan', 'success');
            }
        });
    });
    
    // Calculate totals on input change
    $('#discount, #paidAmount').on('input', calculateTotals);
    $('#discountType').change(calculateTotals);
    
    // Barcode scanner simulation
    $(document).keypress(function(e) {
        if (e.which === 13) { // Enter key
            const barcode = $('#productSearch').val();
            if (barcode.length >= 8) {
                scanBarcode(barcode);
                $('#productSearch').val('');
            }
        }
    });
});

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
    const product = $(`.product-item[data-id="${productId}"]`);
    const stock = parseInt(product.data('stock'));
    
    if (stock === 0) {
        Swal.fire('Stok Habis', 'Produk ini tidak tersedia', 'warning');
        return;
    }
    
    // Check if already in cart
    const existingIndex = cart.findIndex(item => item.id === productId);
    
    if (existingIndex >= 0) {
        if (cart[existingIndex].quantity >= stock) {
            Swal.fire('Stok Tidak Cukup', 'Jumlah melebihi stok tersedia', 'warning');
            return;
        }
        cart[existingIndex].quantity++;
        saveCart();
        updateCartDisplay();
    } else {
        const price = customerType === 'wholesale' 
            ? parseFloat(product.data('wholesale')) || parseFloat(product.data('price'))
            : parseFloat(product.data('price'));
        
        // Detect if Premium by category button or data
        const categoryId = parseInt(product.data('category'));
        const isPremium = categoryId === 1; // category_id 1 = Premium
        
        cart.push({
            id: productId,
            name: product.data('name'),
            price: price,
            original_price: parseFloat(product.data('price')),
            quantity: 1,
            stock: stock,
            barcode: product.data('barcode'),
            is_premium: isPremium,
            bonus_quantity: 0,
        });
        
        saveCart();
        updateCartDisplay();
    }
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
                <td colspan="6" class="text-center text-muted py-3">
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
        const bonusQty = item.bonus_quantity || 0;
        
        const bonusCell = isPremium ? `
            <td>
                <div class="d-flex align-items-center">
                    <span class="badge badge-success mr-1" title="Bonus 20ml Sedang">🎁</span>
                    <input type="number" class="form-control form-control-sm text-center bonus-input" 
                           style="width:55px;" min="0" max="99"
                           value="${bonusQty}"
                           onchange="updateBonus(${index}, this.value)"
                           title="Jumlah bonus 20ml Sedang">
                </div>
            </td>
        ` : `<td><span class="text-muted">-</span></td>`;
        
        const row = `
            <tr class="${isPremium ? 'table-warning' : ''}">
                <td>${index + 1}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="mr-2">
                            <i class="fas fa-wine-bottle"></i>
                        </div>
                        <div>
                            <small class="d-block font-weight-bold">${item.name}</small>
                            ${isPremium ? '<span class="badge badge-warning" style="font-size:0.65rem;">⭐ Premium – Bonus 20ml</span>' : ''}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary" type="button" 
                                onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                        <input type="text" class="form-control text-center" 
                               value="${item.quantity}" readonly style="width: 40px;">
                        <button class="btn btn-outline-secondary" type="button" 
                                onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                    </div>
                </td>
                <td>
                    <div class="text-right">
                        <div>Rp ${item.price.toLocaleString('id-ID')}</div>
                        <small class="text-muted">Total: Rp ${itemTotal.toLocaleString('id-ID')}</small>
                    </div>
                </td>
                ${bonusCell}
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

function calculateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discountInput = $('#discount').val();
    const discountType = $('#discountType').val();
    
    let discount = 0;
    if (discountType === 'percent') {
        discount = subtotal * (parseFloat(discountInput) || 0) / 100;
    } else {
        discount = parseFloat(discountInput) || 0;
    }
    
    const tax = (subtotal - discount) * 0.1; // 10% PPN
    const total = Math.max(0, subttotal - discount + tax);
    
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

function processPayment() {
    if (cart.length === 0) {
        Swal.fire('Keranjang Kosong', 'Tambahkan produk terlebih dahulu', 'warning');
        return;
    }
    
    const total = parseFloat($('#totalAmount').text().replace(/[^0-9]/g, ''));
    const paid = parseFloat($('#paidAmount').val()) || 0;
    
    if (paid < total) {
        Swal.fire('Pembayaran Kurang', 'Jumlah pembayaran kurang dari total', 'warning');
        return;
    }
    
    // Collect transaction data
    const transactionData = {
        customer_id: $('#customerSelect').val(),
        customer_type: $('#customerType').val(),
        items: cart.map(item => ({
            product_id:      item.id,
            quantity:        item.quantity,
            price:           item.price,
            bonus_quantity:  item.bonus_quantity || 0,
            bonus_note:      item.is_premium && item.bonus_quantity > 0 
                                ? `Bonus 20ml Sedang x${item.bonus_quantity} untuk ${item.name}` 
                                : null,
        })),
        discount_amount: parseFloat($('#discount').val()) || 0,
        discount_type: $('#discountType').val(),
        payment_method: $('input[name="payment_method"]:checked').val(),
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
            printReceipt(response.transaction_id);
            
            // Show success message
            Swal.fire({
                title: 'Transaksi Berhasil!',
                html: `
                    <div class="text-center">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h5>Invoice: ${response.invoice_number}</h5>
                        <p>Total: Rp ${total.toLocaleString('id-ID')}</p>
                        <p>Kembalian: Rp ${response.change.toLocaleString('id-ID')}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Cetak Ulang',
                cancelButtonText: 'Transaksi Baru'
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt(response.transaction_id);
                }
                clearCart();
            });
        },
        error: function(xhr) {
            Swal.fire('Error', 'Terjadi kesalahan saat memproses transaksi', 'error');
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