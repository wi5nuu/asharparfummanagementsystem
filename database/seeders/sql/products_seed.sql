-- ============================================================
-- IMPORT PRODUK PARFUM - ASHAR GROSIR PARFUM
-- Data diambil dari stok per Selasa 6 Januari 2026
-- Database: systemasharparfum
-- Jalankan di: phpMyAdmin > pilih database > Tab SQL
--
-- CATATAN: Harga beli & jual adalah ESTIMASI.
-- Update kolom purchase_price & selling_price sesuai harga asli Anda.
-- Stok = kolom "Total" dari tabel stok Anda (satuan: ml)
-- ============================================================

-- Pastikan kategori ada
INSERT IGNORE INTO `product_categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Premium', 'Parfum Gold/Premium', NOW(), NOW()),
(2, 'Regular', 'Parfum Regular (Uv)', NOW(), NOW()),
(3, 'New', 'Produk Baru', NOW(), NOW());

-- ============================================================
-- BAGIAN A
-- ============================================================
INSERT INTO `products` (`name`, `barcode`, `product_category_id`, `brand`, `size`, `unit`, `purchase_price`, `selling_price`, `wholesale_price`, `initial_stock`, `minimum_stock`, `is_active`, `track_inventory`, `created_at`, `updated_at`) VALUES
('Aigner black (Uv)',           'A-001', 2, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  359, 10, 1, 1, NOW(), NOW()),
('Aigner black (Gold)',         'A-002', 1, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  217, 10, 1, 1, NOW(), NOW()),
('Aigner debut',                'A-003', 2, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  217, 10, 1, 1, NOW(), NOW()),
('Aigner starlight',            'A-004', 2, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  242, 10, 1, 1, NOW(), NOW()),
('Aigner blue Emotion (Uv)',    'A-005', 2, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  573, 10, 1, 1, NOW(), NOW()),
('Aigner Blue Emotion (Gold New)','A-006',1,'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  173, 10, 1, 1, NOW(), NOW()),
('Aigner Blue (Gold)',          'A-007', 1, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,    0, 10, 1, 1, NOW(), NOW()),
('Aigner feminim',              'A-008', 2, 'Aigner',         '30ml', 'ml', 8000, 15000, 13000,  152, 10, 1, 1, NOW(), NOW()),
('Ashanti',                     'A-009', 2, 'Lokal',          '30ml', 'ml', 6000, 12000, 10000,  179, 10, 1, 1, NOW(), NOW()),
('Agnes Monica',                'A-010', 2, 'Lokal',          '30ml', 'ml', 6000, 12000, 10000,  110, 10, 1, 1, NOW(), NOW()),
('Axe coklat',                  'A-011', 2, 'Axe',            '30ml', 'ml', 6000, 12000, 10000,  305, 10, 1, 1, NOW(), NOW()),
('Axe Alaska',                  'A-012', 2, 'Axe',            '30ml', 'ml', 6000, 12000, 10000,  132, 10, 1, 1, NOW(), NOW()),
('Anasui Dream (Uv)',           'A-013', 2, 'Anna Sui',       '30ml', 'ml', 8000, 15000, 13000,  284, 10, 1, 1, NOW(), NOW()),
('Anasui Dream (Gold)',         'A-014', 1, 'Anna Sui',       '30ml', 'ml', 8000, 15000, 13000,   53, 10, 1, 1, NOW(), NOW()),
('Annasui Pretty',              'A-015', 2, 'Anna Sui',       '30ml', 'ml', 8000, 15000, 13000,  157, 10, 1, 1, NOW(), NOW()),
('Annasui Mellow Yellow',       'A-016', 1, 'Anna Sui',       '30ml', 'ml', 8000, 15000, 13000,  187, 10, 1, 1, NOW(), NOW()),
('Antonio Banderas',            'A-017', 2, 'Antonio Banderas','30ml','ml', 6000, 12000, 10000,    0, 10, 1, 1, NOW(), NOW()),
('Adidas',                      'A-018', 2, 'Adidas',         '30ml', 'ml', 6000, 12000, 10000,  140, 10, 1, 1, NOW(), NOW()),
('Aqua digio man',              'A-019', 2, 'Armani',         '30ml', 'ml', 8000, 15000, 13000,  222, 10, 1, 1, NOW(), NOW()),
('Angle heart',                 'A-020', 2, 'Cacharel',       '30ml', 'ml', 8000, 15000, 13000,  187, 10, 1, 1, NOW(), NOW()),
('Ayu Ting Ting',               'A-021', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  172, 10, 1, 1, NOW(), NOW()),
('Ara wonder rose',             'A-022', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  263, 10, 1, 1, NOW(), NOW()),
('Ariana grande',               'A-023', 2, 'Ariana Grande',  '30ml', 'ml', 8000, 15000, 13000,  194, 10, 1, 1, NOW(), NOW()),
('Apple green',                 'A-024', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  174, 10, 1, 1, NOW(), NOW()),
('Anggur',                      'A-025', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  117, 10, 1, 1, NOW(), NOW()),
('Ariel impuls',                'A-026', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  122, 10, 1, 1, NOW(), NOW()),
('Avril Lavigne',               'A-027', 2, 'Avril Lavigne',  '30ml', 'ml', 7000, 13000, 11000,  508, 10, 1, 1, NOW(), NOW()),
('Avril L Forbidden Rose',      'A-028', 1, 'Avril Lavigne',  '30ml', 'ml', 7000, 13000, 11000,  176, 10, 1, 1, NOW(), NOW()),
('Avril L Wild Rose',           'A-029', 1, 'Avril Lavigne',  '30ml', 'ml', 7000, 13000, 11000,  199, 10, 1, 1, NOW(), NOW()),
('Al Haramain',                 'A-030', 2, 'Al Haramain',    '30ml', 'ml', 8000, 15000, 13000,  173, 10, 1, 1, NOW(), NOW()),
('Alrehab unisex',              'A-031', 2, 'Al Rehab',       '30ml', 'ml', 7000, 13000, 11000,    0, 10, 1, 1, NOW(), NOW()),
('Al rehab red Rose (New)',     'A-032', 3, 'Al Rehab',       '30ml', 'ml', 7000, 13000, 11000,  169, 10, 1, 1, NOW(), NOW()),
('Ajmal Misol',                 'A-033', 2, 'Ajmal',          '30ml', 'ml', 8000, 15000, 13000,   73, 10, 1, 1, NOW(), NOW()),

-- ============================================================
-- BAGIAN B
-- ============================================================
('Burberry London',             'B-034', 2, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  164, 10, 1, 1, NOW(), NOW()),
('Burberry Hero',               'B-035', 2, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  166, 10, 1, 1, NOW(), NOW()),
('Burberry Man (Uv)',           'B-036', 2, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  124, 10, 1, 1, NOW(), NOW()),
('Burberry man (Gold)',         'B-037', 1, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  113, 10, 1, 1, NOW(), NOW()),
('Burbery Woman (Gold New)',    'B-038', 3, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,   21, 10, 1, 1, NOW(), NOW()),
('Burberry Goddes',             'B-039', 2, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  139, 10, 1, 1, NOW(), NOW()),
('Burberry Woman (Gold)',       'B-040', 1, 'Burberry',       '30ml', 'ml', 8000, 15000, 13000,  140, 10, 1, 1, NOW(), NOW()),
('Bulgari Omnia crystal',       'B-041', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  135, 10, 1, 1, NOW(), NOW()),
('Bulgari Omnia amnesty',       'B-042', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  275, 10, 1, 1, NOW(), NOW()),
('Bulgari Omnia Amnesthy (Gold)','B-043',1, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  113, 10, 1, 1, NOW(), NOW()),
('Bulgari Omnia Pink Shapire',  'B-044', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  263, 10, 1, 1, NOW(), NOW()),
('Bulgari extreme',             'B-045', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000, 1145, 10, 1, 1, NOW(), NOW()),
('Bulgari extreme sport',       'B-046', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  107, 10, 1, 1, NOW(), NOW()),
('Bulgari esential',            'B-047', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,   74, 10, 1, 1, NOW(), NOW()),
('Bulgari Aqua (Uv)',           'B-048', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  261, 10, 1, 1, NOW(), NOW()),
('Bulgari Aqua (Gold)',         'B-049', 1, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  166, 10, 1, 1, NOW(), NOW()),
('Bulgari Aqua Pour Home',      'B-050', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  109, 10, 1, 1, NOW(), NOW()),
('Bulgari White',               'B-051', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  171, 10, 1, 1, NOW(), NOW()),
('Bulgari Black',               'B-052', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,   58, 10, 1, 1, NOW(), NOW()),
('Bulgari man',                 'B-053', 2, 'Bvlgari',        '30ml', 'ml', 8000, 15000, 13000,  177, 10, 1, 1, NOW(), NOW()),
('Blue de chanel (New)',        'B-054', 3, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  121, 10, 1, 1, NOW(), NOW()),
('Biyonce (Uv)',                'B-055', 2, 'Beyonce',        '30ml', 'ml', 7000, 13000, 11000,  120, 10, 1, 1, NOW(), NOW()),
('Beyonce Noir (Gold)',         'B-056', 1, 'Beyonce',        '30ml', 'ml', 7000, 13000, 11000,  167, 10, 1, 1, NOW(), NOW()),
('Benetton pink',               'B-057', 2, 'Benetton',       '30ml', 'ml', 6000, 12000, 10000,   82, 10, 1, 1, NOW(), NOW()),
('Black musk body shop',        'B-058', 2, 'Body Shop',      '30ml', 'ml', 7000, 13000, 11000,  279, 10, 1, 1, NOW(), NOW()),
('Bubble gum',                  'B-059', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  299, 10, 1, 1, NOW(), NOW()),
('Bbw mad about you',           'B-060', 2, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,  189, 10, 1, 1, NOW(), NOW()),
('Bbw pink Chifon (Gold)',      'B-061', 1, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,  204, 10, 1, 1, NOW(), NOW()),
('Bbw Berry Waffle (New)',      'B-062', 3, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,  161, 10, 1, 1, NOW(), NOW()),
('Bbw A Thousand',              'B-063', 2, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,    0, 10, 1, 1, NOW(), NOW()),
('Bbw Taekwod (New)',           'B-064', 3, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,  163, 10, 1, 1, NOW(), NOW()),
('Bbw Manggo Lagoon (New)',     'B-065', 3, 'BBW',            '30ml', 'ml', 8000, 15000, 13000,   70, 10, 1, 1, NOW(), NOW()),
('Bellagio',                    'B-066', 2, 'Bellagio',       '30ml', 'ml', 6000, 12000, 10000,  228, 10, 1, 1, NOW(), NOW()),
('Baccarat oud',                'B-067', 2, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  321, 10, 1, 1, NOW(), NOW()),
('Baccarat Rouge',              'B-068', 2, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  961, 10, 1, 1, NOW(), NOW()),
('Baccarat red',                'B-069', 2, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  274, 10, 1, 1, NOW(), NOW()),
('Mfk Baccarat rouge (Uv)',     'B-070', 2, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  111, 10, 1, 1, NOW(), NOW()),
('Mfk Baccarat Rouge (Gold)',   'B-071', 1, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  175, 10, 1, 1, NOW(), NOW()),
('Baccarat Kuning',             'B-072', 2, 'MFK',            '30ml', 'ml', 9000, 17000, 15000,  154, 10, 1, 1, NOW(), NOW()),
('Barcelona',                   'B-073', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  369, 10, 1, 1, NOW(), NOW()),
('Britney Spears Fantasi',      'B-074', 2, 'Britney Spears', '30ml', 'ml', 7000, 13000, 11000,  158, 10, 1, 1, NOW(), NOW()),

-- ============================================================
-- BAGIAN C
-- ============================================================
('Chanell choco',               'C-075', 2, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  154, 10, 1, 1, NOW(), NOW()),
('Chanell alur man',            'C-076', 2, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  126, 10, 1, 1, NOW(), NOW()),
('Chanell blue (Uv)',           'C-077', 2, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  115, 10, 1, 1, NOW(), NOW()),
('Chanel blue (Gold)',          'C-078', 1, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  153, 10, 1, 1, NOW(), NOW()),
('Chanell platinum ghoist',     'C-079', 2, 'Chanel',         '30ml', 'ml', 8000, 15000, 13000,  169, 10, 1, 1, NOW(), NOW()),
('Can be crazy (New)',          'C-080', 3, 'Lokal',          '30ml', 'ml', 6000, 12000, 10000,  163, 10, 1, 1, NOW(), NOW()),
('Creed adventus',              'C-081', 2, 'Creed',          '30ml', 'ml', 9000, 17000, 15000,    0, 10, 1, 1, NOW(), NOW()),
('Creed irish',                 'C-082', 2, 'Creed',          '30ml', 'ml', 9000, 17000, 15000,  103, 10, 1, 1, NOW(), NOW()),
('Creation (New)',              'C-083', 3, 'Lokal',          '30ml', 'ml', 6000, 12000, 10000,  181, 10, 1, 1, NOW(), NOW()),
('CK one (Uv)',                 'C-084', 2, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  124, 10, 1, 1, NOW(), NOW()),
('CK one (Gold)',               'C-085', 1, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  196, 10, 1, 1, NOW(), NOW()),
('CK bee',                      'C-086', 2, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  150, 10, 1, 1, NOW(), NOW()),
('CK deffy',                    'C-087', 2, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,   81, 10, 1, 1, NOW(), NOW()),
('Ck my euphoria (New)',        'C-088', 3, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  112, 10, 1, 1, NOW(), NOW()),
('CK enternity man',            'C-089', 2, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  175, 10, 1, 1, NOW(), NOW()),
('CK enternity woman',          'C-090', 2, 'Calvin Klein',   '30ml', 'ml', 7000, 13000, 11000,  100, 10, 1, 1, NOW(), NOW()),

-- ============================================================
-- BAGIAN D
-- ============================================================
('Delina la rose',              'D-121', 2, 'Parfums de Marie', '30ml','ml', 9000, 17000, 15000,  639, 10, 1, 1, NOW(), NOW()),
('Dunhil Alfred Desire Blue',   'D-122', 1, 'Dunhill',        '30ml', 'ml', 8000, 15000, 13000,  197, 10, 1, 1, NOW(), NOW()),
('Dior sauvage',                'D-123', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  218, 10, 1, 1, NOW(), NOW()),
('Dior sauvage elixir',         'D-124', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  188, 10, 1, 1, NOW(), NOW()),
('Dior jadore',                 'D-125', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  378, 10, 1, 1, NOW(), NOW()),
('Dior poison',                 'D-126', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  101, 10, 1, 1, NOW(), NOW()),
('Dior Poison (Gold)',          'D-127', 1, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,   79, 10, 1, 1, NOW(), NOW()),
('Dior jadore (Gold)',          'D-128', 1, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  213, 10, 1, 1, NOW(), NOW()),
('Dior addict woman',           'D-129', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,  200, 10, 1, 1, NOW(), NOW()),
('Dior toba color (New)',       'D-130', 3, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,    7, 10, 1, 1, NOW(), NOW()),
('Miss Dior blooming (Uv)',     'D-131', 2, 'Dior',           '30ml', 'ml', 9000, 17000, 15000,   92, 10, 1, 1, NOW(), NOW()),
('Dakar',                       'D-132', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  126, 10, 1, 1, NOW(), NOW()),
('Dalal',                       'D-133', 2, 'Lokal',          '30ml', 'ml', 5000, 10000,  8500,  135, 10, 1, 1, NOW(), NOW()),
('Davidof Aqua man',            'D-134', 2, 'Davidoff',       '30ml', 'ml', 7000, 13000, 11000,    0, 10, 1, 1, NOW(), NOW()),
('Davidof cool water (Uv)',     'D-135', 2, 'Davidoff',       '30ml', 'ml', 7000, 13000, 11000,  353, 10, 1, 1, NOW(), NOW()),
('Davidof Game Woman',          'D-136', 2, 'Davidoff',       '30ml', 'ml', 7000, 13000, 11000,   76, 10, 1, 1, NOW(), NOW()),
('Davidof cool water summer (New)','D-137',3,'Davidoff',      '30ml', 'ml', 7000, 13000, 11000,  214, 10, 1, 1, NOW(), NOW()),
('D&g light blue (Uv)',         'D-138', 2, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  130, 10, 1, 1, NOW(), NOW()),
('D&g Light blue pour home (New)','D-139',3,'D&G',            '30ml', 'ml', 8000, 15000, 13000,  144, 10, 1, 1, NOW(), NOW()),
('D&g imperactive (Uv)',        'D-140', 2, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  428, 10, 1, 1, NOW(), NOW()),
('D&g the only one',            'D-141', 2, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  157, 10, 1, 1, NOW(), NOW()),
('D&g king for man',            'D-142', 2, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  242, 10, 1, 1, NOW(), NOW()),
('D&g Imperatif (Gold)',        'D-143', 1, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  111, 10, 1, 1, NOW(), NOW()),
('D&g Light blue (Gold)',       'D-144', 1, 'D&G',            '30ml', 'ml', 8000, 15000, 13000,  161, 10, 1, 1, NOW(), NOW()),
('Davidof cool water man (Gold)','D-145',1, 'Davidoff',       '30ml', 'ml', 7000, 13000, 11000,  217, 10, 1, 1, NOW(), NOW()),
('Diamor woman',                'D-146', 2, 'Lokal',          '30ml', 'ml', 6000, 12000, 10000,  219, 10, 1, 1, NOW(), NOW()),
('Diesel bad (New)',            'D-147', 3, 'Diesel',         '30ml', 'ml', 7000, 13000, 11000,  139, 10, 1, 1, NOW(), NOW()),
('Diesel only the brave',       'D-148', 2, 'Diesel',         '30ml', 'ml', 7000, 13000, 11000,  150, 10, 1, 1, NOW(), NOW()),
('DKNY be delicious',           'D-149', 2, 'DKNY',           '30ml', 'ml', 7000, 13000, 11000,  110, 10, 1, 1, NOW(), NOW()),

-- ============================================================
-- BAGIAN E (Escada)
-- ============================================================
('Escada cherry',               'E-157', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  133, 10, 1, 1, NOW(), NOW()),
('Escada candy love',           'E-290', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  298, 10, 1, 1, NOW(), NOW()),
('Escada sexy grafity',         'E-195', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  181, 10, 1, 1, NOW(), NOW()),
('Escada moon sparkle',         'E-374', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  240, 10, 1, 1, NOW(), NOW()),
('Escada magnetis',             'E-274', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  413, 10, 1, 1, NOW(), NOW()),
('Escada party love (New)',     'E-278', 3, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,   97, 10, 1, 1, NOW(), NOW()),
('Escada Island',               'E-210', 2, 'Escada',         '30ml', 'ml', 7000, 13000, 11000,  147, 10, 1, 1, NOW(), NOW());

-- ============================================================
-- BUAT INVENTORY OTOMATIS UNTUK SEMUA PRODUK BARU
-- (Hanya untuk yang belum punya inventory)
-- ============================================================
INSERT INTO `inventories` (`product_id`, `current_stock`, `minimum_stock`, `stock_in`, `stock_out`, `cost_per_unit`, `date_received`, `created_at`, `updated_at`)
SELECT 
    p.`id`,
    p.`initial_stock`,
    p.`minimum_stock`,
    p.`initial_stock`,
    0,
    p.`purchase_price`,
    CURDATE(),
    NOW(),
    NOW()
FROM `products` p
WHERE p.`id` NOT IN (SELECT DISTINCT `product_id` FROM `inventories`);

-- ============================================================
-- VERIFIKASI
-- ============================================================
SELECT COUNT(*) AS total_produk FROM products;
SELECT COUNT(*) AS total_inventory FROM inventories;
