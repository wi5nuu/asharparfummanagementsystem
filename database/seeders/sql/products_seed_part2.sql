-- ============================================================
-- IMPORT PRODUK - BAGIAN TAMBAHAN (F, G, H, I, J, K, L, M, N, O, P, R, S, T, V)
-- Tambahkan SETELAH menjalankan products_seed.sql (bagian pertama)
-- ============================================================

INSERT INTO `products` (`name`, `barcode`, `product_category_id`, `brand`, `size`, `unit`, `purchase_price`, `selling_price`, `wholesale_price`, `initial_stock`, `minimum_stock`, `is_active`, `track_inventory`, `created_at`, `updated_at`) VALUES

-- === BAGIAN E (lanjutan) ===
('Elizabet arden',                     'E-166', 2, 'Elizabeth Arden',  '30ml','ml', 7000,13000,11000,  176, 10,1,1,NOW(),NOW()),

-- === BAGIAN F ===
('Ferrari black',                      'F-158', 2, 'Ferrari',          '30ml','ml', 7000,13000,11000,  117, 10,1,1,NOW(),NOW()),

-- === BAGIAN G ===
('Gaharu',                             'G-159', 2, 'Lokal',            '30ml','ml', 6000,12000,10000,  342, 10,1,1,NOW(),NOW()),
('Gatsby',                             'G-160', 2, 'Gatsby',           '30ml','ml', 5000,10000, 8500,  105, 10,1,1,NOW(),NOW()),
('Green tea',                          'G-161', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  177, 10,1,1,NOW(),NOW()),
('Garuda',                             'G-162', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  148, 10,1,1,NOW(),NOW()),
('Givenchy gentlement society (New)',  'G-163', 3, 'Givenchy',         '30ml','ml', 8000,15000,13000,  240, 10,1,1,NOW(),NOW()),
('Guest pink',                         'G-164', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  263, 10,1,1,NOW(),NOW()),
('Guess girl summer (New)',            'G-165', 3, 'Guess',            '30ml','ml', 7000,13000,11000,   20, 10,1,1,NOW(),NOW()),
('Giorgio Armani stronger (Uv)',       'G-166', 2, 'Giorgio Armani',   '30ml','ml', 8000,15000,13000,  410, 10,1,1,NOW(),NOW()),
('Giorgio armany stronger with you only (Gold)','G-167',1,'Giorgio Armani','30ml','ml',8000,15000,13000,117,10,1,1,NOW(),NOW()),
('Giorgio Armani may way',             'G-168', 2, 'Giorgio Armani',   '30ml','ml', 8000,15000,13000, 1049, 10,1,1,NOW(),NOW()),
('Gucci bloom (Uv)',                   'G-169', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  309, 10,1,1,NOW(),NOW()),
('Gucci flora (Uv)',                   'G-170', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  146, 10,1,1,NOW(),NOW()),
('Gucci bambo',                        'G-171', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  138, 10,1,1,NOW(),NOW()),
('Gucci rush',                         'G-172', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  101, 10,1,1,NOW(),NOW()),
('Gucci the voice',                    'G-173', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  112, 10,1,1,NOW(),NOW()),
('Gucci envy me',                      'G-174', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  326, 10,1,1,NOW(),NOW()),
('Gucci bloom for women (Gold)',       'G-175', 1, 'Gucci',            '30ml','ml', 8000,15000,13000,  184, 10,1,1,NOW(),NOW()),
('Gucci Garden (Gold)',                'G-176', 1, 'Gucci',            '30ml','ml', 8000,15000,13000,  162, 10,1,1,NOW(),NOW()),
('Gucci guilty woman',                 'G-177', 2, 'Gucci',            '30ml','ml', 8000,15000,13000,  136, 10,1,1,NOW(),NOW()),

