</div> 
    
    <footer class="footer-neu mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h4 class="font-weight-bold mb-3 text-uppercase">
                        <i class="fas fa-mug-hot mr-2"></i> KiiseCoffeee
                    </h4>
                     <p class="mb-1 font-weight-bold">Developed by Fikri Bintang Purnomo</p>
                    <small class="text-muted d-block" style="font-weight: 600;">© 2025 KiiseCoffeee. All Rights Reserved.</small>
                </div>
                <div class="col-md-6 text-md-right">
                    <div class="social-buttons">
                        <a href="https://www.instagram.com/isagi7sins/" target="_blank" class="btn-social ig">
                            <i class="fab fa-instagram"></i> Instagram
                        </a>
                        <a href="https://wa.me/6281292870932" target="_blank" class="btn-social wa">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer-neu {
            background-color: #121212; /* Dark Background */
            color: #fff; /* White text */
            border-top: 5px solid #000; /* Stays black or maybe colorful? */
            padding: 50px 0;
            margin-top: auto;
            position: relative;
        }
        /* Add a colorful strip at the top */
        .footer-neu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #fff200, #4ade80, #60a5fa, #f472b6, #fb923c);
        }

        .footer-neu h4, .footer-neu p, .footer-neu small, .footer-neu i {
            color: #fff !important;
        }

        .btn-social {
            display: inline-block;
            border: 2px solid #fff; /* White border for contrast */
            padding: 10px 20px;
            margin-left: 10px;
            margin-bottom: 10px;
            color: #fff;
            font-weight: 700;
            text-transform: uppercase;
            background: #000;
            box-shadow: 4px 4px 0px #fff; /* White shadow */
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .btn-social:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0px #fff;
            text-decoration: none;
            color: #fff;
        }

        /* Specific Colors */
        .btn-social.ig { background: #E1306C; border-color: #E1306C; }
        .btn-social.wa { background: #25D366; border-color: #25D366; }

        .btn-social.ig:hover { background: #C13584; }
        .btn-social.wa:hover { background: #128C7E; }

        @media (max-width: 768px) {
            .footer-neu { text-align: center; }
            .col-md-6.text-md-right { text-align: center !important; }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- DARK MODE JS -->
    <script src="<?= base_url(); ?>assets/js/darkmode.js"></script>

    
    <script>
        $(document).ready(function() {
            // Smooth Scroll
            $("a").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 100
                    }, 800);
                }
            });

            // Search Feature
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#menuContainer .menu-item").filter(function() {
                    var text = $(this).find('.search-name').text().toLowerCase();
                    $(this).toggle(text.indexOf(value) > -1)
                });
            });

            // Filter Feature
            $('.filter-btn').on('click', function() {
                $('.filter-btn').removeClass('active');
                $(this).addClass('active');
                var category = $(this).data('filter');
                if(category == 'all') {
                    $('.menu-item').show();
                } else {
                    $('.menu-item').hide();
                    $('.menu-item[data-category="' + category + '"]').show();
                }
            });

            // LOGIKA MODAL DETAIL (Fix Stok 0)
            $('.btn-detail').on('click', function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var harga = $(this).data('harga');
                var gambar = $(this).data('gambar');
                var stok = $(this).data('stok'); // AMBIL DATA STOK DARI TOMBOL

                $('#modalNamaMenu').text(nama);
                $('#modalHarga').text('Rp ' + new Intl.NumberFormat('id-ID').format(harga));
                $('#modalGambar').attr('src', gambar);
                $('#inputMakananId').val(id);
                
                // Update tampilan stok di modal
                $('#modalStok').text(stok); 
                
                // Set max input sesuai stok
                $('#inputJumlah').attr('max', stok);
                $('#inputJumlah').val(1); // Reset jumlah ke 1 setiap buka modal
            });

            // Tombol Plus (+)
            $('#btnPlus').click(function(){
                var val = parseInt($('#inputJumlah').val());
                var max = parseInt($('#inputJumlah').attr('max')); // Ambil batas stok
                
                // Cek apakah jumlah masih dibawah stok
                if(val < max) {
                    $('#inputJumlah').val(val + 1);
                } else {
                    alert('Maaf, stok hanya tersedia ' + max + ' porsi.');
                }
            });

            // Tombol Minus (-)
            $('#btnMinus').click(function(){
                var val = parseInt($('#inputJumlah').val());
                if(val > 1) {
                    $('#inputJumlah').val(val - 1);
                }
            });

            // WhatsApp Order
            $('#btnKirimWA').on('click', function() {
                var nama = $('#waNama').val();
                var menu = $('#waMenu').val();
                var catatan = $('#waCatatan').val();
                
                if(nama == '' || menu == '') {
                    alert('Mohon isi Nama dan Menu pesanan!');
                    return;
                }

                var text = "Halo Admin KiiseCoffee, saya mau pesan:%0a";
                text += "Nama: *" + nama + "*%0a";
                text += "Pesanan: *" + menu + "*%0a";
                text += "Catatan: " + (catatan ? catatan : '-') + "%0a%0a";
                text += "Mohon diproses ya, terima kasih!";

                window.open('https://wa.me/6281292870932?text=' + text, '_blank');
            });
        });
    </script>
</body>
</html>
