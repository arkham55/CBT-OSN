<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
  
  <br>

  <h1 align="center">CBT OSN (Computer Based Test - Olimpiade) 🚀</h1>

  <p align="center">
    Aplikasi Latihan Ujian OSN Interaktif yang Dilengkapi dengan Evaluasi AI (Kecerdasan Buatan).
  </p>
</div>

---

## 📖 Tentang Aplikasi

**CBT OSN** adalah platform ujian berbasis web (*Computer Based Test*) yang dikhususkan untuk membantu siswa-siswi jenjang SMP (dan jenjang lainnya) dalam mempersiapkan diri menghadapi Olimpiade Sains Nasional. 

Berbeda dengan aplikasi ujian biasa, sistem ini terintegrasi langsung dengan **Kecerdasan Buatan (Google Gemini AI)**. Setelah menyelesaikan ujian, sistem tidak hanya menampilkan skor akhir, tetapi AI akan otomatis menganalisis pola jawaban siswa dan memberikan:
1. **Evaluasi Akurasi:** Membedah di mana kelemahan dan kekuatan penguasaan materi siswa.
2. **Strategi Ujian:** Saran taktis menghadapi sistem penilaian OSN (misal: sistem minus).
3. **Saran Belajar Lanjutan:** Topik spesifik yang harus segera dipelajari ulang.

## ✨ Fitur Utama

- 🧠 **AI-Powered Evaluation:** Analisis gaya menjawab dan saran cerdas langsung dari AI.
- 📊 **Statistik Performa & Tren:** Dashboard yang memantau perkembangan nilai, rata-rata durasi, dan tren naik-turunnya performa siswa.
- 🎨 **Modern Glassmorphism UI:** Antarmuka super cantik, responsif, dan animasi transisi yang mulus.
- 🌓 **Mode Gelap (Dark Mode):** Sistem pergantian mode terang/gelap otomatis untuk kenyamanan mata.
- ⚡ **SPA-like Experience:** Menggunakan Livewire untuk perpindahan halaman dan interaksi ujian secepat kilat tanpa *loading* halaman penuh.

## 🛠️ Teknologi yang Digunakan

Proyek ini dibangun di atas fondasi teknologi modern (TALL Stack):
- **[Laravel 11](https://laravel.com)** (PHP Framework)
- **[Livewire 3](https://livewire.laravel.com)** (Reaktivitas Backend)
- **[Alpine.js](https://alpinejs.dev)** (Reaktivitas Frontend Ringan)
- **[Tailwind CSS](https://tailwindcss.com)** (Styling & Desain)
- **MySQL** (Database)

## 🚀 Cara Instalasi (Local Development)

Jika Anda ingin menjalankan aplikasi ini di komputer Anda sendiri, ikuti langkah-langkah berikut:

1. **Clone repository ini**
   ```bash
   git clone https://github.com/arkham55/CBT-OSN.git
   cd CBT-OSN
   ```

2. **Instal dependensi PHP & Node.js**
   ```bash
   composer install
   npm install
   ```

3. **Atur file konfigurasi**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database & API Key**
   Buka file `.env` dan atur koneksi database Anda. Jangan lupa tambahkan API Key Gemini Anda:
   ```env
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=

   GEMINI_API_KEY=masukkan_api_key_anda_di_sini
   ```

5. **Buat Key Aplikasi dan Jalankan Migrasi**
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

6. **Bangun aset (CSS/JS) & Jalankan Server**
   Buka 2 terminal secara bersamaan:
   ```bash
   # Terminal 1 (Untuk membangun aset desain)
   npm run build

   # Terminal 2 (Untuk menjalankan server lokal)
   php artisan serve
   ```
   
Kunjungi `http://localhost:8000` di browser Anda! 🎉

## 📝 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan latihan dan pendidikan. *Codebase* dasar menggunakan framework [Laravel](https://laravel.com) yang bersifat Open Source (Lisensi MIT).
