<?php

namespace App\Services;

/**
 * APMS Knowledge Base — The AI's "Brain Library"
 * Covers every page, feature, how-to, and troubleshooting guide.
 */
class ApmsKnowledgeBase
{
    /**
     * Returns a huge map of topics -> knowledge entries.
     * Each entry has: answer (HTML), url (optional), label (button label)
     */
    public static function getAll(): array
    {
        return [

            // ───── KASIR / POS ─────────────────────────────────────────────
            'cara_transaksi' => [
                'keywords' => ['cara transaksi', 'cara jual', 'cara pakai kasir', 'buat transaksi', 'proses penjualan', 'cara input penjualan', 'pakai pos', 'cara buka kasir'],
                'answer'   => "🏪 <strong>Cara Melakukan Transaksi Kasir:</strong><br>1. Buka menu <strong>Kasir</strong> (atau klik tombol di bawah)<br>2. Pilih pelanggan (opsional, untuk poin/loyalitas)<br>3. Cari barang lalu klik <strong>Tambah ke Keranjang</strong><br>4. Masukkan jumlah uang yang diterima<br>5. Klik <strong>Proses Pembayaran</strong><br>6. Struk otomatis tampil — bisa langsung cetak!",
                'url'      => '/transactions/create',
                'label'    => 'Buka Mesin Kasir',
            ],
            'cara_cetak_struk' => [
                'keywords' => ['cetak struk', 'print nota', 'struk tidak keluar', 'tidak bisa cetak', 'printer nota', 'cara print', 'ukuran kertas', 'struk tidak muncul'],
                'answer'   => "🖨️ <strong>Cara Cetak Struk/Nota:</strong><br>1. Pastikan printer terkoneksi ke komputer kasir<br>2. Periksa ukuran kertas di <strong>Pengaturan → Printer</strong> (pilih 58mm atau 80mm sesuai printer Anda)<br>3. Setelah transaksi, klik tombol <strong>Cetak Struk</strong> yang muncul<br><br>⚠️ Struk tidak keluar? Kemungkinan: ukuran kertas tidak cocok atau printer belum aktif.",
                'url'      => '/settings',
                'label'    => 'Atur Ukuran Printer',
            ],
            'cara_diskon_kasir' => [
                'keywords' => ['diskon di kasir', 'kurangi harga', 'potongan harga', 'cashback', 'cara diskon', 'diskon transaksi'],
                'answer'   => "💸 <strong>Cara Memberikan Diskon di Kasir:</strong><br>1. Tambahkan barang ke keranjang<br>2. Di kolom harga / qty, ada field <strong>Diskon (%)</strong> — isi langsung<br>3. Atau gunakan <strong>Kode Kupon</strong> di bagian bawah form kasir<br><br>💡 Buat kupon promo terlebih dahulu di menu Kupon & Loyalty!",
                'url'      => '/coupons',
                'label'    => 'Kelola Kupon Promo',
            ],
            'riwayat_transaksi' => [
                'keywords' => ['riwayat transaksi', 'history penjualan', 'transaksi kemarin', 'lihat transaksi lama', 'rekap jual', 'transaksi yang sudah'],
                'answer'   => "📋 <strong>Melihat Riwayat Transaksi:</strong><br>Semua penjualan yang sudah diproses tersimpan di halaman <strong>Transaksi</strong>. Anda bisa filter berdasarkan tanggal, kasir, atau status pembayaran.",
                'url'      => '/transactions',
                'label'    => 'Buka Riwayat Transaksi',
            ],

            // ───── PRODUK ───────────────────────────────────────────────────
            'cara_tambah_produk' => [
                'keywords' => ['cara tambah produk', 'tambah barang baru', 'input aroma baru', 'daftarkan produk', 'cara daftar parfum', 'entry produk', 'buat produk'],
                'answer'   => "🍶 <strong>Cara Menambah Produk Baru:</strong><br>1. Buka menu <strong>Produk</strong><br>2. Klik tombol <strong>+ Tambah Produk</strong><br>3. Isi: Nama Aroma, Brand, Ukuran (ml), Unit, Harga Jual, Harga Grosir, Harga Modal<br>4. Upload foto produk (opsional)<br>5. Klik <strong>Simpan</strong> — produk langsung muncul di Kasir!",
                'url'      => '/products',
                'label'    => 'Kelola Produk',
            ],
            'cara_edit_produk' => [
                'keywords' => ['edit produk', 'ubah harga', 'ganti nama barang', 'update produk', 'ubah aroma', 'koreksi produk'],
                'answer'   => "✏️ <strong>Cara Edit Produk:</strong><br>1. Buka <strong>Produk</strong><br>2. Klik ikon pensil/edit di baris produk yang ingin diubah<br>3. Ubah data yang diperlukan (harga, nama, ukuran, dll)<br>4. Klik <strong>Update</strong> untuk menyimpan perubahan",
                'url'      => '/products',
                'label'    => 'Buka Daftar Produk',
            ],
            'cara_barcode' => [
                'keywords' => ['barcode', 'cetak label', 'print barcode', 'qrcode', 'kode produk'],
                'answer'   => "📦 <strong>Cara Cetak Barcode Produk:</strong><br>1. Buka menu <strong>Produk</strong><br>2. Pada baris produk, klik ikon <strong>Barcode</strong><br>3. Halaman cetak barcode akan terbuka — langsung Ctrl+P untuk print!",
                'url'      => '/products',
                'label'    => 'Buka Produk',
            ],

            // ───── INVENTORY / GUDANG ────────────────────────────────────────
            'cara_tambah_stok' => [
                'keywords' => ['tambah stok', 'isi stok', 'restok', 'masukkan barang', 'input gudang', 'update stok', 'cara restock', 'isi gudang'],
                'answer'   => "📦 <strong>Cara Tambah/Update Stok Gudang:</strong><br>1. Buka menu <strong>Inventory</strong><br>2. Cari produk yang ingin diisi stoknya<br>3. Klik tombol <strong>Adjust Stok</strong><br>4. Pilih tipe: Tambah (+), Kurangi (-), atau Set Langsung<br>5. Masukkan jumlah dan keterangan<br>6. Klik <strong>Simpan Perubahan</strong>",
                'url'      => '/inventory',
                'label'    => 'Buka Inventory',
            ],
            'cara_set_minimum_stok' => [
                'keywords' => ['minimum stok', 'batas stok', 'alert stok', 'notifikasi stok', 'stok minimal', 'set batas'],
                'answer'   => "⚠️ <strong>Cara Set Batas Minimum Stok:</strong><br>Di halaman <strong>Inventory</strong>, setiap produk memiliki kolom <strong>Minimum Stock</strong>. Jika stok di bawah angka ini, sistem akan menandai produk tersebut sebagai <strong>Kritis</strong> (ditampilkan di Dashboard dan AI Copilot).",
                'url'      => '/inventory',
                'label'    => 'Atur Minimum Stok',
            ],

            // ───── AUDIT STOK ────────────────────────────────────────────────
            'cara_audit_stok' => [
                'keywords' => ['cara audit', 'stock opname', 'hitung fisik', 'cek fisik barang', 'mulai audit', 'buat audit baru', 'cara opname'],
                'answer'   => "📋 <strong>Cara Melakukan Audit Stok (Stock Opname):</strong><br>1. Buka menu <strong>Audit Stok</strong><br>2. Klik <strong>Buat Audit Baru</strong> — pilih produk yang ingin diaudit<br>3. Hitung fisik parfum di rak/gudang<br>4. Input angka pada kolom <strong>Stok Fisik</strong> — selisih otomatis dihitung<br>5. Isi catatan jika ada kejanggalan<br>6. Klik <strong>Selesaikan Audit</strong> untuk mengunci hasil<br><br>⚠️ Audit yang sudah 'Selesai' tidak bisa diubah lagi!",
                'url'      => '/stock_audits',
                'label'    => 'Buka Audit Stok',
            ],

            // ───── GROSIR ───────────────────────────────────────────────────
            'cara_pesanan_grosir' => [
                'keywords' => ['cara grosir', 'buat pesanan grosir', 'input pesanan agen', 'pesanan partai', 'cara order grosir', 'invoice grosir', 'cara wholesale'],
                'answer'   => "🚚 <strong>Cara Membuat Pesanan Grosir:</strong><br>1. Buka <strong>Manajemen Grosir → Buat Pesanan</strong><br>2. Pilih barang dari dropdown (harga grosir otomatis terisi!)<br>3. Isi qty, volume, penerima, alamat, dan kurir<br>4. Masukkan <strong>Target Nilai Paket</strong> (misal Rp 10 juta)<br>5. Klik <strong>Simpan</strong> — pesanan masuk status Pending<br>6. Admin konfirmasi → stok gudang otomatis terpotong!",
                'url'      => '/wholesale/create',
                'label'    => 'Buat Pesanan Grosir',
            ],
            'konfirmasi_grosir' => [
                'keywords' => ['konfirmasi grosir', 'approve pesanan', 'proses grosir', 'setujui pesanan', 'cara konfirmasi'],
                'answer'   => "✅ <strong>Cara Konfirmasi Pesanan Grosir:</strong><br>1. Buka <strong>Manajemen Grosir</strong><br>2. Cari pesanan berstatus <strong>Pending</strong><br>3. Klik tombol <strong>Konfirmasi</strong> (hanya Admin/Owner)<br>4. Sistem otomatis memotong stok, status jadi <strong>On Progress</strong><br>5. Saat siap kirim, klik <strong>Siap Kirim</strong> untuk generate invoice<br>6. Bagikan invoice via WhatsApp dengan tombol WA yang tersedia!",
                'url'      => '/wholesale',
                'label'    => 'Lihat Pesanan Grosir',
            ],

            // ───── SHIFT & KASIR ─────────────────────────────────────────────
            'cara_open_shift' => [
                'keywords' => ['open shift', 'buka shift', 'mulai kerja', 'buka toko', 'cara shift', 'mulai shift', 'cara buka'],
                'answer'   => "🔓 <strong>Cara Open Shift Kasir:</strong><br>1. Buka menu <strong>Kasir / POS</strong><br>2. Jika belum ada shift aktif, sistem meminta buka shift<br>3. Masukkan <strong>Modal Awal Kas</strong> (uang yang ada di laci)<br>4. Klik <strong>Mulai Shift</strong> — sistem siap mencatat transaksi!",
                'url'      => '/transactions/create',
                'label'    => 'Buka POS / Open Shift',
            ],
            'cara_closing_kasir' => [
                'keywords' => ['closing kasir', 'tutup shift', 'akhir hari', 'laporan shift', 'cara closing', 'tutup toko', 'end shift', 'close shift'],
                'answer'   => "🔒 <strong>Cara Closing / Tutup Shift:</strong><br>1. Buka menu <strong>Shift & Closing Kasir</strong><br>2. Klik pada shift yang aktif<br>3. Hitung uang di laci kasir, masukkan jumlahnya<br>4. Sistem menghitung selisih otomatis (setoran vs penjualan)<br>5. Upload foto kas jika diperlukan<br>6. Klik <strong>Selesaikan Shift</strong>",
                'url'      => '/shifts',
                'label'    => 'Buka Shift & Closing',
            ],

            // ───── PELANGGAN / MEMBER ─────────────────────────────────────────
            'cara_daftar_pelanggan' => [
                'keywords' => ['daftar pelanggan', 'tambah member', 'input pelanggan baru', 'cara tambah customer', 'registrasi member', 'buat akun pelanggan'],
                'answer'   => "👥 <strong>Cara Mendaftarkan Pelanggan Baru:</strong><br>1. Buka menu <strong>Pelanggan</strong><br>2. Klik <strong>+ Tambah Pelanggan</strong><br>3. Isi: Nama, Nomor HP, Alamat (opsional)<br>4. Klik <strong>Simpan</strong><br><br>💡 Pelanggan terdaftar bisa langsung dipilih saat transaksi kasir — poin loyalitas otomatis terakumulasi!",
                'url'      => '/customers',
                'label'    => 'Kelola Pelanggan',
            ],
            'cara_cari_pelanggan' => [
                'keywords' => ['cari pelanggan', 'cari member', 'data pelanggan', 'nomor hp pelanggan', 'temukan customer'],
                'answer'   => "🔍 <strong>Cara Cari Pelanggan:</strong><br>Di halaman <strong>Pelanggan</strong>, gunakan kolom pencarian (🔍) — bisa cari berdasarkan <strong>nama</strong> atau <strong>nomor HP</strong>. Hasil pencarian real-time!",
                'url'      => '/customers',
                'label'    => 'Buka Data Pelanggan',
            ],

            // ───── KAS BON / UTANG ───────────────────────────────────────────
            'cara_kasbon' => [
                'keywords' => ['cara kasbon', 'input utang', 'catat hutang', 'cara bon', 'buat kas bon', 'cara piutang', 'pelanggan belum bayar'],
                'answer'   => "📒 <strong>Cara Catat Kas Bon (Piutang):</strong><br>Saat transaksi kasir, pilih metode bayar <strong>'Kas Bon / Kredit'</strong> — sistem otomatis mencatat sebagai piutang pelanggan tersebut.<br><br>Untuk melihat semua tagihan yang belum lunas, buka menu <strong>Manajemen Kas Bon</strong>.",
                'url'      => '/debts',
                'label'    => 'Buka Buku Kas Bon',
            ],
            'cara_bayar_kasbon' => [
                'keywords' => ['bayar bon', 'lunasi hutang', 'pelanggan bayar', 'cicil bon', 'terima bayar kasbon', 'konfirmasi bayar'],
                'answer'   => "💳 <strong>Cara Terima Pembayaran Kas Bon:</strong><br>1. Buka menu <strong>Manajemen Kas Bon</strong><br>2. Cari nama pelanggan yang ingin membayar<br>3. Klik tombol <strong>Terima Pembayaran</strong><br>4. Masukkan jumlah yang dibayar (bisa parsial/cicil)<br>5. Sistem otomatis menghitung sisa tagihan!",
                'url'      => '/debts',
                'label'    => 'Kelola Kas Bon',
            ],

            // ───── PENGELUARAN ───────────────────────────────────────────────
            'cara_input_pengeluaran' => [
                'keywords' => ['input pengeluaran', 'catat pengeluaran', 'tambah biaya', 'cara expense', 'catat belanja', 'biaya operasional'],
                'answer'   => "💸 <strong>Cara Catat Pengeluaran / Biaya:</strong><br>1. Buka menu <strong>Pengeluaran</strong><br>2. Klik <strong>+ Tambah Pengeluaran</strong><br>3. Isi: Kategori, Jumlah, Deskripsi, Tanggal<br>4. Klik <strong>Simpan</strong><br><br>📊 Pengeluaran otomatis masuk ke kalkulasi Laporan Laba Rugi!",
                'url'      => '/expenses',
                'label'    => 'Kelola Pengeluaran',
            ],

            // ───── KARYAWAN & ABSENSI ────────────────────────────────────────
            'cara_tambah_karyawan' => [
                'keywords' => ['tambah karyawan', 'daftar karyawan baru', 'input pegawai', 'buat akun kasir', 'kasir baru', 'create user'],
                'answer'   => "👤 <strong>Cara Tambah Karyawan / Kasir Baru:</strong><br>1. Buka menu <strong>Karyawan</strong><br>2. Klik <strong>+ Tambah Karyawan</strong><br>3. Isi nama, email, role (kasir/admin), serta password<br>4. Klik <strong>Simpan</strong><br><br>💡 Role <strong>Kasir</strong> hanya bisa akses POS. Role <strong>Admin</strong> bisa kelola produk & inventory. Role <strong>Owner</strong> punya akses penuh!",
                'url'      => '/employees',
                'label'    => 'Kelola Karyawan',
            ],
            'cara_absensi' => [
                'keywords' => ['cara absen', 'input absensi', 'check in karyawan', 'record kehadiran', 'catat hadir', 'absensi hari ini'],
                'answer'   => "🕐 <strong>Cara Input Absensi Kehadiran:</strong><br>1. Buka menu <strong>Absensi Kehadiran</strong><br>2. Klik tombol <strong>Check In</strong> untuk mulai kerja<br>3. Klik <strong>Check Out</strong> saat selesai<br>4. Sistem otomatis menghitung jam kerja per hari<br><br>📊 Rekap absensi bulanan tersedia untuk perhitungan gaji.",
                'url'      => '/attendances',
                'label'    => 'Buka Absensi',
            ],

            // ───── LAPORAN ──────────────────────────────────────────────────
            'cara_laporan_penjualan' => [
                'keywords' => ['laporan penjualan', 'rekap omzet', 'laporan harian', 'laporan bulanan', 'export laporan', 'download laporan', 'cetak laporan'],
                'answer'   => "📊 <strong>Cara Lihat & Export Laporan Penjualan:</strong><br>1. Buka menu <strong>Laporan</strong><br>2. Pilih tab: Penjualan Harian / Produk Terlaris / Laba Rugi / dll<br>3. Atur rentang tanggal yang diinginkan<br>4. Klik <strong>Export PDF</strong> atau <strong>Export CSV</strong> untuk download<br><br>💡 Laporan Laba Rugi sudah otomatis mengurangi pengeluaran dari omzet!",
                'url'      => '/reports',
                'label'    => 'Buka Papan Laporan',
            ],
            'produk_terlaris' => [
                'keywords' => ['produk terlaris', 'aroma terlaris', 'barang paling laku', 'best seller', 'paling sering dibeli', 'populer'],
                'answer'   => null, // will be handled dynamically
                'dynamic'  => 'top_products',
            ],

            // ───── KUPON & LOYALTY ───────────────────────────────────────────
            'cara_buat_kupon' => [
                'keywords' => ['cara buat kupon', 'buat promo', 'input kode diskon', 'tambah voucher', 'cara voucher', 'cara kode promo'],
                'answer'   => "🎫 <strong>Cara Buat Kupon Promo:</strong><br>1. Buka menu <strong>Kupon & Loyalty</strong><br>2. Klik <strong>+ Tambah Kupon</strong><br>3. Isi: Kode Unik, Jumlah Diskon (% atau nominal), Batas Pemakaian, Tanggal Berlaku<br>4. Klik <strong>Simpan</strong><br><br>💡 Kode kupon bisa langsung dipakai kasir saat melayani pelanggan!",
                'url'      => '/coupons',
                'label'    => 'Buat Kupon Promo',
            ],

            // ───── PENGATURAN ────────────────────────────────────────────────
            'cara_ganti_logo' => [
                'keywords' => ['ganti logo', 'upload logo', 'logo toko', 'foto toko', 'branding', 'identitas toko'],
                'answer'   => "🏷️ <strong>Cara Ganti Logo Toko:</strong><br>1. Buka <strong>Pengaturan → Identitas Toko</strong><br>2. Klik tombol <strong>Upload Logo</strong> — preview langsung tampil<br>3. Klik <strong>Simpan Pengaturan</strong><br><br>Logo toko langsung tampil di sidebar dan semua struk/nota cetak!",
                'url'      => '/settings',
                'label'    => 'Buka Pengaturan',
            ],
            'cara_backup' => [
                'keywords' => ['cara backup', 'backup data', 'simpan database', 'export database', 'download backup', 'amankan data'],
                'answer'   => "💾 <strong>Cara Backup Database:</strong><br>1. Buka <strong>Pengaturan → Sistem & Backup</strong><br>2. Klik tombol <strong>Backup Database</strong><br>3. File <strong>.sql</strong> otomatis terdownload ke komputer Anda<br><br>⭐ Disarankan backup <strong>setiap minggu</strong> dan simpan di Google Drive atau flashdisk terpisah!",
                'url'      => '/settings',
                'label'    => 'Buka Sistem & Backup',
            ],
            'cara_restore' => [
                'keywords' => ['cara restore', 'pulihkan data', 'kembalikan data', 'import database', 'upload backup', 'restore database'],
                'answer'   => "♻️ <strong>Cara Restore Database dari Backup:</strong><br>1. Buka <strong>Pengaturan → Sistem & Backup</strong><br>2. Di bagian <strong>Restore Data</strong>, klik <strong>Pilih File .sql</strong><br>3. Upload file backup yang pernah didownload<br>4. Konfirmasi peringatan — data saat ini akan diganti!<br><br>⚠️ <strong>PENTING:</strong> Selalu backup data terkini sebelum restore!",
                'url'      => '/settings',
                'label'    => 'Buka Sistem & Backup',
            ],
            'cara_ubah_pajak' => [
                'keywords' => ['ubah pajak', 'setting ppn', 'ganti ppn', 'pajak toko', 'tarif pajak', 'set pajak'],
                'answer'   => "📋 <strong>Cara Mengatur Pajak (PPN):</strong><br>1. Buka <strong>Pengaturan</strong><br>2. Cari input <strong>Pajak Standard (PPN %)</strong><br>3. Masukkan angka persentase (misal: 11 untuk PPN 11%)<br>4. Klik <strong>Simpan</strong> — nilai PPN otomatis dipakai di faktur grosir!",
                'url'      => '/settings',
                'label'    => 'Buka Pengaturan',
            ],

            // ───── TROUBLESHOOTING ───────────────────────────────────────────
            'masalah_login' => [
                'keywords' => ['tidak bisa login', 'lupa password', 'password salah', 'akun kunci', 'gagal masuk', 'error login'],
                'answer'   => "🔑 <strong>Masalah Login:</strong><br>1. Pastikan email & password benar (huruf besar/kecil sensitif)<br>2. Lupa password? Klik <strong>Lupa Password</strong> di halaman login — link reset dikirim ke email<br>3. Jika akun diblokir, hubungi Owner untuk mereset akun di menu <strong>Karyawan</strong>",
                'url'      => '/employees',
                'label'    => 'Kelola Akun Karyawan',
            ],
            'masalah_stok_minus' => [
                'keywords' => ['stok minus', 'stok negatif', 'stok kurang dari nol', 'stok error', 'stok tidak wajar'],
                'answer'   => "⚠️ <strong>Stok Negatif / Minus:</strong><br>Ini bisa terjadi jika stok tidak diinput sebelum sistem dipakai. Cara perbaiki:<br>1. Buka <strong>Inventory</strong> → cari produk yang minus<br>2. Klik <strong>Adjust Stok</strong> → pilih tipe <strong>Set Langsung</strong><br>3. Masukkan jumlah stok fisik yang benar<br>4. Lakukan <strong>Audit Stok</strong> rutin untuk mencegah hal ini!",
                'url'      => '/inventory',
                'label'    => 'Buka Inventory',
            ],
            'masalah_laporan_kosong' => [
                'keywords' => ['laporan kosong', 'grafik tidak muncul', 'data laporan nol', 'laba rugi kosong', 'report tidak ada'],
                'answer'   => "📊 <strong>Laporan Tampak Kosong?</strong><br>1. Pastikan rentang <strong>tanggal filter</strong> sudah benar (cek pilihan Dari - Sampai)<br>2. Pastikan sudah ada transaksi di periode tersebut<br>3. Arahkan tim untuk input pengeluaran agar Laba Rugi terhitung<br>4. Jika tetap kosong, coba <em>clear cache</em>: jalankan <code>php artisan cache:clear</code>",
                'url'      => '/reports',
                'label'    => 'Buka Laporan',
            ],
            'masalah_harga_salah' => [
                'keywords' => ['harga salah', 'harga berbeda', 'harga tidak sesuai', 'update harga', 'harga lama', 'harga tidak berubah'],
                'answer'   => "💰 <strong>Harga Produk Tidak Sesuai?</strong><br>1. Buka <strong>Produk</strong> → cari produk yang bermasalah<br>2. Klik <strong>Edit</strong> → ubah <strong>Harga Jual</strong> dan/atau <strong>Harga Grosir</strong><br>3. Klik <strong>Update</strong><br><br>⚡ Harga di Kasir langsung berubah! Tidak perlu restart aplikasi.",
                'url'      => '/products',
                'label'    => 'Edit Produk',
            ],

            // ───── TENTANG APMS ─────────────────────────────────────────────
            'tentang_apms' => [
                'keywords' => ['apms itu apa', 'apa itu apms', 'penjelasan apms', 'fitur apms', 'fungsi sistem', 'sistem ini untuk apa'],
                'answer'   => "🌟 <strong>Tentang APMS (Ashar Parfum Management System):</strong><br>APMS adalah sistem manajemen toko parfum Enterprise yang dirancang khusus untuk operasional <strong>Ashar Parfum</strong>. Fitur lengkap:<br>▸ POS / Kasir Eceran<br>▸ Manajemen Grosir (Wholesale)<br>▸ Inventory & Audit Stok<br>▸ Laporan & Analitik<br>▸ Karyawan & Absensi<br>▸ Kupon & Loyalty Program<br>▸ Kas Bon / Piutang<br>▸ Backup & Restore Data<br>▸ AI Copilot (sedang berbicara dengan Anda!) 🤖",
                'url'      => '/dashboard',
                'label'    => 'Ke Dashboard Utama',
            ],
        ];
    }
}
