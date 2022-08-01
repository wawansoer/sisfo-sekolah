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
}
