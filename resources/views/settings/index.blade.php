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
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Logo Toko (Opsional)</label>
                                    <div class="col-sm-9">
                                        @if(isset($settings['store_logo']) && $settings['store_logo'])
                                            <div class="mb-2 bg-light p-2 rounded border" style="display: inline-block;">
                                                <img src="{{ Storage::url($settings['store_logo']) }}" alt="Logo" style="max-height: 60px;">
                                            </div>
                                        @endif
                                        <div class="custom-file custom-file-sm mb-1">
                                            <input type="file" name="store_logo" class="custom-file-input" id="storeLogo" accept="image/png, image/jpeg, image/jpg">
                                            <label class="custom-file-label text-sm" for="storeLogo">Pilih gambar logo...</label>
                                        </div>
                                        <small class="text-muted d-block"><i class="fas fa-info-circle mr-1"></i> Disarankan: PNG dengan background transparan, ukuran kotak.</small>
                                    </div>
                                </div>
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
                            <h3 class="card-title font-weight-bold">Pengaturan Nota & Finansial</h3>
                        </div>
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <h6 class="font-weight-bold border-bottom pb-2 mb-3">Tampilan Nota/Struk</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Ukuran Kertas Printer</label>
                                    <div class="col-sm-9">
                                        <select name="printer_type" class="form-control">
                                            <option value="58mm" {{ ($settings['printer_type'] ?? '') == '58mm' ? 'selected' : '' }}>Thermal 58mm (Nota Kecil)</option>
                                            <option value="80mm" {{ ($settings['printer_type'] ?? '') == '80mm' ? 'selected' : '' }}>Thermal 80mm (Standar POS)</option>
                                            <option value="A4" {{ ($settings['printer_type'] ?? '') == 'A4' ? 'selected' : '' }}>Kertas A4 (Faktur Grosir)</option>
                                        </select>
                                        <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Mempengaruhi tata letak saat Anda mencetak nota kasir.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Header Nota</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="receipt_header" class="form-control" value="{{ $settings['receipt_header'] ?? '' }}" placeholder="Misal: Pusat Grosir Parfum Original">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Footer Nota</label>
                                    <div class="col-sm-9">
                                        <textarea name="receipt_footer" class="form-control" rows="2" placeholder="Misal: Terima kasih atas kunjungan Anda">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                                    </div>
                                </div>

                                <h6 class="font-weight-bold border-bottom pb-2 mb-3 mt-4">Pengaturan Finansial</h6>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Simbol Mata Uang</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? 'Rp' }}" style="width: 100px;">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Pajak / PPN Default</label>
                                    <div class="col-sm-9">
                                        <div class="input-group" style="width: 150px;">
                                            <input type="number" name="default_tax" class="form-control" value="{{ $settings['default_tax'] ?? '0' }}" min="0" max="100" step="0.1">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-light">%</span>
                                            </div>
                                        </div>
                                        <small class="text-muted text-sm d-block mt-1">Pajak yang otomatis diterapkan pada perhitungan grosir/faktur resmi. Isi 0 jika tidak ada.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <button type="submit" class="btn btn-primary-apms px-4">
                                    <i class="fas fa-save mr-1"></i> Simpan Peraturan
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
                            <div class="alert alert-warning mb-4">
                                <h6 class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Langkah Keamanan Penting</h6>
                                <p class="small mb-0">Melakukan <strong>Restore</strong> akan menghapus data Anda saat ini dan menggantinya dengan data dari file backup. Pastikan Anda telah melakukan <strong>Backup</strong> data terbaru sebelum melanjutkan.</p>
                            </div>
                            
                            <div class="row">
                                <!-- Backup Database -->
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border shadow-none rounded p-4 text-center">
                                        <div class="mb-3">
                                            <div class="bg-faint-primary d-inline-block rounded-circle p-3">
                                                <i class="fas fa-database fa-2x text-primary"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-weight-bold">Backup Database</h5>
                                        <p class="text-muted small mb-4">Unduh salinan lengkap data transaksi, produk, dan pelanggan Anda dalam format .sql.</p>
                                        <form action="{{ route('settings.backup') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary-apms btn-block font-weight-bold">
                                                <i class="fas fa-download mr-1"></i> Unduh Cadangan Data
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Restore Database -->
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border shadow-none rounded p-4 text-center">
                                        <div class="mb-3">
                                            <div class="bg-faint-warning d-inline-block rounded-circle p-3">
                                                <i class="fas fa-history fa-2x text-warning"></i>
                                            </div>
                                        </div>
                                        <h5 class="font-weight-bold">Restore Data</h5>
                                        <p class="text-muted small mb-3">Kembalikan sistem ke keadaan sebelumnya menggunakan file backup .sql Anda.</p>
                                        
                                        <form action="{{ route('settings.restore') }}" method="POST" enctype="multipart/form-data" id="restoreForm">
                                            @csrf
                                            <div class="form-group text-left mb-3">
                                                <div class="custom-file custom-file-sm">
                                                    <input type="file" name="backup_file" class="custom-file-input" id="backupFile" accept=".sql" required>
                                                    <label class="custom-file-label text-sm" for="backupFile">Pilih file .sql...</label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-outline-warning btn-block font-weight-bold" onclick="return confirmRestore()">
                                                <i class="fas fa-upload mr-1"></i> Mulai Restore Data
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
                                    <td>{{ \Illuminate\Foundation\Application::VERSION }}</td>@push('scripts')
<script>
$(document).ready(function () {
    // Show filename in custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});

function confirmRestore() {
    return confirm('PERINGATAN: Menjalankan restore akan MENGHAPUS semua data saat ini. Apakah Anda yakin ingin melanjutkan dan mengganti data dengan file backup yang dipilih?');
}
</script>
@endpush
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

