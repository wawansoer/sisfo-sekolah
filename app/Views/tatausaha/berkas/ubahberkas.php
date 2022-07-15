<?= $this->extend('tatausaha/layout') ?>
<?= $this->section('content') ?>

<div class="row" align="center">
	<form action="<?= base_url('/tatausaha/prosesubahberkas/'.$idBerkas); ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
		<?= csrf_field(); ?>
		<div class="card-header">
			<h5 class="card-title">Ubah Data Berkas</h5>
			<button type="button" class="btn-close" data-bs-dismiss="card" aria-label="Close"></button>
		</div>
		<div class="card-body">
			<div class="row mb-3">
				<label for="namaBerkas" class="col-sm-3 col-form-label">Nama Berkas</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="namaBerkas" name="namaBerkas" value="<?= $namaBerkas;?>">
				</div>
			</div>
			<div class="row mb-3">
				<label for="ket" class="col-sm-3 col-form-label">Keterangan</label>
				<div class="col-sm-9">
					<textarea name="ket" id="ket" class="form-control" rows="4"><?= $ket;?></textarea>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
			<button type="submit" class="btn btn-success">Simpan</button>
		</div>
	</form>
</div>
<script src="<?= base_url('assets/vendors/simple-datatables/simple-datatables.js');?>"></script>
<script>
    // Simple Datatable
    let table1 = document.querySelector('#berkas');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
<?= $this->endSection() ?>