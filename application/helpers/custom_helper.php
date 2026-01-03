<?php

function cek_sudah_masuk()
{
    $ci = get_instance();
    if (!$ci->session->userdata('email')) {
        redirect('auth');
    } else {
        // use role_id based on database
        $email = $ci->session->userdata('email');
        $role_id_query = $ci->db->query("SELECT `user_data`.`role_id` FROM `user_data` WHERE `user_data`.`email` = '$email'")->row_array();
        $role_id = $role_id_query['role_id'];

        $menu = $ci->uri->segment(1);

        // 1. Cek match di user_menu (Header Menu)
        $queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
        
        $menu_id = 0;
        if ($queryMenu) {
            $menu_id = $queryMenu['id'];
        } else {
            // 2. Jika tidak ada di Header, cek di Sub Menu (Controller)
            // Mencari menu_id berdasarkan URL controller
            $querySubMenu = $ci->db->liKe('url', $menu, 'both')->get('user_sub_menu')->row_array();
            if ($querySubMenu) {
                $menu_id = $querySubMenu['menu_id'];
            }
        }

        if ($menu_id > 0) {
            $userAccess = $ci->db->get_where('user_access_menu', [
                'role_id' => $role_id,
                'menu_id' => $menu_id
            ]);

            if ($userAccess->num_rows() < 1) {
                redirect('auth/blocked');
            }
        }
    }
}

function cek_akses($role_id, $menu_id)
{
    $ci = get_instance();

    $ci->db->where('role_id', $role_id);
    $ci->db->where('menu_id', $menu_id);
    $result = $ci->db->get('user_access_menu');

    if ($result->num_rows() > 0) {
        return "checked='checked'";
    }
}
