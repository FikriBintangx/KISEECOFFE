<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi - <?= $transaksi['kode_transaksi']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Public+Sans:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background: #eee; font-family: 'Public Sans', monospace; padding: 20px; }
        .resi-paper {
            background: #fff; max-width: 480px; margin: 0 auto; padding: 30px;
            border: 3px solid #000; box-shadow: 10px 10px 0 #000; position: relative;
        }
        .brand { font-family: 'Archivo Black'; font-size: 2rem; margin-bottom: 5px; text-transform: uppercase; }
        .divider { border-top: 3px dashed #000; margin: 20px 0; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-weight: 700; font-size: 0.95rem; }
        .btn-neu {
            border: 3px solid #000; font-weight: 900; text-transform: uppercase; padding: 10px 20px;
            background: #fff; color: #000; box-shadow: 4px 4px 0 #000; text-decoration: none; display: inline-block; margin: 5px; cursor: pointer;
        }
        .btn-neu:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 #000; color: #000; }
        @media print {
            body { background: #fff; }
            .resi-paper { box-shadow: none; border: none; width: 100%; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn-neu" style="background: #fff200;">
            <i class="fas fa-print"></i> CETAK
        </button>
        <a href="<?= base_url('transaksi/riwayat'); ?>" class="btn-neu">KEMBALI</a>
    </div>

    <div class="resi-paper">
        <div class="text-center mb-4">
            <div class="brand">KIISE COFFEE</div>
            <p class="m-0 font-weight-bold">Jl. Kopi Nikmat No. 1, Jakarta</p>
            <p class="m-0"><?= date('d/m/Y H:i', strtotime($transaksi['created_at'])); ?></p>
            <p class="m-0 font-weight-bold">#<?= $transaksi['kode_transaksi']; ?></p>
        </div>

        <div class="mb-3">
            <p class="m-0"><strong>Pelanggan:</strong> <?= $user['nama']; ?></p>
            <p class="m-0"><strong>Metode:</strong> <?= strtoupper($transaksi['metode_pembayaran']); ?></p>
            
            <?php if(!empty($transaksi['alamat_pengiriman'])): ?>
                <div class="my-2 p-2" style="border: 2px dashed #000; background: #fafafa;">
                    <p class="m-0 small font-weight-bold">ALAMAT PENGIRIMAN:</p>
                    <p class="m-0 small"><?= $transaksi['alamat_pengiriman']; ?></p>
                </div>
            <?php endif; ?>

            <p class="m-0"><strong>Status:</strong> 
                <?php if($transaksi['payment_status'] == 'paid'): ?>
                    <span style="background: #000; color: #fff; padding: 0 5px;">LUNAS</span>
                <?php else: ?>
                    <span style="border: 2px solid #000; padding: 0 5px;">BELUM LUNAS</span>
                <?php endif; ?>
            </p>
        </div>

        <div class="divider"></div>

        <?php foreach ($items as $item) : ?>
            <div class="item-row">
                <div style="flex: 2;"><?= $item['nama']; ?></div>
                <div style="flex: 1; text-align: right;">x<?= $item['jumlah']; ?></div>
                <div style="flex: 1; text-align: right;"><?= number_format($item['subtotal'], 0, ',', '.'); ?></div>
            </div>
        <?php endforeach; ?>

        <div class="divider"></div>

        <div class="item-row" style="font-size: 1.2rem;">
            <div>TOTAL</div>
            <div>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.'); ?></div>
        </div>
        
        <div class="divider"></div>
        <div class="text-center font-weight-bold">
            <?php if($transaksi['payment_status'] == 'paid'): ?>
                <p class="mb-1">TERIMA KASIH!</p>
                <p class="small text-muted">Simpan struk ini sebagai bukti pembayaran yang sah.</p>
            <?php else: ?>
                <p class="mb-1">SILAKAN LAKUKAN PEMBAYARAN</p>
                <p class="small text-muted">Tunjukkan struk ini kepada kasir/kurir.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
