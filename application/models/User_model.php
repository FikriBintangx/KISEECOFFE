<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    /**
     * Get user by email
     * @param string $email
     * @return array|null
     */
    public function find_by_email($email)
    {
        return $this->db->get_where('user_data', ['email' => $email])->row_array();
    }

    /**
     * Get user by provider and provider_id
     * @param string $provider
     * @param string $provider_id
     * @return array|null
     */
    public function find_by_provider($provider, $provider_id)
    {
        return $this->db->get_where('user_data', [
            'auth_provider' => $provider,
            'provider_id' => $provider_id
        ])->row_array();
    }

    /**
     * Create new user from OAuth data
     * @param array $data
     * @return int Insert ID
     */
    public function create_oauth_user($data)
    {
        $this->db->insert('user_data', $data);
        return $this->db->insert_id();
    }

    public function getAllUserRole()
    {
        $query = "SELECT `user_data`.`id`, `user_data`.`nama`, `email`, `user_role`.`role`
        FROM `user_data` JOIN `user_role` ON `user_data`.`role_id` = `user_role`.`id`";

        return $this->db->query($query)->result_array();
    }

    public function getUserRole($user_id)
    {
        $query = "SELECT `user_data`.`id`, `user_data`.`nama`, `email`, `user_role`.`id` AS `role_id`, `user_role`.`role`
        FROM `user_data` JOIN `user_role` ON `user_data`.`role_id` = `user_role`.`id` WHERE `user_data`.`id` = $user_id";

        return $this->db->query($query)->row();
    }

    public function getRoleName($role_id)
    {
        $query = "SELECT `user_role`.`role` FROM `user_role` WHERE `user_role`.`id` = $role_id";

        return $this->db->query($query)->row();
    }
}
