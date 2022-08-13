<?= $this->extend('keuangan/layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6 col-md-12 ">
                        <h4> Laporan Transaksi Keuangan</h4>
                    </div>
                    <div class="col-lg-6 col-md-12 d-grid gap-2 d-md-flex justify-content-md-end ">
                        <a href="<?= base_url('/keuangan/pembayaranspp/'); ?>">
                            <button type="button" class="btn btn-primary bg-gradient mx-auto">
                                <i class="fas fa-plus-square"></i>
                                Tambah Transaksi
                            </button>
                        </a>
                        <a href="<?= base_url('/keuangan/generatetagihan/'); ?>">
                            <button type="button" class="btn btn-success bg-gradient mx-auto">
                                <i class="fas fa-tasks"></i>
                                Jenis Transaksi
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
                        <th class="text-center">Jenis </th>
                        <th class="text-center">Nama </th>
                        <th class="text-center">Waktu</th>
                        <th class="text-center">Jumlah</th>
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