# 🧪 QUICK TESTING GUIDE

## 1️⃣ Start Server

```bash
cd d:\systemtoko\APMS
php artisan serve
```

Server akan running di: **http://localhost:8000**

---

## 2️⃣ Login

- **Email:** `admin@apms.local`
- **Password:** `password`

---

## 3️⃣ Test CRUD Operations

### ✅ Test Create Produk
1. Click: **Produk** (sidebar)
2. Click: **Tambah Produk Baru** (button)
3. Isi form:
   - Nama: "Parfum Test"
   - Kategori: "Premium"
   - Brand: "TestBrand"
   - Ukuran: "100ml"
   - Harga Beli: 500000
   - Harga Jual: 750000
   - Stok: 20
4. Click: **Simpan**
5. Check: Produk muncul di list & database tersimpan ✅

### ✅ Test Edit Produk
1. Click: **Produk** (sidebar)
2. Click: **Edit** (icon pensil)
3. Ubah data (misal: harga jadi 800000)
4. Click: **Simpan Perubahan**
5. Check: Data terupdate ✅

### ✅ Test Delete Produk
1. Click: **Produk** (sidebar)
2. Click: **Hapus** (icon trash)
3. Click: **Hapus** (konfirmasi)
4. Check: Produk hilang dari list ✅

### ✅ Test Create Pelanggan
1. Click: **Pelanggan** (sidebar)
2. Click: **Pelanggan Baru** (button)
3. Isi form:
   - Nama: "Pelanggan Test"
   - Telepon: "081234567890"
   - Email: "test@example.com"
   - Tipe: "Retail"
4. Click: **Simpan**
5. Check: Pelanggan muncul di list ✅

### ✅ Test Edit Pelanggan
1. Click: **Pelanggan** (sidebar)
2. Click: **Edit** (icon pensil)
3. Ubah data
4. Click: **Simpan Perubahan**
5. Check: Data terupdate ✅

### ✅ Test View Pelanggan
1. Click: **Pelanggan** (sidebar)
2. Click: **View** (icon mata)
3. Check: Detail pelanggan & statistik tampil ✅

---

## 4️⃣ Test Database Connection

Buka Terminal & jalankan:
```bash
php artisan tinker
>>> \App\Models\Product::count()
9 (atau jumlah produk yang ada)
>>> \App\Models\Customer::count()
2 (atau jumlah pelanggan)
>>> exit()
```

✅ Semua data terlihat = Database connected!

---

## 5️⃣ Check Database Directly

Buka MySQL:
```bash
mysql -u root -p systemasharparfum
```

Query:
```sql
SELECT * FROM products LIMIT 5;
SELECT * FROM customers;
SELECT * FROM inventories;
```

---

## 🎯 Expected Results

| Test | Expected | Actual |
|------|----------|--------|
| Create Produk | ✅ Data tersimpan | |
| Create Pelanggan | ✅ Data tersimpan | |
| Edit Produk | ✅ Data terupdate | |
| Edit Pelanggan | ✅ Data terupdate | |
| Delete Produk | ✅ Data terhapus | |
| Database Connection | ✅ Data terlihat | |
| Form Validation | ✅ Error ditampilkan | |
| Navigation | ✅ Semua menu aktif | |

---

## ❌ Jika Ada Error

1. **Error: "Column not found"**
   - Solusi: `php artisan migrate --force`

2. **Error: "Route not found"**
   - Solusi: `php artisan route:cache --clear`

3. **Error: "Database not connected"**
   - Check `.env` database settings
   - Pastikan MySQL running

4. **Error: "Asset not loaded"**
   - Solusi: `npm run build`

5. **Error: "Server crash"**
   - Solusi: Restart server
   - Check log: `storage/logs/laravel.log`

---

## ✅ Verification Checklist

- [ ] Server running tanpa error
- [ ] Login successful
- [ ] Create produk successful
- [ ] Edit produk successful
- [ ] Delete produk successful
- [ ] Create pelanggan successful
- [ ] Edit pelanggan successful
- [ ] Database data tersimpan
- [ ] All navigation working
- [ ] Forms validated properly

---

**Jika semua ✅, berarti aplikasi sudah WORKING 100%!** 🎉
