# APMS - Automatic Perfume Management System

Sistem Manajemen Parfum Otomatis untuk toko ritel dan grosir dengan fitur lengkap.

## 🚀 Fitur Utama

- **Dashboard**: Visualisasi real-time penjualan, stok, dan performa
- **Manajemen Produk**: CRUD produk dengan kategori, harga beli/jual, dan barcode
- **Manajemen Stok**: Inventory tracking, stok minimum, alert kadaluarsa
- **Transaksi Penjualan**: POS system dengan support multiple payment methods
- **Pelanggan**: CRM dengan kategorisasi (retail/wholesale) dan loyalty points
- **Kupon & Promo**: Diskon, bonus, cashback dengan management terintegrasi
- **Laporan & Analitik**: Sales report, inventory analysis, profit/loss, customer analytics
- **Karyawan**: Management team dengan role-based access control
- **Backup & Restore**: Database backup otomatis dan restore functionality
- **Responsive UI**: Bootstrap + Tailwind CSS design

## 📋 Requirement

- PHP 8.2+
- MySQL 5.7+
- Node.js 14+
- Composer

## 🔧 Instalasi

### 1. Clone Repository
```bash
cd d:\systemtoko\APMS
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file dengan database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=systemasharparfum
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6. Build Assets
```bash
npm run build
```

### 7. Generate Storage Link
```bash
php artisan storage:link
```

## 🏃 Running Application

### Development Server
```bash
php artisan serve --port=8000
npm run dev  # On another terminal for Vite
```

Server akan berjalan di: `http://localhost:8000`

### Production Build
```bash
npm run build
```

## 👥 Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@apms.local | password | Admin |
| cashier@apms.local | password | Cashier |

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/         # Request handlers
│   ├── Models/                   # Eloquent models
│   └── Providers/
├── database/
│   ├── migrations/               # Database schemas
│   └── seeders/                  # Sample data
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # Tailwind + custom styles
│   └── js/                       # JavaScript assets
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
└── storage/
    └── logs/                     # Application logs
```

## 🛣️ Routes

### Authentication Routes
- `GET /login` - Login page
- `POST /login` - Process login
- `POST /logout` - Logout
- `GET /register` - Register page (if enabled)

### Dashboard
- `GET /` - Main dashboard

### Products
- `GET /products` - List products
- `GET /products/create` - Create form
- `POST /products` - Store product
- `GET /products/{id}` - View detail
- `GET /products/{id}/edit` - Edit form
- `PUT /products/{id}` - Update product
- `DELETE /products/{id}` - Delete product
- `GET /products/{id}/barcode` - Print barcode

### Transactions (POS)
- `GET /transactions` - List transactions
- `GET /transactions/create` - New transaction
- `POST /transactions` - Store transaction
- `GET /transactions/{id}` - View invoice
- `GET /transactions/{id}/print` - Print invoice

### Inventory
- `GET /inventory` - Inventory list
- `POST /inventory/adjust` - Adjust stock
- `POST /inventory/audit` - Audit inventory

### Customers
- `GET /customers` - List customers
- `POST /customers` - Create customer
- `PUT /customers/{id}` - Update customer
- `DELETE /customers/{id}` - Delete customer

### Coupons
- `GET /coupons` - List coupons
- `POST /coupons` - Create coupon
- `PUT /coupons/{id}` - Update coupon
- `DELETE /coupons/{id}` - Delete coupon
- `POST /coupons/{id}/redeem` - Redeem coupon

### Reports
- `GET /reports` - Report dashboard
- `GET /reports/sales` - Sales report
- `GET /reports/inventory` - Inventory report
- `GET /reports/profit-loss` - P&L report
- `GET /reports/customers` - Customer analytics
- `GET /reports/export/sales` - Export sales

### Employees
- `GET /employees` - List employees
- `POST /employees` - Create employee
- `PUT /employees/{id}` - Update employee
- `DELETE /employees/{id}` - Delete employee
- `POST /employees/{id}/attendance` - Record attendance

### Settings
- `GET /settings` - Settings page
- `POST /settings/backup` - Backup database
- `POST /settings/restore` - Restore database

## 🗄️ Database Tables

### Core Tables
- `users` - Users/Employees
- `customers` - Customer data
- `products` - Product catalog
- `product_categories` - Product categories
- `suppliers` - Supplier information
- `inventories` - Stock tracking

### Transaction Tables
- `transactions` - Sales transactions
- `transaction_details` - Transaction items

### Promo Tables
- `coupons` - Coupon/Promotion codes

### Operational Tables
- `expenses` - Expense records
- `expense_categories` - Expense categories

## 🔐 Security Features

- Laravel authentication & authorization
- Password hashing with bcrypt
- CSRF protection
- SQL injection prevention (Eloquent ORM)
- XSS protection
- Role-based access control (Admin, Manager, Cashier)

## 📊 Models & Relationships

### User
- hasMany: Transaction
- hasMany: Attendance (implied)

### Customer
- hasMany: Transaction
- hasMany: Coupon

### Product
- belongsTo: ProductCategory
- hasMany: Inventory
- hasMany: TransactionDetail

### Transaction
- belongsTo: Customer
- belongsTo: User
- hasMany: TransactionDetail

### TransactionDetail
- belongsTo: Transaction
- belongsTo: Product

### Inventory
- belongsTo: Product

### Coupon
- belongsTo: Customer (nullable)

### Expense
- belongsTo: ExpenseCategory

## 🎨 Frontend Technologies

- **Framework**: Bootstrap 5 + Tailwind CSS
- **JavaScript**: Alpine.js, jQuery
- **UI Components**: SweetAlert2, Select2, DataTables
- **Charts**: Chart.js
- **Icons**: Font Awesome 6
- **Build Tool**: Vite

## 📝 Available Commands

```bash
# Development
php artisan serve
npm run dev

# Production
npm run build

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Maintenance
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan optimize:clear

# Backup
php artisan backup:run

# Monitoring
php artisan pail
php artisan queue:listen
```

## 🐛 Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check `.env` database credentials
- Ensure database exists: `CREATE DATABASE systemasharparfum;`

### Missing Assets
```bash
npm install
npm run build
php artisan optimize:clear
```

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
```

### Cache Issues
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## 📞 Support

Untuk bantuan atau pertanyaan, silakan hubungi tim development.

## 📄 License

Laravel framework adalah open-source software yang dilisensikan di bawah lisensi MIT.

---

**Status**: ✅ Production Ready
**Last Updated**: 21 January 2026
