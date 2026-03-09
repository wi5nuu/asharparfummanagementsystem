-- ============================================================
-- BAGIAN 3: Produk W, X, Y, Z + Produk Bonus 20ml
-- Jalankan SETELAH products_seed.sql dan products_seed_part2.sql
-- ============================================================

-- === BAGIAN W ===
INSERT INTO `products` (`name`, `barcode`, `product_category_id`, `brand`, `size`, `unit`, `purchase_price`, `selling_price`, `wholesale_price`, `initial_stock`, `minimum_stock`, `is_active`, `track_inventory`, `created_at`, `updated_at`) VALUES
('White musk bodyshop',              'W-347', 2, 'Body Shop',       '30ml','ml', 6000,12000,10000,   92, 10,1,1,NOW(),NOW()),
('White rose',                       'W-348', 2, 'Lokal',           '30ml','ml', 5000,10000, 8500,  105, 10,1,1,NOW(),NOW()),

-- === BAGIAN X ===
('Xerjoff Erba Pura (2019) Unisex',  'X-349', 1, 'Xerjoff',        '30ml','ml', 9000,17000,15000,  263, 10,1,1,NOW(),NOW()),

-- === BAGIAN Y ===
('Ysl black opium (Uv)',             'Y-350', 2, 'YSL',            '30ml','ml', 8000,15000,13000,  130, 10,1,1,NOW(),NOW()),
('Ysl Black Opium Floral Shock (Gold)','Y-351',1,'YSL',            '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Ysl Black Opium 2014(Gold)',       'Y-352', 1, 'YSL',            '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Ysl Y Elixir (New)',               'Y-353', 3, 'YSL',            '30ml','ml', 8000,15000,13000,  536, 10,1,1,NOW(),NOW()),
('Ysl libre intens moel (Uv)',       'Y-354', 2, 'YSL',            '30ml','ml', 8000,15000,13000,  554, 10,1,1,NOW(),NOW()),
('Ysl Libre Intens (Gold)',          'Y-355', 1, 'YSL',            '30ml','ml', 8000,15000,13000,  385, 10,1,1,NOW(),NOW()),
('Ysl Moon Paris (Gold)',            'Y-356', 1, 'YSL',            '30ml','ml', 8000,15000,13000,   91, 10,1,1,NOW(),NOW()),
('Ysl moon paris (Uv)',              'Y-357', 2, 'YSL',            '30ml','ml', 8000,15000,13000,  156, 10,1,1,NOW(),NOW()),

-- === BAGIAN Z ===
('Zara red vanilla (Uv)',            'Z-358', 2, 'Zara',           '30ml','ml', 6000,12000,10000,  416, 10,1,1,NOW(),NOW()),
('Zara Red Vanila (Gold)',           'Z-359', 1, 'Zara',           '30ml','ml', 6000,12000,10000,   88, 10,1,1,NOW(),NOW()),
('Zara man',                         'Z-360', 2, 'Zara',           '30ml','ml', 6000,12000,10000,  172, 10,1,1,NOW(),NOW()),
('Zara Orchid',                      'Z-361', 2, 'Zara',           '30ml','ml', 6000,12000,10000,  176, 10,1,1,NOW(),NOW()),
('Zara Oriental',                    'Z-362', 2, 'Zara',           '30ml','ml', 6000,12000,10000,  353, 10,1,1,NOW(),NOW()),
('Zara Woman',                       'Z-362B',2, 'Zara',           '30ml','ml', 6000,12000,10000,  175, 10,1,1,NOW(),NOW()),
('Zara Vibran Leather',              'Z-363', 2, 'Zara',           '30ml','ml', 6000,12000,10000,  272, 10,1,1,NOW(),NOW()),
('Zwitsal baby',                     'Z-364', 2, 'Zwitsal',        '30ml','ml', 5000,10000, 8500,  591, 10,1,1,NOW(),NOW()),
('Zahrat Hawai',                     'Z-365', 2, 'Lokal',          '30ml','ml', 5000,10000, 8500,  450, 10,1,1,NOW(),NOW()),

-- === TAMBAHAN (Angka/Khusus) ===
('1000 bunga',                       'NUM-366',2,'Lokal',          '30ml','ml', 5000,10000, 8500,  139, 10,1,1,NOW(),NOW()),
('2000 bunga (Gold)',                 'NUM-367',1,'Lokal',          '30ml','ml', 5000,10000, 8500,  236, 10,1,1,NOW(),NOW()),
('2000 bunga (Uv)',                   'NUM-368',2,'Lokal',          '30ml','ml', 5000,10000, 8500,   35, 10,1,1,NOW(),NOW()),
('mfk oud silk mood (New)',          'NUM-369',3,'MFK',            '30ml','ml', 9000,17000,15000,  261, 10,1,1,NOW(),NOW()),
('Bulgari Aqua pour home Atlantis',  'NUM-370',2,'Bvlgari',        '30ml','ml', 8000,15000,13000,  219, 10,1,1,NOW(),NOW());

-- ============================================================
-- UPDATE HARGA sesuai tabel harga resmi (untuk produk 30ml Regular/Uv)
-- Premium Original: Rp 63.000  |  Sedang: Rp 50.000  |  Standar: Rp 35.000
-- ============================================================

-- Update harga jual: Kategori Premium (id=1) → Rp 63.000
UPDATE `products` SET `selling_price` = 63000, `purchase_price` = 38000 
WHERE `product_category_id` = 1 AND `size` = '30ml';

-- Update harga jual: Kategori Regular/Sedang (id=2) → Rp 50.000
UPDATE `products` SET `selling_price` = 50000, `purchase_price` = 30000 
WHERE `product_category_id` = 2 AND `size` = '30ml';

-- Update harga jual: Kategori New/Standar (id=3) → Rp 35.000
UPDATE `products` SET `selling_price` = 35000, `purchase_price` = 20000 
WHERE `product_category_id` = 3 AND `size` = '30ml';

-- ============================================================
-- BUAT INVENTORY OTOMATIS (untuk semua produk baru)
-- ============================================================
INSERT INTO `inventories` (`product_id`, `current_stock`, `minimum_stock`, `stock_in`, `stock_out`, `cost_per_unit`, `date_received`, `created_at`, `updated_at`)
SELECT p.`id`, p.`initial_stock`, p.`minimum_stock`, p.`initial_stock`, 0, p.`purchase_price`, CURDATE(), NOW(), NOW()
FROM `products` p
WHERE p.`id` NOT IN (SELECT DISTINCT `product_id` FROM `inventories`);

-- Verifikasi
SELECT COUNT(*) AS total_produk FROM products;
SELECT COUNT(*) AS total_inventory FROM inventories;
SELECT pc.name AS kategori, COUNT(p.id) AS jumlah 
FROM products p 
JOIN product_categories pc ON p.product_category_id = pc.id 
GROUP BY pc.name;
