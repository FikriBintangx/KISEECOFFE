<?php
require_once APPPATH . 'controllers/api/Api_Controller.php';

class Auth extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // Endpoint: POST /api/auth/login
    public function login()
    {
        // Terima input bisa dari Form Data (Postman/Retrofit form) atau JSON Body
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // Jika kosong, coba ambil dari JSON raw body
        if (!$email) {
            $input = $this->json_input();
            $email = isset($input['email']) ? $input['email'] : '';
            $password = isset($input['password']) ? $input['password'] : '';
        }

        if (empty($email) || empty($password)) {
            return $this->response([
                'status' => false,
                'message' => 'Email dan Password wajib diisi'
            ], 400);
        }

        $user = $this->User_model->find_by_email($email);

        if ($user) {
            if ($user['is_active'] == 0) {
                return $this->response([
                    'status' => false,
                    'message' => 'Akun belum diaktivasi'
                ], 401);
            }

            if ($user['password'] && password_verify($password, $user['password'])) {
                // Login Sukses
                
                // Hapus data sensitif sebelum dikirim ke HP
                unset($user['password']);
                
                return $this->response([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'data' => $user
                ], 200);
            } else {
                return $this->response([
                    'status' => false,
                    'message' => 'Password salah'
                ], 401);
            }
        } else {
            return $this->response([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }
    }

    // Endpoint: POST /api/auth/register
    public function register()
    {
        $input = $this->json_input();
        if(empty($input)) {
            $input = $this->input->post();
        }
        
        $nama = isset($input['nama']) ? $input['nama'] : '';
        $email = isset($input['email']) ? $input['email'] : '';
        $password = isset($input['password']) ? $input['password'] : '';
        $no_telepon = isset($input['no_telepon']) ? $input['no_telepon'] : '';

        // Validasi Simple
        if(empty($nama) || empty($email) || empty($password)) {
             return $this->response(['status' => false, 'message' => 'Data Nama, Email, dan Password wajib diisi'], 400);
        }

        // Cek email ada atau ngga
        $existing = $this->User_model->find_by_email($email);
        if($existing) {
             return $this->response(['status' => false, 'message' => 'Email sudah terdaftar'], 400);
        }

        $data = [
            'nama' => htmlspecialchars($nama),
            'email' => htmlspecialchars($email),
            'image' => 'default.png',
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role_id' => 2, // Default Member
            'is_active' => 1,
            'date_created' => time(),
            'no_telepon' => htmlspecialchars($no_telepon),
            'auth_provider' => 'local'
        ];

        if ($this->db->insert('user_data', $data)) {
            return $this->response([
                'status' => true, 
                'message' => 'Registrasi Berhasil',
                'user_id' => $this->db->insert_id()
            ], 201);
        } else {
            return $this->response(['status' => false, 'message' => 'Gagal mendaftar, kesalahan server'], 500);
        }
    }
}