-- === BAGIAN H ===
('Hugobos emotion',                    'H-178', 2, 'Hugo Boss',        '30ml','ml', 7000,13000,11000,  160, 10,1,1,NOW(),NOW()),
('Hugobos orange',                     'H-179', 2, 'Hugo Boss',        '30ml','ml', 7000,13000,11000,  178, 10,1,1,NOW(),NOW()),
('Hugobos energiser',                  'H-180', 2, 'Hugo Boss',        '30ml','ml', 7000,13000,11000,  329, 10,1,1,NOW(),NOW()),
('Hugobos army',                       'H-181', 2, 'Hugo Boss',        '30ml','ml', 7000,13000,11000,  128, 10,1,1,NOW(),NOW()),
('Hugobos Bottle',                     'H-182', 2, 'Hugo Boss',        '30ml','ml', 7000,13000,11000,  197, 10,1,1,NOW(),NOW()),
('Hmns Orgsm (Gold)',                  'H-183', 1, 'HMNS',             '30ml','ml', 8000,15000,13000,   43, 10,1,1,NOW(),NOW()),
('Harazuku',                           'H-184', 2, 'Harajuku',         '30ml','ml', 7000,13000,11000,  164, 10,1,1,NOW(),NOW()),
('Hmns Orgsm (Gold New)',              'H-185', 3, 'HMNS',             '30ml','ml', 8000,15000,13000,  194, 10,1,1,NOW(),NOW()),
('Hermes twily',                       'H-186', 2, 'Hermes',           '30ml','ml', 9000,17000,15000,  295, 10,1,1,NOW(),NOW()),

-- === BAGIAN I ===
('Issey Miyake (Uv)',                  'I-187', 2, 'Issey Miyake',     '30ml','ml', 8000,15000,13000,  305, 10,1,1,NOW(),NOW()),
('Issey Miyake(Gold)',                 'I-188', 1, 'Issey Miyake',     '30ml','ml', 8000,15000,13000,   51, 10,1,1,NOW(),NOW()),
('Incanto shine',                      'I-189', 2, 'Salvatore Ferragamo','30ml','ml',7000,13000,11000,  283,10,1,1,NOW(),NOW()),

-- === BAGIAN J ===
('Jaguar black',                       'J-190', 2, 'Jaguar',           '30ml','ml', 6000,12000,10000,   48, 10,1,1,NOW(),NOW()),
('Jaguar blue',                        'J-191', 2, 'Jaguar',           '30ml','ml', 6000,12000,10000,   71, 10,1,1,NOW(),NOW()),
('Jaguar Visions (Gold)',              'J-192', 1, 'Jaguar',           '30ml','ml', 6000,12000,10000,  157, 10,1,1,NOW(),NOW()),
('Jaguar vision (Uv)',                 'J-193', 2, 'Jaguar',           '30ml','ml', 6000,12000,10000,  153, 10,1,1,NOW(),NOW()),
('Jaguar classic blue (New)',          'J-194', 3, 'Jaguar',           '30ml','ml', 6000,12000,10000,  137, 10,1,1,NOW(),NOW()),
('Jessica Parker',                     'J-195', 2, 'Sarah Jessica Parker','30ml','ml',7000,13000,11000,340,10,1,1,NOW(),NOW()),
('Jafaron',                            'J-196', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  305, 10,1,1,NOW(),NOW()),
('Jayrose greyy',                      'J-197', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  561, 10,1,1,NOW(),NOW()),
('Jambu',                              'J-198', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  208, 10,1,1,NOW(),NOW()),
('Jappanes Cherry blossom',            'J-199', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  335, 10,1,1,NOW(),NOW()),
('Juice of Viva',                      'J-200', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  127, 10,1,1,NOW(),NOW()),
('Justin Bieber',                      'J-201', 2, 'Justin Bieber',    '30ml','ml', 7000,13000,11000,  155, 10,1,1,NOW(),NOW()),
('Justin Bieber next girl friend',     'J-202', 1, 'Justin Bieber',    '30ml','ml', 7000,13000,11000,  153, 10,1,1,NOW(),NOW()),
('Jlo still',                          'J-203', 2, 'J.Lo',             '30ml','ml', 7000,13000,11000,  236, 10,1,1,NOW(),NOW()),
('Jlo Still (Gold)',                   'J-204', 1, 'J.Lo',             '30ml','ml', 7000,13000,11000,    0, 10,1,1,NOW(),NOW()),
('Jlo platinum',                       'J-205', 2, 'J.Lo',             '30ml','ml', 7000,13000,11000,  155, 10,1,1,NOW(),NOW()),
('Jadore oud (Uv)',                    'J-206', 2, 'Dior',             '30ml','ml', 8000,15000,13000,  261, 10,1,1,NOW(),NOW()),
('Jomalone sea daffodi (New)',         'J-207', 3, 'Jo Malone',        '30ml','ml', 9000,17000,15000,  112, 10,1,1,NOW(),NOW()),
('Jomalone velvet Rose/oud (Gold)',    'J-208', 1, 'Jo Malone',        '30ml','ml', 9000,17000,15000,  171, 10,1,1,NOW(),NOW()),
('Jomalone English (Gold)',            'J-209', 1, 'Jo Malone',        '30ml','ml', 9000,17000,15000,    0, 10,1,1,NOW(),NOW()),
('Jomalone English pear (Uv)',         'J-210', 2, 'Jo Malone',        '30ml','ml', 9000,17000,15000,  493, 10,1,1,NOW(),NOW()),
('Jomalone Peony',                     'J-211', 2, 'Jo Malone',        '30ml','ml', 9000,17000,15000,  148, 10,1,1,NOW(),NOW()),
('Jomalone Velvet rose&oud',           'J-212', 2, 'Jo Malone',        '30ml','ml', 9000,17000,15000,  220, 10,1,1,NOW(),NOW()),
('Joyphoria Charlotta (New)',          'J-213', 3, 'Lokal',            '30ml','ml', 6000,12000,10000,  160, 10,1,1,NOW(),NOW()),

