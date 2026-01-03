<?php
// ... existing code ... (no change to start of file)
?>
        </div>
    </div>
</div>

<!-- General JS Scripts -->
<!-- MOBILE BOTTOM NAVIGATION (Visible only on Mobile) -->
<div class="d-md-none mobile-bottom-nav">
    <a href="<?= base_url('home'); ?>" class="mobile-nav-item <?= ($this->uri->segment(1) == 'home' || $this->uri->segment(1) == '') ? 'active' : '' ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    
    <a href="<?= base_url('makanan'); ?>" class="mobile-nav-item <?= ($this->uri->segment(1) == 'makanan') ? 'active' : '' ?>">
        <i class="fas fa-utensils"></i>
        <span>Menu</span>
    </a>

    <!-- Center Action Button (if Admin -> Transaksi, if User -> Keranjang) -->
    <?php if($this->session->userdata('role_id') == 1): ?>
        <a href="<?= base_url('transaksi/kelola'); ?>" class="mobile-nav-item <?= ($this->uri->segment(1) == 'transaksi') ? 'active' : '' ?>">
            <i class="fas fa-cash-register"></i>
            <span>Kasir</span>
        </a>
    <?php else: ?>
        <a href="<?= base_url('transaksi/keranjang'); ?>" class="mobile-nav-item <?= ($this->uri->segment(1) == 'transaksi' && $this->uri->segment(2) == 'keranjang') ? 'active' : '' ?>">
            <i class="fas fa-shopping-cart"></i>
            <span>Order</span>
        </a>
    <?php endif; ?>

    <a href="<?= base_url('user'); ?>" class="mobile-nav-item <?= ($this->uri->segment(1) == 'user' && $this->uri->segment(2) == '') ? 'active' : '' ?>">
        <i class="fas fa-user"></i>
        <span>Akun</span>
    </a>
</div>

<!-- General JS Scripts -->
<!-- ONLINE -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script> -->

<!-- OFFLINE -->
<script src="<?= base_url(); ?>template/stisla/node_modules/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url(); ?>template/stisla/node_modules/popper.js/dist/umd/popper.js"></script>
<script src="<?= base_url(); ?>template/stisla/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url(); ?>template/stisla/node_modules/jquery.nicescroll/dist/jquery.nicescroll.min.js"></script>
<script src="<?= base_url(); ?>template/stisla/node_modules/moment/min/moment.min.js"></script>

<!-- JS Libraies -->
<script src="<?= base_url(); ?>assets/js/datatables/datatables.js"></script>

<!-- Template JS File -->
<script src="<?= base_url(); ?>template/stisla/assets/js/stisla.js"></script>
<script src="<?= base_url(); ?>template/stisla/assets/js/scripts.js"></script>
<script src="<?= base_url(); ?>template/stisla/assets/js/custom.js"></script>

<!-- DARK MODE JS -->
<script src="<?= base_url(); ?>assets/js/darkmode.js"></script>

<!-- SWEETALERT 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Page Specific JS File -->
<script>
    $(document).ready(function() {
        // Initialize DataTable
        if($('#table-1').length) {
            $('#table-1').DataTable({
                "language": {
                    "url": "<?= base_url(); ?>assets/js/datatables/id.json"
                }
            });
        }

        if($('#myTableRiwayat').length) {
            $('#myTableRiwayat').DataTable({
                "language": {
                    "url": "<?= base_url(); ?>assets/js/datatables/id.json"
                },
                "order": [[2, "desc"]] // Sort by date column (index 2) descending
            });
        }
        
        // --- REALTIME NOTIFICATIONS (POLLING) ---
        // Only run if user is admin
        <?php if($this->session->userdata('role_id') == 1): ?>
            
            let lastTransactionCount = null;
            
            function checkNewOrders() {
                $.ajax({
                    url: '<?= base_url('admin/get_realtime_stats'); ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success') {
                            const currentCount = parseInt(response.total_transaksi);
                            
                            // Initialize on first load
                            if (lastTransactionCount === null) {
                                lastTransactionCount = currentCount;
                                return;
                            }
                            
                            // Check for increase
                            if (currentCount > lastTransactionCount) {
                                let diff = currentCount - lastTransactionCount;
                                lastTransactionCount = currentCount;
                                
                                // Show Notification
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Pesanan Baru!',
                                    text: `Ada ${diff} pesanan baru yang masuk. Yuk cek segera!`,
                                    showCancelButton: true,
                                    confirmButtonText: 'Lihat Orderan',
                                    confirmButtonColor: '#4ade80', // Soft green from our palette
                                    cancelButtonText: 'Tutup',
                                    backdrop: `rgba(0,0,0,0.4)`
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '<?= base_url('transaksi/kelola'); ?>';
                                    }
                                });
                                
                                // Optional Audio Alert (Browser policy requires interaction first, but worth a try)
                                // const audio = new Audio('<?= base_url("assets/audio/notification.mp3"); ?>');
                                // audio.play().catch(e => console.log('Audio blocked:', e));
                            }
                        }
                    },
                    error: function(err) {
                        console.log('Realtime check failed, retrying...', err);
                    }
                });
            }
            
            // Poll every 10 seconds
            setInterval(checkNewOrders, 10000);
            
            // Initial check
            checkNewOrders();
            
        <?php endif; ?>
    });
</script>

</body>
</html>
