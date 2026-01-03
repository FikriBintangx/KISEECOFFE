<div align="center">

KISEE COFFEE

Sistem Manajemen Coffee Shop Modern

Kisee Coffee adalah sistem manajemen kafe berbasis web yang dirancang untuk mempercepat operasional kedai kopi Kamu. Sistem ini menggunakan framework CodeIgniter 3.

</div>

Fitur Utama

Dashboard Real-time: Pantau statistik penjualan dan transaksi harian.

Status Dapur Dinamis: Atur kondisi dapur (Santai, Sibuk, atau Ngebul).

Manajemen Toko: Kendali penuh status buka atau tutup toko secara manual.

Sistem Role: Batasi hak akses pengguna dengan fitur Role Management.

Laporan Digital: Ekspor data ke format PDF, Excel, atau cetak fisik.

Antarmuka Modern: Dukungan Mode Gelap dengan desain Neobrutalism.

Cuplikan Logika (Backend)

Berikut adalah beberapa logika utama yang digunakan pada halaman utama aplikasi:

1. Logika Status Operasional Toko

Sistem ini menentukan status toko berdasarkan waktu otomatis atau pengaturan manual dari admin.

$jam_sekarang = (int)date('H');
$is_open_time = ($jam_sekarang >= 8 && $jam_sekarang < 22);

if ($shop_status_db == 'open') {
    $is_open = true;
} elseif ($shop_status_db == 'closed') {
$is_open = false;
} else {
$is_open = $is_open_time;
}

2. Penentuan Status Dapur

Status dapur memberikan informasi estimasi waktu tunggu kepada pelanggan.

if($status_db == 1) {
    $dapur_text = 'DAPUR SANTAI'; // Pesanan cepat (5-10 Menit)
} elseif($status_db == 2) {
$dapur_text = 'DAPUR SIBUK'; // Mohon bersabar (15-20 Menit)
} else {
$dapur_text = 'DAPUR NGEBUL'; // Antrian padat (30+ Menit)
}

Gaya Desain (CSS)

Aplikasi ini menggunakan gaya Neobrutalism dengan garis tepi tegas dan bayangan kontras.

.neu-border { border: 3px solid #000; }
.neu-shadow { box-shadow: 6px 6px 0 #000; }
.neu-hover:hover {
transform: translate(-2px, -2px);
box-shadow: 8px 8px 0 #000;
}

Panduan Instalasi

Clone repositori:

git clone [https://github.com/FikriBintangx/KISEECOFFE.git](https://github.com/FikriBintangx/KISEECOFFE.git)

Jalankan composer install.

Buat database kiisecoffee dan impor file SQL yang tersedia.

Sesuaikan konfigurasi pada application/config/database.php.

Atur base_url pada application/config/config.php.

Akses aplikasi melalui http://localhost/KiiseCoffee/.

Kontak dan Kontribusi

Dikembangkan oleh Fikri Bintang Purnomo.

GitHub: FikriBintangx

Demo: Video Presentasi

<div align="center">
Dibuat dengan semangat dan kopi.
</div>
