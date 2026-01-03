<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header neu-brutalism-border">
            <h1><?= $title; ?></h1>
        </div>

        <?= $this->session->flashdata('message') ?>

        <div class="card neu-brutalism-border">
            <div class="card-header">
                <h4>Upload Multiple Gambar untuk Menu Makanan</h4>
                <p class="text-muted">Pilih menu makanan dan upload beberapa gambar sekaligus</p>
            </div>
            <div class="card-body">
                <form action="<?= base_url('makanan/proses_bulk_upload'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih Menu Makanan</label>
                        <select name="makanan_id" class="form-control" required>
                            <option value="">-- Pilih Menu --</option>
                            <?php foreach ($makanan as $m) : ?>
                                <option value="<?= $m['id']; ?>">
                                    <?= $m['nama']; ?> - <?= $m['kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Gambar (Multiple)</label>
                        <input type="file" name="gambar[]" class="form-control-file" multiple accept="image/*" required>
                        <small class="form-text text-muted">
                            Anda dapat memilih beberapa gambar sekaligus. Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2MB per file.
                        </small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary neu-brutalism">
                            <i class="fas fa-upload"></i> Upload Gambar
                        </button>
                        <a href="<?= base_url('makanan'); ?>" class="btn btn-secondary neu-brutalism ml-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>

                <hr>

                <div class="alert alert-info neu-brutalism">
                    <h5><i class="fas fa-info-circle"></i> Petunjuk:</h5>
                    <ul class="mb-0">
                        <li>Pilih menu makanan yang ingin diupdate gambarnya</li>
                        <li>Pilih satu atau lebih gambar yang ingin diupload</li>
                        <li>Gambar yang diupload akan menggantikan gambar lama (jika ada)</li>
                        <li>Semua gambar yang berhasil diupload akan diupdate ke menu yang dipilih</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</div>


