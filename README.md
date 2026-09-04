# SI Cutting Planning – PT Yongjin Javasuka Garment II

Sistem Informasi Cutting Planning berbasis Web  
**Sesuai Laporan KKL – Agil Ardiansyah (I.2410188) – 2026**

---

## Dua Versi Tersedia

### 1. Versi Frontend (HTML + JavaScript) – Siap Pakai Langsung
- Tidak perlu install database
- Data disimpan di LocalStorage browser
- Cocok untuk demo & presentasi cepat
- File: `index.html` + `app.js`

### 2. Versi PHP + MySQL – Sesuai Laporan Bab IV
- Backend PHP Native
- Database MySQL
- Cocok untuk laporan akademik
- Folder: `php-version/`

---

## Cara Cepat Menjalankan (Versi Frontend)

1. Extract ZIP
2. Double-click `index.html`

**Login Demo:**
- admin / admin123
- operator / operator123

---

## Cara Menjalankan Versi PHP + MySQL

Lihat file **`php-version/README-PHP.md`**

Ringkas:
1. Nyalakan XAMPP (Apache + MySQL)
2. Import `php-version/database.sql` di phpMyAdmin
3. Copy folder `php-version` ke `htdocs`
4. Buka `http://localhost/cutting-planning/login.php`

---

## Fitur

| Fitur | Frontend | PHP+MySQL |
|-------|----------|-----------|
| Login | ✅ | ✅ |
| Dashboard | ✅ | ✅ |
| Data Style (CRUD) | ✅ | ✅ |
| Data Order + Size Ratio | ✅ | ✅ |
| Cutting Plan + Hitung Otomatis | ✅ | ✅ |
| Update Status | ✅ | ✅ |
| Laporan + Cetak | ✅ | ✅ |
| Export CSV | ✅ | - |
| Halaman Panduan | ✅ | - |

---

## File Penting

| File / Folder | Keterangan |
|---------------|------------|
| `index.html` + `app.js` | Versi frontend lengkap |
| `TUTORIAL.md` | Panduan upload online (Netlify, GitHub Pages, dll) |
| `php-version/` | Versi PHP + MySQL lengkap |
| `php-version/database.sql` | Struktur & data awal database |

---

**PT Yongjin Javasuka Garment II – Cicurug, Sukabumi**  
Dikembangkan dalam rangka Kuliah Kerja Lapangan 2026
