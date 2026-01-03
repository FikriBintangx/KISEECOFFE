<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_sudah_masuk();
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Count Data Dasar
        $data['total_user'] = $this->db->count_all('user_data');
        $data['total_role'] = $this->db->count_all('user_role');
        $data['total_menu'] = $this->db->count_all('user_menu');
        $data['total_sub_menu'] = $this->db->count_all('user_sub_menu');
        
        $tables = $this->db->list_tables();
        
        // Data Makanan & ALERT STOK REALTIME
        if (in_array('menu', $tables) || $this->db->table_exists('menu')) {
            $data['total_makanan'] = $this->db->count_all('menu');
            
            // Statistik Kategori
            $this->db->select('kategori, COUNT(*) as jumlah');
            $this->db->group_by('kategori');
            $query_kat = $this->db->get('menu');
            $data['makanan_per_kategori'] = $query_kat ? $query_kat->result_array() : [];

            // ALERT: Ambil item dengan stok <= 5 secara langsung (REALTIME)
            // Menggunakan query builder fresh untuk menghindari cache result
            $this->db->reset_query(); 
            // SAFETY: Try catch manually by checking if column exists first could be expensive, 
            // so we just run unsafe query but handle the result.
            $this->db->where('stok <=', 5);
            $this->db->order_by('stok', 'ASC');
            $query_stok = $this->db->get('menu');
            
            if ($query_stok) {
                $data['stok_menipis'] = $query_stok->result_array();
            } else {
                // If query fails (e.g. column 'stok' missing), return empty to avoid crash
                $data['stok_menipis'] = [];
            }
        } else {
            $data['total_makanan'] = 0;
            $data['makanan_per_kategori'] = [];
            $data['stok_menipis'] = [];
        }

        // --- AMBIL STATUS DAPUR ---
        // SAFETY: Check if table exists to prevent crash if migration missed
        $kitchen_status = 1; // Default OPEN
        if ($this->db->table_exists('site_settings')) {
            $query = $this->db->get_where('site_settings', ['setting_key' => 'kitchen_status']);
            if ($query && $query->num_rows() > 0) {
                $row = $query->row_array();
                $kitchen_status = (int)$row['setting_value'];
            }
        }
        $data['kitchen_status'] = $kitchen_status;
        // --- AMBIL STATUS TOKO (MANUAL OVERRIDE) ---
        // SAFETY: Check if table exists to prevent crash if migration missed
        $shop_override_status = 'auto'; // Default AUTO
        if ($this->db->table_exists('site_settings')) {
            $query = $this->db->get_where('site_settings', ['setting_key' => 'shop_status']);
            if ($query && $query->num_rows() > 0) {
                $row = $query->row_array();
                $shop_override_status = $row['setting_value'];
            }
        }
        $data['shop_override_status'] = $shop_override_status;
        // --------------------------

        // Data Transaksi
        if (in_array('transaksi', $tables)) {
            $data['total_transaksi'] = $this->db->count_all('transaksi');
            
            // Total Penjualan (Hanya status selesai/paid jika perlu, tapi disini ambil semua total_harga)
            $this->db->select_sum('total_harga');
            $query = $this->db->get('transaksi');
            $row = $query->row();
            $data['total_penjualan'] = isset($row->total_harga) ? $row->total_harga : 0;
            
            // Transaksi Hari Ini
            $today = date('Y-m-d');
            $this->db->where('DATE(created_at)', $today);
            $data['transaksi_hari_ini'] = $this->db->count_all_results('transaksi');
            
            // Penjualan Hari Ini
            $this->db->select_sum('total_harga');
            $this->db->where('DATE(created_at)', $today);
            $query_today = $this->db->get('transaksi');
            $row_today = $query_today->row();
            $data['penjualan_hari_ini'] = isset($row_today->total_harga) ? $row_today->total_harga : 0;
        } else {
            $data['total_transaksi'] = 0;
            $data['total_penjualan'] = 0;
            $data['transaksi_hari_ini'] = 0;
            $data['penjualan_hari_ini'] = 0;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/index', $data);
        $this->load->view('layout/footer');
    }

    public function role()
    {
        $data['title'] = 'Role Akses';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required|is_unique[user_role.role]', [
            'required' => 'Nama Role tidak boleh kosong',
            'is_unique' => 'Role ' . $this->input->post('role') .  ' sudah ada!'
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/topbar');
            $this->load->view('layout/sidebar');
            $this->load->view('admin/role', $data);
            $this->load->view('layout/footer');
        } else {
            $role_name = $this->input->post('role');
            $this->db->insert('user_role', ['role' => $role_name]);
            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Role <b>' . $role_name . '</b> berhasil ditambahkan!</div>');
            redirect('admin/role');
        }
    }

    public function role_akses($role_id)
    {
        $data['title'] = 'Role Akses';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/role_akses', $data);
        $this->load->view('layout/footer');
    }

    public function role_ubah()
    {
        $id = $this->input->post('id');
        $role = $this->input->post('role');
        $this->db->where('id', $id);
        $this->db->update('user_role', ['role' => $role]);
        $this->session->set_flashdata('message', '<div class="alert alert-warning neu-brutalism mb-4">Role berhasil diubah!</div>');
        redirect('admin/role');
    }

    public function role_hapus()
    {
        $role_id = $this->uri->segment(3);
        $this->db->where('id', $role_id);
        $this->db->delete('user_role');
        $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Role berhasil dihapus!</div>');
        redirect("admin/role");
    }

    public function ubah_akses()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');
        $data = ['role_id' => $role_id, 'menu_id' => $menu_id];
        $result = $this->db->get_where('user_access_menu', $data);
        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Akses berhasil diubah!</div>');
    }

    public function get_role($id)
    {
        $data = $this->db->get_where('user_role', ['id' => $id])->row();
        echo json_encode($data);
    }
    
    public function role_user()
    {
        $data['title'] = 'Role User';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->model('User_model', 'user');
        $data['users'] = $this->user->getAllUserRole();
        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/role_user', $data);
        $this->load->view('layout/footer');
    }

    public function role_user_ubah()
    {
        $id = $this->input->post('id');
        $role_id = $this->input->post('role_id');
        $this->db->where('id', $id);
        $this->db->update('user_data', ['role_id' => $role_id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Role user berhasil diubah!</div>');
        redirect("admin/role_user");
    }

    public function get_user_role($id)
    {
        $this->load->model('User_model', 'user');
        $user = $this->user->getUserRole($id);
        $roles = $this->db->get('user_role')->result();
        $user->roles = $roles;
        echo json_encode($user);
    }

    public function update_kitchen_status()
    {
        $status = $this->input->post('status');
        
        // Cek apakah setting sudah ada
        $check = $this->db->get_where('site_settings', ['setting_key' => 'kitchen_status'])->row_array();
        
        if ($check) {
            $this->db->where('setting_key', 'kitchen_status');
            $this->db->update('site_settings', ['setting_value' => $status]);
        } else {
            $this->db->insert('site_settings', [
                'setting_key' => 'kitchen_status',
                'setting_value' => $status
            ]);
        }
        
        $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Status Dapur berhasil diupdate!</div>');
        redirect('admin');
    }

    public function update_shop_status()
    {
        $status = $this->input->post('status'); // auto, open, closed
        
        // Cek apakah setting sudah ada
        $check = $this->db->get_where('site_settings', ['setting_key' => 'shop_status'])->row_array();
        
        if ($check) {
            $this->db->where('setting_key', 'shop_status');
            $this->db->update('site_settings', ['setting_value' => $status]);
        } else {
            $this->db->insert('site_settings', [
                'setting_key' => 'shop_status',
                'setting_value' => $status
            ]);
        }
        
        $msg = "Status Toko berhasil diubah ke: " . strtoupper($status);
        if($status == 'auto') $msg .= " (Mengikuti Jam Operasional 08:00 - 22:00)";
        
        $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">'. $msg .'</div>');
        redirect('admin');
    }

    public function profile()
    {
        $data['title'] = 'Profile Admin';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        
        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar');
        $this->load->view('layout/sidebar');
        $this->load->view('admin/profile', $data);
        $this->load->view('layout/footer');
    }

    public function update_profile()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim', [
            'required' => 'Nama tidak boleh kosong'
        ]);

        if ($this->form_validation->run() == false) {
            $this->profile();
        } else {
            $nama = $this->input->post('nama');
            $email = $this->input->post('email');

            // Cek jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|jpeg|png|heic|webp';
                $config['max_size']      = 5120; // 5MB
                $config['upload_path']   = './assets/img/profile/';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $this->input->post('old_image');
                    if ($old_image != 'default.jpg' && $old_image != 'default.png') {
                        if(file_exists(FCPATH . 'assets/img/profile/' . $old_image)){
                            unlink(FCPATH . 'assets/img/profile/' . $old_image);
                        }
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism">' . $this->upload->display_errors() . '</div>');
                    redirect('admin/profile');
                }
            }

            $this->db->set('nama', $nama);
            $this->db->where('email', $email);
            $this->db->update('user_data');

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism">Profil berhasil diperbarui!</div>');
            redirect('admin/profile');
        }
    }

    public function change_password()
    {
        $this->form_validation->set_rules('current_password', 'Password Saat Ini', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'Password Baru', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Konfirmasi Password', 'required|trim|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->profile();
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

            if (!password_verify($current_password, $user['password'])) {
                $this->session->set_flashdata('message_password', '<div class="alert alert-danger neu-brutalism">Password saat ini salah!</div>');
                redirect('admin/profile');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message_password', '<div class="alert alert-warning neu-brutalism">Password baru tidak boleh sama dengan password lama!</div>');
                    redirect('admin/profile');
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user_data');

                    $this->session->set_flashdata('message_password', '<div class="alert alert-success neu-brutalism">Password berhasil diubah!</div>');
                    redirect('admin/profile');
                }
            }
        }
    }

    public function upload_dashboard_photo()
    {
        $config['upload_path'] = FCPATH . 'assets/img/dashboard/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['file_name'] = 'dashboard.jpg';
        $config['overwrite'] = true;
        
        $this->load->library('upload', $config);
        
        if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, true);
        
        if ($this->upload->do_upload('dashboard_photo')) {
            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Foto dashboard berhasil diupdate!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">' . $this->upload->display_errors() . '</div>');
        }
        redirect('admin');
    }

    public function get_realtime_stats()
    {
        // 1. Total Transaksi (Indikator Pesanan Baru)
        $total_transaksi = $this->db->count_all('transaksi');
        
        // 2. Transaksi pending? (Opsional, saat ini pakai total count saja cukup untuk trigger)
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'total_transaksi' => $total_transaksi,
            'timestamp' => time()
        ]);
    }
}
