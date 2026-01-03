<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Makanan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_sudah_masuk();
    }

    public function index()
    {
        $data['title'] = 'Manajemen Menu Makanan';
        $data['user'] = $this->db->get_where('user_data', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        // ambil semua data makanan
        // Change key to 'makanan' to match View expectation
        $data['makanan'] = $this->db->get('menu')->result_array();
        
        // ambil daftar kategori unik untuk dropdown
        $this->db->distinct();
        $this->db->select('kategori');
        $this->db->order_by('kategori', 'ASC');
        $data['kategori_list'] = $this->db->get('menu')->result_array();

        $this->form_validation->set_rules('nama', 'Nama menu', 'required', [
            'required' => 'Nama menu tidak boleh kosong'
        ]);
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric', [
            'required' => 'Harga wajib diisi',
            'numeric' => 'Harga harus angka'
        ]);
        // Validasi stok
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric', [
            'required' => 'Stok wajib diisi',
            'numeric' => 'Stok harus angka'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('makanan/index', $data);
            $this->load->view('layout/footer');
        } else {
            $nama = htmlspecialchars($this->input->post('nama', true));
            $harga = htmlspecialchars($this->input->post('harga', true));
            $stok = htmlspecialchars($this->input->post('stok', true)); // Ambil stok
            
            // Logika Kategori Baru
            $kategori_select = $this->input->post('kategori', true);
            if ($kategori_select == 'new_category') {
                $kategori = htmlspecialchars($this->input->post('kategori_baru', true));
            } else {
                $kategori = htmlspecialchars($kategori_select);
            }
            
            $deskripsi = htmlspecialchars($this->input->post('deskripsi', true));

            // handle upload gambar
            $upload_image = $_FILES['gambar']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['max_size'] = 2048;
                $config['upload_path'] = './assets/img/makanan/';
                $config['encrypt_name'] = TRUE; 

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $gambar = $this->upload->data('file_name');
                } else {
                    $error = $this->upload->display_errors('', '');
                    $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4"><i class="fas fa-exclamation-triangle"></i> <strong>Data Input Error!</strong> Gagal upload gambar: ' . $error . '</div>');
                    redirect('makanan');
                }
            } else {
                $gambar = 'default.jpg';
            }

            $this->db->insert('menu', [
                'nama' => $nama,
                'harga' => $harga,
                'stok' => $stok, // Simpan stok
                'kategori' => $kategori,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar
            ]);

            $this->session->set_flashdata('message', '<div class="alert alert-success neu-brutalism mb-4">Menu makanan <b>' . $nama . '</b> berhasil ditambahkan!</div>');
            redirect('makanan');
        }
    }

    public function ubah()
    {
        $id = $this->input->post('id');
        $nama = htmlspecialchars($this->input->post('nama', true));
        $harga = htmlspecialchars($this->input->post('harga', true));
        $stok = htmlspecialchars($this->input->post('stok', true)); // Ambil stok update
        
        // Logika Kategori Baru (Update)
        $kategori_select = $this->input->post('kategori', true);
        if ($kategori_select == 'new_category') {
            $kategori = htmlspecialchars($this->input->post('kategori_baru', true));
        } else {
            $kategori = htmlspecialchars($kategori_select);
        }
        
        $deskripsi = htmlspecialchars($this->input->post('deskripsi', true));

        $makananLama = $this->db->get_where('menu', ['id' => $id])->row_array();

        $upload_image = $_FILES['gambar']['name'];
        
        $gambar_baru = $makananLama['gambar']; // Default gambar lama

        if ($upload_image) {
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['upload_path'] = './assets/img/makanan/';
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('gambar')) {
                $gambar_baru = $this->upload->data('file_name');

                if ($makananLama['gambar'] != 'default.jpg' && file_exists(FCPATH . 'assets/img/makanan/' . $makananLama['gambar'])) {
                    unlink(FCPATH . 'assets/img/makanan/' . $makananLama['gambar']);
                }
            }
        }

        $this->db->where('id', $id);
        $this->db->update('menu', [
            'nama' => $nama,
            'harga' => $harga,
            'stok' => $stok, // Update stok
            'kategori' => $kategori,
            'deskripsi' => $deskripsi,
            'gambar' => $gambar_baru
        ]);

        $this->session->set_flashdata('message', '<div class="alert alert-warning neu-brutalism mb-4">Menu makanan <b>' . $makananLama['nama'] . '</b> berhasil diubah!</div>');
        redirect('makanan');
    }

    public function hapus($id)
    {
        $makanan = $this->db->get_where('menu', ['id' => $id])->row_array();

        if ($makanan && $makanan['gambar'] != 'default.jpg' && file_exists(FCPATH . 'assets/img/makanan/' . $makanan['gambar'])) {
            unlink(FCPATH . 'assets/img/makanan/' . $makanan['gambar']);
        }

        $this->db->where('id', $id);
        $this->db->delete('menu');

        $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4"><i class="fas fa-trash"></i> <strong>Data Berhasil Dihapus!</strong> Menu makanan <b>' . $makanan['nama'] . '</b> telah dihapus dari sistem.</div>');
        redirect('makanan');
    }

    public function get_makanan($id)
    {
        $makanan = $this->db->get_where('menu', ['id' => $id])->row();
        echo json_encode($makanan);
    }

    public function bulk_upload()
    {
        if ($this->session->userdata('role_id') != 1) {
            redirect('auth/blocked');
        }

        $data['title'] = 'Bulk Upload Gambar Makanan';
        $data['user'] = $this->db->get_where('user_data', [
            'email' => $this->session->userdata('email')
        ])->row_array();

        $data['makanan'] = $this->db->get('menu')->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('makanan/bulk_upload', $data);
        $this->load->view('layout/footer');
    }

    public function proses_bulk_upload()
    {
        if ($this->session->userdata('role_id') != 1) {
            redirect('auth/blocked');
        }

        $makanan_id = $this->input->post('makanan_id');
        
        if (!$makanan_id) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Pilih menu makanan terlebih dahulu!</div>');
            redirect('makanan/bulk_upload');
        }

        $config['upload_path'] = './assets/img/makanan/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 2048; 
        $config['encrypt_name'] = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->load->library('upload', $config);

        $files = $_FILES;
        $upload_count = count($_FILES['gambar']['name']);
        $success_count = 0;
        $error_count = 0;
        $errors = [];

        for ($i = 0; $i < $upload_count; $i++) {
            if (!empty($files['gambar']['name'][$i])) {
                $_FILES['gambar']['name'] = $files['gambar']['name'][$i];
                $_FILES['gambar']['type'] = $files['gambar']['type'][$i];
                $_FILES['gambar']['tmp_name'] = $files['gambar']['tmp_name'][$i];
                $_FILES['gambar']['error'] = $files['gambar']['error'][$i];
                $_FILES['gambar']['size'] = $files['gambar']['size'][$i];

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];

                    $makanan = $this->db->get_where('menu', ['id' => $makanan_id])->row_array();
                    
                    if ($makanan && $makanan['gambar'] != 'default.jpg' && file_exists(FCPATH . 'assets/img/makanan/' . $makanan['gambar'])) {
                        unlink(FCPATH . 'assets/img/makanan/' . $makanan['gambar']);
                    }

                    $this->db->where('id', $makanan_id);
                    $this->db->update('menu', ['gambar' => $gambar]);
                    $success_count++;
                } else {
                    $error_count++;
                    $errors[] = $files['gambar']['name'][$i] . ': ' . $this->upload->display_errors('', '');
                }
            }
        }

        if ($success_count > 0) {
            $message = '<div class="alert alert-success neu-brutalism mb-4">Berhasil upload <b>' . $success_count . '</b> gambar!';
            if ($error_count > 0) {
                $message .= ' Gagal upload <b>' . $error_count . '</b> gambar.';
            }
            $message .= '</div>';
            $this->session->set_flashdata('message', $message);
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger neu-brutalism mb-4">Gagal upload gambar! ' . implode('<br>', $errors) . '</div>');
        }

        redirect('makanan/bulk_upload');
    }
}
