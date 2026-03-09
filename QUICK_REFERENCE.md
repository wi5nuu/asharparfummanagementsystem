# 🚀 APMS - Quick Reference Guide

## Akses Aplikasi

```
URL:      http://localhost:8000
Status:   ✅ Running (Development Server)
```

## Login Credentials

### Admin Account (Full Access)
```
Email:    admin@apms.local
Password: password
Role:     admin
```

### Cashier Account (POS Access)
```
Email:    cashier@apms.local
Password: password
Role:     cashier
```

---

## Menu Navigation

### 📊 Dashboard
- **URL:** `/dashboard`
- **Access:** All users
- **Features:** Statistics, charts, quick links

### 🍾 Produk (Products)
- **List:** `/products` (Create, search, filter)
- **Create:** `/products/create` (New product form)
- **Edit:** `/products/{id}/edit` (Update product)
- **View:** `/products/{id}` (Product details)
- **Delete:** Delete from list (Confirm modal)
- **Extra:** Print barcode `/products/{id}/barcode`
- **Access:** Admin, Manager

### 📦 Inventory
- **URL:** `/inventory`
- **Features:** Stock levels, low stock alerts, adjustment
- **Access:** Admin, Manager

### 💰 Kasir (POS)
- **URL:** `/transactions/create`
- **Features:** Point of sale, cart, payments
- **Access:** Admin, Cashier, Manager

### 📋 Transaksi (Transactions)
- **List:** `/transactions` (View all transactions)
- **View:** `/transactions/{id}` (Transaction details)
- **Print:** `/transactions/{id}/print` (Invoice)
- **Access:** All authenticated users

### 👥 Pelanggan (Customers)
- **List:** `/customers` (All customers)
- **Create:** `/customers/create` (New customer)
- **View:** `/customers/{id}` (Customer details)
- **Edit:** `/customers/{id}/edit` (Update customer)
- **Delete:** Delete from list
- **Access:** Admin, Manager, Cashier

### 🏷️ Kupon & Loyalty (Coupons)
- **List:** `/coupons` (All coupons)
- **Create:** `/coupons/create` (New coupon)
- **Edit:** `/coupons/{id}/edit` (Update coupon)
- **Delete:** Delete from list
- **Access:** Admin, Manager

### 📈 Laporan (Reports)
- **Dashboard:** `/reports` (Charts & statistics)
- **Sales:** `/reports/sales` (Sales analysis)
- **Inventory:** `/reports/inventory` (Stock analysis)
- **P&L:** `/reports/profit-loss` (Profit & Loss)
- **Customers:** `/reports/customers` (Customer analytics)
- **Export:** Export to Excel/PDF
- **Access:** Admin, Manager

### 👔 Karyawan (Employees)
- **List:** `/employees` (All employees)
- **Create:** `/employees/create` (New employee)
- **Edit:** `/employees/{id}/edit` (Update employee)
- **Delete:** Delete from list
- **Access:** Admin only

### ⚙️ Pengaturan (Settings)
- **URL:** `/settings`
- **Features:** Backup, Restore, System info
- **Access:** Admin only

---

## API Endpoints

```
GET  /api/dashboard/stats      - Dashboard statistics
GET  /api/products/search      - Product search
GET  /api/inventory/alerts     - Inventory alerts
GET  /api/products/{id}        - Product details
```

---

## Common Tasks

### Add New Product
1. Click "Produk" in sidebar
2. Click "Tambah Produk Baru" button
3. Fill form with product details
4. Upload product image (optional)
5. Click "Simpan"

### Create Sale (POS)
1. Click "Kasir" in sidebar
2. Select product category
3. Click products to add to cart
4. Adjust quantity with +/- buttons
5. Select customer (optional)
6. Choose payment method
7. Click "Proses Pembayaran"

### Manage Customers
1. Click "Pelanggan" in sidebar
2. Click "Pelanggan Baru" to add
3. Fill customer form
4. Click "Simpan"

### View Reports
1. Click "Laporan" in sidebar
2. Select report type
3. View charts and statistics
4. Click export to download

### Manage Inventory
1. Click "Inventory" in sidebar
2. View stock levels and tabs
3. Click "Adjustment" for stock changes
4. Or click "Audit" for physical inventory

---

## Database Info

### Default Database
- **Host:** localhost
- **Database:** apms_db (or from .env)
- **User:** root (or from .env)
- **Password:** (from .env)

### Sample Data
- **Users:** 2 (admin, cashier)
- **Customers:** 2 (wholesale, retail)
- **Categories:** 4 (Premium, Regular, Refill, Bundle)

---

## Important Files

### Configuration
- `.env` - Environment variables
- `config/app.php` - App configuration
- `routes/web.php` - Web routes

### Controllers
- `app/Http/Controllers/ProductController.php`
- `app/Http/Controllers/CustomerController.php`
- `app/Http/Controllers/TransactionController.php`
- etc.

### Views
- `resources/views/products/` - Product views
- `resources/views/customers/` - Customer views
- `resources/views/transactions/` - Transaction views
- etc.

### Database
- `database/migrations/` - Database migrations
- `database/seeders/DatabaseSeeder.php` - Seed data

---

## Development Commands

### Start Server
```bash
php artisan serve
```

### Run Migrations
```bash
php artisan migrate
```

### Seed Database
```bash
php artisan db:seed
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Build Assets
```bash
npm run build
```

### Watch Assets (Development)
```bash
npm run dev
```

---

## Troubleshooting

### Server Won't Start
```bash
php artisan serve --port=8000
# Check if port is already in use
# Kill process: netstat -ano | findstr :8000
# taskkill /PID [PID] /F
```

### Database Connection Error
```
Check .env file for correct database credentials
Run: php artisan migrate --fresh
```

### Assets Not Loading
```bash
npm install
npm run build
php artisan storage:link
```

### Permission Denied
```
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## Form Validation Rules

### Products
- **Name:** Required, string, max 255
- **Category:** Required, exists in categories
- **Price:** Required, numeric
- **Size:** Required, string
- **Unit:** Required, in (ml, gr, pcs, liter)
- **Image:** Optional, image, max 2MB

### Customers
- **Name:** Required, string, max 255
- **Phone:** Required, string, unique
- **Email:** Optional, email, unique
- **Type:** Required, in (retail, wholesale, vip)

### Transactions
- **Items:** Required, at least 1
- **Customer:** Optional
- **Payment Method:** Required
- **Total Amount:** Calculated

---

## Feature Matrix

| Feature | Admin | Manager | Cashier |
|---------|-------|---------|---------|
| Products | ✅ | ✅ | ❌ |
| Inventory | ✅ | ✅ | ❌ |
| Customers | ✅ | ✅ | ✅ |
| Transactions | ✅ | ✅ | ✅ |
| Reports | ✅ | ✅ | ❌ |
| Coupons | ✅ | ✅ | ❌ |
| Employees | ✅ | ❌ | ❌ |
| Settings | ✅ | ❌ | ❌ |

---

## Support

For issues or questions:
1. Check documentation in project files
2. Review database schema
3. Check logs: `storage/logs/laravel.log`
4. Review validation error messages

---

**Last Updated:** 21 Januari 2026  
**Version:** 1.0.0  
**Status:** ✅ Production Ready
