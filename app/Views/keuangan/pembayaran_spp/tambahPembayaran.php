<?= $this->extend('keuangan/layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="card">
        <div class="card-header">
            <h4> Formulir Pembayaran SPP </h4>
        </div>
        <div class="card-body">
            <?php if (!empty(session()->getFlashdata('error'))) : ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Mohon maaf Gagal Simpan Data ! </strong> Silahkan cek kembali inputan anda <?php echo session()->getFlashdata('error'); ?>
                </div>
            <?php endif; ?>
            <form action="<?= base_url('keuangan/prosestambahperiodespp') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="row mb-3">
                    <label for="idSiswa" class="col-sm-4 col-form-label">Nama Siswa</label>
                    <div class="col-sm-8">
                        <select class="idSiswa form-control" name="idSiswa"></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="awalPeriode" class="col-sm-4 col-form-label">Awal Waktu Bayar</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="awalPeriode" name="awalPeriode" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="akhirPeriode" class="col-sm-4 col-form-label">Akhir Waktu Bayar</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="akhirPeriode" name="akhirPeriode" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="jumlah" class="col-sm-4 col-form-label">Jumlah</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="keterangan" class="col-sm-4 col-form-label">Keterangan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-block text-end">
                    <button type="submit" class="btn btn-success bg-gradient">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>
</div>
<script src="<?= base_url('assets/vendors/simple-datatables/simple-datatables.js'); ?>"></script>
<script>
    $('.idSiswa').select2({
        placeholder: '--- Pilih Siswa  ---',
        ajax: {
            url: '<?php echo base_url('keuangan/carisiswa'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
</script>
<?= $this->endSection() ?>