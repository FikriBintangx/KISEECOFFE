<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->model('Auditlog_model');
        
        // Set headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx: unbuffered responses suitable for Comet/HTTP Streaming
        
        // Disable time limit for long polling
        set_time_limit(0);
        
        // Start output buffering
        if (ob_get_level() == 0) {
            ob_start();
        }
    }

    // Stream notifications for the current user
    public function stream() {
        // Only allow logged-in users
        if (!$this->session->userdata('user_id')) {
            $this->_send_sse_message('error', ['message' => 'Unauthorized']);
            exit();
        }
        
        $user_id = $this->session->userdata('user_id');
        $last_event_id = $this->input->get('last_event_id') ?: 0;
        
        // Log the connection
        $this->auditlog_model->create([
            'user_id' => $user_id,
            'action' => 'sse_connect',
            'table_name' => 'notifications',
            'new_value' => json_encode(['last_event_id' => $last_event_id])
        ]);
        
        // Send a comment to keep the connection alive
        echo ":\n\n";
        ob_flush();
        flush();
        
        // Check for new notifications every second for 30 seconds
        $start_time = time();
        $max_duration = 30; // seconds
        
        while ((time() - $start_time) < $max_duration) {
            // Get unread notifications since last event
            $this->db->where('user_id', $user_id);
            $this->db->where('id >', $last_event_id);
            $this->db->order_by('created_at', 'DESC');
            $notifications = $this->db->get('notifications')->result();
            
            if (!empty($notifications)) {
                foreach ($notifications as $notification) {
                    $this->_send_notification($notification);
                    $last_event_id = max($last_event_id, $notification->id);
                }
                
                ob_flush();
                flush();
                
                // Log the notifications sent
                $this->auditlog_model->create([
                    'user_id' => $user_id,
                    'action' => 'notifications_sent',
                    'table_name' => 'notifications',
                    'record_id' => $last_event_id,
                    'new_value' => json_encode(['count' => count($notifications)])
                ]);
                
                // Mark notifications as read
                $this->notification_model->mark_all_as_read($user_id);
                
                // Reset the timer since we sent data
                $start_time = time();
            }
            
            // Sleep for 1 second before checking again
            sleep(1);
        }
        
        // Send a keep-alive comment
        echo ":keep-alive\n\n";
        ob_flush();
        flush();
    }
    
    // Get all notifications for the current user
    public function get_notifications() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('user_id');
        $offset = $this->input->get('offset') ?: 0;
        $limit = $this->input->get('limit') ?: 10;
        
        $notifications = $this->notification_model->get_user_notifications($user_id, $limit, $offset);
        $unread_count = $this->notification_model->get_unread_count($user_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'data' => $notifications,
                'unread_count' => $unread_count
            ]));
    }
    
    // Mark a notification as read
    public function mark_as_read($notification_id) {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->mark_as_read($notification_id, $user_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'unread_count' => $this->notification_model->get_unread_count($user_id)
            ]));
    }
    
    // Mark all notifications as read
    public function mark_all_read() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->session->userdata('user_id');
        $success = $this->notification_model->mark_all_as_read($user_id);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $success,
                'unread_count' => 0
            ]));
    }
    
    // Send a test notification (admin only)
    public function test($user_id = null) {
        if (!$this->session->userdata('role_id') != 1) {
            show_404();
        }
        
        $user_id = $user_id ?: $this->session->userdata('user_id');
        
        $notification_id = $this->notification_model->create([
            'user_id' => $user_id,
            'title' => 'Test Notification',
            'message' => 'This is a test notification sent at ' . date('Y-m-d H:i:s'),
            'type' => 'info'
        ]);
        
        if ($notification_id) {
            echo "Notification sent successfully! ID: $notification_id";
        } else {
            echo "Failed to send notification";
        }
    }
    
    // Send a notification to all users (admin only)
    public function broadcast() {
        if (!$this->session->userdata('role_id') != 1) {
            show_404();
        }
        
        $title = $this->input->post('title');
        $message = $this->input->post('message');
        $type = $this->input->post('type') ?: 'info';
        
        if (empty($title) || empty($message)) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Title and message are required'
                ]));
            return;
        }
        
        // Get all active users
        $users = $this->db->select('id')
            ->from('user')
            ->where('is_active', 1)
            ->get()
            ->result();
        
        $count = 0;
        foreach ($users as $user) {
            $this->notification_model->create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'type' => $type
            ]);
            $count++;
        }
        
        // Log the broadcast
        $this->auditlog_model->create([
            'user_id' => $this->session->userdata('user_id'),
            'action' => 'notification_broadcast',
            'table_name' => 'notifications',
            'new_value' => json_encode([
                'title' => $title,
                'recipient_count' => $count
            ])
        ]);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'message' => "Notification sent to $count users",
                'count' => $count
            ]));
    }
    
    // Helper method to send SSE message
    private function _send_sse_message($event, $data) {
        echo "event: $event\n";
        echo 'data: ' . json_encode($data) . "\n\n";
        ob_flush();
        flush();
    }
    
    // Helper method to format and send a notification
    private function _send_notification($notification) {
        $data = [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'type' => $notification->type,
            'is_read' => (bool)$notification->is_read,
            'created_at' => $notification->created_at,
            'time_ago' => $this->_time_elapsed_string($notification->created_at)
        ];
        
        $this->_send_sse_message('notification', $data);
    }
    
    // Helper method to format time as "X minutes/hours/days ago"
    private function _time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = [
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        ];
        
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }
        
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
    }
}
