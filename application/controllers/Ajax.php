<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
    }

    public function search_global()
    {
        $keyword = $this->input->get('q');
        $results = [];
        $role_id = $this->session->userdata('role_id');
        $user_email = $this->session->userdata('email');

        if (empty($keyword)) {
            echo json_encode([]);
            return;
        }

        // 1. Search in Menus (Admin Only)
        if ($role_id == 1) {
            $this->db->like('menu', $keyword);
            $menus = $this->db->get('user_menu')->result_array();
            foreach ($menus as $m) {
                $results[] = [
                    'title' => $m['menu'],
                    'url' => base_url('menu'),
                    'type' => 'Menu'
                ];
            }

            // 2. Search in Submenus (Admin Only)
            $this->db->select('user_sub_menu.*, user_menu.menu');
            $this->db->join('user_menu', 'user_sub_menu.menu_id = user_menu.id');
            $this->db->like('title', $keyword);
            $submenus = $this->db->get('user_sub_menu')->result_array();
            foreach ($submenus as $sm) {
                $results[] = [
                    'title' => $sm['title'],
                    'url' => base_url($sm['url']),
                    'type' => 'Submenu'
                ];
            }
        }

        // 3. Search in Makanan (All Users)
        if ($this->db->table_exists('menu')) {
            $this->db->like('nama', $keyword);
            $makanan = $this->db->get('menu')->result_array();
            foreach ($makanan as $mk) {
                $url = ($role_id == 1) ? base_url('makanan') : base_url('transaksi/detail/' . $mk['id']); // Or just base_url('transaksi')
                // For users, maybe point to add to cart or just list? 
                // Let's point to 'transaksi' (menu list) for users, 'makanan' (manage) for admin.
                $url = ($role_id == 1) ? base_url('makanan') : base_url('transaksi'); 
                
                $results[] = [
                    'title' => $mk['nama'],
                    'url' => $url,
                    'type' => 'Makanan'
                ];
            }
        }

        // 4. Search in Users (Admin Only)
        if ($role_id == 1 && $this->db->table_exists('user_data')) {
            $this->db->like('nama', $keyword);
            $this->db->or_like('email', $keyword);
            $users = $this->db->get('user_data')->result_array();
            foreach ($users as $u) {
                $results[] = [
                    'title' => $u['nama'] . ' (' . $u['email'] . ')',
                    'url' => base_url('admin/role_user'),
                    'type' => 'User'
                ];
            }
        }

        // 5. Search in Transaksi (by Code)
        if ($this->db->table_exists('transaksi')) {
            $this->db->like('kode_transaksi', $keyword);
            if ($role_id != 1) {
                // Get user ID first
                $user = $this->db->get_where('user_data', ['email' => $user_email])->row_array();
                if ($user) {
                    $this->db->where('user_id', $user['id']);
                } else {
                    $this->db->where('user_id', 0); // Should not happen if logged in
                }
            }
            $transaksi = $this->db->get('transaksi')->result_array();
            foreach ($transaksi as $tr) {
                $url = ($role_id == 1) ? base_url('transaksi/detail_admin/' . $tr['id']) : base_url('transaksi/detail/' . $tr['id']);
                $results[] = [
                    'title' => 'Transaksi #' . $tr['kode_transaksi'],
                    'url' => $url,
                    'type' => 'Transaksi'
                ];
            }
        }

        echo json_encode($results);
    }
    public function get_kitchen_status()
    {
        $db_status = $this->db->get_where('site_settings', ['setting_key' => 'kitchen_status'])->row_array();
        $status = $db_status ? (int)$db_status['setting_value'] : 1;
        
        $response = [
            'status' => $status,
            'text' => '',
            'color' => '',
            'icon' => ''
        ];
        
        if($status == 1) {
            $response['text'] = 'DAPUR SANTAI (EST. 5-10 MENIT)';
            $response['color'] = '#4ade80'; 
            $response['icon'] = 'fa-smile';
        } elseif($status == 2) {
            $response['text'] = 'DAPUR SIBUK (EST. 15-20 MENIT)';
            $response['color'] = '#facc15'; 
            $response['icon'] = 'fa-stopwatch';
        } else {
            $response['text'] = 'DAPUR NGEBUL (EST. 30+ MENIT)';
            $response['color'] = '#f87171'; 
            $response['icon'] = 'fa-fire';
        }
        
        echo json_encode($response);
    }
}
