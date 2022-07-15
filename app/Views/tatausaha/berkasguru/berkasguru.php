<?= $this->extend('tatausaha/layout') ?>
<?= $this->section('content') ?>

<div class="row" align="center">
	<div class="col-lg-12 col-md-12 mb-3">
		<div class="d-grid gap-2">
			<!-- Button trigger modal -->
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
				<i class="fas fa-plus-square"></i>
				Tambah Berkas Guru/Tendik
			</button>
		</div> 

		<!-- Modal -->
		<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form action="<?= base_url('tatausaha/prosestambahberkasguru') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
						<?= csrf_field(); ?>
						<div class="modal-header">
							<h5 class="modal-title" id="staticBackdropLabel">Tambah Data Berkas Guru/Tendik</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body text-start">
							<div class="row mb-3">
								<label for="id_guru" class="col-sm-3 col-form-label">Nama Guru</label>
								<div class="col-sm-9">
									<select name="id_guru" class="form-control" required>
										<option <?= (old('id_guru') == "" ? "selected" : "");?>> -- Pilih Guru/Tendik -- </option>
										<?php foreach ($guru as $detGuru):?>
											<option 
												value="<?= $detGuru->id_guru;?>" 
												<?= (old('id_guru') == "$detGuru->id_guru" ? "selected" : "");?>>
												<?= $detGuru->nama;?>
											</option>   
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<label for="id_berkas" class="col-sm-3 col-form-label">Nama Berkas</label>
								<div class="col-sm-9">
									<select name="idBerkas" class="form-control" required>
										<option <?= (old('idBerkas') == "" ? "selected" : "");?>> -- Pilih Nama Berkas -- </option>
										<?php foreach ($berkas as $detBerkas):?>
											<option 
												value="<?= $detBerkas->idBerkas;?>" 
												<?= (old('idBerkas') == "$detBerkas->idBerkas" ? "selected" : "");?>>
												<?= $detBerkas->namaBerkas;?>
											</option>   
										<?php endforeach;?>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<label for="ket" class="col-sm-3 col-form-label">Berkas</label>
								<div class="col-sm-9">
									<textarea name="ket" id="ket" class="form-control" rows="4"></textarea>
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
<div class="row">
	<div class="card">
		<div class="card-header">
			<h4> Daftar berkas </h4>
		</div>
		<div class="card-body">
			<?php if (!empty(session()->getFlashdata('message'))) : ?>
			<div class="alert alert-success" role="alert">
				<strong>Selamat ! </strong> <?php echo session()->getFlashdata('message'); ?>
			</div>
		<?php endif; ?>
		<table class="table table-striped" id="berkas">
			<thead>
				<tr>
					<th class="text-center">Nama Guru/Tendik</th>
					<th class="text-center">Nama Berkas</th>
					<th class="text-center">Berkas</th>
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
    let table1 = document.querySelector('#berkas');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>
<?= $this->endSection() ?>

