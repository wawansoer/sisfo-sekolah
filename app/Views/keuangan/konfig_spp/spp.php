<?= $this->extend('keuangan/layout') ?>
<?= $this->section('content') ?>

<div class="row" align="center">
  <div class="col-lg-12 col-md-12 mb-3">
    <div class="col-lg-12 col-md-12 mb-3">
      <div class="d-grid gap-2">
        <!-- tombol modal tambah berkas-->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
          <i class="fas fa-plus-square"></i>
          Tambah Periode SPP
        </button>
      </div>

      <!-- Modal Form tambah berkas -->
      <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="<?= base_url('keuangan/prosestambahperiodespp') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
              <?= csrf_field(); ?>
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambah Periode SPP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row mb-3">
                  <label for="namaPeriode" class="col-sm-3 col-form-label">Nama Periode</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="namaPeriode" name="namaPeriode" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="awalPeriode" class="col-sm-3 col-form-label">Awal Waktu Bayar</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="awalPeriode" name="awalPeriode" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="akhirPeriode" class="col-sm-3 col-form-label">Akhir Waktu Bayar</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="akhirPeriode" name="akhirPeriode" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                  <div class="col-sm-9">
                    <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="ket" class="col-sm-3 col-form-label">Keterangan</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="ket" name="ket" required>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
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