# 🎯 APMS - Quick Start Guide

## ⚡ 30 Detik Setup

```bash
cd d:\systemtoko\APMS
php artisan serve --port=8000
# Open http://localhost:8000
```

## 🔑 Login Credentials

```
Email: admin@apms.local
Password: password
```

---

## 📊 Dashboard Features

**Real-time Stats:**
- Sales today & this month
- Product count
- Customer count
- Low stock alerts
- Revenue analysis

**Navigation:**
- Products → Manage inventory & pricing
- Transactions → POS system
- Customers → CRM features
- Coupons → Create promotions
- Reports → Analytics & exports
- Employees → Team management
- Settings → Backup & restore

---

## 🛒 Using The POS System

1. Go to **Transactions** → **New Transaction**
2. Select customer (or create new)
3. Search & add products
4. Set quantities & check prices
5. Apply coupon if available
6. Select payment method
7. Complete transaction
8. Print invoice

---

## 📦 Inventory Management

**Check Stock:**
1. Go to **Inventory**
2. View all products with current stock
3. See low stock & expiry alerts

**Adjust Stock:**
- Click adjust button
- Update quantities
- Add notes/reason
- Save

---

## 👥 Customer Management

**Add Customer:**
1. Go to **Customers** → **New Customer**
2. Fill customer info
3. Select type (retail/wholesale)
4. Save

**View History:**
- Click customer → see all transactions
- Check loyalty points (1 point per 10k)
- View total spent

---

## 🏷️ Coupon System

**Create Coupon:**
1. Go to **Coupons** → **New Coupon**
2. Set code, type, value
3. Set expiration date
4. Set usage limit
5. Save

**Use in Transaction:**
- Apply during transaction creation
- Auto-calculate discount
- Track usage

---

## 📊 Reports

**Available Reports:**
- Sales (daily/monthly)
- Inventory (current stock)
- Low Stock Items
- Expiry Items
- Profit & Loss
- Customer Analytics
- Employee Performance

**Export:**
- All reports can be exported as PDF
- Use date filters for custom ranges

---

## 👔 Employee Management

**Add Employee:**
1. Go to **Employees** → **New Employee**
2. Set name, email, phone
3. Assign role (Cashier/Manager/Supervisor)
4. Set password
5. Save

**Roles:**
- **Admin**: Full access
- **Manager**: Reports & analytics
- **Cashier**: Sales & inventory only
- **Supervisor**: Manage team & transactions

---

## 🔧 Admin Settings

**Backup Database:**
1. Go to **Settings**
2. Click "Backup Database"
3. Saved to storage/backups/

**Restore Database:**
1. Go to **Settings**
2. Click "Restore Database"
3. Select backup file
4. Confirm

---

## ⚙️ Important Commands

```bash
# Clear cache
php artisan cache:clear

# Clear views
php artisan view:clear

# Clear config
php artisan config:clear

# Rebuild assets
npm run build

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# View all routes
php artisan route:list
```

---

## 📱 Product Features

**Create Product:**
1. Go to **Products** → **New Product**
2. Fill product info (name, price, etc)
3. Select category
4. Set stock levels
5. Upload image (optional)
6. Save

**Print Barcode:**
- Click product → Print Barcode
- Use for inventory tracking

---

## 💾 Database Info

**Database Name:** systemasharparfum
**Tables:** 15
**Users:** 2 (admin + cashier)
**Customers:** 2 sample (wholesale + retail)

---

## 🐛 Troubleshooting

**Server won't start:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan serve --port=8000
```

**Database error:**
```bash
# Check .env file
# Verify MySQL is running
# Run migrations
php artisan migrate --force
```

**Assets not loading:**
```bash
npm install
npm run build
php artisan optimize:clear
```

---

## 📞 Support

**Common Issues:**
- Missing assets → Run `npm run build`
- Database error → Check `.env` file
- Login fails → Verify user in database

**Quick Debug:**
```bash
php artisan list              # Show all commands
php artisan route:list        # Show all routes
php artisan db:show           # Show DB info
```

---

## ✨ Pro Tips

1. **Keyboard Shortcuts:**
   - Use Tab to navigate forms
   - Enter to submit forms
   - Esc to close modals

2. **Performance:**
   - Cache is cleared regularly
   - Database indexes optimized
   - Assets minified for production

3. **Data Safety:**
   - Always backup before updates
   - Test in staging first
   - Keep .env secure

4. **Best Practices:**
   - Use strong passwords
   - Regular backups
   - Monitor inventory weekly
   - Review reports monthly

---

## 🚀 Deployment Checklist

Before going live:

- [ ] Set `APP_ENV=production` in .env
- [ ] Set `APP_DEBUG=false` in .env
- [ ] Run `php artisan config:cache`
- [ ] Run `npm run build`
- [ ] Test all features
- [ ] Backup database
- [ ] Setup error monitoring
- [ ] Configure email settings
- [ ] Test backup/restore
- [ ] Document access procedures

---

## 📚 Documentation Files

In project root:
- `PERBAIKAN_SUMMARY.md` - Complete summary of all fixes
- `SETUP_GUIDE.md` - Detailed setup & installation
- `TECHNICAL_DOCS.md` - Technical architecture

---

## 🎯 Project Stats

```
✅ Controllers:      9 (all working)
✅ Models:          12 (all with relationships)
✅ Routes:          50+ (all registered)
✅ Views:           20+ (all complete)
✅ Database Tables: 15 (all migrated)
✅ Frontend Assets: Compiled & minified
✅ Security:        HTTPS ready
✅ Performance:     Optimized
```

---

## 🏆 Status

**PROJECT STATUS: PRODUCTION READY ✅**

All components integrated, tested, and ready for use.

---

*Last Updated: 21 January 2026*
*Version: 1.0.0*
*License: MIT*
