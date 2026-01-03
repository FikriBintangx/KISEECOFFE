<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
    }

    public function get_or_create_cart($user_id)
    {
        // SAFETY: Dummy cart if table missing
        if (!$this->db->table_exists('transaksi')) {
            return ['id' => 0, 'total_harga' => 0]; 
        }

        $cart = $this->db
            ->where('user_id', $user_id)
            ->where('status', 'pending')
            ->get('transaksi')
            ->row_array();

        if ($cart) {
            return $cart;
        }

        $data = [
            'kode_transaksi' => strtoupper(random_string('alnum', 10)),
            'user_id' => $user_id,
            'total_harga' => 0,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ];

        $this->db->insert('transaksi', $data);
        return $this->db
            ->get_where('transaksi', ['id' => $this->db->insert_id()])
            ->row_array();
    }

    public function add_item($transaksi_id, $makanan_id, $harga)
    {
        $item = $this->db
            ->where('transaksi_id', $transaksi_id)
            ->where('makanan_id', $makanan_id)
            ->get('detail_transaksi')
            ->row_array();

        if ($item) {
            $this->db
                ->set('jumlah', 'jumlah+1', false)
                ->set('subtotal', 'subtotal+'.$harga, false)
                ->where('id', $item['id'])
                ->update('detail_transaksi');
        } else {
            $this->db->insert('detail_transaksi', [
                'transaksi_id' => $transaksi_id,
                'makanan_id' => $makanan_id,
                'jumlah' => 1,
                'harga_satuan' => $harga,
                'subtotal' => $harga
            ]);
        }
    }

    public function get_cart_items($transaksi_id)
    {
        // SAFETY CHECK: Prevent crash if tables missing
        if (!$this->db->table_exists('detail_transaksi') || !$this->db->table_exists('menu')) {
            return [];
        }

        return $this->db
            ->select('detail_transaksi.*, menu.nama, menu.gambar')
            ->from('detail_transaksi')
            ->join('menu', 'menu.id = detail_transaksi.makanan_id', 'left')
            ->where('transaksi_id', $transaksi_id)
            ->get()
            ->result_array();
    }

    public function hitung_total($transaksi_id)
    {
        $this->db->select_sum('subtotal');
        $row = $this->db
            ->where('transaksi_id', $transaksi_id)
            ->get('detail_transaksi')
            ->row_array();

        return isset($row['subtotal']) ? $row['subtotal'] : 0;
    }

    public function update_transaksi($id, $data)
    {
        $this->db->where('id', $id)->update('transaksi', $data);
    }

    public function get_riwayat($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->where('status !=', 'pending')
            ->order_by('created_at', 'DESC')
            ->get('transaksi')
            ->result_array();
    }

    public function get_transaksi($id)
    {
        return $this->db->where('id', $id)->get('transaksi')->row_array();
    }

    public function remove_item($detail_id)
    {
        $this->db->where('id', $detail_id)->delete('detail_transaksi');
    }

    /**
     * Get all transaksi for admin dengan filter
     */
    public function get_all_for_admin($filters = [])
    {
        $this->db->select('transaksi.*, user_data.nama as nama_user, user_data.email')
                  ->from('transaksi')
                  ->join('user_data', 'user_data.id = transaksi.user_id')
                  ->order_by('transaksi.created_at', 'DESC');

        if (isset($filters['status']) && $filters['status'] != '') {
            $this->db->where('transaksi.status', $filters['status']);
        }

        if (isset($filters['payment_status']) && $filters['payment_status'] != '') {
            $this->db->where('transaksi.payment_status', $filters['payment_status']);
        }

        if (isset($filters['tanggal_awal']) && $filters['tanggal_awal'] != '') {
            $this->db->where('DATE(transaksi.created_at) >=', $filters['tanggal_awal']);
        }

        if (isset($filters['tanggal_akhir']) && $filters['tanggal_akhir'] != '') {
            $this->db->where('DATE(transaksi.created_at) <=', $filters['tanggal_akhir']);
        }

        return $this->db->get()->result_array();
    }

    /**
     * Get transaksi dengan data user untuk admin
     */
    public function get_with_user($transaksi_id)
    {
        return $this->db->select('transaksi.*, user_data.nama as nama_user, user_data.email')
                        ->from('transaksi')
                        ->join('user_data', 'user_data.id = transaksi.user_id')
                        ->where('transaksi.id', $transaksi_id)
                        ->get()
                        ->row_array();
    }

    /**
     * Get detail transaksi
     */
    public function get_detail($transaksi_id)
    {
        return $this->get_cart_items($transaksi_id);
    }

    /**
     * Update status transaksi
     */
    public function update_status($transaksi_id, $status, $payment_status = null)
    {
        $data = ['status' => $status];
        
        if ($payment_status !== null && $payment_status != '') {
            $data['payment_status'] = $payment_status;
        }

        $this->db->where('id', $transaksi_id)->update('transaksi', $data);
    }
}
