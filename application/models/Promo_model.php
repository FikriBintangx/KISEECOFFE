<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo_model extends CI_Model {

    protected $table = 'promo';

    public function __construct() {
        parent::__construct();
    }

    // Get all active promos
    public function get_active_promos() {
        $now = date('Y-m-d');
        $this->db->where('is_active', 1);
        $this->db->where('tanggal_mulai <=', $now);
        $this->db->where('tanggal_berakhir >=', $now);
        $this->db->group_start();
        $this->db->where('kuota IS NULL');
        $this->db->or_where('digunakan < kuota', null, false);
        $this->db->group_end();
        
        return $this->db->get($this->table)->result();
    }

    // Get promo by code
    public function get_by_code($code) {
        $now = date('Y-m-d');
        $this->db->where('kode_promo', $code);
        $this->db->where('is_active', 1);
        $this->db->where('tanggal_mulai <=', $now);
        $this->db->where('tanggal_berakhir >=', $now);
        $this->db->group_start();
        $this->db->where('kuota IS NULL');
        $this->db->or_where('digunakan < kuota', null, false);
        $this->db->group_end();
        
        return $this->db->get($this->table)->row();
    }

    // Apply promo to cart
    public function apply_promo($promo_code, $total_amount) {
        $promo = $this->get_by_code($promo_code);
        
        if (!$promo) {
            return [
                'success' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa'
            ];
        }
        
        // Check minimum purchase
        if ($total_amount < $promo->minimal_pembelian) {
            return [
                'success' => false,
                'message' => 'Minimal pembelian Rp ' . number_format($promo->minimal_pembelian, 0, ',', '.')
            ];
        }
        
        // Calculate discount
        if ($promo->jenis_diskon == 'persen') {
            $discount = ($promo->nilai_diskon / 100) * $total_amount;
            
            // Apply maximum discount if set
            if ($promo->maksimal_diskon > 0 && $discount > $promo->maksimal_diskon) {
                $discount = $promo->maksimal_diskon;
            }
        } else {
            $discount = $promo->nilai_diskon;
        }
        
        // Ensure discount doesn't exceed total amount
        if ($discount > $total_amount) {
            $discount = $total_amount;
        }
        
        return [
            'success' => true,
            'promo_id' => $promo->id,
            'kode_promo' => $promo->kode_promo,
            'nama_promo' => $promo->nama_promo,
            'diskon' => $discount,
            'total_akhir' => $total_amount - $discount
        ];
    }

    // Increment promo usage
    public function increment_usage($promo_id) {
        $this->db->set('digunakan', 'digunakan + 1', false);
        $this->db->where('id', $promo_id);
        $this->db->update($this->table);
        
        return $this->db->affected_rows() > 0;
    }

    // CRUD operations for admin
    public function get_all($filters = [], $limit = 10, $offset = 0) {
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('kode_promo', $filters['search']);
            $this->db->or_like('nama_promo', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('is_active', $filters['is_active']);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get($this->table)->result();
    }
    
    public function count_all($filters = []) {
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('kode_promo', $filters['search']);
            $this->db->or_like('nama_promo', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('is_active', $filters['is_active']);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }
    
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
