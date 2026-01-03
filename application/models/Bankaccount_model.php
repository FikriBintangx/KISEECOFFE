<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bankaccount_model extends CI_Model {

    protected $table = 'bank_accounts';

    public function __construct() {
        parent::__construct();
    }

    // Get all active bank accounts
    public function get_active_accounts() {
        $this->db->where('is_active', 1);
        $this->db->order_by('bank_name', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Get bank account by ID
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }

    // Get all bank accounts with optional filters
    public function get_all($filters = [], $limit = 10, $offset = 0) {
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('bank_name', $filters['search']);
            $this->db->or_like('account_name', $filters['search']);
            $this->db->or_like('account_number', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('is_active', $filters['is_active']);
        }
        
        $this->db->order_by('bank_name', 'ASC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get($this->table)->result();
    }
    
    // Count all bank accounts with optional filters
    public function count_all($filters = []) {
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('bank_name', $filters['search']);
            $this->db->or_like('account_name', $filters['search']);
            $this->db->or_like('account_number', $filters['search']);
            $this->db->group_end();
        }
        
        if (isset($filters['is_active'])) {
            $this->db->where('is_active', $filters['is_active']);
        }
        
        return $this->db->count_all_results($this->table);
    }
    
    // Create a new bank account
    public function create($data) {
        return $this->db->insert($this->table, $data);
    }
    
    // Update a bank account
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    // Delete a bank account
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
    
    // Toggle account active status
    public function toggle_status($id, $status) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['is_active' => $status]);
    }
    
    // Get bank accounts for payment selection
    public function get_payment_options() {
        $accounts = $this->get_active_accounts();
        $options = [];
        
        foreach ($accounts as $account) {
            $options[$account->id] = $account->bank_name . ' - ' . $account->account_name . ' (' . $this->format_account_number($account->account_number) . ')';
        }
        
        return $options;
    }
    
    // Format account number for display (show only last 4 digits)
    private function format_account_number($number) {
        $length = strlen($number);
        if ($length <= 4) {
            return $number;
        }
        return str_repeat('*', $length - 4) . substr($number, -4);
    }
}
