<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
    }

    public function index()
    {
        $data['title'] = 'KiiseCoffee';

        // ambil data menu dari database (table menu)
        // ambil data menu dari database (table menu)
        // SAFETY: Check if table 'menu' exists first to prevent crash
        $data['menu'] = [];
        if ($this->db->table_exists('menu')) {
            $data['menu'] = $this->db->get('menu')->result_array();
        }

        // --- LOAD SETTINGS DARI DATABASE (SINKRONISASI) ---
        // 1. Ambil Running Text
        $db_text = $this->db->get_where('site_settings', ['setting_key' => 'running_text'])->row_array();
        $data['running_text'] = $db_text ? $db_text['setting_value'] : '';

        // 2. Ambil Status Dapur
        $db_status = $this->db->get_where('site_settings', ['setting_key' => 'kitchen_status'])->row_array();
        $data['kitchen_status'] = $db_status ? (int)$db_status['setting_value'] : 1;
        
        // 3. Ambil Status Manual Toko (BUKA/TUTUP Override)
        $db_shop = $this->db->get_where('site_settings', ['setting_key' => 'shop_status'])->row_array();
        $data['shop_override_status'] = $db_shop ? $db_shop['setting_value'] : 'auto';
        // --------------------------------------------------

        // Ambil jumlah item di keranjang jika user sudah login
        $data['cart_count'] = 0;
        $data['cart_total'] = 0;
        
        if ($this->session->userdata('email')) {
            $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
            if ($user) {
                $data['user'] = $user;
                $cart = $this->Transaksi_model->get_or_create_cart($user['id']);
                $items = $this->Transaksi_model->get_cart_items($cart['id']);
                $data['cart_count'] = count($items);
                $data['cart_total'] = $this->Transaksi_model->hitung_total($cart['id']);
            }
        }

        // Urutan pemanggilan sudah benar
        $this->load->view('customer/layout/header', $data);
        $this->load->view('customer/index', $data);
        $this->load->view('customer/layout/footer');
    }

    public function search_ajax()
    {
        $keyword = $this->input->get('keyword');
        if (empty($keyword)) {
            echo json_encode([]);
            return;
        }
        
        $this->db->like('nama', $keyword, 'both');
        $this->db->or_like('kategori', $keyword, 'both');
        $this->db->limit(10);
        $data = $this->db->get('menu')->result_array();
        
        // Add base_url for images
        foreach ($data as &$item) {
            $item['gambar_url'] = base_url('assets/img/makanan/' . $item['gambar']);
            $item['harga_formatted'] = 'Rp ' . number_format($item['harga'], 0, ',', '.');
        }
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