-- === BAGIAN K ===
('Kylie Jenner (New)',                 'K-214', 3, 'Kylie Jenner',     '30ml','ml', 7000,13000,11000,  188, 10,1,1,NOW(),NOW()),
('Kenzo Bali',                         'K-215', 2, 'Kenzo',            '30ml','ml', 7000,13000,11000,  122, 10,1,1,NOW(),NOW()),
('Kenzo batang (Uv)',                  'K-216', 2, 'Kenzo',            '30ml','ml', 7000,13000,11000,  244, 10,1,1,NOW(),NOW()),
('Kenzo batang (Gold)',                'K-217', 1, 'Kenzo',            '30ml','ml', 7000,13000,11000,  169, 10,1,1,NOW(),NOW()),
('Kenzo daun',                         'K-218', 2, 'Kenzo',            '30ml','ml', 7000,13000,11000,  153, 10,1,1,NOW(),NOW()),
('Kenzo flower ikebana sakura (New)',  'K-219', 3, 'Kenzo',            '30ml','ml', 7000,13000,11000,  500, 10,1,1,NOW(),NOW()),
('Kenzo Flower (Gold)',                'K-220', 1, 'Kenzo',            '30ml','ml', 7000,13000,11000,   88, 10,1,1,NOW(),NOW()),
('Kilian apple brandy on the rock (New)','K-221',3,'Kilian',           '30ml','ml', 9000,17000,15000,  106, 10,1,1,NOW(),NOW()),
('kasturi kijang',                     'K-222', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  165, 10,1,1,NOW(),NOW()),
('Kasturi putih',                      'K-223', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  174, 10,1,1,NOW(),NOW()),

