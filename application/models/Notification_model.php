<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    protected $table = 'notifications';

    public function __construct() {
        parent::__construct();
    }

    // Create a new notification
    public function create($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Get all notifications for a user
    public function get_user_notifications($user_id, $limit = 10, $offset = 0, $unread_only = false) {
        $this->db->where('user_id', $user_id);
        
        if ($unread_only) {
            $this->db->where('is_read', 0);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get($this->table)->result();
    }

    // Mark notification as read
    public function mark_as_read($notification_id, $user_id = null) {
        $data = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $notification_id);
        
        if ($user_id) {
            $this->db->where('user_id', $user_id);
        }
        
        return $this->db->update($this->table, $data);
    }

    // Mark all notifications as read for a user
    public function mark_all_as_read($user_id) {
        $data = [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        
        return $this->db->update($this->table, $data);
    }

    // Get unread notification count for a user
    public function get_unread_count($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        return $this->db->count_all_results($this->table);
    }

    // Send email notification (BYPASSED FOR DEMO STABILITY)
    public function send_email_notification($to_email, $subject, $message) {
        $this->load->library('email');
        
        // AMAN: Setup dummy/safe config tanpa getenv
        // Mode Demo: Tidak kirim email asli untuk mencegah error koneksi
        return true; 
        
        /* 
        // Original Code (disimpan untuk referensi hosting nanti)
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'mail.example.com',
            'smtp_port'   => 465,
            'smtp_user'   => 'user@example.com',
            'smtp_pass'   => 'secret',
            'smtp_crypto' => 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n"
        ];

        $this->email->initialize($config);
        $this->email->from('no-reply@kiisecoffee.com', 'Kiise Coffee System');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        
        return $this->email->send();
        */
    }
}
