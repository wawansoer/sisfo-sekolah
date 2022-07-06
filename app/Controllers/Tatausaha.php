<?php

namespace App\Controllers;

class Tatausaha extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper('auth');
        helper('tgl');
    }

    public function index()
    {
        $data['title'] = "Dashboard | Tata Usaha";
        $data['tatausaha'] = 1;

        return view('/tatausaha/dashboard', $data);

    }

    public function guru()
    {
        $data['title'] = "Dashboard | Tata Usaha";
        $data['tatausaha'] = 2;

        return view('/tatausaha/guru/guru', $data);

    }

    public function tambahguru()
    {
        $data['title'] = "Tambah Data Guru |  Tata Usaha";
        $data['tatausaha'] = 2;

        $query = $this->db->table('mapel');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['mapel'] = $hasilQuery->getResult();

        return view('/tatausaha/guru/tambahguru', $data);
    }

    public function tambahtendik()
    {
        $data['title'] = "Tambah Data Guru |  Tata Usaha";
        $data['tatausaha'] = 2;

        $query = $this->db->table('mapel');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['mapel'] = $hasilQuery->getResult();

        return view('/tatausaha/guru/tambahtendik', $data);
    }


    public function prosestambahguru()
    {
        if (!$this->validate([
            'foto' => [
                'mime_in[foto,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[foto,512]',
            ],
            'nama' => [
                'rules' => 'required|is_unique[guru.nama]',
                'errors' => [
                    'required' => 'Nama Harus diisi',
                    'is_unique'=> 'Nama Tidak Boleh Sama',
                ]
            ],
            'nik' => [
                'rules' => 'required|is_unique[guru.nik]|numeric',
                'errors' => [
                    'required' => 'NIK Harus diisi',
                    'is_unique'=> 'NIK Tidak Boleh Sama',
                    'numeric'=> 'Format NIK Tidak Dikenali',                    
                ]
            ],
            'nip' => [
                'rules' => 'required|is_unique[guru.nip]|numeric',
                'errors' => [
                    'required' => 'NIP Harus diisi',
                    'is_unique'=> 'NIP Tidak Boleh Sama',
                    'numeric'=> 'Format NIP Tidak Dikenali',                    
                ]
            ],
            'tempat_lahir' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Tempat Lahir Harus diisi',
                    'alpha_space'=> 'Format Tempat Lahir Tidak Dikenali',                    
                ]
            ],
            'tanggal_lahir' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Lahir Harus diisi',                    
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Jenis Kelamin Harus diisi',
                    'numeric'=> 'Format Jenis Kelamin Tidak Dikenali',                    
                ]
            ],
            'agama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => ' Agama Harus diisi',                    
                ]
            ],
            'pendidikan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => ' Pendidikan Harus diisi',
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => ' Status Harus diisi',
                ]
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => ' Alamat Harus diisi',
                ]
            ],
            'pos' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Kode Pos Harus diisi',
                    'numeric' => 'Format Kode Pos Tidak Sesuai Format'
                ]
            ],
            'kontak' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Nomor Kontak Harus diisi',
                    'numeric' => 'Format Nomor Kontak Tidak Sesuai Format'
                ]
            ],
            'jabatan' => [
                'rules' => 'required|numeric',
            ],
        ])) 
        {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $avatar   = $this->request->getFile('foto');
        $namabaru = str_replace(' ', '-', $avatar->getName());
        $avatar->move(WRITEPATH . '../public/assets/upload/guru/', $namabaru);
                        // Create thumb
        $image = \Config\Services::image()
        ->withFile(WRITEPATH . '../public/assets/upload/guru/' . $namabaru)
        ->fit(100, 100, 'center')
        ->save(WRITEPATH . '../public/assets/upload/guru/thumbs/' . $namabaru);

        if($this->request->getVar('jabatan') == 1){
            $data = [
                'nama'              => $this->request->getVar('nama'),
                'nik'               => $this->request->getVar('nik'),
                'nip'               => $this->request->getVar('nip'),
                'tempat_lahir'      => $this->request->getVar('tempat_lahir'),
                'tanggal_lahir'     => $this->request->getVar('tanggal_lahir'),
                'jenis_kelamin'     => $this->request->getVar('jenis_kelamin'),
                'foto'              => $namabaru,
                'agama'             => $this->request->getVar('agama'),
                'pendidikan'        => $this->request->getVar('pendidikan'),
                'status'            => $this->request->getVar('status'),
                'mapel'             => $this->request->getVar('mapel'),
                'alamat'            => $this->request->getVar('alamat'),
                'pos'               => $this->request->getVar('pos'),
                'kontak'            => $this->request->getVar('kontak'),
                'jabatan'           => $this->request->getVar('jabatan'),
            ];
        }elseif($this->request->getVar('jabatan') == 2){
            $data = [
                'nama'              => $this->request->getVar('nama'),
                'nik'               => $this->request->getVar('nik'),
                'nip'               => $this->request->getVar('nip'),
                'tempat_lahir'      => $this->request->getVar('tempat_lahir'),
                'tanggal_lahir'     => $this->request->getVar('tanggal_lahir'),
                'jenis_kelamin'     => $this->request->getVar('jenis_kelamin'),
                'foto'              => $namabaru,
                'agama'             => $this->request->getVar('agama'),
                'pendidikan'        => $this->request->getVar('pendidikan'),
                'status'            => $this->request->getVar('status'),
                'alamat'            => $this->request->getVar('alamat'),
                'pos'               => $this->request->getVar('pos'),
                'kontak'            => $this->request->getVar('kontak'),
                'jabatan'           => $this->request->getVar('jabatan'),
            ];
        }        

        $builder = $this->db->table('guru');
        $builder->insert($data);

        $data['tatausaha'] = 2;

        return redirect()->to(base_url('tatausaha/guru'))->with('message', 'Data Berhasil Ditambahkan');
    }
}
