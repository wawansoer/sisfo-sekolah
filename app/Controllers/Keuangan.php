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

        $jumlah = $this->request->getVar('jumlah');
        $this->db->transBegin();

        $this->db->table('periode_spp')->where('idPeriode', $id)->update($data);

        $this->db->query("UPDATE pembSPP set 
                        jumlah = -$jumlah 
                        WHERE idSpp = $id AND keterangan = 'Inisiasi Tagihan'");


        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Tidak dapat membuat tagihan. Anda Telah Membuat Tagihan Bulan Ini');
        } else {
            $this->db->transCommit();
            return redirect()->to(base_url('keuangan/periodespp'))->with('message', 'Data Berhasil Diubah');
        }
    }

    public function bayarspp()
    {
        $data['title'] = "Pembayaran SPP | Keuangan";
        $data['keuangan'] = 3;

        $query = $this->db->query('SELECT 
                                    pembSPP.idSiswa as idSiswa,
                                    siswa.nis as nis, 
                                    siswa.nama as namaSiswa,
                                    kelas.nama_kelas as kelas,
                                    ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))) as tagihan,
                                    SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)) as bayar, 
                                    ((ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))))-
                                    (SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)))) as sisa
                                FROM pembSPP 
                                INNER join periode_spp on pembSPP.idSpp = periode_spp.idPeriode
                                INNER join siswa on siswa.idSiswa = pembSPP.idSiswa
                                LEFT JOIN kelas on siswa.id_kelas = kelas.id_kelas 
                                GROUP BY pembSPP.idSiswa');
        $data['pembSPP'] = $query->getResult();

        return view('/keuangan/pembayaran_spp/pembayaran', $data);
    }

    public function detailtagihan($id)
    {
        $data['title'] = "Detail Tagihan SPP | Keuangan";
        $data['keuangan'] = 3;

        $query = $this->db->query('SELECT periode_spp.idPeriode  as idPeriode,
                                    periode_spp.namaPeriode as periode,
                                    pembSPP.idSiswa as idSiswa, 
                                    ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))) as tagihan,
                                    SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)) as bayar, 
                                    ((ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))))-
                                    (SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)))) as sisa
                                FROM pembSPP 
                                INNER join periode_spp on pembSPP.idSpp = periode_spp.idPeriode
                                WHERE pembSPP.idSiswa = ' . $id . '
                                GROUP BY pembSPP.idSpp ');
        $data['rincian'] = $query->getResult();

        return view('/keuangan/pembayaran_spp/detailtagihan', $data);
    }

    public function bayartagihan($idSiswa, $idPeriode)
    {
        $decIdSiswa     = base64_decode($idSiswa);
        $decIdPeriode   = base64_decode($idPeriode);

        $query = $this->db->query('SELECT periode_spp.idPeriode  as idPeriode, 
                                    ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))) as tagihan,
                                    SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)) as bayar, 
                                    ((ABS(SUM(IF(pembSPP.jumlah < 0, pembSPP.jumlah,0))))-
                                    (SUM(IF(pembSPP.jumlah > 0, pembSPP.jumlah,0)))) as sisa
                                FROM pembSPP 
                                INNER join periode_spp on pembSPP.idSpp = periode_spp.idPeriode
                                WHERE pembSPP.idSiswa = ' . $decIdSiswa . ' AND pembSPP.idSpp = ' . $decIdPeriode . '
                                GROUP BY pembSPP.idSpp ');
        $data = $query->getResult();

        foreach ($data as $datas) :
            $idPeriode = $datas->idPeriode;
            $tagihan = $datas->tagihan;
            $sisa = $datas->sisa;
        endforeach;

        if ($sisa > 0) {
            $idTime = date('Ymd');
            $idPembayaran = md5($decIdSiswa . $tagihan . $decIdSiswa . "1" . $idTime);

            $this->db->transBegin();
            $date = date("Y-m-d h:i:s");
            $this->db->query("INSERT INTO trx_keuangan (namaTrx, jumlah, createdAt, keterangan)
                            VALUES (20000, $tagihan, '$date', 'Pembayaran SPP Siswa')");

            $this->db->query("INSERT INTO pembSPP (idPembayaran, idSiswa, idSpp, jumlah, waktu, keterangan)
                            VALUES ('$idPembayaran', $decIdSiswa, $decIdPeriode, $tagihan, '$date', 'Pembayaran SPP Siswa')");

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                return redirect()->to(base_url('keuangan/detailtagihan/' . $decIdSiswa))->with('error', 'Tidak dapat bayar tagihan.');
            } else {
                $this->db->transCommit();
                return redirect()->to(base_url('keuangan/detailtagihan/' . $decIdSiswa))->with('message', 'Berhasil Melakukan Pembayaran SPP');
            }
        } else {
            return redirect()->to(base_url('keuangan/detailtagihan/' . $decIdSiswa))->with('error', 'Tidak ada tagihan untuk periode ini');
        }
    }

    public function generatetagihan()
    {
        $sekarang = date('Y-m-d h:i:s');
        $builder = $this->db->table('siswa')
            ->select('idSiswa')
            ->where('status', 'Aktif')
            ->where('createdAt <', $sekarang)
            ->get();
        $dataSiswa = $builder->getResult();

        $tahun = date('Y');
        $bulan = date('m');
        $tarikPeriode = $this->db->query("SELECT idPeriode, jumlah from periode_spp
		WHERE YEAR(awalPeriode) = $tahun 
		AND MONTH(awalPeriode) = $bulan
		AND YEAR (akhirPeriode) = $tahun
		AND MONTH (akhirPeriode) = $bulan")
            ->getResult();

        foreach ($tarikPeriode as $periode) :
            $usePeriode = $periode->idPeriode;
            $tagihan = $periode->jumlah;
        endforeach;
        $waktuTransaksi = date('Y-m-d h:i:s');

        $this->db->transBegin();

        foreach ($dataSiswa as $siswa) :
            $idTime = date('Ymd');
            $idPembayaran = md5($siswa->idSiswa . "0" . $usePeriode . "1" . $idTime);
            $this->db->query("INSERT INTO  pembSPP 
							(idPembayaran, idSiswa, idSpp, jumlah, waktu, keterangan) VALUES
							('$idPembayaran', $siswa->idSiswa, $usePeriode, -$tagihan, '$waktuTransaksi', 'Inisiasi Tagihan')");
        endforeach;

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return redirect()->to(base_url('keuangan/bayarspp'))->with('error', 'Tidak dapat membuat tagihan. Anda Telah Membuat Tagihan Bulan Ini');
        } else {
            $this->db->transCommit();
            return redirect()->to(base_url('keuangan/bayarspp'))->with('message', 'Anda Telah Membuat Tagihan Bulan Ini');
        }
    }

    public function transaksi()
    {
        if (empty($this->request->getVar('awal')) && empty($this->request->getVar('akhir'))) {
            $awal = date('Y-m-01 00:00:01');
            $akhir = date('Y-m-d 23:59:59');
        } else {
            $awal = $this->request->getVar('awal') . " 00:00:01";
            $akhir = $this->request->getVar('akhir') . " 23:59:59";
        }

        $builder = $this->db->query("SELECT namaTrx.jenisTrx  as jenis,
                                    namaTrx.namaTransaksi  as nama,  
									trx_keuangan.jumlah as jumlah, 
									trx_keuangan.waktu as waktu, 
									trx_keuangan.keterangan as ket 
									FROM trx_keuangan 
									INNER JOIN namaTrx on namaTrx.idNamaTrx = trx_keuangan.namaTrx 
									WHERE trx_keuangan.waktu  BETWEEN  '$awal' AND '$akhir'
									ORDER BY waktu DESC ");

        $data['transaksi'] = $builder->getResult();
        $data['title'] = "Transaksi | Keuangan";
        $data['keuangan'] = 4;

        return view('/keuangan/transaksi/transaksi', $data);
    }

    public function tambahtransaksi($id)
    {
        $builder = $this->db->table('namaTrx')
            ->select("*")->where('jenisTrx', $id)
            ->get();
        $data['namaTrx'] = $builder->getResult();
        $data['id'] = $id;
        $data['title'] = "Penambahan Transaksi" . $id . " | Keuangan";
        $data['keuangan'] = 4;
        return view('/keuangan/transaksi/tambahtransaksi', $data);
    }

    public function prosestambahtransaksi()
    {
        if (!$this->validate([
            'idNamaTrx' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Silahkan Pilih Jenis Transaksi ',
                    'numeric' => 'Jenis Transaksi Yang anda masukan Tidak Valid'
                ]
            ],
            'jumlah' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Jumlah Bayar SPP Harus diisi',
                    'numeric' => 'Format Jumlah Bayar SPP Tidak Dikenali',
                ]
            ],
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Keterangan Transaksi Harus Di isi',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        } else {

            $data = [
                'namaTrx'                    => $this->request->getVar('idNamaTrx'),
                'jumlah'                      => $this->request->getVar('jumlah'),
                'keterangan'                    => $this->request->getVar('keterangan'),
                'createdAt'                      => date('Y-m-d h:i:s'),
            ];

            $this->db->transBegin();

            $this->db->table('trx_keuangan')->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                session()->setFlashdata('error', "Silahkan Periksa Kembali Inputan Anda");
                return redirect()->back()->withInput();
            } else {
                $this->db->transCommit();
                return redirect()->to(base_url('keuangan/transaksi'))->with('message', 'Berhasil simpan data transaksi');
            }
        }
    }

    public function tambahjenistransaksi()
    {
        $data['title'] = "Penambahan Jenis Transaksi | Keuangan";
        $data['keuangan'] = 4;
        return view('/keuangan/transaksi/tambahjenistransaksi', $data);
    }

    public function prosestambahjenistransaksi()
    {
        if (!$this->validate([
            'jenisTransaksi' => [
                'rules' => 'required|in_list[Pemasukan,Pengeluaran]',
                'errors' => [
                    'required' => 'Silahkan Pilih Jenis Pengeluaran',
                    'in_list'   => 'Jenis Pengeluaran Tidak Valid'
                ]
            ],
            'namaTransaksi' => [
                'rules' => 'required|is_unique[namaTrx.namaTransaksi]',
                'errors' => [
                    'required' => 'Keterangan Transaksi Harus Di isi',
                    'is_unique' => 'Keterangan Transaksi Tidak Boleh Sama'
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        } else {

            $data = [
                'jenisTrx'                      => $this->request->getVar('jenisTransaksi'),
                'namaTransaksi'          => $this->request->getVar('namaTransaksi'),
            ];

            $this->db->transBegin();

            $this->db->table('namaTrx')->insert($data);

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                session()->setFlashdata('error', "Silahkan Periksa Kembali Inputan Anda");
                return redirect()->back()->withInput();
            } else {
                $this->db->transCommit();
                return redirect()->to(base_url('keuangan/transaksi'))->with('message', 'Berhasil Simpan Data Jenis Transaksi');
            }
        }
    }
}
