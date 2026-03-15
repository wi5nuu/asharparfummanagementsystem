@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <!-- Settings Sidebar -->
            <div class="card card-apms shadow-sm border-0">
                <div class="card-header bg-primary-apms text-white">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-cog mr-2"></i> Menu Pengaturan</h3>
                </div>
                <div class="card-body p-0">
                    <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active p-3 border-bottom" id="store-tab" data-toggle="pill" href="#store-settings" role="tab" aria-controls="store-settings" aria-selected="true">
                            <i class="fas fa-store mr-2"></i> Identitas Toko
                        </a>
                        <a class="nav-link p-3 border-bottom" id="transaction-tab" data-toggle="pill" href="#transaction-settings" role="tab" aria-controls="transaction-settings" aria-selected="false">
                            <i class="fas fa-receipt mr-2"></i> Pengaturan Nota
                        </a>
                        <a class="nav-link p-3 border-bottom" id="system-tab" data-toggle="pill" href="#system-settings" role="tab" aria-controls="system-settings" aria-selected="false">
                            <i class="fas fa-server mr-2"></i> Sistem & Backup
                        </a>
                        <a class="nav-link p-3" href="{{ route('settings.profile') }}">
                            <i class="fas fa-user-circle mr-2"></i> Profil Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content" id="settings-tabsContent">
                <!-- Store Identity Settings -->
                <div class="tab-pane fade show active" id="store-settings" role="tabpanel" aria-labelledby="store-tab">
                    <div class="card card-apms shadow-sm border-0">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Identitas Toko</h3>
                        </div>
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nama Toko</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Alamat Lengkap</label>
                                    <div class="col-sm-9">
                                        <textarea name="store_address" class="form-control" rows="3">{{ $settings['store_address'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nomor Telepon</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="store_phone" class="form-control" value="{{ $settings['store_phone'] ?? '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <button type="submit" class="btn btn-primary-apms px-4">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transaction Settings -->
                <div class="tab-pane fade" id="transaction-settings" role="tabpanel" aria-labelledby="transaction-tab">
                    <div class="card card-apms shadow-sm border-0">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Pengaturan Nota / Struk</h3>
                        </div>
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Header Nota (Teks Atas)</label>
                                    <input type="text" name="receipt_header" class="form-control" value="{{ $settings['receipt_header'] ?? '' }}">
                                    <small class="text-muted">Muncul di bagian paling bawah setelah nama toko.</small>
                                </div>
                                <div class="form-group">
                                    <label>Footer Nota (Teks Bawah)</label>
                                    <textarea name="receipt_footer" class="form-control" rows="3">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                                    <small class="text-muted">Biasanya berisi ucapan terima kasih atau syarat & ketentuan.</small>
                                </div>
                                <div class="form-group">
                                    <label>Simbol Mata Uang</label>
                                    <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? 'Rp' }}" style="width: 100px;">
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <button type="submit" class="btn btn-primary-apms px-4">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System & Backup Settings -->
                <div class="tab-pane fade" id="system-settings" role="tabpanel" aria-labelledby="system-tab">
                    <div class="card card-apms shadow-sm border-0">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Sistem & Keamanan Data</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i> Disarankan melakukan backup database secara berkala untuk menghindari kehilangan data.
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="border rounded p-4 text-center">
                                        <i class="fas fa-database fa-3x text-primary mb-3"></i>
                                        <h5>Backup Database</h5>
                                        <p class="text-muted small">Unduh salinan data transaksi, produk, dan pelanggan Anda saat ini.</p>
                                        <form action="{{ route('settings.backup') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-block">
                                                <i class="fas fa-download mr-1"></i> Jalankan Backup
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-4 text-center">
                                        <i class="fas fa-history fa-3x text-warning mb-3"></i>
                                        <h5>Restore Data</h5>
                                        <p class="text-muted small">Kembalikan data dari file backup yang sudah ada.</p>
                                        <form action="{{ route('settings.restore') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-block">
                                                <i class="fas fa-upload mr-1"></i> Restore (Manual)
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3">Informasi Lingkungan</h5>
                            <table class="table table-sm table-striped">
                                <tr>
                                    <td width="30%">Versi Aplikasi</td>
                                    <td><strong>1.0.0 (Production)</strong></td>
                                </tr>
                                <tr>
                                    <td>Laravel Version</td>
                                    <td>{{ \Illuminate\Foundation\Application::VERSION }}</td>
                                </tr>
                                <tr>
                                    <td>PHP Version</td>
                                    <td>{{ PHP_VERSION }}</td>
                                </tr>
                                <tr>
                                    <td>Waktu Server</td>
                                    <td>{{ date('d M Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

