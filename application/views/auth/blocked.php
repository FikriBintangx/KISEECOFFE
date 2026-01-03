<div class="main-content">
    <section class="section">
        <div class="container mt-5">
            <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card neu-brutalism border-0">
                        <div class="card-header bg-danger text-white neu-brutalism-border">
                            <h4 class="mb-0"><?= $title; ?></h4>
                        </div>
                        <div class="card-body text-center p-5">
                            <h1 class="display-1 fw-bold text-danger"><?= $error_code; ?></h1>
                            <div class="fs-5 mb-4 text-dark">
                                <?= $error_message; ?>
                            </div>
                            <a href="<?= base_url(); ?>" class="btn btn-primary btn-lg neu-button">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .neu-brutalism {
        border: 3px solid #000;
        box-shadow: 5px 5px 0px #000;
    }
    .neu-brutalism-border {
        border-bottom: 3px solid #000;
    }
    .neu-button {
        border: 2px solid #000;
        box-shadow: 4px 4px 0px #000;
        transition: all 0.2s ease;
    }
    .neu-button:hover {
        transform: translate(2px, 2px);
        box-shadow: 2px 2px 0px #000;
    }
    .neu-button:active {
        transform: translate(4px, 4px);
        box-shadow: none;
    }
</style>