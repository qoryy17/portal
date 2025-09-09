# Portal Aplikasi SPBE - Pengadilan Negeri Lubuk Pakam

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

Sebuah portal web modern yang berfungsi sebagai pusat akses terpadu untuk berbagai aplikasi internal dan eksternal di lingkungan Pengadilan Negeri Lubuk Pakam. Portal ini dirancang dengan antarmuka yang cantik, interaktif, dan responsif.

 
*(Ganti URL di atas dengan URL screenshot portal Anda)*

---

## 📜 Deskripsi

Portal Aplikasi SPBE (Sistem Pemerintahan Berbasis Elektronik) ini dibangun untuk menyederhanakan akses pengguna ke berbagai layanan dan sistem informasi. Dengan desain yang terpusat, pengguna dapat dengan mudah menemukan dan membuka aplikasi yang mereka butuhkan dari satu halaman.

Portal ini juga dilengkapi dengan fitur dinamis seperti *ticker* pengumuman yang mengambil data langsung dari RSS feed dan dasbor statistik capaian EIS (Electronic Information System) yang terintegrasi dengan API Badilum.

## ✨ Fitur Utama

- **Desain Modern & Interaktif**: Antarmuka yang bersih dan modern menggunakan Bootstrap 5.
- **Animasi Halus**: Efek visual dan animasi yang memanjakan mata menggunakan **GSAP (GreenSock Animation Platform)**.
- **Latar Belakang Dinamis**: Latar belakang partikel interaktif yang dibuat dengan **Particles.js**.
- **Responsif**: Tampilan yang optimal di berbagai perangkat, mulai dari desktop hingga mobile.
- **Widget Jam & Tanggal**: Menampilkan waktu dan tanggal real-time.
- **Ticker Pengumuman Dinamis**: Mengambil dan menampilkan pengumuman terbaru secara otomatis dari **RSS Feed**.
- **Dasbor Statistik EIS**: Menampilkan peringkat dan capaian kinerja pengadilan yang diambil langsung dari **API EIS Badilum**.
- **Konfigurasi Mudah**: Daftar aplikasi dapat dengan mudah ditambah atau diubah melalui sebuah array PHP.

## 🚀 Teknologi yang Digunakan

- **Frontend**:
  - HTML5
  - CSS3
  - **Bootstrap 5**
  - **Font Awesome 6**
  - **Particles.js**
  - **GSAP (GreenSock Animation Platform)**
- **Backend**:
  - **PHP 7.4+** (untuk fetching data RSS & EIS)

## ⚙️ Instalasi & Setup

Untuk menjalankan proyek ini di lingkungan lokal, ikuti langkah-langkah berikut:

1.  **Clone Repository**
    ```bash
    git clone https://url-repository-anda.git
    ```
    Atau cukup salin folder proyek ini.

2.  **Web Server**
    - Letakkan folder proyek di dalam direktori root web server Anda (misalnya: `htdocs` untuk XAMPP, `www` untuk WampServer).
    - Pastikan Anda memiliki web server seperti Apache dengan **PHP versi 7.4 atau lebih baru**.

3.  **Ekstensi PHP**
    - Pastikan ekstensi `php-curl` dan `php-xml` aktif di file `php.ini` Anda. Keduanya diperlukan untuk mengambil data dari API EIS dan RSS Feed.

4.  **Jalankan Proyek**
    - Buka browser dan akses portal melalui alamat `http://localhost/nama-folder-proyek/`.

## 🔧 Konfigurasi

Anda dapat dengan mudah mengkonfigurasi beberapa bagian dinamis dari portal ini.

### 1. Daftar Aplikasi

Untuk mengubah, menambah, atau menghapus tautan aplikasi, buka file `index.php` dan modifikasi array `$applications`.

```php
// d:\CODING\PHP\portal\index.php

$applications = [
    // Format: ['name' => 'Nama Tampilan', 'url' => 'URL Aplikasi', 'icon' => 'Ikon Font Awesome'],
    ['name' => 'Website', 'url' => 'https://pn-lubukpakam.go.id/', 'icon' => 'fa-globe'],
    ['name' => 'SIPP 6.0.0', 'url' => 'http://192.168.1.147/SIPP330', 'icon' => 'fa-scale-balanced'],
    // ...tambahkan atau ubah aplikasi lainnya di sini
];
```

### 2. Statistik EIS

Konfigurasi untuk data EIS (nama pengadilan, kelas, dan kategori) dapat diubah di file `scripts/eis.php` pada array `$courtConfig`.

```php
// d:\CODING\PHP\portal\scripts\eis.php

$courtConfig = [
    'name' => 'Pengadilan Negeri Lubuk Pakam',
    'classId' => '2',
    'categoryId' => '4'
];
```

### 3. Ticker Pengumuman

URL untuk RSS Feed pengumuman dapat diubah pada variabel `$rss_url` di file `index.php`.

```php
// d:\CODING\PHP\portal\index.php

$rss_url = 'https://url-rss-feed-anda.go.id/';
```

## 📂 Struktur File

```
portal/
├── assets/
│   ├── css/style.css       # Kustomisasi CSS
│   └── js/portal.js        # Logika JavaScript (Jam, Animasi)
├── scripts/
│   └── eis.php             # Skrip untuk mengambil data EIS dari API
├── index.php               # Halaman utama portal
├── logo.png                # Favicon
└── README.md               # Dokumentasi ini
```

---

Dibuat dengan ❤️ oleh **Qori Chairawan, S.Kom**.