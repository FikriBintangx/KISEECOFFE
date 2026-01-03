<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library("form_validation");
        $this->load->model('User_model');
        // Helper auth_helper is loaded here if not autoloaded
        // assuming we might need to load it manually or it's in autoload
    }

    /**
     * Display Login Page
     */
    public function index()
    {
        $this->_sudahLogin();
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', [
            "required" => "Email tidak boleh kosong",
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required', [
            "required" => "Password tidak boleh kosong",
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Kiise Coffee - Masuk";
            $this->load->view('layout/header', $data);
            $this->load->view('auth/login');
        } else {
            $this->_masuk();
        }
    }

    /**
     * Process Local Login
     */
    private function _masuk()
    {
        $email = htmlspecialchars($this->input->post('email'), true);
        $password = $this->input->post('password');

        $user = $this->User_model->find_by_email($email);

        if ($user) {
            // Check if user is active
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                 $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Akun belum diaktivasi!</div>');
                 redirect('auth');
            }

            // Check password (only if not OAuth user without password)
            if ($user['password'] && password_verify($password, $user['password'])) {
                $data = [
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);
                $this->_redirectUser($user['role_id']);
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism"><i class="fas fa-exclamation-triangle"></i> <strong>Login Gagal!</strong> Password salah.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism"><i class="fas fa-exclamation-triangle"></i> <strong>Login Gagal!</strong> Email belum terdaftar.</div>');
            redirect('auth');
        }
    }

    /**
     * Redirect User based on Role
     */
    private function _redirectUser($role_id)
    {
        if ($role_id == 1) {
            redirect('admin');
        } elseif ($role_id == 2) {
            redirect('home');
        } else {
            redirect('user');
        }
    }

    /**
     * Check if user is already logged in
     */
    private function _sudahLogin()
    {
        if ($this->session->userdata('role_id')) {
            $this->_redirectUser($this->session->userdata('role_id'));
        }
    }

    /**
     * Process Registration
     */
    public function daftar()
    {
        $this->_sudahLogin();
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim', ['required' => 'Tidak boleh kosong']);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user_data.email]', [
            'required' => 'Tidak boleh kosong',
            'is_unique' => 'Sudah digunakan'
        ]);
        $this->form_validation->set_rules('password1', 'Kata Sandi', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Tidak boleh kosong',
            'matches' => '',
            'min_length' => 'Terlalu pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Konfirmasi Kata Sandi', 'required|trim|matches[password1]', [
            'required' => 'Tidak boleh kosong',
            'matches' => 'Tidak sama',
        ]);
        $this->form_validation->set_rules('no_telepon', 'Nomor Telepon', 'required|trim', ['required' => 'Wajib diisi']);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Kiise Coffee - Daftar";
            $this->load->view('layout/header', $data);
            $this->load->view('auth/daftar');
        } else {
            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.png',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 1,
                'date_created' => time(),
                'no_telepon' => htmlspecialchars($this->input->post('no_telepon', true)),
                'auth_provider' => 'local'
            ];

            if ($this->db->insert('user_data', $data)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success ml-4 mr-4 neu-brutalism">Akun berhasil dibuat! Silahkan Masuk</div>');
                redirect('auth');
            } else {
                 $error = $this->db->error();
                 $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Gagal membuat akun: ' . $error['message'] . '</div>');
                 redirect('auth/daftar');
            }
        }
    }

    /**
     * Logout
     */
    public function keluar()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->unset_userdata('nama');

        // Optional: Logout from HybridAuth adapters if needed (usually handled by session destroy)
        
        $this->session->set_flashdata('message', '<div class="alert alert-success ml-4 mr-4 neu-brutalism">Anda telah keluar!</div>');
        redirect('auth');
    }

    public function blocked()
    {
        $data['title'] = 'ERROR';
        $data['error_code'] = "403";
        $data['error_message'] = "Access Fobidden";
        $data['user'] = $this->db->get_where('user_data', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('auth/blocked', $data);
        $this->load->view('layout/footer');
    }

    public function forgotpassword()
    {
        $this->_sudahLogin();
        
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Kiise Coffee - Lupa Password";
            $this->load->view('layout/header', $data);
            $this->load->view('auth/forgot_password');
        } else {
            $email = $this->input->post('email', true);
            $user = $this->db->get_where('user_data', ['email' => $email, 'is_active' => 1])->row_array();

            if ($user) {
                // Generate Token
                // Generate Token (PHP 5.6 Compatible)
                $token = base64_encode(openssl_random_pseudo_bytes(32));
                $user_token = [
                    'email' => $email,
                    'token' => $token,
                    'date_created' => time()
                ];

                // Simpan token (hapus token lama jika ada)
                $this->db->delete('user_token', ['email' => $email]);
                $this->db->insert('user_token', $user_token);

                // Kirim Email
                if ($this->_sendEmail($token, 'forgot')) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success ml-4 mr-4 neu-brutalism">Silakan cek email Anda untuk reset password! (Cek folder Spam jika tidak ada)</div>');
                } else {
                     $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Gagal mengirim email. Pastikan settingan email benar atau hubungi admin.</div>');
                }
                
                redirect('auth/forgotpassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Email tidak terdaftar atau belum diaktivasi!</div>');
                redirect('auth/forgotpassword');
            }
        }
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com', // Menggunakan smtp.gmail.com
            'smtp_user' => 'kiisecoffee@gmail.com', // GANTI DENGAN EMAIL ASLI ANDA
            'smtp_pass' => 'password_app_anda',     // PASTI DIGANTI DENGAN APP PASSWORD 16 DIGIT
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'crlf'      => "\r\n"
        ];
        
        // Load library dengan config SMTP
        $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->from('no-reply@kiisecoffee.com', 'Kiise Coffee System');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {
            $this->email->subject('Verifikasi Akun');
            $this->email->message('Klik link ini untuk verifikasi akun Anda: <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Aktivasi</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('
                <div style="font-family: Arial, sans-serif; padding: 20px; border: 3px solid #000; max-width: 600px; margin: 0 auto; background: #fff;">
                    <h2 style="text-transform: uppercase; font-weight: 900; margin-bottom: 20px;">Lupa Password?</h2>
                    <p>Seseorang meminta untuk mereset password akun Kiise Coffee Anda.</p>
                    <p>Klik tombol di bawah ini untuk mengganti password:</p>
                    <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '" style="background: #000; color: #fff; text-decoration: none; padding: 10px 20px; text-transform: uppercase; font-weight: bold; display: inline-block; margin: 20px 0;">Reset Password</a>
                    <p style="font-size: 12px; color: #555;">Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.</p>
                </div>
            ');
        }

        if ($this->email->send()) {
            return true;
        } else {
            // Log error untuk admin
            // log_message('error', $this->email->print_debugger());
            return false;
        }
    }

    public function resetpassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        if ($email && $token) {
            $user_token = $this->db->get_where('user_token', ['token' => $token, 'email' => $email])->row_array();

            if ($user_token) {
                if (time() - $user_token['date_created'] < (24 * 60 * 60)) { // 24 jam
                    $this->session->set_userdata('reset_email', $email);
                    $this->change_password();
                } else {
                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Token expired! Silakan coba lagi.</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Token salah atau tidak valid!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism">Link reset password tidak valid!</div>');
            redirect('auth');
        }
    }

    public function change_password()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth');
        }

        $this->form_validation->set_rules('password1', 'Password Baru', 'required|trim|min_length[3]|matches[password2]');
        $this->form_validation->set_rules('password2', 'Konfirmasi Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Ganti Password";
            $this->load->view('layout/header', $data);
            $this->load->view('auth/change_password'); // Need to create this view
        } else {
            $password = password_hash($this->input->post('password1'), PASSWORD_DEFAULT);
            $email = $this->session->userdata('reset_email');

            $this->db->set('password', $password);
            $this->db->where('email', $email);
            $this->db->update('user_data');

            // Hapus token
            $this->db->delete('user_token', ['email' => $email]);

            $this->session->unset_userdata('reset_email');
            $this->session->set_flashdata('message', '<div class="alert alert-success ml-4 mr-4 neu-brutalism">Password berhasil diubah! Silakan login.</div>');
            redirect('auth');
        }
    }

    // =========================================================================
    // HYBRIDAUTH OAUTH IMPLEMENTATION
    // =========================================================================

    /**
     * Initiate OAuth Login
     * @param string $provider (Google, Facebook)
     */
    public function social_login($provider)
    {
        if (!in_array($provider, ['Google', 'Facebook'])) {
            show_404();
        }

        try {
            // Load config
            $this->config->load('hybridauth');
            $config = $this->config->item('hybridauth');
            
            // Set callback specifically to this provider to handle it properly
            $config['callback'] = base_url('auth/oauth_callback/') . $provider;

            // Instantiate Hybridauth
            $hybridauth = new Hybridauth($config);

            // Authenticate (Adapter)
            $adapter = $hybridauth->authenticate($provider);

            // We are done here, redirect happens in authenticate() if not connected
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism">OAuth Error: ' . $e->getMessage() . '</div>');
            redirect('auth');
        }
    }

    /**
     * Handle OAuth Callback and User Processing
     * @param string $provider
     */
    public function oauth_callback($provider)
    {
        try {
            $this->config->load('hybridauth');
            $config = $this->config->item('hybridauth');
            $config['callback'] = base_url('auth/oauth_callback/') . $provider;

            $hybridauth = new Hybridauth($config);
            $adapter = $hybridauth->authenticate($provider);

            // Get User Profile
            $userProfile = $adapter->getUserProfile();
            
            // Disconnect adapter (optional, but good for shared tokens)
            $adapter->disconnect();

            // Process User
            $this->_process_social_user($provider, $userProfile);

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism">Login Failed: ' . $e->getMessage() . '</div>');
            redirect('auth');
        }
    }

    /**
     * Check/Create User from Social Profile
     */
    private function _process_social_user($provider, $profile)
    {
        $email = $profile->email;
        $provider_id = $profile->identifier;
        
        // 1. Check by Provider ID
        $user = $this->User_model->find_by_provider($provider, $provider_id);

        // 2. If not found by ID, check by Email (Merge accounts)
        if (!$user && $email) {
            $user = $this->User_model->find_by_email($email);
            if ($user) {
                // Update existing user with provider info
                $this->db->where('id', $user['id']);
                $this->db->update('user_data', [
                    'auth_provider' => $provider,
                    'provider_id' => $provider_id
                ]);
            }
        }

        // 3. Register if new
        if (!$user) {
            $data = [
                'nama' => !empty($profile->displayName) ? $profile->displayName : 'User',
                'email' => $email, // Note: email might be null from FB if not permitted
                'image' => 'default.png',
                'password' => null, // No password for OAuth
                'role_id' => 2, // Member
                'is_active' => 1,
                'date_created' => time(),
                'auth_provider' => $provider,
                'provider_id' => $provider_id,
                'no_telepon' => '' // Default empty
            ];
            
            // Handle null email (create random placeholder or error)
            if(!$email) {
                $data['email'] = $provider_id . '@' . strtolower($provider) . '.com';
            }

            $user_id = $this->User_model->create_oauth_user($data);
            $user = $this->db->get_where('user_data', ['id' => $user_id])->row_array();
        }

        // 4. Set Session
        $session_data = [
            'email' => $user['email'],
            'role_id' => $user['role_id'],
            'nama' => $user['nama']
        ];
        $this->session->set_userdata($session_data);

        // 5. Redirect
        $this->_redirectUser($user['role_id']);
    }

    public function daftar_admin()
    {
        $this->_sudahLogin();
        
        // PIN VALIDATION
        $this->form_validation->set_rules('pin', 'PIN Keamanan', 'required|trim', [
            'required' => 'PIN harus diisi'
        ]);

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim', ['required' => 'Tidak boleh kosong']);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user_data.email]', [
            'required' => 'Tidak boleh kosong',
            'is_unique' => 'Sudah digunakan'
        ]);
        $this->form_validation->set_rules('password1', 'Kata Sandi', 'required|trim|min_length[3]|matches[password2]', [
            'required' => 'Tidak boleh kosong',
            'matches' => '',
            'min_length' => 'Terlalu pendek'
        ]);
        $this->form_validation->set_rules('password2', 'Konfirmasi Kata Sandi', 'required|trim|matches[password1]', [
            'required' => 'Tidak boleh kosong',
            'matches' => 'Tidak sama',
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Kiise Coffee - Daftar Admin";
            $this->load->view('layout/header', $data);
            $this->load->view('auth/daftar_admin');
        } else {
            // VERIFY PIN
            $pin_input = $this->input->post('pin', true);
            $correct_pin = "1122140142";
            
            if ($pin_input !== $correct_pin) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger ml-4 mr-4 neu-brutalism"><i class="fas fa-exclamation-triangle"></i> <strong>Akses Ditolak!</strong> PIN Keamanan salah.</div>');
                // redirect('auth/daftar_admin');
                $data['title'] = "Kiise Coffee - Daftar Admin";
                $this->load->view('layout/header', $data);
                $this->load->view('auth/daftar_admin');
                return;
            }

            $data = [
                'nama' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.png',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 1, // ADMIN ROLE
                'date_created' => time(),
                'auth_provider' => 'local',
                'is_active' => 1,
                'no_telepon' => ''
            ];

            $this->db->insert('user_data', $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success ml-4 mr-4 neu-brutalism"><strong>SUKSES!</strong> Akun Admin berhasil dibuat. Silakan Masuk.</div>');
            redirect('auth');
        }
    }
}
