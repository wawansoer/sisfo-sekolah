<?= $this->extend('keuangan/layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-12 text-start">
                        <h4> Daftar Periode SPP </h4>
                    </div>
                    <div class="col-lg-6 col-md-12 text-end ">
                        <a href="<?= base_url('/keuangan/pembayaranspp/'); ?>">
                            <button type="button" class="btn btn-primary bg-gradient">
                                <i class="fas fa-plus-square"></i>
                                Pembayaran SPP
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty(session()->getFlashdata('error'))) : ?>
                <div class="alert alert-danger" role="alert">
                    <strong>Mohon maaf ! </strong> <?php echo session()->getFlashdata('error'); ?>
                    <p> Silahkan cek kembali inputan anda.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty(session()->getFlashdata('message'))) : ?>
                <div class="alert alert-success" role="alert">
                    <strong>Selamat ! </strong> <?php echo session()->getFlashdata('message'); ?>
                </div>
            <?php endif; ?>
            <table class="table table-striped" id="periode">
                <thead>
                    <tr>
                        <th class="text-center">Nama Periode</th>
                        <th class="text-center">Jumlah Tagihan</th>
                        <th class="text-center">Waktu Pembayaran</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/vendors/simple-datatables/simple-datatables.js'); ?>"></script>
<script>
    // Simple Datatable
    let table1 = document.querySelector('#periode');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
<?= $this->endSection() ?>