-- === BAGIAN L ===
('Lovely',                             'L-224', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  370, 10,1,1,NOW(),NOW()),
('Lacoste pink',                       'L-225', 2, 'Lacoste',          '30ml','ml', 7000,13000,11000,  100, 10,1,1,NOW(),NOW()),
('Lacoste sport',                      'L-226', 2, 'Lacoste',          '30ml','ml', 7000,13000,11000,  265, 10,1,1,NOW(),NOW()),
('Lacoste esential',                   'L-227', 2, 'Lacoste',          '30ml','ml', 7000,13000,11000,  273, 10,1,1,NOW(),NOW()),
('Lacoste L1212 blanc (New)',          'L-228', 3, 'Lacoste',          '30ml','ml', 7000,13000,11000,  200, 10,1,1,NOW(),NOW()),
('Lancome la vie est Belle (New)',     'L-229', 3, 'Lancome',          '30ml','ml', 8000,15000,13000,  130, 10,1,1,NOW(),NOW()),
('Latafa Amerr Al oud (New)',          'L-230', 3, 'Lattafa',          '30ml','ml', 7000,13000,11000,   72, 10,1,1,NOW(),NOW()),
('Lattafa Oud Mood Man In Black',      'L-231', 2, 'Lattafa',          '30ml','ml', 7000,13000,11000,  149, 10,1,1,NOW(),NOW()),
('Lelabo tabac 28 Miami (New)',        'L-232', 3, 'Le Labo',          '30ml','ml', 9000,17000,15000,  236, 10,1,1,NOW(),NOW()),
('Lemon gress',                        'L-233', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  552, 10,1,1,NOW(),NOW()),
('Luna maya',                          'L-234', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  100, 10,1,1,NOW(),NOW()),
('Lamborghini',                        'L-235', 2, 'Lamborghini',      '30ml','ml', 7000,13000,11000,   88, 10,1,1,NOW(),NOW()),
('Lv Attrape Reves',                   'L-236', 2, 'Louis Vuitton',    '30ml','ml', 9000,17000,15000,  115, 10,1,1,NOW(),NOW()),
('lv Rose des vent s (New)',           'L-237', 3, 'Louis Vuitton',    '30ml','ml', 9000,17000,15000,  189, 10,1,1,NOW(),NOW()),
('Lux',                                'L-238', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  284, 10,1,1,NOW(),NOW()),
('lelabo santal 33',                   'L-239', 2, 'Le Labo',          '30ml','ml', 9000,17000,15000,  137, 10,1,1,NOW(),NOW()),

-- === BAGIAN M ===
('Midnight fantasy',                   'M-240', 2, 'Britney Spears',   '30ml','ml', 7000,13000,11000,  138, 10,1,1,NOW(),NOW()),
('Melon',                              'M-241', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,   99, 10,1,1,NOW(),NOW()),
('Melati full',                        'M-242', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  167, 10,1,1,NOW(),NOW()),
('Melati keraton',                     'M-243', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,   34, 10,1,1,NOW(),NOW()),
('Maher zain',                         'M-244', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  104, 10,1,1,NOW(),NOW()),
('Malaikat subuh',                     'M-245', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  280, 10,1,1,NOW(),NOW()),
('Misik putih',                        'M-246', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,   82, 10,1,1,NOW(),NOW()),
('Misk thaharah',                      'M-247', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  495, 10,1,1,NOW(),NOW()),
('Manggo',                             'M-248', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  166, 10,1,1,NOW(),NOW()),
('Mancera rose vanilla',               'M-249', 2, 'Mancera',          '30ml','ml', 9000,17000,15000,  184, 10,1,1,NOW(),NOW()),
('Mancera midnight gold (New)',        'M-250', 3, 'Mancera',          '30ml','ml', 9000,17000,15000,  208, 10,1,1,NOW(),NOW()),
('Michael sparkling blush',            'M-251', 2, 'Michael Kors',     '30ml','ml', 8000,15000,13000,  130, 10,1,1,NOW(),NOW()),
('Mark jacob perfect daisy (Uv)',      'M-252', 2, 'Marc Jacobs',      '30ml','ml', 8000,15000,13000,  331, 10,1,1,NOW(),NOW()),
('Marck jacob daisy wild (New)',       'M-253', 3, 'Marc Jacobs',      '30ml','ml', 8000,15000,13000,  238, 10,1,1,NOW(),NOW()),
('Mont blanc signature',               'M-254', 2, 'Mont Blanc',       '30ml','ml', 7000,13000,11000,    0, 10,1,1,NOW(),NOW()),
('Mont blanc starwalker',              'M-255', 2, 'Mont Blanc',       '30ml','ml', 7000,13000,11000,  185, 10,1,1,NOW(),NOW()),
('Mont blanc legend',                  'M-256', 2, 'Mont Blanc',       '30ml','ml', 7000,13000,11000,  281, 10,1,1,NOW(),NOW()),
('Moschino toys (New)',                'M-257', 3, 'Moschino',         '30ml','ml', 7000,13000,11000,  167, 10,1,1,NOW(),NOW()),
('Mfk Paris feminime pluriel (New)',   'M-258', 3, 'MFK',              '30ml','ml', 9000,17000,15000,   84, 10,1,1,NOW(),NOW()),
('Miss Dior blooming (New)',           'M-259', 3, 'Dior',             '30ml','ml', 9000,17000,15000,  279, 10,1,1,NOW(),NOW()),
('Mercedes benz (New)',                'M-260', 3, 'Mercedes Benz',    '30ml','ml', 7000,13000,11000,  195, 10,1,1,NOW(),NOW()),
('Mfk 725 unisex (New)',              'M-261', 3, 'MFK',              '30ml','ml', 9000,17000,15000,  186, 10,1,1,NOW(),NOW()),
('Man brud',                           'M-262', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  100, 10,1,1,NOW(),NOW()),
('Moving',                             'M-263', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  237, 10,1,1,NOW(),NOW()),

