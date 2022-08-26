<?= $this->extend('home/layout') ?>
<?= $this->section('content') ?>



<!-- Berita Start -->
<div class="container py-5">
    <?php foreach ($berita as $det) : ?>
        <div class="card mb-3">
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= base_url('assets/upload/image/' . $det->gambar); ?>" class="d-block w-100 blur" alt="<?= $det->judul_berita; ?>" height="450em">
                        <!-- <img src="..." class="d-block w-100" alt="..."> -->
                        <div class="carousel-caption d-none d-md-block">
                            <h1 class="text-primary"><?= $det->judul_berita; ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <p class="card-text"><small class="text-muted"><?= tgl_indo($det->tanggal_post); ?></small></p>
                <p class="card-text"> <?= $det->isi; ?> </p>
                <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Berita End -->

<?= $this->endSection() ?>