<p align="center">
  <img src="https://img.shields.io/badge/APMS-Official%20Release-2c7be5?style=for-the-badge&labelColor=1a1a2e" alt="APMS Official">
  <img src="https://img.shields.io/badge/Version-1.0.0-00C851?style=for-the-badge&labelColor=1a1a2e" alt="Version">
  <br>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL">
</p>

<h1 align="center">
  💎 APMS — Ashar Parfum Management System
</h1>

<p align="center">
  <strong>The Ultimate Enterprise Point-of-Sale & Business Intelligence Platform</strong><br>
  <sub>Powering Ashar Grosir Parfum Bekasi · Made in Indonesia · Built for Scale</sub>
</p>

<p align="center">
  <a href="#-overview">Overview</a> •
  <a href="#-core-modules">Core Modules</a> •
  <a href="#-apms-copilot-ai">APMS Copilot (AI)</a> •
  <a href="#-system-architecture">Architecture</a> •
  <a href="#-security">Security</a> •
  <a href="#-quick-start">Quick Start</a>
</p>

---

## 📌 Overview

**APMS** (Ashar Parfum Management System) is the proprietary enterprise management platform of **Ashar Grosir Parfum Bekasi** — Indonesia's premier fragrance wholesale and retail distributor.

APMS is not a generic POS system. It is a purpose-built, high-performance ecosystem designed to solve the specific operational complexities of a large-scale perfume business. The system unifies retail point-of-sale, wholesale distribution, AI-driven business intelligence, automated inventory deductions, employee accountability loops, and full financial reporting into a single command center.

> _"We don't just sell perfume. We orchestrate fragrance commerce at scale."_

---

## ✨ Core Modules

### 🏪 Point-of-Sale (POS) Engine

- **Dual-Mode Commerce:** Unified interface for Retail (Eceran) and Wholesale (Grosir).
- **Automated Promotional Logic:** Auto-allocates bonus products based on purchase volume (e.g., free 20ml standard for every 100ml premium).
- **Loyalty & Discount Engine:** Supports dynamically generated coupons, percentage discounts, and fixed cuts.
- **Financial Flexibility:** Supports PPN (10-11%) toggles, multi-payment methods (Cash, GoPay, OVO, Dana, Transfer), and Debt/Kas Bon management.

### 📦 Supply Chain & Inventory

- **Real-Time Deductions:** Warehouse inventory automatically syncs and deducts upon retail sale or wholesale order confirmation.
- **Stock Audit & Discrepancy Tracking:** Complete opname/audit module with physical vs. system variance detection.
- **Low-Stock Intelligence:** Dynamic product statuses (`in_stock`, `low_stock`, `out_of_stock`) with visual dashboard alerts when crossing minimum stock thresholds.
- **Wholesale Order Lifecycle:** Track wholesale orders from `Pending` → `On Progress` → `Ready to Ship` → `Completed`.

### 👥 Employee & Accountability Loop

- **Strict Shift Management:** Financial integrity ensured via mandatory Open/Close Shift protocols.
- **Digital Cash Reconciliation:** Cashiers must upload photographic evidence of manual ledgers at end-of-shift. Owners perform digital review/ACC.
- **Role-Based Access (RBAC):** Military-grade isolation between Owner, Admin, and Kasir roles across all endpoints.
- **Attendance Logging:** One-click Check-in/Check-out for automated timesheets.

### 📊 Business Intelligence & Reporting

- **P&L Generation:** Real-time Gross vs. Net Profit calculus, auto-deducting logged operating expenses.
- **Data Visualization:** Interactive Chart.js dashboards showing monthly trajectories, sales composition, and top-performing products.
- **Granular Export:** Bank-grade PDF & CSV exportation for daily, weekly, and monthly ledgers.

---

## 🤖 APMS Copilot (Artificial Intelligence)

The crown jewel of APMS is the **Offline APMS Copilot** — a locally-hosted, rule-based Expert System deeply integrated into the database, guaranteeing 100% data privacy with zero third-party API dependencies.