-- === BAGIAN N ===
('Nina rici',                          'N-264', 2, 'Nina Ricci',       '30ml','ml', 7000,13000,11000,  103, 10,1,1,NOW(),NOW()),
('Nagita slavina',                     'N-265', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  176, 10,1,1,NOW(),NOW()),
('Nagita Slavina OA (Uv)',             'N-266', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  152, 10,1,1,NOW(),NOW()),
('Narciso Rodriguez (Gold New)',       'N-267', 3, 'Narciso Rodriguez','30ml','ml', 8000,15000,13000,  144, 10,1,1,NOW(),NOW()),
('Narciso Rodriguez (Gold)',           'N-268', 1, 'Narciso Rodriguez','30ml','ml', 8000,15000,13000,   90, 10,1,1,NOW(),NOW()),
('Nissa sabyan',                       'N-269', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  100, 10,1,1,NOW(),NOW()),

-- === BAGIAN O ===
('Original coffee',                    'O-270', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  124, 10,1,1,NOW(),NOW()),
('Olla ramlan (Uv)',                   'O-271', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  144, 10,1,1,NOW(),NOW()),
('Olla ramlan (Gold)',                  'O-272', 1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  208, 10,1,1,NOW(),NOW()),
('One direction',                      'O-273', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  185, 10,1,1,NOW(),NOW()),

-- === BAGIAN P ===
('Paris hilton woman',                 'P-274', 2, 'Paris Hilton',     '30ml','ml', 7000,13000,11000,  341, 10,1,1,NOW(),NOW()),
('Paris hilton heires',                'P-275', 2, 'Paris Hilton',     '30ml','ml', 7000,13000,11000,  321, 10,1,1,NOW(),NOW()),
('Paris hilton shiren',                'P-276', 2, 'Paris Hilton',     '30ml','ml', 7000,13000,11000,  588, 10,1,1,NOW(),NOW()),
('Paris hilton passport',              'P-277', 2, 'Paris Hilton',     '30ml','ml', 7000,13000,11000,  333, 10,1,1,NOW(),NOW()),
('Pramugari (Uv)',                     'P-278', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  217, 10,1,1,NOW(),NOW()),
('Pramugari (Gold)',                   'P-278G',1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  437, 10,1,1,NOW(),NOW()),
('Pop diva',                           'P-279', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  106, 10,1,1,NOW(),NOW()),
('Pure police',                        'P-280', 2, 'Police',           '30ml','ml', 6000,12000,10000,  100, 10,1,1,NOW(),NOW()),
('Penhaligon Elizabeth Rose',          'P-281', 1, 'Penhaligon',       '30ml','ml', 9000,17000,15000,  143, 10,1,1,NOW(),NOW()),
('Pink chifon',                        'P-282', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,    0, 10,1,1,NOW(),NOW()),
('Pink Chifon Bbw (Uv)',              'P-283', 2, 'BBW',              '30ml','ml', 8000,15000,13000,   40, 10,1,1,NOW(),NOW()),
('Paccorabane black XS',               'P-284', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  216, 10,1,1,NOW(),NOW()),
('Paccorabane one million',            'P-285', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,   93, 10,1,1,NOW(),NOW()),
('Paccorabane olympea',                'P-286', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  122, 10,1,1,NOW(),NOW()),
('Paccorabane Invictus (Uv)',          'P-287', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  416, 10,1,1,NOW(),NOW()),
('Paccorabane one million royal(New)', 'P-288', 3, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  135, 10,1,1,NOW(),NOW()),
('Paccorabane Lucky Milion',           'P-289', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  224, 10,1,1,NOW(),NOW()),
('Pacorabbane One Milion (Gold)',      'P-290', 1, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  150, 10,1,1,NOW(),NOW()),
('Pacorabbane Lady Milion',            'P-291', 2, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,  173, 10,1,1,NOW(),NOW()),
('Pacorabbane invictus (Gold)',        'P-292', 1, 'Paco Rabanne',     '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Polo red',                           'P-293', 2, 'Ralph Lauren',     '30ml','ml', 7000,13000,11000,  248, 10,1,1,NOW(),NOW()),
('Polo Sport (Gold)',                  'P-294', 1, 'Ralph Lauren',     '30ml','ml', 7000,13000,11000,    0, 10,1,1,NOW(),NOW()),
('Polo sport (Uv)',                    'P-295', 2, 'Ralph Lauren',     '30ml','ml', 7000,13000,11000,  313, 10,1,1,NOW(),NOW()),
('Polo black (Gold)',                  'P-296', 1, 'Ralph Lauren',     '30ml','ml', 7000,13000,11000,  186, 10,1,1,NOW(),NOW()),
('Polo black (Uv)',                    'P-297', 2, 'Ralph Lauren',     '30ml','ml', 7000,13000,11000,  188, 10,1,1,NOW(),NOW()),

