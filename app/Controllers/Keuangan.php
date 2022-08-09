<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Request;

class Keuangan extends BaseController
{
	public function __construct()
	{
		$this->db = \Config\Database::connect();
		helper(['form', 'url', 'auth', 'tgl', 'rp']);
	}

	public function index()
	{
		$data['title'] = "Dashboard | Keuangan";
		$data['keuangan'] = 1;

		return view('/keuangan/dashboard', $data);
	}

	public function periodespp()
	{
		$data['title'] = "Periode SPP | Keuangan";
		$data['keuangan'] = 2;

		$query = $this->db->table('periode_spp');
		$query->select('*');
		$hasilQuery = $query->get();
		$data['periode'] = $hasilQuery->getResult();

		return view('/keuangan/konfig_spp/spp', $data);
	}

	public function prosestambahperiodespp()
	{
		if (!$this->validate([
			'namaPeriode' => [
				'rules' => 'required|is_unique[periode_spp.namaPeriode]',
				'errors' => [
					'required' => 'Nama Periode Harus diisi',
					'is_unique' => 'Nama Periode Tidak Boleh Sama',
				]
			],
			'jumlah' => [
				'rules' => 'required|numeric',
				'errors' => [
					'required' => 'Jumlah Bayar SPP Harus diisi',
					'numeric' => 'Format Jumlah Bayar SPP Tidak Dikenali',
				]
			],
			'awalPeriode' => [
				'rules' => 'required|valid_date',
				'errors' => [
					'required' => 'Awal Periode Harus diisi',
					'valid_date' => 'Format Awal Periode Tidak Dikenali',
				]
			],
			'akhirPeriode' => [
				'rules' => 'required|valid_date',
				'errors' => [
					'required' => 'Akhir Periode Harus diisi',
					'valid_date' => 'Format Akhir Periode Tidak Dikenali',
				]
			],

		])) {
			session()->setFlashdata('error', $this->validator->listErrors());
			return redirect()->back()->withInput();
		}

		$data = [
			'namaPeriode'        => $this->request->getVar('namaPeriode'),
			'jumlah'             => $this->request->getVar('jumlah'),
			'awalPeriode'        => $this->request->getVar('awalPeriode'),
			'akhirPeriode'       => $this->request->getVar('akhirPeriode'),
			'keterangan'         => $this->request->getVar('keterangan')
		];

		$builder = $this->db->table('periode_spp');
		$builder->insert($data);

		if (!$builder) {
			return redirect()->to(base_url('keuangan/periodespp'))->with('message', 'Data Berhasil Ditambahkan');
		} else {
			session()->setFlashdata('error', "");
			return redirect()->back()->withInput();
		}
	}

	public function hapusperiodespp($id)
	{
		$query = $this->db->table('periode_spp');
		$query->where('idPeriode', $id);
		$hasilQuery = $query->get();

		if (empty($hasilQuery)) {
			throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tidak ditemukan !');
		} else {
			$query = $this->db->table('periode_spp');
			$query->delete(['idPeriode' => $id]);

			if ($query) {
				return redirect()->to(base_url('keuangan/periodespp/'))->with('message', 'Data Berhasil Dihapus');
			} else {
				session()->setFlashdata('error', "Silahkan cek kembali inputan anda");
				return redirect()->back()->withInput();
			}
		}
	}

	public function prosesubahperiodespp($id)
	{
		if (!$this->validate([
			'namaPeriode' => [
				'rules' => 'required|is_unique[periode_spp.namaPeriode, idPeriode,' . $id . ']',
				'errors' => [
					'required' => 'Nama Periode Harus diisi',
					'is_unique' => 'Nama Periode Tidak Boleh Sama',
				]
			],
			'jumlah' => [
				'rules' => 'required|numeric',
				'errors' => [
					'required' => 'Jumlah Bayar SPP Harus diisi',
					'numeric' => 'Format Jumlah Bayar SPP Tidak Dikenali',
				]
			],
			'awalPeriode' => [
				'rules' => 'required|valid_date',
				'errors' => [
					'required' => 'Awal Periode Harus diisi',
					'valid_date' => 'Format Awal Periode Tidak Dikenali',
				]
			],
			'akhirPeriode' => [
				'rules' => 'required|valid_date',
				'errors' => [
					'required' => 'Akhir Periode Harus diisi',
					'valid_date' => 'Format Akhir Periode Tidak Dikenali',
				]
			],

		])) {
			session()->setFlashdata('error', $this->validator->listErrors());
			return redirect()->back()->withInput();
		}

		$data = [
			'namaPeriode'        => $this->request->getVar('namaPeriode'),
			'jumlah'             => $this->request->getVar('jumlah'),
			'awalPeriode'        => $this->request->getVar('awalPeriode'),
			'akhirPeriode'       => $this->request->getVar('akhirPeriode'),
			'keterangan'         => $this->request->getVar('keterangan')
		];

		$builder = $this->db->table('periode_spp');
		$builder->where('idPeriode', $id);
		$builder->update($data);

		if ($builder) {
			return redirect()->to(base_url('keuangan/periodespp'))->with('message', 'Data Berhasil Diubah');
		} else {
			session()->setFlashdata('error', "");
			return redirect()->back()->withInput();
		}
	}

	public function bayarspp()
	{
		$data['title'] = "Pembayaran SPP | Keuangan";
		$data['keuangan'] = 3;

		$query = $this->db->table('pembSPP');
		$query->select('*');
		$hasilQuery = $query->get();
		$data['pembSPP'] = $hasilQuery->getResult();

		return view('/keuangan/pembayaran_spp/pembayaran', $data);
	}

	public function pembayaranspp()
	{
		$data['title'] = "Pembayaran SPP | Keuangan";
		$data['keuangan'] = 3;

		return view('/keuangan/pembayaran_spp/tambahPembayaran', $data);
	}

	public function carisiswa()
	{
		helper(['form', 'url']);
		$data = [];
		$builder = $this->db->table('siswa')->select('idSiswa as id, nama as text');
		if (empty($this->request->getVar('q'))) {
			$query = $builder->limit(10)->get();
		} else {
			$query = $builder->like('nama', $this->request->getVar('q'))
				->limit(10)->get();
		}

		$data = $query->getResult();

		echo json_encode($data);
	}

	public function prosestambahpembayaran()
	{
		if (!$this->validate([
			'search' => [
				'rules' => 'required',
				'errors' => [
					'required' => 'Silahkan Pilih Siswa ',
				]
			],

		])) {
			session()->setFlashdata('error', $this->validator->listErrors());
			return redirect()->back()->withInput();
		}

		echo $this->request->getVar('search');
	}

	public function generatetagihan()
	{
		$builder = $this->db->table('siswa')
			->select('idSiswa')
			->where('status', 'Aktif')
			->get();
		$dataSiswa = $builder->getResult();

		$tahun = date('Y');
		$bulan = date('m');
		$tarikPeriode = $this->db->query("SELECT idPeriode  from periode_spp
		WHERE YEAR(awalPeriode) = $tahun 
		AND MONTH(awalPeriode) = $bulan
		AND YEAR (akhirPeriode) = $tahun
		AND MONTH (akhirPeriode) = $bulan")
			->getResult();

		foreach ($tarikPeriode as $periode) :
			$usePeriode = $periode->idPeriode;
		endforeach;

		foreach ($dataSiswa as $siswa) :
			echo $siswa->idSiswa . "-> " . $usePeriode . "<br>";
		endforeach;
	}
}
