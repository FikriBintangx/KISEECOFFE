<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditlog_model extends CI_Model {

    protected $table = 'audit_logs';

    public function __construct() {
        parent::__construct();
    }

    // Create a new audit log entry
    public function create($data) {
        // Get user agent and IP address
        $data['user_agent'] = $this->input->user_agent();
        $data['ip_address'] = $this->input->ip_address();
        
        // If user is logged in, get user ID
        if ($this->session->userdata('user_id')) {
            $data['user_id'] = $this->session->userdata('user_id');
        }
        
        // Convert arrays to JSON strings
        if (is_array($data['old_value'])) {
            $data['old_value'] = json_encode($data['old_value']);
        }
        
        if (is_array($data['new_value'])) {
            $data['new_value'] = json_encode($data['new_value']);
        }
        
        return $this->db->insert($this->table, $data);
    }

    // Get audit logs with filters
    public function get_logs($filters = [], $limit = 50, $offset = 0) {
        // Apply filters
        if (!empty($filters['user_id'])) {
            $this->db->where('user_id', $filters['user_id']);
        }
        
        if (!empty($filters['action'])) {
            $this->db->where('action', $filters['action']);
        }
        
        if (!empty($filters['table_name'])) {
            $this->db->where('table_name', $filters['table_name']);
        }
        
        if (!empty($filters['record_id'])) {
            $this->db->where('record_id', $filters['record_id']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        
        // Join with users table to get usernames
        $this->db->select('audit_logs.*, user.name as username, user.email as user_email');
        $this->db->join('user', 'user.id = audit_logs.user_id', 'left');
        
        // Order and limit
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get($this->table)->result();
    }

    // Get audit log by ID
    public function get_log($id) {
        $this->db->where('id', $id);
        $this->db->select('audit_logs.*, user.name as username, user.email as user_email');
        $this->db->join('user', 'user.id = audit_logs.user_id', 'left');
        return $this->db->get($this->table)->row();
    }

    // Log a model event (create, update, delete)
    public function log_model_event($action, $table, $record_id, $old_data = null, $new_data = null) {
        $log_data = [
            'action' => $action,
            'table_name' => $table,
            'record_id' => $record_id,
            'old_value' => $old_data,
            'new_value' => $new_data
        ];
        
        return $this->create($log_data);
    }

    // Log a login attempt
    public function log_login_attempt($user_id, $success = false, $email = null) {
        $action = $success ? 'login_success' : 'login_failed';
        $this->create([
            'user_id' => $user_id,
            'action' => $action,
            'table_name' => 'user',
            'record_id' => $user_id,
            'new_value' => json_encode(['email' => $email, 'success' => $success])
        ]);
    }

    // Log a password change
    public function log_password_change($user_id) {
        $this->create([
            'user_id' => $user_id,
            'action' => 'password_change',
            'table_name' => 'user',
            'record_id' => $user_id
        ]);
    }

    // Log a permission change
    public function log_permission_change($admin_id, $user_id, $changes) {
        $this->create([
            'user_id' => $admin_id,
            'action' => 'permission_change',
            'table_name' => 'user',
            'record_id' => $user_id,
            'new_value' => json_encode($changes)
        ]);
    }
}
