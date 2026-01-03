<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <?php if (validation_errors()) : ?>
            <div class="alert alert-danger neu-brutalism mb-3"><?= validation_errors(); ?></div>
        <?php endif; ?>
        <?= $this->session->flashdata('message'); ?>

        <a href="" class="btn btn-primary neu-brutalism mb-3" data-toggle="modal" data-target="#newMenuModal">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>

        <div class="row">
            <div class="col-12">
                <div class="card neu-brutalism-border">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered" id="table-1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Gambar</th>
                                        <th>Nama Menu</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($makanan as $m) : ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td>
                                                <div style="position: relative; width: 80px; height: 80px;">
                                                    <img src="<?= base_url('assets/img/makanan/') . $m['gambar']; ?>" 
                                                         class="neu-brutalism" 
                                                         style="width: 100%; height: 100%; object-fit: cover; border-width: 2px; <?= ($m['stok'] <= 0) ? 'filter: grayscale(100%);' : '' ?>">
                                                    
                                                    <?php if($m['stok'] <= 0): ?>
                                                        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; text-align: center;">
                                                            SOLD OUT
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= $m['nama']; ?></td>
                                            <td><span class="badge badge-info"><?= $m['kategori']; ?></span></td>
                                            <td>Rp <?= number_format($m['harga'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?php if($m['stok'] <= 0): ?>
                                                    <span class="badge badge-secondary">Habis</span>
                                                <?php elseif($m['stok'] <= 5): ?>
                                                    <span class="badge badge-danger">Sisa: <?= $m['stok']; ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-success"><?= $m['stok']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-warning btn-sm neu-brutalism" onclick="editMenu(<?= $m['id']; ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="<?= base_url('makanan/hapus/') . $m['id']; ?>" class="btn btn-danger btn-sm neu-brutalism" onclick="return confirm('Yakin hapus?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="newMenuModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content neu-brutalism-border">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Menu Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('makanan'); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control" id="kategori_tambah" onchange="toggleKategori('kategori_tambah', 'kategori_baru_tambah')">
                                <?php foreach($kategori_list as $k): ?>
                                    <option value="<?= $k['kategori']; ?>"><?= $k['kategori']; ?></option>
                                <?php endforeach; ?>
                                <option value="new_category" class="font-weight-bold">+ Tambah Kategori Baru</option>
                            </select>
                            <input type="text" name="kategori_baru" id="kategori_baru_tambah" class="form-control mt-2" placeholder="Nama Kategori Baru..." style="display:none;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stok Awal</label>
                            <input type="number" class="form-control" name="stok" value="10" required>
                        </div>
                    </div>
                </div>
                <!-- ... (rest of form) ... -->
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" class="form-control" name="harga" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi"></textarea>
                </div>
                <div class="form-group">
                    <label>Gambar</label>
                    <input type="file" class="form-control" name="gambar">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary neu-brutalism" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary neu-brutalism">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content neu-brutalism-border">
            <div class="modal-header">
                <h5 class="modal-title">Edit Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('makanan/ubah'); ?>
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" class="form-control" name="nama" id="edit_nama" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" class="form-control" id="edit_kategori" onchange="toggleKategori('edit_kategori', 'kategori_baru_edit')">
                                <?php foreach($kategori_list as $k): ?>
                                    <option value="<?= $k['kategori']; ?>"><?= $k['kategori']; ?></option>
                                <?php endforeach; ?>
                                <option value="new_category" class="font-weight-bold">+ Tambah Kategori Baru</option>
                            </select>
                            <input type="text" name="kategori_baru" id="kategori_baru_edit" class="form-control mt-2" placeholder="Nama Kategori Baru..." style="display:none;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" class="form-control" name="stok" id="edit_stok" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" class="form-control" name="harga" id="edit_harga" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea class="form-control" name="deskripsi" id="edit_deskripsi"></textarea>
                </div>
                <div class="form-group">
                    <label>Ganti Gambar (Biarkan kosong jika tidak ubah)</label>
                    <input type="file" class="form-control" name="gambar">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary neu-brutalism" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary neu-brutalism">Update</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
function toggleKategori(selectId, inputId) {
    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);
    if (select.value === 'new_category') {
        input.style.display = 'block';
        input.required = true;
    } else {
        input.style.display = 'none';
        input.required = false;
        input.value = ''; 
    }
}

function editMenu(id) {
    // Reset view modal
    $('#kategori_baru_edit').hide();
    $('#kategori_baru_edit').prop('required', false);

    $.ajax({
        url: '<?= base_url("makanan/get_makanan/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#edit_id').val(data.id);
            $('#edit_nama').val(data.nama);
            $('#edit_kategori').val(data.kategori); // Ini akan memilih opsi jika value cocok
            $('#edit_harga').val(data.harga);
            $('#edit_stok').val(data.stok);
            $('#edit_deskripsi').val(data.deskripsi);
            
            $('#editMenuModal').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Gagal mengambil data menu. Pastikan Anda login.');
            console.error(xhr);
        }
    });
}
</script>
