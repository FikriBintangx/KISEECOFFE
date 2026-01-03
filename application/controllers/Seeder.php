<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seeder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        // 1. KOSONGKAN TABEL (OPTIONAL - matikan jika ingin menambah saja)
        // $this->db->empty_table('menu');
        
        // 2. DATA MINUMAN BARU (KOPI & LAINNYA)
        $data_baru = [
            // KATEGORI KOPI
            [
                'nama' => 'Caramel Macchiato',
                'kategori' => 'Kopi',
                'harga' => 32000,
                'stok' => 50,
                'minimum_stok' => 5,
                'deskripsi' => 'Espresso dengan syrup vanilla, susu, dan topping saus karamel.',
                'gambar' => 'Caramel Macchiato.jpg'
            ],
            [
                'nama' => 'Caffe Latte Classic',
                'kategori' => 'Kopi',
                'harga' => 25000,
                'stok' => 60,
                'minimum_stok' => 5,
                'deskripsi' => 'Kopi susu klasik dengan foam susu yang lembut.',
                'gambar' => 'Caffe Latte Classic.jpg'
            ],
            [
                'nama' => 'Americano Bold',
                'kategori' => 'Kopi',
                'harga' => 20000,
                'stok' => 100,
                'minimum_stok' => 5,
                'deskripsi' => 'Kopi hitam murni dari biji arabika pilihan.',
                'gambar' => 'Americano Bold.jpg'
            ],
            [
                'nama' => 'Vanilla Latte',
                'kategori' => 'Kopi',
                'harga' => 28000,
                'stok' => 55,
                'minimum_stok' => 5,
                'deskripsi' => 'Latte lembut dengan aroma vanilla yang manis.',
                'gambar' => 'Vanilla Latte.jpg'
            ],
        ];

        // Insert Batch Data Baru
        $this->db->insert_batch('menu', $data_baru);
        echo "Berhasil menambah " . count($data_baru) . " Menu Kopi Baru!<br>";

        // 3. UPDATE GAMBAR MINUMAN LAMA YG MASIH KOSONG/ERROR
        // Nasi Goreng
        $this->db->where('nama', 'Nasi Goreng Special Kiise')->update('menu', ['gambar' => 'Nasi Goreng Special Kiise.jpg']);
        // Matcha
        $this->db->where('nama', 'Matcha Latte Premium')->update('menu', ['gambar' => 'Matcha Latte Premium.jpg']);
        // Chicken Katsu
        $this->db->where('nama', 'Chicken Katsu Curry')->update('menu', ['gambar' => 'Chicken Katsu Curry.jpg']);
        // Pisang Goreng
        $this->db->where('nama', 'Pisang Goreng Keju')->update('menu', ['gambar' => 'Pisang Goreng Keju.jpg']);
        // Strawberry
        $this->db->where('nama', 'Strawberry Smoothie')->update('menu', ['gambar' => 'Strawberry Smoothie.jpg']);
        // Taro
        $this->db->where('nama', 'Taro Milk Tea')->update('menu', ['gambar' => 'Taro Milk Tea.jpg']);

        echo "Berhasil update gambar menu yang hilang!<br>";
        echo "<a href='" . base_url() . "'>KEMBALI KE HOME</a>";
    }
}
