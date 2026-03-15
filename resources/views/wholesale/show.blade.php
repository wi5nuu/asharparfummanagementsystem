@extends('layouts.app')

@section('title', 'Detail Pesanan Grosir')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Order info & Items -->
        <div class="col-lg-8">
            <div class="card card-apms border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 font-weight-bold text-primary-apms">
                        <i class="fas fa-file-invoice mr-2"></i> {{ $order->invoice_number }}
                    </h5>
                    <span class="badge badge-lg 
                        @if($order->status == 'pending') badge-warning 
                        @elseif($order->status == 'on_progress') badge-primary 
                        @elseif($order->status == 'ready_to_ship') badge-info 
                        @elseif($order->status == 'completed') badge-success 
                        @else badge-danger @endif p-2">
                        {{ strtoupper($order->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item / Aroma</th>
                                    <th>Qty</th>
                                    <th>Volume</th>
                                    <th>Harga</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                <tr>
                                    <td>{{ $detail->product_name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->volume_ml ? $detail->volume_ml . ' ml' : '-' }}</td>
                                    <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-right font-weight-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="h5">
                                    <td colspan="4" class="text-right font-weight-bold">Total Nilai Pesanan:</td>
                                    <td class="text-right text-primary font-weight-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right text-muted italic">Target Paket:</td>
                                    <td class="text-right text-muted font-weight-bold">Rp {{ number_format($order->package_target_amount, 0, ',', '.') }}</td>
                                </tr>
                                @if($order->total_amount < $order->package_target_amount)
                                <tr>
                                    <td colspan="4" class="text-right text-danger italic">Sisa Kekurangan:</td>
                                    <td class="text-right text-danger font-weight-bold">-Rp {{ number_format($order->package_target_amount - $order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="4" class="text-right text-success font-weight-bold">Status Target:</td>
                                    <td class="text-right text-success font-weight-bold">TERPENUHI</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping & Log -->
            <div class="card card-apms border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-truck mr-2"></i> Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
                            <p><strong>Telepon:</strong> {{ $order->recipient_phone }}</p>
                            <p><strong>Alamat:</strong> <br> {{ $order->shipping_address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Kurir:</strong> {{ $order->shipping_courier ?? '-' }}</p>
                            <p><strong>P. Jawab / Pengiriman:</strong> {{ $order->delivery_handler ?? '-' }}</p>
                            <p><strong>Estimasi Packing:</strong> {{ $order->packing_days ?? 1 }} Hari</p>
                            <p><strong>Dibuat Oleh:</strong> {{ $order->user->name }}</p>
                            <p><strong>Terdaftar:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                            @if($order->barcode)
                                <div class="mt-3">
                                    <p class="mb-1"><strong>Barcode Pengiriman:</strong></p>
                                    <div class="bg-light p-2 text-center rounded">
                                        <i class="fas fa-barcode fa-3x"></i><br>
                                        <code>{{ $order->barcode }}</code>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Actions -->
        <div class="col-lg-4">
            <div class="card card-apms border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 font-weight-bold text-primary-apms"><i class="fas fa-tasks mr-2"></i> Alur Kerja (Workflows)</h5>
                </div>
                <div class="card-body">
                    @if($order->status == 'pending')
                        <div class="alert alert-warning border-0 small">
                            Produk sedang menunggu konfirmasi Admin/Owner untuk pengerjaan paket.
                        </div>
                        @if(in_array(auth()->user()->role, ['owner', 'admin']))
                            <form action="{{ route('wholesale.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary-apms btn-block btn-lg">
                                    <i class="fas fa-check-double mr-2"></i> KONFIRMASI PESANAN
                                </button>
                                <small class="text-muted d-block mt-2 text-center">Menandakan paket senilai Rp {{ number_format($order->package_target_amount, 0, ',', '.') }} siap dikerjakan.</small>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-block disabled" disabled>Menunggu Konfirmasi Admin</button>
                        @endif

                    @elseif($order->status == 'on_progress')
                        <div class="alert alert-primary border-0 small">
                            Pesanan dalam proses pengerjaan oleh tim gudang/racikan.
                        </div>
                        <form action="{{ route('wholesale.ready', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-box mr-2"></i> SELESAI & SIAP ANTAR
                            </button>
                            <small class="text-muted d-block mt-2 text-center">Menandakan pengerjaan aroma/alat sudah selesai 100%.</small>
                        </form>

                    @elseif($order->status == 'ready_to_ship')
                        <div class="alert alert-info border-0 small">
                            Pesanan telah selesai dikemas dan siap untuk dikirim/diambil.
                        </div>
                        <form action="{{ route('wholesale.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-shipping-fast mr-2"></i> SELESAI TERKIRIM
                            </button>
                        </form>
                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-block mt-3 shadow-sm rounded-pill">
                            <i class="fab fa-whatsapp mr-2"></i> Kirim Invoice ke WhatsApp
                        </a>
                        <a href="{{ route('wholesale.print', $order->id) }}" target="_blank" class="btn btn-outline-dark btn-block mt-2 shadow-sm rounded-pill border-2">
                            <i class="fas fa-print mr-2"></i> Cetak Invoice Professional
                        </a>

                    @elseif($order->status == 'completed')
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h5 class="font-weight-bold">Pesanan Selesai</h5>
                            <p class="text-muted">Faktur telah masuk dalam catatan keuangan Grosir.</p>
                            <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-success btn-block mt-3 shadow-sm rounded-pill">
                                <i class="fab fa-whatsapp mr-2"></i> Kirim Ulang ke WhatsApp
                            </a>
                            <a href="{{ route('wholesale.print', $order->id) }}" target="_blank" class="btn btn-outline-dark btn-block mt-2 shadow-sm rounded-pill border-2">
                                <i class="fas fa-print mr-2"></i> Cetak Ulang Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <a href="{{ route('wholesale.index') }}" class="btn btn-light btn-block"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar</a>
        </div>
    </div>
</div>
@endsection
