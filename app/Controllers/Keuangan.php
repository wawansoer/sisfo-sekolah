<?php

namespace App\Controllers;

class Keuangan extends BaseController
{
  public function __construct()
  {
    $this->db = \Config\Database::connect();
    helper('auth');
    helper('tgl');
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

    if ($builder) {
      return redirect()->to(base_url('keuangan/periodespp'))->with('message', 'Data Berhasil Ditambahkan');
    } else {
      session()->setFlashdata('error', "Silahkan cek kembali inputan anda");
      return redirect()->back()->withInput();
    }
  }
}
