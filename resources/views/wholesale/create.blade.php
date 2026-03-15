@extends('layouts.app')

@section('title', 'Buat Pesanan Grosir')

@section('content')
<div class="container-fluid">
    <form action="{{ route('wholesale.store') }}" method="POST" id="wholesaleForm">
        @csrf
        <div class="row">
            <!-- Order Details -->
            <div class="col-lg-8">
                <div class="card card-apms border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold text-primary-apms"><i class="fas fa-list mr-2"></i> Detail Isi Paket</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Nama Barang / Aroma</th>
                                        <th>Qty</th>
                                        <th>Volume (ml)</th>
                                        <th>Harga Satuan</th>
                                        <th style="width: 50px"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemRows">
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" name="items[0][product_name]" class="form-control" placeholder="Contoh: Aroma Baccarat" required>
                                            <input type="hidden" name="items[0][product_id]" value="">
                                        </td>
                                        <td><input type="number" name="items[0][quantity]" class="form-control qty-input" value="1" min="1" required></td>
                                        <td><input type="number" name="items[0][volume_ml]" class="form-control" placeholder="Misal: 100"></td>
                                        <td><input type="number" name="items[0][price]" class="form-control price-input" placeholder="Harga" required></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addItemRow()">
                            <i class="fas fa-plus mr-1"></i> Tambah Item Lagi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Package & Shipping Info -->
            <div class="col-lg-4">
                <div class="card card-apms border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 font-weight-bold text-primary-apms"><i class="fas fa-truck mr-2"></i> Info Paket & Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Target Nilai Paket (Rp) *</label>
                            <input type="number" name="package_target_amount" class="form-control form-control-lg text-primary font-weight-bold" placeholder="Misal: 10000000" required>
                            <small class="text-muted">Target nominal paket yang harus terpenuhi (Misal 10jt, 50jt).</small>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label>Nama Penerima *</label>
                            <input type="text" name="recipient_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>No. Telp Penerima *</label>
                            <input type="text" name="recipient_phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap *</label>
                            <textarea name="shipping_address" class="form-control" rows="3" required placeholder="Jalan, No Rumah, Kec, Kota..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Jasa Pengiriman / Kurir</label>
                            <input type="text" name="shipping_courier" class="form-control" placeholder="Misal: J&T, Sicepat, Indah Cargo">
                        </div>
                        <div class="form-group">
                            <label>Penanggung Jawab / Cara Antar</label>
                            <input type="text" name="delivery_handler" class="form-control" placeholder="Misal: Tim Packing Ashar / Gojek">
                        </div>
                        <div class="form-group">
                            <label>Estimasi Hari Packing (Hari)</label>
                            <input type="number" name="packing_days" class="form-control" value="1" min="1">
                            <small class="text-muted">Lama waktu pengerjaan paket.</small>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Total Estimasi Input:</span>
                                <span class="text-dark font-weight-bold" id="grandTotalDisplay">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Sisa Kekurangan:</span>
                                <span class="h6 text-danger font-weight-bold" id="remainingBalanceDisplay">Rp 0</span>
                            </div>
                            <button type="submit" class="btn btn-primary-apms btn-block btn-lg shadow-sm">
                                <i class="fas fa-save mr-2"></i> Simpan Pesanan Grosir
                            </button>
                            <a href="{{ route('wholesale.index') }}" class="btn btn-light btn-block mt-2">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let rowCount = 1;

function addItemRow() {
    const html = `
    <tr class="item-row">
        <td>
            <input type="text" name="items[${rowCount}][product_name]" class="form-control" placeholder="Nama Barang..." required>
            <input type="hidden" name="items[${rowCount}][product_id]" value="">
        </td>
        <td><input type="number" name="items[${rowCount}][quantity]" class="form-control qty-input" value="1" min="1" required></td>
        <td><input type="number" name="items[${rowCount}][volume_ml]" class="form-control" placeholder="ml"></td>
        <td><input type="number" name="items[${rowCount}][price]" class="form-control price-input" placeholder="Harga" required></td>
        <td>
            <button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)">
                <i class="fas fa-times-circle"></i>
            </button>
        </td>
    </tr>`;
    $('#itemRows').append(html);
    rowCount++;
    calculateTotal();
}

function removeRow(btn) {
    $(btn).closest('tr').remove();
    calculateTotal();
}

function calculateTotal() {
    let total = 0;
    $('.item-row').each(function() {
        const qty = $(this).find('.qty-input').val() || 0;
        const price = $(this).find('.price-input').val() || 0;
        total += (qty * price);
    });
    
    const target = parseFloat($('input[name="package_target_amount"]').val()) || 0;
    const remaining = target - total;
    
    $('#grandTotalDisplay').text('Rp ' + total.toLocaleString('id-ID'));
    
    if (remaining > 0) {
        $('#remainingBalanceDisplay').text('-Rp ' + remaining.toLocaleString('id-ID')).removeClass('text-success').addClass('text-danger');
    } else {
        $('#remainingBalanceDisplay').text('TERPENUHI').removeClass('text-danger').addClass('text-success');
    }
}

$(document).on('input', '.qty-input, .price-input, input[name="package_target_amount"]', function() {
    calculateTotal();
});

calculateTotal();
</script>
@endpush
@endsection
