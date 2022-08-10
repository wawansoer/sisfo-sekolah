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
			$query = $this->db->table('periode_spp')->delete(['idPeriode' => $id]);

			if ($query == TRUE) {
				return redirect()->to(base_url('keuangan/periodespp/'))->with('message', 'Data Berhasil Dihapus');
			} else {
				session()->setFlashdata('error', "Periode SPP Telah Digunakan");
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

	public function pembayaranspp()
	{
		$data['title'] = "Pembayaran SPP | Keuangan";
		$data['keuangan'] = 3;
		$query = $this->db->table('periode_spp')->select('idPeriode, namaPeriode')
			->orderBy('awalPeriode',  'DESC')->limit(12);
		$hasilQuery = $query->get();
		$data['periode'] = $hasilQuery->getResult();

		return view('/keuangan/pembayaran_spp/tambahPembayaran', $data);
	}

	public function prosestambahpembayaran()
	{
		if (!$this->validate([
			'idSiswa' => [
				'rules' => 'required',
				'errors' => [
					'required' => 'Silahkan Pilih Siswa ',
				]
			],
			'jumlah' => [
				'rules' => 'required|numeric',
				'errors' => [
					'required' => 'Jumlah Bayar SPP Harus diisi',
					'numeric' => 'Format Jumlah Bayar SPP Tidak Dikenali',
				]
			],
		])) {
			session()->setFlashdata('error', $this->validator->listErrors());
			return redirect()->back()->withInput();
		} else {
			$idTime = date('Ymd');
			$idPembayaran = md5($this->request->getVar('idSiswa') . "" . $this->request->getVar('jumlah') . "" . $this->request->getVar('idSpp') . "2" . $idTime);
			$data = [
				'idPembayaran' 	=> $idPembayaran,
				'idSiswa'        		=> $this->request->getVar('idSiswa'),
				'idSpp'        		=> $this->request->getVar('idSpp'),
				'jumlah'             => $this->request->getVar('jumlah'),
				'waktu'             	=> date('Y-m-d h:i:s'),
				'keterangan' 		=> $this->request->getVar('keterangan')
			];

			$builder = $this->db->table('pembSPP')->insert($data);

			if ($builder) {
				return redirect()->to(base_url('keuangan/bayarspp'))->with('message', 'Data Berhasil Ditambahkan');
			} else {
				session()->setFlashdata('error', "Silahkan Periksa Kembali Inputan Anda");
				return redirect()->back()->withInput();
			}
		}
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
		$waktuTransaksi = date('Y-m-d h:i:s');

		$this->db->transBegin();

		foreach ($dataSiswa as $siswa) :
			$idTime = date('Ymd');
			$idPembayaran = md5($siswa->idSiswa . "0" . $usePeriode . "1" . $idTime);
			$this->db->query("INSERT INTO  pembSPP 
												(idPembayaran, idSiswa, idSpp, jumlah, waktu, keterangan) VALUES
												('$idPembayaran', $siswa->idSiswa, $usePeriode, 0, '$waktuTransaksi', 'Inisiasi Tagihan')");
		endforeach;

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return redirect()->to(base_url('keuangan/bayarspp'))->with('error', 'Tidak dapat membuat tagihan. Anda Telah Membuat Tagihan Bulan Ini');
		} else {
			$this->db->transCommit();
			return redirect()->to(base_url('keuangan/bayarspp'))->with('message', 'Anda Telah Membuat Tagihan Bulan Ini');
		}
	}
}
