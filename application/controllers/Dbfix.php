<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dbfix extends CI_Controller {
    
    public function index()
    {
        $this->load->database();
        
        echo "<div style='font-family: sans-serif; padding: 20px;'>";
        echo "<h1>🛠️ Database Fixer Tool</h1>";
        
        // 1. Create user_token table
        $sql1 = "CREATE TABLE IF NOT EXISTS `user_token` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `email` varchar(128) NOT NULL,
          `token` varchar(128) NOT NULL,
          `date_created` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        echo "<p>Membuat tabel <code>user_token</code>... ";
        if ($this->db->query($sql1)) {
            echo "<b style='color:green'>BERHASIL</b> ✅</p>";
        } else {
            echo "<b style='color:red'>GAGAL</b> ❌<br>" . $this->db->error()['message'] . "</p>";
        }

        // 2. Create riwayat_poin table
        $sql2 = "CREATE TABLE IF NOT EXISTS `riwayat_poin` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `transaksi_id` int(11) NOT NULL,
          `poin` int(11) NOT NULL,
          `tipe` enum('masuk','keluar') NOT NULL,
          `keterangan` varchar(255) NOT NULL,
          `tanggal` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        echo "<p>Membuat tabel <code>riwayat_poin</code>... ";
        if ($this->db->query($sql2)) {
            echo "<b style='color:green'>BERHASIL</b> ✅</p>";
        } else {
            echo "<b style='color:red'>GAGAL</b> ❌<br>" . $this->db->error()['message'] . "</p>";
        }
        
        echo "<hr>";
        echo "<p>Sekarang silakan langsung coba fitur Lupa Password.</p>";
        echo "<p><i>Catatan: Anda bisa menghapus file controllers/Dbfix.php setelah ini.</i></p>";
        echo "</div>";
    }
}
