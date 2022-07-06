<?= $this->extend('tatausaha/layout') ?>
<?= $this->section('content') ?>

<div class="row" align="center">
	<div class="col-6 col-lg-6 col-md-12 mb-3">
		<a href="<?= base_url('tatausaha/tambahguru');?>">
			<div class="d-grid gap-2">
				<button class="btn btn-primary" type="button">
					<i class="bi bi-plus-square-fill"></i> 
					Tambah Guru
				</button>
			</div> 
		</a>
	</div>
	<div class="col-6 col-lg-6 col-md-12 mb-3">
		<a href="<?= base_url('tatausaha/tambahtendik');?>">
			<div class="d-grid gap-2">
				<button class="btn btn-primary" type="button">
					<i class="bi bi-plus-square-fill"></i> 
					Tambah Tenaga Pendidik
				</button>
			</div> 
		</a>
	</div>
</div>
<div class="row">
	<div class="card">
		<div class="card-header">
			<h4 class="text-center"> Daftar Guru/Tenaga Pendidik </h4>
		</div>
		<div class="card-body">
			<?php if (!empty(session()->getFlashdata('message'))) : ?>
			<div class="alert alert-success" role="alert">
				<strong>Selamat ! </strong> <?php echo session()->getFlashdata('message'); ?>
			</div>
		<?php endif; ?>
		<table class="table table-striped" id="guru">
			<thead>
				<tr>
					<th class="text-center">Nama</th>
					<th class="text-center">Jenis Kelamin</th>
					<th class="text-center">Pendidikan Terakhir</th>
					<th class="text-center">Status Kepegawaian</th>
					<th class="text-center">Jabatan</th>
					<th class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>
</div>
<script src="<?= base_url('assets/vendors/simple-datatables/simple-datatables.js');?>"></script>
<script>
    // Simple Datatable
    let table1 = document.querySelector('#guru');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
<?= $this->endSection() ?>