-- === BAGIAN R ===
('Raffi ahmad (Gold)',                 'R-298', 1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  122, 10,1,1,NOW(),NOW()),
('Raffi ahmad (Uv)',                   'R-299', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  158, 10,1,1,NOW(),NOW()),

-- === BAGIAN S ===
('Syahrini',                           'S-300', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,   67, 10,1,1,NOW(),NOW()),
('Selena gomes (Uv)',                  'S-301', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  514, 10,1,1,NOW(),NOW()),
('Selena Gomes Regina OA (Uv)',        'S-302', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  331, 10,1,1,NOW(),NOW()),
('Selena sweet',                       'S-302B',2, 'Lokal',            '30ml','ml', 5000,10000, 8500,    0, 10,1,1,NOW(),NOW()),
('Selena Bandung',                     'S-303', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  550, 10,1,1,NOW(),NOW()),
('Soft',                               'S-304', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  567, 10,1,1,NOW(),NOW()),
('Strawberry',                         'S-305', 2, 'Lokal',            '30ml','ml', 4000, 8000, 6500,  267, 10,1,1,NOW(),NOW()),
('St lauther pleasure',                'S-306', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,   91, 10,1,1,NOW(),NOW()),
('Sabaya',                             'S-307', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  212, 10,1,1,NOW(),NOW()),
('Silver',                             'S-308', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  178, 10,1,1,NOW(),NOW()),

-- === BAGIAN T ===
('Terre D\'hermest',                   'T-309', 2, 'Hermes',           '30ml','ml', 9000,17000,15000,  110, 10,1,1,NOW(),NOW()),
('Taylor swift',                       'T-310', 2, 'Taylor Swift',     '30ml','ml', 7000,13000,11000,  706, 10,1,1,NOW(),NOW()),
('Tomford Ombre L(Gold)',              'T-311', 1, 'Tom Ford',         '30ml','ml', 9000,17000,15000,  153, 10,1,1,NOW(),NOW()),

