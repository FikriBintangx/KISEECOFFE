<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_sudah_masuk();
        // Pastikan hanya admin yang bisa akses
        if ($this->session->userdata('role_id') != 1) {
            redirect('auth/blocked');
        }
    }

    public function index()
    {
        $data['title'] = 'Laporan';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/index', $data);
        $this->load->view('layout/footer');
    }

    public function makanan()
    {
        $data['title'] = 'Laporan Menu Makanan';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Filter berdasarkan kategori jika ada
        $kategori = $this->input->get('kategori');
        if ($kategori && $kategori != '') {
            $this->db->where('kategori', $kategori);
        }

        // SAFETY: Check if 'menu' exists
        if ($this->db->table_exists('menu')) {
            $data['makanan'] = $this->db->get('menu')->result_array();
            $data['kategori'] = $this->db->select('kategori')->distinct()->get('menu')->result_array();
        } else {
            $data['makanan'] = [];
            $data['kategori'] = [];
        }
        $data['kategori_filter'] = $kategori;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/makanan', $data);
        $this->load->view('layout/footer');
    }

    public function user()
    {
        $data['title'] = 'Laporan Data User';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Filter berdasarkan role jika ada
        $role_id = $this->input->get('role_id');
        if ($role_id && $role_id != '') {
            $this->db->where('role_id', $role_id);
        }

        // SAFETY: Check tables
        $data['users'] = [];
        $data['roles'] = [];
        
        if ($this->db->table_exists('user_data') && $this->db->table_exists('user_role')) {
            $this->db->select('user_data.*, user_role.role');
            $this->db->from('user_data');
            $this->db->join('user_role', 'user_data.role_id = user_role.id');
            $data['users'] = $this->db->get()->result_array();
            $data['roles'] = $this->db->get('user_role')->result_array();
        }
        $data['role_filter'] = $role_id;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/user', $data);
        $this->load->view('layout/footer');
    }

    public function penjualan()
    {
        $data['title'] = 'Laporan Penjualan';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Filter berdasarkan periode (harian/bulanan)
        $periode = $this->input->get('periode'); // 'harian', 'bulanan', 'custom'
        
        $tanggal_awal = '';
        $tanggal_akhir = '';

        if ($periode == 'harian') {
            $tanggal_awal = $this->input->get('tanggal_harian_awal');
            $tanggal_akhir = $this->input->get('tanggal_harian_akhir');
            // Jika tanggal akhir kosong, samakan dengan tanggal awal (untuk single date behavior jika user cuma isi satu)
            if (empty($tanggal_akhir)) {
                $tanggal_akhir = $tanggal_awal;
            }
        } elseif ($periode == 'bulanan') {
            $bulan = $this->input->get('bulan');
            if ($bulan) {
                $tanggal_awal = $bulan . '-01';
                $tanggal_akhir = date('Y-m-t', strtotime($tanggal_awal));
            }
        } elseif ($periode == 'custom') {
            $tanggal_awal = $this->input->get('tanggal_custom_awal');
            $tanggal_akhir = $this->input->get('tanggal_custom_akhir');
        } else {
            // Default fallback if parameters come from export links (which use tanggal_awal/akhir directly)
            $tanggal_awal = $this->input->get('tanggal_awal');
            $tanggal_akhir = $this->input->get('tanggal_akhir');
        }

        // Filter berdasarkan status
        $status = $this->input->get('status');
        if ($status && $status != '') {
            $this->db->where('status', $status);
        }

        // Cek apakah tabel transaksi ada
        $tables = $this->db->list_tables();
        $has_transaksi = in_array('transaksi', $tables);

        if ($has_transaksi) {
            // Query transaksi dengan join user_data
            $this->db->select('transaksi.*, user_data.nama as nama_user, user_data.email');
            $this->db->from('transaksi');
            $this->db->join('user_data', 'transaksi.user_id = user_data.id');
            
            if ($tanggal_awal && $tanggal_akhir) {
                $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
                $this->db->where('DATE(transaksi.created_at) <=', $tanggal_akhir);
            } elseif ($tanggal_awal) {
                $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
            }

            $this->db->order_by('transaksi.created_at', 'DESC');
            $data['penjualan'] = $this->db->get()->result_array();

            // Hitung total penjualan
            $this->db->select_sum('total_harga');
            if ($tanggal_awal && $tanggal_akhir) {
                $this->db->where('DATE(created_at) >=', $tanggal_awal);
                $this->db->where('DATE(created_at) <=', $tanggal_akhir);
            } elseif ($tanggal_awal) {
                $this->db->where('DATE(created_at) >=', $tanggal_awal);
            }
            if ($status && $status != '') {
                $this->db->where('status', $status);
            }
            $total_result = $this->db->get('transaksi')->row();
            $data['total_penjualan'] = $total_result->total_harga ? $total_result->total_harga : 0;

            // Hitung jumlah transaksi
            $data['jumlah_transaksi'] = count($data['penjualan']);
        } else {
            $data['penjualan'] = [];
            $data['total_penjualan'] = 0;
            $data['jumlah_transaksi'] = 0;
            $data['info'] = 'Tabel transaksi belum tersedia. Silakan jalankan query SQL di file database/add_transaksi_laporan.sql';
        }

        $data['tanggal_awal'] = $tanggal_awal;
        $data['tanggal_akhir'] = $tanggal_akhir;
        $data['status_filter'] = $status;
        $data['periode'] = $periode;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/penjualan', $data);
        $this->load->view('layout/footer');
    }

    public function detail_user_transaksi($user_id)
    {
        $data['title'] = 'Riwayat Transaksi User';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();
        
        // Get Info User
        $data['target_user'] = $this->db->get_where('user_data', ['id' => $user_id])->row_array();

        if(!$data['target_user']) {
            redirect('laporan/penjualan');
        }

        // --- FILTER LOGIC ---
        $filter_type = $this->input->get('filter_type');
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $month = $this->input->get('month');

        $this->db->where('user_id', $user_id);

        if ($filter_type == 'daily') {
            if ($date_start && $date_end) {
                $this->db->where('DATE(created_at) >=', $date_start);
                $this->db->where('DATE(created_at) <=', $date_end);
            } elseif ($date_start) {
                 $this->db->where('DATE(created_at)', $date_start);
            }
        } elseif ($filter_type == 'monthly') {
            if ($month) {
                // $month format YYYY-MM
                $this->db->like('created_at', $month, 'after'); 
            }
        }

        $this->db->order_by('created_at', 'DESC');
        $data['riwayat'] = $this->db->get('transaksi')->result_array();

        // Pass filter data back to view
        $data['filter_type'] = $filter_type;
        $data['date_start'] = $date_start;
        $data['date_end'] = $date_end;
        $data['month'] = $month;

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/detail_user_transaksi', $data);
        $this->load->view('layout/footer');
    }

    // ============================================
    // EXPORT EXCEL
    // ============================================

    public function export_excel_makanan()
    {
        $this->load->library('Excel_export');
        
        // Filter berdasarkan kategori jika ada
        $kategori = $this->input->get('kategori');
        if ($kategori && $kategori != '') {
            $this->db->where('kategori', $kategori);
        }
        
        $makanan = $this->db->get('menu')->result_array();
        
        $headers = ['No', 'Nama', 'Kategori', 'Harga', 'Deskripsi'];
        $data = [];
        
        $no = 1;
        foreach ($makanan as $m) {
            $data[] = [
                $no++,
                $m['nama'],
                $m['kategori'],
                'Rp ' . number_format($m['harga'], 0, ',', '.'),
                $m['deskripsi']
            ];
        }
        
        $filename = 'Laporan_Menu_Makanan_' . date('Ymd_His');
        $this->excel_export->export_excel($filename, $data, $headers, 'Laporan Menu Makanan - KiiseCoffee');
    }

    public function export_excel_user()
    {
        $this->load->library('Excel_export');
        
        // Filter berdasarkan role jika ada
        $role_id = $this->input->get('role_id');
        if ($role_id && $role_id != '') {
            $this->db->where('role_id', $role_id);
        }
        
        $this->db->select('user_data.*, user_role.role');
        $this->db->from('user_data');
        $this->db->join('user_role', 'user_data.role_id = user_role.id');
        $users = $this->db->get()->result_array();
        
        $headers = ['No', 'Nama', 'Email', 'Role', 'Tanggal Daftar'];
        $data = [];
        
        $no = 1;
        foreach ($users as $u) {
            $data[] = [
                $no++,
                $u['nama'],
                $u['email'],
                $u['role'],
                date('d F Y', $u['date_created'])
            ];
        }
        
        $filename = 'Laporan_Data_User_' . date('Ymd_His');
        $this->excel_export->export_excel($filename, $data, $headers, 'Laporan Data User - KiiseCoffee');
    }

    public function export_excel_penjualan()
    {
        $this->load->library('Excel_export');
        
        // Filter berdasarkan tanggal jika ada
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $status = $this->input->get('status');
        
        $this->db->select('transaksi.*, user_data.nama as nama_user, user_data.email');
        $this->db->from('transaksi');
        $this->db->join('user_data', 'transaksi.user_id = user_data.id');
        
        if ($tanggal_awal && $tanggal_akhir) {
            $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
            $this->db->where('DATE(transaksi.created_at) <=', $tanggal_akhir);
        } elseif ($tanggal_awal) {
            $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
        }
        
        if ($status && $status != '') {
            $this->db->where('status', $status);
        }
        
        $this->db->order_by('transaksi.created_at', 'DESC');
        $penjualan = $this->db->get()->result_array();
        
        $headers = ['No', 'Tanggal', 'Kode Transaksi', 'User', 'Email', 'Total', 'Status', 'Metode Pembayaran'];
        $data = [];
        
        $no = 1;
        $total_penjualan = 0;
        foreach ($penjualan as $p) {
            $total_penjualan += $p['total_harga'];
            $data[] = [
                $no++,
                date('d F Y H:i', strtotime($p['created_at'])),
                $p['kode_transaksi'],
                $p['nama_user'],
                $p['email'],
                'Rp ' . number_format($p['total_harga'], 0, ',', '.'),
                ucfirst($p['status']),
                $p['metode_pembayaran'] ? $p['metode_pembayaran'] : '-'
            ];
        }
        
        // Tambah total di akhir
        $data[] = ['', '', '', '', 'TOTAL:', 'Rp ' . number_format($total_penjualan, 0, ',', '.'), '', ''];
        
        $filename = 'Laporan_Penjualan_' . date('Ymd_His');
        $title = 'Laporan Penjualan - KiiseCoffee';
        if ($tanggal_awal && $tanggal_akhir) {
            $title .= ' (Periode: ' . date('d F Y', strtotime($tanggal_awal)) . ' - ' . date('d F Y', strtotime($tanggal_akhir)) . ')';
        }
        $this->excel_export->export_excel($filename, $data, $headers, $title);
    }

    // ============================================
    // EXPORT PDF
    // ============================================

    public function export_pdf_makanan()
    {
        $this->load->library('Pdf_export');
        
        // Filter berdasarkan kategori jika ada
        $kategori = $this->input->get('kategori');
        if ($kategori && $kategori != '') {
            $this->db->where('kategori', $kategori);
        }
        
        $makanan = $this->db->get('menu')->result_array();
        
        $headers = ['No', 'Nama', 'Kategori', 'Harga', 'Deskripsi'];
        $data = [];
        
        $no = 1;
        foreach ($makanan as $m) {
            $data[] = [
                $no++,
                $m['nama'],
                $m['kategori'],
                'Rp ' . number_format($m['harga'], 0, ',', '.'),
                $m['deskripsi']
            ];
        }
        
        $title = 'Laporan Menu Makanan - KiiseCoffee';
        $html = $this->pdf_export->generate_table_html($title, $headers, $data);
        
        $filename = 'Laporan_Menu_Makanan_' . date('Ymd_His');
        $this->pdf_export->export_pdf($filename, $html, $title);
    }

    public function export_pdf_user()
    {
        $this->load->library('Pdf_export');
        
        // Filter berdasarkan role jika ada
        $role_id = $this->input->get('role_id');
        if ($role_id && $role_id != '') {
            $this->db->where('role_id', $role_id);
        }
        
        $this->db->select('user_data.*, user_role.role');
        $this->db->from('user_data');
        $this->db->join('user_role', 'user_data.role_id = user_role.id');
        $users = $this->db->get()->result_array();
        
        $headers = ['No', 'Nama', 'Email', 'Role', 'Tanggal Daftar'];
        $data = [];
        
        $no = 1;
        foreach ($users as $u) {
            $data[] = [
                $no++,
                $u['nama'],
                $u['email'],
                $u['role'],
                date('d F Y', $u['date_created'])
            ];
        }
        
        $title = 'Laporan Data User - KiiseCoffee';
        $html = $this->pdf_export->generate_table_html($title, $headers, $data);
        
        $filename = 'Laporan_Data_User_' . date('Ymd_His');
        $this->pdf_export->export_pdf($filename, $html, $title);
    }

    public function export_excel_detail_user($user_id)
    {
        $this->load->library('Excel_export');
        
        $user = $this->db->get_where('user_data', ['id' => $user_id])->row_array();
        if(!$user) redirect('laporan/penjualan');

        // --- FILTER LOGIC ---
        $filter_type = $this->input->get('filter_type');
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $month = $this->input->get('month');

        $this->db->where('user_id', $user_id);

        if ($filter_type == 'daily') {
            if ($date_start && $date_end) {
                $this->db->where('DATE(created_at) >=', $date_start);
                $this->db->where('DATE(created_at) <=', $date_end);
            } elseif ($date_start) {
                 $this->db->where('DATE(created_at)', $date_start);
            }
        } elseif ($filter_type == 'monthly') {
            if ($month) {
                // $month format YYYY-MM
                $this->db->like('created_at', $month, 'after'); 
            }
        }

        $this->db->order_by('created_at', 'DESC');
        $riwayat = $this->db->get('transaksi')->result_array();
        
        $headers = ['No', 'Tanggal', 'Kode Transaksi', 'Total', 'Status', 'Metode'];
        $data = [];
        
        $no = 1;
        $total_amount = 0;
        foreach ($riwayat as $r) {
            $total_amount += $r['total_harga'];
            $data[] = [
                $no++,
                date('d F Y H:i', strtotime($r['created_at'])),
                $r['id'], // Using ID as code for now
                'Rp ' . number_format($r['total_harga'], 0, ',', '.'),
                strtoupper($r['status']),
                $r['metode_pembayaran'] ? strtoupper($r['metode_pembayaran']) : '-'
            ];
        }

        // Add total row
        $data[] = ['', '', 'TOTAL', 'Rp ' . number_format($total_amount, 0, ',', '.'), '', ''];
        
        $filename = 'Riwayat_Transaksi_' . str_replace(' ', '_', $user['nama']) . '_' . date('Ymd_His');
        $title = 'Riwayat Transaksi: ' . $user['nama'];
        
        $this->excel_export->export_excel($filename, $data, $headers, $title);
    }

    public function export_pdf_detail_user($user_id)
    {
        $this->load->library('Pdf_export');
        
        $user = $this->db->get_where('user_data', ['id' => $user_id])->row_array();
        if(!$user) redirect('laporan/penjualan');

        // --- FILTER LOGIC ---
        $filter_type = $this->input->get('filter_type');
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $month = $this->input->get('month');

        $this->db->where('user_id', $user_id);

        if ($filter_type == 'daily') {
            if ($date_start && $date_end) {
                $this->db->where('DATE(created_at) >=', $date_start);
                $this->db->where('DATE(created_at) <=', $date_end);
            } elseif ($date_start) {
                 $this->db->where('DATE(created_at)', $date_start);
            }
        } elseif ($filter_type == 'monthly') {
            if ($month) {
                // $month format YYYY-MM
                $this->db->like('created_at', $month, 'after'); 
            }
        }

        $this->db->order_by('created_at', 'DESC');
        $riwayat = $this->db->get('transaksi')->result_array();
        
        $headers = ['No', 'Tanggal', 'Kode', 'Total', 'Status', 'Metode'];
        $data = [];
        
        $no = 1;
        $total_amount = 0;
        foreach ($riwayat as $r) {
            $total_amount += $r['total_harga'];
            $data[] = [
                $no++,
                date('d F Y H:i', strtotime($r['created_at'])),
                '#' . $r['id'],
                'Rp ' . number_format($r['total_harga'], 0, ',', '.'),
                strtoupper($r['status']),
                $r['metode_pembayaran'] ? strtoupper($r['metode_pembayaran']) : '-'
            ];
        }
        
        $title = 'Riwayat Transaksi: ' . $user['nama'];
        $footer = 'Total Transaksi: Rp ' . number_format($total_amount, 0, ',', '.');
        $html = $this->pdf_export->generate_table_html($title, $headers, $data, $footer);
        
        $filename = 'Riwayat_Transaksi_' . str_replace(' ', '_', $user['nama']) . '_' . date('Ymd_His');
        $this->pdf_export->export_pdf($filename, $html, $title);
    }

    public function export_pdf_penjualan()
    {
        $this->load->library('Pdf_export');
        
        // Filter berdasarkan tanggal jika ada
        $tanggal_awal = $this->input->get('tanggal_awal');
        $tanggal_akhir = $this->input->get('tanggal_akhir');
        $status = $this->input->get('status');
        
        $this->db->select('transaksi.*, user_data.nama as nama_user, user_data.email');
        $this->db->from('transaksi');
        $this->db->join('user_data', 'transaksi.user_id = user_data.id');
        
        if ($tanggal_awal && $tanggal_akhir) {
            $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
            $this->db->where('DATE(transaksi.created_at) <=', $tanggal_akhir);
        } elseif ($tanggal_awal) {
            $this->db->where('DATE(transaksi.created_at) >=', $tanggal_awal);
        }
        
        if ($status && $status != '') {
            $this->db->where('status', $status);
        }
        
        $this->db->order_by('transaksi.created_at', 'DESC');
        $penjualan = $this->db->get()->result_array();
        
        $headers = ['No', 'Tanggal', 'Kode Transaksi', 'User', 'Total', 'Status'];
        $data = [];
        
        $no = 1;
        $total_penjualan = 0;
        foreach ($penjualan as $p) {
            $total_penjualan += $p['total_harga'];
            $data[] = [
                $no++,
                date('d F Y H:i', strtotime($p['created_at'])),
                $p['kode_transaksi'],
                $p['nama_user'],
                'Rp ' . number_format($p['total_harga'], 0, ',', '.'),
                ucfirst($p['status'])
            ];
        }
        
        $title = 'Laporan Penjualan - KiiseCoffee';
        if ($tanggal_awal && $tanggal_akhir) {
            $title .= ' (Periode: ' . date('d F Y', strtotime($tanggal_awal)) . ' - ' . date('d F Y', strtotime($tanggal_akhir)) . ')';
        }
        
        $footer = 'Total Penjualan: Rp ' . number_format($total_penjualan, 0, ',', '.') . ' | Jumlah Transaksi: ' . count($penjualan);
        $html = $this->pdf_export->generate_table_html($title, $headers, $data, $footer);
        
        $filename = 'Laporan_Penjualan_' . date('Ymd_His');
        $this->pdf_export->export_pdf($filename, $html, $title);
    }

    public function dashboard()
    {
        $data['title'] = 'Laporan Dashboard';
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        // Statistik lengkap
        // Statistik lengkap - SAFETY CHECKS
        $data['total_user'] = $this->db->table_exists('user_data') ? $this->db->from('user_data')->count_all_results() : 0;
        $data['total_makanan'] = $this->db->table_exists('menu') ? $this->db->from('menu')->count_all_results() : 0;
        $data['total_role'] = $this->db->table_exists('user_role') ? $this->db->from('user_role')->count_all_results() : 0;
        $data['total_menu'] = $this->db->table_exists('user_menu') ? $this->db->from('user_menu')->count_all_results() : 0;

        // Statistik Transaksi
        $tables = $this->db->list_tables();
        if (in_array('transaksi', $tables)) {
            $data['total_transaksi'] = $this->db->from('transaksi')->count_all_results();
            
            // Total penjualan
            $this->db->select_sum('total_harga');
            $total_result = $this->db->get('transaksi')->row();
            $data['total_penjualan'] = $total_result->total_harga ? $total_result->total_harga : 0;
            
            // Transaksi per status
            $this->db->select('status, COUNT(*) as jumlah');
            $this->db->from('transaksi');
            $this->db->group_by('status');
            $data['transaksi_per_status'] = $this->db->get()->result_array();
            
            // Transaksi hari ini
            $this->db->where('DATE(created_at)', date('Y-m-d'));
            $data['transaksi_hari_ini'] = $this->db->from('transaksi')->count_all_results();
            
            // Penjualan hari ini
            $this->db->select_sum('total_harga');
            $this->db->where('DATE(created_at)', date('Y-m-d'));
            $penjualan_hari_ini = $this->db->get('transaksi')->row();
            $data['penjualan_hari_ini'] = $penjualan_hari_ini->total_harga ? $penjualan_hari_ini->total_harga : 0;
            
            // Transaksi terbaru
            $this->db->select('transaksi.*, user_data.nama as nama_user');
            $this->db->from('transaksi');
            $this->db->join('user_data', 'transaksi.user_id = user_data.id');
            $this->db->order_by('transaksi.created_at', 'DESC');
            $this->db->limit(5);
            $data['transaksi_terbaru'] = $this->db->get()->result_array();
        } else {
            $data['total_transaksi'] = 0;
            $data['total_penjualan'] = 0;
            $data['transaksi_per_status'] = [];
            $data['transaksi_hari_ini'] = 0;
            $data['penjualan_hari_ini'] = 0;
            $data['transaksi_terbaru'] = [];
        }

        // Makanan per kategori
        $data['makanan_per_kategori'] = [];
        if($this->db->table_exists('menu')) {
            $this->db->select('kategori, COUNT(*) as jumlah');
            $this->db->from('menu');
            $this->db->group_by('kategori');
            $data['makanan_per_kategori'] = $this->db->get()->result_array();
        }

        // User per role
        $data['user_per_role'] = [];
        if($this->db->table_exists('user_role') && $this->db->table_exists('user_data')) {
            $this->db->select('user_role.role, COUNT(user_data.id) as jumlah');
            $this->db->from('user_role');
            $this->db->join('user_data', 'user_role.id = user_data.role_id', 'left');
            $this->db->group_by('user_role.id');
            $data['user_per_role'] = $this->db->get()->result_array();
        }

        // Makanan terbaru
        $data['makanan_terbaru'] = [];
        if($this->db->table_exists('menu')) {
            $this->db->order_by('id', 'DESC');
            $this->db->limit(5);
            $data['makanan_terbaru'] = $this->db->get('menu')->result_array();
        }

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('laporan/dashboard', $data);
        $this->load->view('layout/footer');
    }
}

