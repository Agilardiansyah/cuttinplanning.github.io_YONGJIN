# SI Cutting Planning – Versi PHP + MySQL

Sesuai teknologi yang disebutkan di Laporan KKL Bab IV  
(PHP Native + MySQL + Bootstrap 5)

---

## Cara Install (XAMPP / Localhost)

### 1. Persiapan
- Install **XAMPP** (https://www.apachefriends.org)
- Nyalakan **Apache** dan **MySQL**

### 2. Import Database
1. Buka **http://localhost/phpmyadmin**
2. Buat database baru atau langsung import file `database.sql`
3. Klik **Import** → pilih file `database.sql` → Go

### 3. Letakkan File
1. Copy folder `php-version` ke:
   ```
   C:\xampp\htdocs\cutting-planning
   ```
2. Atau rename folder menjadi `cutting-planning`

### 4. Akses
Buka browser:
```
http://localhost/cutting-planning/login.php
```

### 5. Login
| Username | Password   |
|----------|------------|
| admin    | admin123   |
| operator | operator123|

---

## Struktur Folder

```
php-version/
├── config/
│   └── database.php      ← Koneksi database
├── includes/
│   └── auth.php          ← Session & login check
├── pages/
│   ├── styles.php        ← CRUD Style
│   ├── orders.php        ← Input Order + Size Ratio
│   ├── plans.php         ← Cutting Plan + Perhitungan
│   └── laporan.php       ← Laporan + Cetak
├── login.php
├── logout.php
├── index.php             ← Dashboard utama
├── database.sql          ← File database
└── README-PHP.md
```

---

## Fitur yang Sudah Ada

- Login & Session
- Dashboard statistik
- CRUD Data Style
- Input Order + Size Ratio (XS–3XL)
- Cutting Plan dengan perhitungan otomatis:
  - **Fabric Used (YD)** = Marker Length × Jumlah Lay × 1.0936
  - Utilization %
- Update Status (Belum / Proses / Selesai)
- Laporan + Cetak

---

## Catatan untuk Laporan

Versi ini menggunakan stack yang sama dengan yang ditulis di laporan:
- Backend: **PHP Native**
- Database: **MySQL**
- Frontend: **Bootstrap 5**

Jika ingin dikembangkan lebih lanjut (edit data, export Excel, multi-user concurrent, dsb), bisa dilanjutkan dari struktur ini.
EOF