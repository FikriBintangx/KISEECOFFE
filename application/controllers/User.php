<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_sudah_masuk();
    }

    public function index()
    {
        $data['title'] = 'Dashboard Saya';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        
        // Hitung total transaksi user (hanya yang paid atau awaiting verification)
        $data['total_transaksi'] = $this->db
            ->where('user_id', $data['user']['id'])
            ->where_in('payment_status', ['paid', 'awaiting_verification'])
            ->count_all_results('transaksi');
        
        // Hitung total belanja user
        $total_belanja = $this->db
            ->select_sum('total_harga')
            ->where('user_id', $data['user']['id'])
            ->where('payment_status', 'paid')
            ->get('transaksi')
            ->row();
        $data['total_belanja'] = isset($total_belanja->total_harga) ? $total_belanja->total_harga : 0;
        
        // Ambil transaksi terakhir
        $data['transaksi_terakhir'] = $this->db
            ->where('user_id', $data['user']['id'])
            ->order_by('created_at', 'DESC')
            ->limit(5)
            ->get('transaksi')
            ->result_array();
        
        // Ambil menu yang paling sering dipesan
        $this->db->select('menu.id, menu.nama, menu.gambar, COUNT(detail_transaksi.id) as jumlah_order');
        $this->db->from('detail_transaksi');
        $this->db->join('menu', 'menu.id = detail_transaksi.makanan_id');
        $this->db->join('transaksi', 'transaksi.id = detail_transaksi.transaksi_id');
        $this->db->where('transaksi.user_id', $data['user']['id']);
        $this->db->where('transaksi.payment_status', 'paid');
        $this->db->group_by('menu.id');
        $this->db->order_by('jumlah_order', 'DESC');
        $this->db->limit(3);
        $data['menu_favorit'] = $this->db->get()->result_array();

        // Hitung Tier berdasarkan jumlah transaksi
        $total_orders = $data['total_transaksi'];
        if ($total_orders >= 20) {
            $data['tier'] = 'PLATINUM';
            $data['tier_color'] = '#e5e7eb'; // Silver/Platinum color
            $data['tier_icon'] = 'fa-gem';
        } elseif ($total_orders >= 10) {
            $data['tier'] = 'GOLD';
            $data['tier_color'] = '#fbbf24'; // Gold color
            $data['tier_icon'] = 'fa-crown';
        } elseif ($total_orders >= 1) {
            $data['tier'] = 'SILVER';
            $data['tier_color'] = '#9ca3af'; // Silver color
            $data['tier_icon'] = 'fa-medal';
        } else {
            $data['tier'] = 'BRONZE';
            $data['tier_color'] = '#cd7f32'; // Bronze color
            $data['tier_icon'] = 'fa-award';
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('user/dashboard', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ubah()
    {
        $data['title'] = 'Ubah Profil';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim|min_length[3]', [
            'required' => "tidak boleh kosong"
        ]);


        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/topbar');
            $this->load->view('layout/sidebar');
            $this->load->view('user/ubah');
            $this->load->view('layout/footer');
        } else {
            $nama = $this->input->post('nama');
            $email = $this->input->post('email');

            // cek jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types']    = 'gif|jpg|jpeg|png|webp|svg|bmp|avif|heic|heif';
                $config['max_size']         = 50000; // 50MB
                $config['upload_path']      = './assets/img/profile/';
                $config['file_ext_tolower'] = true;
                $config['remove_spaces']    = true;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('image')) {
                    $gambar_lama = $data['user']['image'];
                    if ($gambar_lama != "default.png" && file_exists(FCPATH . 'assets/img/profile/' . $gambar_lama)) {
                        unlink(FCPATH . 'assets/img/profile/' . $gambar_lama);
                    }
                    $gambar_baru = $this->upload->data('file_name');
                    $this->db->set('image', $gambar_baru);
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism">' . strip_tags($this->upload->display_errors('', '')) . '</div>');
                    redirect('user/ubah');
                    return;
                }
            }

            $this->db->set('nama', $nama);
            $this->db->where('email', $email);
            $this->db->update('user_data');

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism">Profil berhasil diubah!</div>');
            redirect('user');
        }
    }

    public function ganti_kata_sandi()
    {
        $data['title'] = 'Ganti Kata Sandi';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Kata Sandi', 'required|trim', [
            'required' => 'Tidak boleh kosong',
            'min_length' => 'Terlalu pendek'
        ]);

        $this->form_validation->set_rules('password1', 'Kata Sandi Baru', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Tidak boleh kosong',
            'matches' => '',
            'min_length' => 'Terlalu pendek'
        ]);

        $this->form_validation->set_rules('password2', 'Konfirmasi Kata Sandi Baru', 'required|trim|matches[password1]', [
            'required' => 'Tidak boleh kosong',
            'matches' => 'Tidak sama',
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/topbar');
            $this->load->view('layout/sidebar');
            $this->load->view('user/ganti_kata_sandi', $data);
            $this->load->view('layout/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('password1');
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism">Kata sandi yang anda masukan salah!</div>');
                redirect('user/ganti_kata_sandi');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-warning neu-brutalism">Kata sandi baru tidak bisa boleh sama dengan kata sandi saat ini!</div>');
                    redirect('user/ganti_kata_sandi');
                } else {
                    // password sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user_data');

                    $this->session->set_flashdata(
                        'message',
                        '<div class="alert alert-success neu-brutalism">Kata sandi berhasil diubah!</div>'
                    );
                    redirect('user/ganti_kata_sandi');
                }
            }
        }
    }
}
