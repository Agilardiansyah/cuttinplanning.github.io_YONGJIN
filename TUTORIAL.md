# 📘 TUTORIAL LENGKAP
# SI Cutting Planning – PT Yongjin Javasuka Garment II
## Cara Menjalankan & Meng-upload Sistem

---

## 1. Cara Menjalankan di Komputer Sendiri (Lokal)

### Cara Paling Mudah
1. Extract file `cutting-planning-system.zip`
2. Masuk ke folder hasil extract
3. **Double-click** file `index.html`
4. Browser akan terbuka otomatis → sistem siap digunakan

### Cara dengan Local Server (Direkomendasikan)
Jika double-click tidak bekerja dengan baik:

**Menggunakan Python (sudah terinstall di kebanyakan komputer):**
```bash
# Buka Command Prompt / Terminal di folder project
python -m http.server 8080
```
Lalu buka browser dan ketik:
```
http://localhost:8080
```

**Menggunakan VS Code:**
1. Install extension **Live Server**
2. Klik kanan `index.html` → **Open with Live Server**

---

## 2. Akun Login Demo

| Role     | Username  | Password     |
|----------|-----------|--------------|
| Admin    | `admin`   | `admin123`   |
| Operator | `operator`| `operator123`|

---

## 3. Cara Meng-upload / Deploy ke Internet (Gratis)

Ada beberapa cara mudah agar sistem bisa diakses online (bisa dibuka dari HP/laptop mana saja).

### Cara A: Netlify Drop (Paling Mudah – Tanpa Akun GitHub)

1. Buka website: [https://app.netlify.com/drop](https://app.netlify.com/drop)
2. **Drag & drop** seluruh folder project (yang berisi `index.html`, `app.js`, dll) ke area drop
3. Tunggu beberapa detik
4. Netlify akan memberikan link seperti:  
   `https://random-name-123.netlify.app`
5. Sistem sudah online! Bagikan link tersebut.

> Catatan: Untuk menyimpan link permanen, buat akun Netlify gratis.

---

### Cara B: GitHub Pages (Paling Profesional untuk Laporan)

1. Buat akun di [https://github.com](https://github.com) (gratis)
2. Buat repository baru, contoh nama: `si-cutting-planning`
3. Upload semua file (`index.html`, `app.js`, `README.md`) ke repository
4. Masuk ke **Settings** → **Pages**
5. Di bagian Source pilih **Deploy from a branch** → Branch `main` → folder `/ (root)`
6. Klik Save
7. Tunggu 1–2 menit, lalu akses:
   ```
   https://username-anda.github.io/si-cutting-planning/
   ```

**Cara upload file ke GitHub:**
- Via website GitHub (tombol “Add file” → Upload files), atau
- Via GitHub Desktop (lebih mudah), atau
- Via command line:
```bash
git init
git add .
git commit -m "SI Cutting Planning - KKL Agil Ardiansyah"
git branch -M main
git remote add origin https://github.com/username/si-cutting-planning.git
git push -u origin main
```

---

### Cara C: Vercel (Sangat Cepat)

1. Buka [https://vercel.com](https://vercel.com)
2. Login dengan akun GitHub
3. Klik **Add New Project** → Import repository yang berisi project ini
4. Klik Deploy
5. Dapatkan link langsung

---

### Cara D: Hosting Biasa (cPanel / Hostinger / dll)

Jika Anda punya hosting + domain:

1. Login ke cPanel
2. Masuk ke **File Manager**
3. Buka folder `public_html`
4. Upload semua file (`index.html`, `app.js`, dll)
5. Akses melalui domain Anda, contoh:  
   `https://namadomain.com` atau `https://namadomain.com/cutting`

---

## 4. Struktur File Project

```
cutting-planning-system/
├── index.html          ← Halaman utama (UI)
├── app.js              ← Semua logika sistem
├── README.md           ← Ringkasan project
└── TUTORIAL.md         ← File ini (panduan lengkap)
```

Tidak perlu database terpisah karena data disimpan di **LocalStorage** browser (cocok untuk prototype / demo laporan).

---

## 5. Fitur yang Tersedia

| Modul              | Keterangan |
|--------------------|----------|
| Login              | Admin & Operator |
| Dashboard          | Statistik + Cutting Plan terbaru |
| Data Style         | CRUD Style / Artikel |
| Data Order         | Input order + Size Ratio XS–3XL |
| Cutting Plan       | Hitung Fabric Used & Utilization otomatis |
| Update Status      | Belum → Proses → Selesai |
| Laporan            | Tabel + Cetak |

---

## 6. Tips untuk Laporan KKL / Skripsi

1. Screenshot semua halaman (Login, Dashboard, Data Style, Input Order, Cutting Plan, Laporan)
2. Masukkan screenshot ke **Lampiran 3** seperti di laporan Anda
3. Jelaskan di Bab IV bahwa sistem menggunakan:
   - Frontend: HTML5 + Bootstrap 5 + JavaScript
   - Penyimpanan data: LocalStorage (prototype)
   - Untuk production bisa dikembangkan ke PHP + MySQL
4. Cantumkan link online (jika sudah di-deploy) di laporan atau presentasi

---

## 7. Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Halaman blank / tidak muncul | Pastikan file `app.js` berada di folder yang sama dengan `index.html` |
| Data hilang setelah refresh | Normal di LocalStorage. Jangan clear cache browser |
| Tampilan berantakan di HP | Sudah responsive. Refresh halaman |
| Tidak bisa login | Gunakan `admin` / `admin123` |

---

## 8. Pengembangan Selanjutnya (Opsional)

Jika ingin versi lebih profesional:
- Backend PHP + MySQL (sesuai laporan Bab 4.4)
- Multi-user concurrent
- Integrasi dengan data CAD
- Export Excel / PDF lebih advanced
- Notifikasi real-time

---

**Selamat menggunakan sistem!**  
Semoga bermanfaat untuk laporan KKL dan presentasi.

Agil Ardiansyah – I.2410188  
PT Yongjin Javasuka Garment II – 2026
EOF
---

## 9. Versi PHP + MySQL

Tersedia juga versi PHP Native + MySQL di folder **`php-version/`**.

Cara install lengkap ada di file:
`php-version/README-PHP.md`

Ringkasan:
1. Install XAMPP
2. Import `database.sql`
3. Letakkan folder di `htdocs`
4. Akses `http://localhost/.../login.php`
