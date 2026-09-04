-- =====================================================
-- Database: SI Cutting Planning
-- PT Yongjin Javasuka Garment II
-- Sesuai Laporan KKL Agil Ardiansyah (I.2410188)
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_cutting_planning;
USE db_cutting_planning;

-- Tabel User
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin','operator') DEFAULT 'operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Style / Artikel
CREATE TABLE styles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_style VARCHAR(50) NOT NULL UNIQUE,
    nama_style VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Order
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_order VARCHAR(50) NOT NULL UNIQUE,
    style_id INT NOT NULL,
    warna VARCHAR(50),
    qty_xs INT DEFAULT 0,
    qty_s INT DEFAULT 0,
    qty_m INT DEFAULT 0,
    qty_l INT DEFAULT 0,
    qty_xl INT DEFAULT 0,
    qty_2xl INT DEFAULT 0,
    qty_3xl INT DEFAULT 0,
    total_qty INT GENERATED ALWAYS AS (qty_xs + qty_s + qty_m + qty_l + qty_xl + qty_2xl + qty_3xl) STORED,
    tanggal_order DATE,
    status ENUM('pending','proses','selesai') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (style_id) REFERENCES styles(id) ON DELETE CASCADE
);

-- Tabel Cutting Plan
CREATE TABLE cutting_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    marker_length DECIMAL(10,2) NOT NULL COMMENT 'Panjang marker dalam meter',
    jumlah_lay INT NOT NULL,
    lebar_kain DECIMAL(10,2) DEFAULT 1.50 COMMENT 'Lebar kain dalam meter',
    fabric_used DECIMAL(10,2) COMMENT 'Hasil perhitungan YD',
    utilization DECIMAL(5,2) COMMENT 'Persentase utilization',
    status ENUM('belum','proses','selesai') DEFAULT 'belum',
    catatan TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Data Awal User
INSERT INTO users (username, password, nama_lengkap, role) VALUES
('admin', MD5('admin123'), 'Administrator', 'admin'),
('operator', MD5('operator123'), 'Operator Cutting', 'operator');

-- Data Awal Style (sesuai contoh di laporan)
INSERT INTO styles (kode_style, nama_style, deskripsi) VALUES
('UWA-CGSS', 'Under Armour CGSS', 'Style Under Armour - Compression'),
('TNF-PTX2', 'The North Face PTX2', 'Style TNF - Outdoor Jacket'),
('ARC-ATOM', 'Arc''teryx Atom LT', 'Style Arc''teryx - Insulated Jacket'),
('NKE-DRY', 'Nike Dri-FIT', 'Style Nike - Training Wear'),
('ADI-CLIM', 'Adidas Climacool', 'Style Adidas - Performance');

-- Data Awal Order contoh
INSERT INTO orders (no_order, style_id, warna, qty_xs, qty_s, qty_m, qty_l, qty_xl, qty_2xl, qty_3xl, tanggal_order) VALUES
('ORD-2026-001', 1, 'Black', 10, 25, 40, 35, 20, 10, 5, '2026-07-28'),
('ORD-2026-002', 2, 'Navy', 5, 15, 30, 40, 25, 15, 8, '2026-08-02'),
('ORD-2026-003', 3, 'Grey', 0, 10, 25, 30, 20, 10, 5, '2026-08-10');
EOF