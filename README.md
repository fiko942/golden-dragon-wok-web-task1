# Golden Dragon Wok – Company Profile Website

Tugas akhir mata kuliah **Pemrograman Web C** Universitas Muhammadiyah Malang.
Proyek ini dikerjakan oleh **WIJI FIKO TEREN** (NIM 202310370311437) dan saat ini dapat diakses publik pada URL berikut:

> https://golden-dragon-wok-task1.serverkosfiko.my.id/

Website menghadirkan company profile restoran fiktif “Golden Dragon Wok” lengkap dengan halaman beranda, menu interaktif, serta form lamaran kerja terintegrasi ke backend PHP + SMTP.

---

## ✨ Highlight Fitur
- **Halaman Home** dengan hero ilustratif, section tentang resto, signature dishes, dan slider testimoni.
- **Halaman Menu** memuat daftar hidangan via JSON dengan filter kategori, filter diet, pencarian, sorting, modal detail, serta tombol back-to-top.
- **Halaman Contact & Jobs** menampilkan informasi kontak, peta lokasi, dan form lamaran kerja dengan validasi, CSRF token, captcha aritmatika, upload CV, serta pengiriman email otomatis via PHPMailer (SMTP Hostinger).
- **Dark Mode toggle** dengan penyimpanan preferensi ke `localStorage`.
- **Responsif & aksesibel**: struktur HTML semantik, dukungan keyboard pada modal, aria-label, dan focus ring custom.

---

## 🧰 Teknologi yang Digunakan
| Layer | Stack |
|-------|-------|
| Front-end | HTML5, Tailwind CSS (CDN), custom CSS, jQuery, vanilla JS |
| Data | `js/data.menu.json` untuk konten menu |
| Back-end | PHP 8, PHPMailer (via Composer) untuk SMTP |
| Build / Tools | Composer, npm tidak digunakan (Tailwind via CDN) |
| Hosting | Deploy pada server `serverkosfiko.my.id` |

---

## 🗂️ Struktur Direktori Singkat
```
/
├── index.html            # Beranda
├── menu.html             # Halaman menu interaktif
├── contact.php           # Kontak & form lamaran (PHP + AJAX)
├── css/custom.css        # Styling tambahan & animasi
├── js/app.js             # Dark mode & slider testimonial
├── js/main.jquery.js     # Smooth scroll, filter menu, modal, AJAX form
├── js/data.menu.json     # Data menu restoran
├── assets/img            # Ilustrasi dan foto placeholder
├── assets/icons          # Ikon SVG (moon, sun, badges, dll)
├── php/applyJob.php      # Endpoint form lamaran + PHPMailer
├── php/config-smtp.php   # Konfigurasi SMTP Hostinger
├── vendor/               # Dependensi Composer (PHPMailer)
└── php/logs/             # Log backend form
```

---

## 🚀 Menjalankan Secara Lokal
1. **Clone / salin proyek** ke folder server lokal (misal `htdocs/` untuk XAMPP).
2. Masuk ke direktori proyek lalu jalankan:
   ```bash
   composer install
   ```
   Ini akan memastikan folder `vendor/` berisi PHPMailer.
3. Jalankan server PHP bawaan (opsional):
   ```bash
   php -S localhost:8000
   ```
   Akses di `http://localhost:8000/index.html` dan `http://localhost:8000/contact.php`.
4. Jika memakai XAMPP/MAMP/Laragon, cukup akses melalui `http://localhost/<nama-folder>/`.

---

## 📬 Konfigurasi SMTP
Edit `php/config-smtp.php` sesuai kredensial Hostinger (atau SMTP lain yang kompatibel):
```php
return [
    'host'       => 'smtp.hostinger.com',
    'port'       => 465,
    'secure'     => true,
    'username'   => 'wijifikoteren@streampeg.com',
    'password'   => 'TobelLord1_',
    'from_email' => 'wijifikoteren@streampeg.com',
    'from_name'  => 'Golden Dragon Wok HR'
];
```
> **Catatan:** form hanya menerima email pelamar `@gmail.com`. Ringkasan aplikasi akan dikirim ke inbox Gmail milik pelamar melalui akun SMTP di atas.

---

## ✅ Checklist Fungsional
- [x] 3 halaman utama (Home, Menu, Contact/Jobs) dengan konten lengkap.
- [x] Tailwind CSS (CDN) + custom CSS untuk pattern, animasi, focus ring.
- [x] Interaksi jQuery (smooth scroll, header shrink, filter/sort/search, modal, back-to-top, validasi form & AJAX).
- [x] Data menu dimuat via AJAX dari `data.menu.json`.
- [x] Form lamaran kerja AJAX → PHP → PHPMailer SMTP + upload CV + captcha + CSRF.
- [x] Dark mode toggle tersimpan di `localStorage`.
- [x] Responsif, aksesibel, tanpa error console.

---

## 📄 Lisensi & Hak Cipta
Konten dibuat khusus untuk kebutuhan tugas akademik. Ilustrasi menu merupakan grafis yang digenerate secara programatik agar bebas lisensi.

---

Terima kasih sudah mengunjungi proyek Golden Dragon Wok! 🍜
