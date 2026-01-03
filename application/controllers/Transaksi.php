<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->library('cart');
    }

    public function index()
    {
        $data['title'] = 'Daftar Menu';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Load Settings
        $db_text = $this->db->get_where('site_settings', ['setting_key' => 'running_text'])->row_array();
        $data['running_text'] = $db_text ? $db_text['setting_value'] : '';

        $db_status = $this->db->get_where('site_settings', ['setting_key' => 'kitchen_status'])->row_array();
        $data['kitchen_status'] = $db_status ? (int)$db_status['setting_value'] : 1;

        $data['makanan'] = $this->db->get('menu')->result_array();

        // Hitung Cart
        $data['cart_count'] = 0;
        $data['cart_total'] = 0;
        if ($data['user']) {
            $cart = $this->Transaksi_model->get_or_create_cart($data['user']['id']);
            $items = $this->Transaksi_model->get_cart_items($cart['id']);
            $data['cart_total'] = $this->Transaksi_model->hitung_total($cart['id']);
            
            if($items) {
                foreach($items as $item) {
                    $data['cart_count'] += $item['jumlah'];
                }
            }
        }

        $this->load->view('customer/layout/header', $data);
        // $this->load->view('layout/topbar', $data); // Disable Admin Topbar
        // $this->load->view('layout/sidebar', $data); // Disable Admin Sidebar
        $this->load->view('customer/index', $data);
        $this->load->view('layout/footer');
    }

    public function keranjang()
    {
        cek_sudah_masuk();
        $data['title'] = 'Keranjang Belanja';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        
        $cart = $this->Transaksi_model->get_or_create_cart($data['user']['id']);
        $data['cart'] = $cart;
        $data['items'] = $this->Transaksi_model->get_cart_items($cart['id']);
        $data['total'] = $this->Transaksi_model->hitung_total($cart['id']);

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('transaksi/keranjang', $data);
        $this->load->view('layout/footer');
    }

    public function checkout()
    {
        cek_sudah_masuk();
        $data['title'] = 'Checkout';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        
        $cart = $this->Transaksi_model->get_or_create_cart($data['user']['id']);
        $data['cart'] = $cart;
        $data['items'] = $this->Transaksi_model->get_cart_items($cart['id']);
        $data['total'] = $this->Transaksi_model->hitung_total($cart['id']);

        if (empty($data['items'])) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Keranjang belanja kosong!</div>');
            redirect('transaksi');
        }

        $this->form_validation->set_rules('jenis_pesanan', 'Jenis Pesanan', 'required');
        $this->form_validation->set_rules('metode_pembayaran', 'Metode Pembayaran', 'required');
        
        if ($this->input->post('jenis_pesanan') == 'Delivery') {
            $this->form_validation->set_rules('alamat', 'Alamat Pengiriman', 'required|trim');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('transaksi/checkout', $data);
            $this->load->view('layout/footer');
        } else {
            $jenis_pesanan = $this->input->post('jenis_pesanan');
            $alamat = $this->input->post('alamat');
            $catatan = $this->input->post('catatan');
            $metode = $this->input->post('metode_pembayaran');
            $total = $data['total'];
            $bukti_bayar = null;

            // Handle File Upload if QRIS
            if ($metode == 'QRIS') {
                if (empty($_FILES['bukti_bayar']['name'])) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Bukti pembayaran wajib diupload untuk metode QRIS!</div>');
                    redirect('transaksi/checkout');
                    return;
                }

                $config['upload_path'] = './assets/uploads/payments/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['encrypt_name'] = TRUE;

                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0755, true);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('bukti_bayar')) {
                    $bukti_bayar = $this->upload->data('file_name');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Gagal upload bukti: ' . $this->upload->display_errors() . '</div>');
                    redirect('transaksi/checkout');
                    return;
                }
            }
            
            $update_data = [
                'jenis_pesanan' => $jenis_pesanan,
                'alamat_pengiriman' => $alamat,
                'catatan' => $catatan,
                'metode_pembayaran' => $metode,
                'total_harga' => $total,
                'bukti_bayar' => $bukti_bayar
            ];
            
            // Pengurangan Stok
            $items = $this->Transaksi_model->get_cart_items($cart['id']);
            foreach ($items as $item) {
                $this->db->set('stok', 'stok - ' . $item['jumlah'], FALSE);
                $this->db->where('id', $item['makanan_id']);
                $this->db->update('menu');
            }

            if ($metode == 'QRIS') {
                // Karena sudah upload bukti, status langsung 'diproses' & 'awaiting_verification'
                // Admin akan cek bukti bayar
                $update_data['payment_status'] = 'awaiting_verification';
                $update_data['status'] = 'diproses';
                $this->Transaksi_model->update_transaksi($cart['id'], $update_data);
                
                $this->session->set_flashdata('message', '<div class="alert alert-info neu-brutalism mb-4"><i class="fas fa-info-circle"></i> <strong>Pesanan berhasil!</strong> Bukti pembayaran diterima. Menunggu verifikasi admin.</div>');
                redirect('transaksi/resi/' . $cart['id']);

            } else {
                // Tunai
                $update_data['payment_status'] = 'unpaid';
                $update_data['status'] = 'diproses';
                $this->Transaksi_model->update_transaksi($cart['id'], $update_data);
                
                $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4"><i class="fas fa-check-circle"></i> <strong>Pesanan berhasil dibuat!</strong> Silakan tunjukkan resi ini ke kasir.</div>');
                redirect('transaksi/resi/' . $cart['id']);
            }
        }
    }

    public function tambah_keranjang()
    {
        if (!$this->session->userdata('email')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Silakan login terlebih dahulu untuk memesan!']);
                return;
            }
            redirect('auth');
        }

        // BLOCK ADMIN from ordering
        if ($this->session->userdata('role_id') == 1) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Admin tidak dapat melakukan pemesanan!']);
                return;
            }
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Admin tidak dapat melakukan pemesanan!</div>');
            redirect('transaksi');
        }
        
        // cek_sudah_masuk(); // Skip validasi menu ketat untuk customer order
        $makanan_id = $this->input->post('makanan_id');
        $jumlah = $this->input->post('jumlah') ? $this->input->post('jumlah') : 1;

        $makanan = $this->db->get_where('menu', ['id' => $makanan_id])->row_array();
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        if ($makanan && $user) {
            if ($makanan['stok'] < $jumlah) {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Stok tidak mencukupi!']);
                    return;
                }
                $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Stok tidak mencukupi!</div>');
                redirect('transaksi');
                return;
            }

            $cart = $this->Transaksi_model->get_or_create_cart($user['id']);
            
            // Loop sebanyak jumlah (fallback jika logic add_item hanya increment +1)
            // Sebaiknya add_item dimodifikasi biar terima 'jumlah' sekaligus, tapi kita ikutin logic awal user dulu
            for ($i = 0; $i < $jumlah; $i++) {
                $this->Transaksi_model->add_item($cart['id'], $makanan['id'], $makanan['harga']);
            }
            
            $total = $this->Transaksi_model->hitung_total($cart['id']);
            $this->Transaksi_model->update_transaksi($cart['id'], ['total_harga' => $total]);

            // Hitung Cart Items untuk response AJAX
            $cart_count = 0;
            $items = $this->Transaksi_model->get_cart_items($cart['id']);
            foreach($items as $item) $cart_count += $item['jumlah'];

            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Menu berhasil ditambahkan ke keranjang!',
                    'cart_count' => $cart_count,
                    'cart_total' => $total
                ]); 
                return;
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Menu ditambahkan!</div>');
            redirect('transaksi/keranjang'); 
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Menu tidak ditemukan!']);
                return;
            }
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Menu tidak ditemukan!</div>');
        }

        redirect('transaksi/keranjang');
    }

    public function update_item()
    {
        cek_sudah_masuk();
        $detail_id = $this->input->post('detail_id');
        $jumlah = $this->input->post('jumlah');

        $detail = $this->db->get_where('detail_transaksi', ['id' => $detail_id])->row_array();
        
        if ($detail && $jumlah > 0) {
            $subtotal = $detail['harga_satuan'] * $jumlah;
            $this->db->where('id', $detail_id)->update('detail_transaksi', [
                'jumlah' => $jumlah,
                'subtotal' => $subtotal
            ]);

            $total = $this->Transaksi_model->hitung_total($detail['transaksi_id']);
            $this->Transaksi_model->update_transaksi($detail['transaksi_id'], ['total_harga' => $total]);

            echo json_encode(['status' => 'success', 'total' => $total]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function hapus_item($detail_id)
    {
        cek_sudah_masuk();
        $detail = $this->db->get_where('detail_transaksi', ['id' => $detail_id])->row_array();
        
        if ($detail) {
            $transaksi_id = $detail['transaksi_id'];
            $this->db->where('id', $detail_id)->delete('detail_transaksi');

            $total = $this->Transaksi_model->hitung_total($transaksi_id);
            $this->Transaksi_model->update_transaksi($transaksi_id, ['total_harga' => $total]);

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Item dihapus!</div>');
        }

        redirect('transaksi/keranjang');
    }

    public function generate_qris()
    {
        cek_sudah_masuk();
        ob_clean();
        header('Content-Type: application/json');
        
        $total = $this->input->post('total') ?: $this->input->get('total');
        $qr_url = base_url('assets/img/qris/QRIS.jpg');
        
        echo json_encode([
            'status' => 'success',
            'qr_url' => $qr_url,
            'amount' => $total,
            'message' => 'Silakan scan QRIS manual.'
        ]);
        exit;
    }

    public function konfirmasi_qris()
    {
        cek_sudah_masuk();
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $cart_id = $this->input->post('cart_id');
        $total = $this->input->post('total');
        
        $cart = $this->db->get_where('transaksi', ['id' => $cart_id, 'user_id' => $user['id']])->row_array();
        
        if (!$cart) {
            redirect('transaksi/keranjang');
        }

        $items = $this->Transaksi_model->get_cart_items($cart_id);
        foreach ($items as $item) {
            $this->db->set('stok', 'stok - ' . $item['jumlah'], FALSE);
            $this->db->where('id', $item['makanan_id']);
            $this->db->update('menu');
        }

        $auto_approve_limit = 50000; 

        if ($total <= $auto_approve_limit) {
            $this->Transaksi_model->update_transaksi($cart_id, [
                'metode_pembayaran' => 'qris',
                'payment_status' => 'paid',
                'status' => 'selesai'
            ]);
            
            // [POIN] Tambah poin karena lunas otomatis
            $this->_tambah_poin($user['id'], $total, $cart_id);
            
            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Pembayaran berhasil! Poin ditambahkan.</div>');
            redirect('transaksi/resi/' . $cart_id);
            
        } else {
            $this->Transaksi_model->update_transaksi($cart_id, [
                'metode_pembayaran' => 'qris',
                'payment_status' => 'awaiting_verification',
                'status' => 'diproses'
            ]);
            
            $this->session->set_flashdata('message', '<div class="alert alert-info neu-brutalism mb-4">Konfirmasi berhasil! Menunggu verifikasi admin.</div>');
            redirect('transaksi/riwayat');
        }
    }

    public function riwayat()
    {
        cek_sudah_masuk();
        $this->check_auto_approve_customer();
        
        $data['title'] = 'Riwayat Transaksi';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $data['transaksi'] = $this->Transaksi_model->get_riwayat($data['user']['id']);

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('transaksi/riwayat', $data);
        $this->load->view('layout/footer', $data);
    }

    public function detail($id)
    {
        cek_sudah_masuk();
        $data['title'] = 'Detail Transaksi';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $data['transaksi'] = $this->Transaksi_model->get_transaksi($id);
        $data['items'] = $this->Transaksi_model->get_cart_items($id);

        if (!$data['transaksi'] || $data['transaksi']['user_id'] != $data['user']['id']) {
            redirect('transaksi/riwayat');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('transaksi/detail', $data);
        $this->load->view('layout/footer');
    }

    public function resi($id)
    {
        cek_sudah_masuk();
        $data['title'] = 'Resi Pembayaran';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        $data['transaksi'] = $this->Transaksi_model->get_transaksi($id);
        $data['items'] = $this->Transaksi_model->get_cart_items($id);

        if (!$data['transaksi'] || $data['transaksi']['user_id'] != $data['user']['id']) {
            redirect('transaksi/riwayat');
        }

        // if ($data['transaksi']['payment_status'] != 'paid') {
        //     redirect('transaksi/detail/'.$id);
        // }

        $this->load->view('transaksi/resi', $data);
    }

    public function upload_bukti()
    {
        cek_sudah_masuk();
        $transaksi_id = $this->input->post('transaksi_id');
        
        $config['upload_path'] = './assets/uploads/payments/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('bukti_bayar')) {
            $file_name = $this->upload->data('file_name');
            
            $this->Transaksi_model->update_transaksi($transaksi_id, [
                'bukti_bayar' => $file_name,
                'status' => 'diproses',
                'payment_status' => 'awaiting_verification'
            ]);
            
            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Bukti diupload! Menunggu admin.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Gagal: ' . $this->upload->display_errors() . '</div>');
        }
        
        redirect('transaksi/detail/' . $transaksi_id);
    }

    // --- ADMIN AREA ---
    public function verify_payment()
    {
        if (!$this->session->userdata('email')) redirect('auth');
        
        $transaksi_id = $this->input->post('transaksi_id');
        $action = $this->input->post('action'); // 'approve' atau 'reject'

        if ($action == 'approve') {
            $this->Transaksi_model->update_transaksi($transaksi_id, [
                'payment_status' => 'paid',
                'status' => 'diproses'
            ]);
            
            // [POIN] Tambah poin saat admin approve
            // Ambil data transaksi untuk tau user_id dan total
            $trx = $this->Transaksi_model->get_transaksi($transaksi_id);
            if ($trx) {
                $this->_tambah_poin($trx['user_id'], $trx['total_harga'], $transaksi_id);
            }

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Pembayaran disetujui & Poin dikirim!</div>');
        } elseif ($action == 'reject') {
            $items = $this->Transaksi_model->get_cart_items($transaksi_id);
            foreach ($items as $item) {
                $this->db->set('stok', 'stok + ' . $item['jumlah'], FALSE);
                $this->db->where('id', $item['makanan_id']);
                $this->db->update('menu');
            }

            $this->Transaksi_model->update_transaksi($transaksi_id, [
                'payment_status' => 'failed',
                'status' => 'dibatalkan'
            ]);
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Pembayaran ditolak.</div>');
        }

        redirect('transaksi/detail_admin/' . $transaksi_id);
    }

    private function check_auto_approve_customer()
    {
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        if (!$user) return;
        
        $five_minutes_ago = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        
        $pending_transactions = $this->db
            ->where('user_id', $user['id'])
            ->where('metode_pembayaran', 'qris')
            ->where('payment_status', 'awaiting_verification')
            ->where('created_at <=', $five_minutes_ago)
            ->where('total_harga <=', 100000) 
            ->get('transaksi')
            ->result_array();
        
        foreach ($pending_transactions as $transaksi) {
            $this->Transaksi_model->update_transaksi($transaksi['id'], [
                'payment_status' => 'paid',
                'status' => 'selesai'
            ]);
            
            // [POIN] Tambah poin saat auto-approve system
            $this->_tambah_poin($user['id'], $transaksi['total_harga'], $transaksi['id']);
        }
    }

    // --- FUNGSI TAMBAHAN: HITUNG & UPDATE POIN ---
    private function _tambah_poin($user_id, $total_belanja, $transaksi_id)
    {
        // 1. Hitung Poin: 10.000 = 1 Poin (dibulatkan ke bawah)
        $poin_didapat = floor($total_belanja / 10000);

        if ($poin_didapat > 0) {
            // Cek apakah transaksi ini sudah pernah dapat poin (mencegah duplikat poin saat refresh/double approve)
            $cek_log = $this->db->get_where('riwayat_poin', ['transaksi_id' => $transaksi_id])->num_rows();
            
            if ($cek_log == 0) {
                // 2. Update Poin User
                $this->db->set('poin', 'poin + ' . $poin_didapat, FALSE);
                $this->db->where('id', $user_id);
                $this->db->update('user_data');

                // 3. Catat Riwayat
                $data_history = [
                    'user_id' => $user_id,
                    'transaksi_id' => $transaksi_id,
                    'poin' => $poin_didapat,
                    'tipe' => 'masuk',
                    'keterangan' => 'Reward Transaksi #' . $transaksi_id,
                    'tanggal' => date('Y-m-d H:i:s')
                ];
                $this->db->insert('riwayat_poin', $data_history);
            }
        }
    }

    // Fungsi Admin (Tetap Disertakan Agar Tidak Error)
    public function kelola()
    {
        if (!$this->session->userdata('email')) redirect('auth');
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        if (!$user || $user['role_id'] != 1) redirect('auth/blocked');

        $data['title'] = 'Kelola Transaksi';
        $data['user'] = $user;
        $filters = [];
        $status = $this->input->get('status');
        if ($status && $status != '') $filters['status'] = $status;

        $data['transaksi'] = $this->Transaksi_model->get_all_for_admin($filters);
        $data['status_filter'] = $status;
        $data['tanggal_awal'] = $this->input->get('tanggal_awal');
        $data['tanggal_akhir'] = $this->input->get('tanggal_akhir');

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('transaksi/kelola', $data);
        $this->load->view('layout/footer');
    }

    public function detail_admin($id)
    {
        if (!$this->session->userdata('email')) redirect('auth');
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        if (!$user || $user['role_id'] != 1) redirect('auth/blocked');

        $data['title'] = 'Detail Transaksi';
        $data['user'] = $user;
        $data['transaksi'] = $this->Transaksi_model->get_with_user($id);
        $data['detail'] = $this->Transaksi_model->get_cart_items($id);

        if (!$data['transaksi']) redirect('transaksi/kelola');

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('transaksi/detail_admin', $data);
        $this->load->view('layout/footer');
    }

    public function update_status()
    {
        if (!$this->session->userdata('email')) redirect('auth');
        $user = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        if (!$user || $user['role_id'] != 1) redirect('auth/blocked');

        $transaksi_id = $this->input->post('transaksi_id');
        $status = $this->input->post('status');
        $payment_status = $this->input->post('payment_status');

        $this->Transaksi_model->update_status($transaksi_id, $status, $payment_status);
        
        // Opsional: Jika admin ubah status manual ke 'Selesai' dan Payment 'Paid' lewat tombol Edit, bisa panggil _tambah_poin di sini juga.
        
        $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Status diupdate!</div>');
        redirect('transaksi/detail_admin/' . $transaksi_id);
    }

    public function resi_pdf($id)
    {
        $this->load->library('Pdf_export');
        $transaksi = $this->Transaksi_model->get_transaksi($id);
        $items = $this->Transaksi_model->get_cart_items($id);

        if (!$transaksi) redirect('transaksi/riwayat');

        $html = '<div style="font-family: Arial, sans-serif; padding: 20px;">
                    <h2 style="text-align: center; margin-bottom: 20px;">RESI PEMBAYARAN</h2>
                    <hr style="border: 2px solid #000; margin-bottom: 20px;">
                    <table style="width: 100%; margin-bottom: 20px;">
                        <tr><td><strong>Kode:</strong></td><td>' . $transaksi['kode_transaksi'] . '</td></tr>
                        <tr><td><strong>Tanggal:</strong></td><td>' . date('d F Y H:i:s', strtotime($transaksi['created_at'])) . '</td></tr>
                        <tr><td><strong>Metode:</strong></td><td>' . strtoupper($transaksi['metode_pembayaran']) . '</td></tr>
                        <tr><td><strong>Status:</strong></td><td>' . strtoupper($transaksi['payment_status']) . '</td></tr>
                    </table>
                    <table border="1" cellpadding="8" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <thead>
                            <tr style="background-color:#f2f2f2">
                                <th style="text-align: left;">Produk</th><th style="text-align: right;">Harga</th><th style="text-align: center;">Qty</th><th style="text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach ($items as $item) {
            $html .= '<tr><td>' . $item['nama'] . '</td><td style="text-align: right;">Rp ' . number_format($item['harga_satuan'], 0, ',', '.') . '</td><td style="text-align: center;">' . $item['jumlah'] . '</td><td style="text-align: right;">Rp ' . number_format($item['subtotal'], 0, ',', '.') . '</td></tr>';
        }
        $html .= '</tbody><tfoot><tr><th colspan="3" style="text-align: right;">TOTAL:</th><th style="text-align: right;">Rp ' . number_format($transaksi['total_harga'], 0, ',', '.') . '</th></tr></tfoot></table>
                    <p style="text-align: center; margin-top: 30px;"><strong>Terima kasih telah berbelanja!</strong></p>
                </div>';

        $filename = 'Resi_' . $transaksi['kode_transaksi'] . '_' . date('YmdHis');
        $this->pdf_export->export_pdf($filename, $html, 'Resi - ' . $transaksi['kode_transaksi']);
    }
}
