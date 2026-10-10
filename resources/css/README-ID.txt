ROK REVAMP — STRUKTUR CSS TERPISAH

ISI FOLDER
- app.css: entry point yang di-load oleh Vite melalui @vite(['resources/css/app.css', ...]).
- base/reset.css: variabel warna dan aturan global.
- components/header.css: header, logo, identitas, dan reveal header.
- components/navbar.css: navigasi utama.
- pages/home.css: hero, tombol CTA, slider, indikator, dan responsive Home.

CARA MEMASANG
1. Buat backup folder resources/css yang sekarang.
2. Salin folder base, components, pages, dan file app.css ke resources/css proyek Laravel.
3. Pastikan layouts/app.blade.php tetap memuat @vite(['resources/css/app.css', 'resources/js/app.js']).
4. Jalankan npm run dev di terminal proyek (atau npm run build untuk build produksi).
5. Refresh browser dengan Ctrl+F5.

CATATAN
- Jangan menghapus file CSS lama sampai tampilan Home sudah diuji.
- Jika ada aturan baru, letakkan pada file komponen terkait; hindari menambahkan override berulang di akhir app.css.
- CSS ini disusun berdasarkan CSS yang diberikan. Uji tampilan di browser karena struktur Blade/JS dan seluruh halaman belum tersedia untuk pengujian otomatis.