![APMS Copilot](https://img.shields.io/badge/Status-100%25%20Offline-success?style=flat-square) ![Navigation](https://img.shields.io/badge/Deep%20Navigation-Enabled-blue?style=flat-square)

**Capabilities:**

- **Live Financial Querying:** _"Berapa transaksi bulan ini?"_ (Fetches real-time revenue and count).
- **Instant Stock Scans:** _"Mana produk yang kritis?"_ or _"Cek stok baccarat"_.
- **Deep Routing Navigation:** Understands 17+ core intents and directly renders clickable HTML action buttons linking to the exact administrative page needed.
- **Integrated Knowledge Base:** Contains a 30-topic neural manual covering system troubleshooting, printer setups, how-to guides, and operational SOPs.
- **Floating UI Widgets:** High-density, visually stunning Quick Action Chips and animated chat logic.

---

## 🏗️ System Architecture

APMS is built on a modern, decoupled monolithic PHP infrastructure designed for 99.9% uptime and extreme data longevity.

```mermaid
graph TD
    Client[📱 Branch POS / Admin iPad] -->|HTTPS| Web[🌐 Nginx / Apache]
    Web --> App[⚙️ Laravel 12 Application]
    App --> Auth[🛡️ Session Auth & RBAC]
    App --> AI[🧠 Offline Copilot Engine]
    App --> Controllers[🏢 Business Logic]
    Controllers --> ORM[🔗 Eloquent ORM]
    ORM --> DB[(🗄️ MySQL 8.0)]

    style DB fill:#4479A1,color:#fff
    style App fill:#FF2D20,color:#fff
    style AI fill:#00C851,color:#fff
```

### Database Integrity Strategy

- **`BIGINT` Primary Keys:** Supports 9.2 quintillion records, zero re-keying needed.
- **Soft Deletes:** Immutable historical states; no record is ever permanently destroyed.
- **`DECIMAL(15,2)` Financials:** Absolutely zero floating-point computation errors for RP currency.
- **ACID Compliant Transactions:** `DB::beginTransaction()` wrappers on all critical POS and Wholesale actions to prevent race conditions.

---

## � System Diagrams

### 1. Use Case Diagram

> Depicts the primary actors and their core interactions with the platform.

```mermaid
graph LR
    Owner(("👑 Owner"))
    Admin(("🧑‍💼 Admin"))
    Kasir(("🧑‍🔧 Kasir"))
    System["⚙️ APMS System / AI"]

    Owner --> UC1[View Gross/Net P&L]
    Owner --> UC2[Verify Shift Closing Photos]
    Owner --> UC3[Global Analytics]

    Admin --> UC4[Manage Restocks & Products]
    Admin --> UC5[Process Wholesale Orders]
    Admin --> UC6[Manage Customer Database]

    Kasir --> UC7[Process Retail POS]
    Kasir --> UC8[Open & Close Shift]
    Kasir --> UC9[Record Attendance]

    UC1 & UC2 & UC3 & UC4 & UC5 & UC6 & UC7 & UC8 & UC9 --> System

    style Owner fill:#f0c040,stroke:#c09020,color:#000
    style Admin fill:#2c7be5,stroke:#1a5bbf,color:#fff
    style Kasir fill:#00b894,stroke:#009070,color:#fff
```

### 2. Sequence Diagram (Retail POS Checkout)

> The data lifecycle when a Cashier performs a standard retail transaction.

```mermaid
sequenceDiagram
    actor Kasir
    participant POS as 🖥️ POS View
    participant App as ⚙️ Controllers
    participant DB as 🗄️ Database

    Kasir->>POS: Select Product (Barcode/Search)
    POS->>App: Validate Cart & Discounts
    App-->>POS: Return calculations (auto-bonus logic)
    Kasir->>POS: Confirm Payment (Cash/E-Wallet)
    POS->>App: POST /transactions

    activate App
    App->>DB: BEGIN TRANSACTION
    App->>DB: INSERT transactions (invoice generated)
    App->>DB: INSERT transaction_details
    App->>DB: UPDATE inventories (stock_out -= qty)
    App->>DB: COMMIT
    deactivate App

    App-->>POS: 200 OK
    POS-->>Kasir: Render Receipt & Print
```

### 3. Class Diagram / ERD (Core Entities)

> Core data models powering the APMS application.

```mermaid
erDiagram
    TRANSACTIONS ||--|{ TRANSACTION_DETAILS : "contains"
    PRODUCTS ||--|{ TRANSACTION_DETAILS : "included_in"
    PRODUCTS ||--|| INVENTORIES : "tracked_in"
    PRODUCTS }o--|| PRODUCT_CATEGORIES : "belongs_to"
    USERS ||--o{ TRANSACTIONS : "processes"
    USERS ||--o{ SHIFTS : "owns"
    SHIFTS ||--o{ TRANSACTIONS : "scopes"
    CUSTOMERS ||--o{ TRANSACTIONS : "makes"

    PRODUCTS {
        bigint id PK
        string name
        decimal selling_price
        decimal wholesale_price
    }
    INVENTORIES {
        bigint product_id FK
        int current_stock
        int minimum_stock
    }
    TRANSACTIONS {
        bigint id PK
        string invoice_number
        decimal total_amount
    }
```

### 4. Operational Workflow (Accountability Loop)

> The mandatory daily shift lifecycle ensuring strict financial integrity over the long term.

```mermaid
graph TD
    A[🔐 Auth Login] --> B[📂 Open Shift]
    B --> C[💵 Input Initial Cash]
    C --> D[🛒 Process Retail Sales]
    D --> E{End of Shift?}
    E -- Yes --> F[📸 Photograph Manual Ledger]
    F --> G[🔒 Close Shift & Upload Photo]
    G --> H{Owner Review}
    H -- ✅ ACC --> I[Shift Verified]
    H -- ❌ Reject --> J[Investigate Discrepancy]

    style A fill:#2c7be5,color:#fff
    style I fill:#00C851,color:#fff
    style J fill:#FF4444,color:#fff
```

---

## �🚀 Quick Start

### Prerequisites

- **PHP** ≥ 8.2 (Extensions: pdo_mysql, gd, zip)
- **Composer** 2.x
- **MySQL** ≥ 8.0

### Local Installation

```bash
# 1. Clone Repo
git clone https://github.com/ashar-parfum/APMS.git
cd APMS

# 2. Install Packages
composer install --optimize-autoloader

# 3. Environment Setup
cp .env.example .env
php artisan key:generate

# 4. Configure Database in .env
# DB_DATABASE=apms_db

# 5. Migrate & Seed
php artisan migrate --seed --seeder=ComprehensiveSeeder

# 6. Storage Link & Run
php artisan storage:link
php artisan serve
```

### Default Credentials (Post-Seeding)

| Role      | Email           | Password |
| --------- | --------------- | -------- |
| **Owner** | owner@ashar.com | password |
| **Admin** | admin@ashar.com | password |
| **Kasir** | kasir@ashar.com | password |

---

## 🛡️ Security Posture

- **CSRF Mitigation:** Enforced token validation across all mutable verbs.
- **XSS Prevention:** Auto-escaped rendering via Laravel Blade engine.
- **SQLi Protection:** Strict parameterized binding via PDO.
- **Data Encapsulation:** Critical settings (like Backup & Restore actions) are restricted explicitly to the `owner` tier.
- **Mass Assignment:** Hardened Models using guarded/fillable attributes.

---

## 📞 Support & Engineering

APMS is developed in-house to secure the technological future of the Ashar Grosir Parfum Group.

**Ashar Grosir Parfum Bekasi**  
📍 Bekasi, West Java, Indonesia 🇮🇩  
🌐 [ashargrosirparfum.com](http://www.ashargrosirparfum.com)

**Technical Architect:**  
Wisnu Alfian Nur Ashar  
✉️ eng@asharparfum.com

---

<p align="center">
  <sub>Copyright © 2024–2026 Ashar Grosir Parfum Group. All Rights Reserved.</sub><br>
  <sub>APMS is proprietary software. Unauthorized reproduction, modification, or distribution is strictly prohibited.</sub>
</p>