-- === BAGIAN V ===
('Vanilla ice (Uv)',                   'V-312', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,    0, 10,1,1,NOW(),NOW()),
('Vanilla ice (Gold)',                 'V-313', 1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  129, 10,1,1,NOW(),NOW()),
('Vanilla cake',                       'V-314', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  169, 10,1,1,NOW(),NOW()),
('Vanilla susu',                       'V-315', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  166, 10,1,1,NOW(),NOW()),
('Vanilla body shop',                  'V-316', 2, 'Body Shop',        '30ml','ml', 6000,12000,10000,  219, 10,1,1,NOW(),NOW()),
('Vanilla cokelat',                    'V-317', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  245, 10,1,1,NOW(),NOW()),
('Vanilla popcron',                    'V-318', 2, 'Lokal',            '30ml','ml', 5000,10000, 8500,  151, 10,1,1,NOW(),NOW()),
('Vanilla Creamy Butter (Gold)',       'V-319', 1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  222, 10,1,1,NOW(),NOW()),
('Vanilla Creamy Powder (Gold)',       'V-320', 1, 'Lokal',            '30ml','ml', 5000,10000, 8500,  264, 10,1,1,NOW(),NOW()),
('Valaya (New)',                       'V-321', 3, 'Lokal',            '30ml','ml', 5000,10000, 8500,  142, 10,1,1,NOW(),NOW()),
('Versace blue jeans',                 'V-322', 2, 'Versace',          '30ml','ml', 8000,15000,13000,  100, 10,1,1,NOW(),NOW()),
('Versace dylan blue',                 'V-323', 2, 'Versace',          '30ml','ml', 8000,15000,13000,  113, 10,1,1,NOW(),NOW()),
('Versace eros (Uv)',                  'V-324', 2, 'Versace',          '30ml','ml', 8000,15000,13000,  236, 10,1,1,NOW(),NOW()),
('Versace Eros (Gold)',                'V-325', 1, 'Versace',          '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Versace bright crystal',             'V-326', 2, 'Versace',          '30ml','ml', 8000,15000,13000,   63, 10,1,1,NOW(),NOW()),
('Versace by versus',                  'V-327', 2, 'Versace',          '30ml','ml', 8000,15000,13000,  163, 10,1,1,NOW(),NOW()),
('Victoria Amber Romance (New)',       'V-328', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  476, 10,1,1,NOW(),NOW()),
('Victoria Romanticwish (New)',        'V-329', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Victoria scandalous (Gold New)',     'V-330', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Victoria scandalous (Uv)',           'V-331', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  404, 10,1,1,NOW(),NOW()),
('Victoria Scandalous (Gold)',         'V-332', 1, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Victoria so sexy',                   'V-333', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  243, 10,1,1,NOW(),NOW()),
('Victoria aqua kiss (Uv)',            'V-334', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  762, 10,1,1,NOW(),NOW()),
('Victoria Aqua Kiss (Gold)',          'V-335', 1, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  165, 10,1,1,NOW(),NOW()),
('Victoria Aqua kis Water bloom (Gold)','V-336',1, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,    0, 10,1,1,NOW(),NOW()),
('Victoria bombshell (Uv)',            'V-337', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,   60, 10,1,1,NOW(),NOW()),
('Victoria Boomshel(Gold)',            'V-338', 1, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,   61, 10,1,1,NOW(),NOW()),
('V.S. Bali coconute palm (New)',      'V-339', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  100, 10,1,1,NOW(),NOW()),
('V.S Coconute Passion (Gold)',        'V-340', 1, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  146, 10,1,1,NOW(),NOW()),
('Victoria Secret Candy Baby',         'V-341', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  262, 10,1,1,NOW(),NOW()),
('Victoria romantic wish (Uv)',        'V-342', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  428, 10,1,1,NOW(),NOW()),
('V.S Dream angel (New)',              'V-343', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  140, 10,1,1,NOW(),NOW()),
('V.S intens Woman (New)',             'V-344', 3, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,   77, 10,1,1,NOW(),NOW()),
('V. Beckham beauty ysidro drive (New)','V-345',3, 'Victoria Beckham', '30ml','ml', 8000,15000,13000,  142, 10,1,1,NOW(),NOW()),
('Victoria coconute passions (Uv)',    'V-346', 2, 'Victoria Secret',  '30ml','ml', 8000,15000,13000,  260, 10,1,1,NOW(),NOW());

-- ============================================================
-- BUAT INVENTORY OTOMATIS (Lanjutan)
-- ============================================================
INSERT INTO `inventories` (`product_id`, `current_stock`, `minimum_stock`, `stock_in`, `stock_out`, `cost_per_unit`, `date_received`, `created_at`, `updated_at`)
SELECT p.`id`, p.`initial_stock`, p.`minimum_stock`, p.`initial_stock`, 0, p.`purchase_price`, CURDATE(), NOW(), NOW()
FROM `products` p
WHERE p.`id` NOT IN (SELECT DISTINCT `product_id` FROM `inventories`);

-- Verifikasi total
SELECT COUNT(*) AS total_produk FROM products;
SELECT COUNT(*) AS total_inventory FROM inventories;
