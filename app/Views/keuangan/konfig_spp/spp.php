<?= $this->extend('keuangan/layout') ?>
<?= $this->section('content') ?>

<div class="row" align="center">
  <div class="col-lg-12 col-md-12 mb-3">
    <a href="<?= base_url('keuangan/tambahkonfigspp'); ?>">
      <div class="d-grid gap-2">
        <button class="btn btn-primary" type="button">
          <i class="fas fa-plus-square"></i>
          Tambah Periode SPP
        </button>
      </div>
    </a>
  </div>
</div>
<div class="row">
  <div class="card">
    <div class="card-header">
      <h4> Daftar Periode SPP </h4>
    </div>
    <div class="card-body">
      <?php if (!empty(session()->getFlashdata('message'))) : ?>
        <div class="alert alert-success" role="alert">
          <strong>Selamat ! </strong> <?php echo session()->getFlashdata('message'); ?>
        </div>
      <?php endif; ?>
      <table class="table table-striped" id="siswa">
        <thead>
          <tr>
            <th>#</th>
            <th class="text-center">Berkas/Pengumuman</th>
            <th class="text-center">Deskripsi</th>
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
  let table1 = document.querySelector('#siswa');
  let dataTable = new simpleDatatables.DataTable(table1);
</script>
<?= $this->endSection() ?>