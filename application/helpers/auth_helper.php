<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('is_logged_in')) {
    function is_logged_in()
    {
        $ci = get_instance();
        if (!$ci->session->userdata('email')) {
            redirect('auth');
        }
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        $ci = get_instance();
        if ($ci->session->userdata('role_id') != 1) {
            redirect('auth/blocked');
        }
    }
}
