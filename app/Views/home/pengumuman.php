<?= $this->extend('home/layout') ?>
<?= $this->section('content') ?>



<!-- Berita Start -->
<div class="container py-5">
    <?php foreach ($pengumuman as $det) : ?>
        <div class="card mb-3">
            <div class="card-body">
                <img src="<?= base_url('assets/upload/image/' . $det['gambar']); ?>" class="d-block w-100 rounded" alt="<?= $det['pengumuman']; ?>">
                <h1 class="text-primary text-center mt-3"><?= $det['pengumuman']; ?></h1>
                <p class="text-center">
                    <small>
                        Ditulis Pada : <?= $det['created_at']  ?>
                    </small>
                </p>
                <p class="card-text"> <?= $det['deskripsi']; ?> </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Berita End -->

<?= $this->endSection() ?>