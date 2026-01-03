<?php
require_once APPPATH . 'controllers/api/Api_Controller.php';

class Makanan extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // Endpoint: GET /api/makanan
    // Bisa tambah filter ?kategori=Minuman
    public function index()
    {
        $kategori = $this->input->get('kategori'); // Optional filter by category

        if ($kategori) {
            $this->db->where('kategori', $kategori);
        }

        $makanan = $this->db->get('menu')->result_array();

        // Tambahkan full URL gambar agar bisa diload di HP
        // Karena HP tidak tahu relative path, dia butuh http://website.com/assets/...
        foreach ($makanan as &$item) {
            $item['gambar_url'] = base_url('assets/img/makanan/' . $item['gambar']);
        }

        return $this->response([
            'status' => true,
            'data' => $makanan
        ]);
    }

    // Endpoint: GET /api/makanan/detail/5
    public function detail($id = null)
    {
        if($id === null) {
            return $this->response(['status' => false, 'message' => 'ID Kosong'], 400);
        }

        $item = $this->db->get_where('menu', ['id' => $id])->row_array();

        if ($item) {
            $item['gambar_url'] = base_url('assets/img/makanan/' . $item['gambar']);
            return $this->response(['status' => true, 'data' => $item]);
        } else {
            return $this->response(['status' => false, 'message' => 'Menu tidak ditemukan'], 404);
        }
    }
}
