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

        $query = $this->db->table('guru');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['guru'] = $hasilQuery->getResult();

        $query = $this->db->table('mapel');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['mapel'] = $hasilQuery->getResult();

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
                    'is_unique' => 'Nama Tidak Boleh Sama',
                ]
            ],
            'nik' => [
                'rules' => 'required|is_unique[guru.nik]|numeric',
                'errors' => [
                    'required' => 'NIK Harus diisi',
                    'is_unique' => 'NIK Tidak Boleh Sama',
                    'numeric' => 'Format NIK Tidak Dikenali',
                ]
            ],
            'nip' => [
                'rules' => 'required|is_unique[guru.nip]|numeric',
                'errors' => [
                    'required' => 'NIP Harus diisi',
                    'is_unique' => 'NIP Tidak Boleh Sama',
                    'numeric' => 'Format NIP Tidak Dikenali',
                ]
            ],
            'tempat_lahir' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Tempat Lahir Harus diisi',
                    'alpha_space' => 'Format Tempat Lahir Tidak Dikenali',
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
                    'numeric' => 'Format Jenis Kelamin Tidak Dikenali',
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
                'rules' => 'required|numeric|is_unique[guru.kontak]',
                'errors' => [
                    'required' => 'Nomor Kontak Harus diisi',
                    'numeric' => 'Format Nomor Kontak Tidak Sesuai Format',
                    'is_unique' => 'Nomor Kontak Tidak Boleh Sama',
                ]
            ],
            'jabatan' => [
                'rules' => 'required|numeric',
            ],
        ])) {
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

        if ($this->request->getVar('jabatan') == 1) {
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
        } elseif ($this->request->getVar('jabatan') == 2) {
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

    public function detailguru($id)
    {
        $query = $this->db->table('guru');
        $query->select('*');
        $query->where('id_guru', $id);
        $hasilQuery = $query->get();
        $dataGuru = $hasilQuery->getResult();

        if (empty($hasilQuery)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tidak ditemukan !');
        }

        foreach ($dataGuru as $guru) :
            $data['id_guru'] = $guru->id_guru;
            $data['nama'] = $guru->nama;
            $data['nik'] = $guru->nik;
            $data['nip'] = $guru->nip;
            $data['tempat_lahir'] = $guru->tempat_lahir;
            $data['tanggal_lahir'] = $guru->tanggal_lahir;
            $data['jenis_kelamin'] = $guru->jenis_kelamin;
            $data['agama'] = $guru->agama;
            $data['pendidikan'] = $guru->pendidikan;
            $data['status'] = $guru->status;
            $data['mapel'] = $guru->mapel;
            $data['alamat'] = $guru->alamat;
            $data['pos'] = $guru->pos;
            $data['kontak'] = $guru->kontak;
            $data['foto'] = $guru->foto;
            $data['jabatan'] = $guru->jabatan;
        endforeach;

        $data['title'] = "Detail Guru | Tata Usaha";
        $data['tatausaha'] = 2;

        return view('/tatausaha/guru/detailguru', $data);
    }

    public function ubahguru($id)
    {
        $query = $this->db->table('guru');
        $query->select('*');
        $query->where('id_guru', $id);
        $hasilQuery = $query->get();
        $dataGuru = $hasilQuery->getResult();

        if (empty($hasilQuery)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tidak ditemukan !');
        }

        foreach ($dataGuru as $guru) :
            $data['id_guru'] = $guru->id_guru;
            $data['nama'] = $guru->nama;
            $data['nik'] = $guru->nik;
            $data['nip'] = $guru->nip;
            $data['tempat_lahir'] = $guru->tempat_lahir;
            $data['tanggal_lahir'] = $guru->tanggal_lahir;
            $data['jenis_kelamin'] = $guru->jenis_kelamin;
            $data['agama'] = $guru->agama;
            $data['pendidikan'] = $guru->pendidikan;
            $data['status'] = $guru->status;
            $data['mapel'] = $guru->mapel;
            $data['alamat'] = $guru->alamat;
            $data['pos'] = $guru->pos;
            $data['kontak'] = $guru->kontak;
            $data['foto'] = $guru->foto;
            $data['jabatan'] = $guru->jabatan;
        endforeach;

        $query = $this->db->table('mapel');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['mapel'] = $hasilQuery->getResult();

        $data['title'] = "Detail Guru | Tata Usaha";
        $data['tatausaha'] = 2;

        return view('/tatausaha/guru/ubahguru', $data);
    }

    public function prosesubahguru($id)
    {
        if (!$this->validate([
            'foto' => [
                'mime_in[foto,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[foto,512]',
            ],
            'nama' => [
                'rules' => 'required|is_unique[guru.nama, id_guru,' . $id . ']',
                'errors' => [
                    'required' => 'Nama Harus diisi',
                    'is_unique' => 'Nama Tidak Boleh Sama',
                ]
            ],
            'nik' => [
                'rules' => 'required|is_unique[guru.nik, id_guru,' . $id . ']|numeric',
                'errors' => [
                    'required' => 'NIK Harus diisi',
                    'is_unique' => 'NIK Tidak Boleh Sama',
                    'numeric' => 'Format NIK Tidak Dikenali',
                ]
            ],
            'nip' => [
                'rules' => 'required|is_unique[guru.nip, id_guru,' . $id . ']|numeric',
                'errors' => [
                    'required' => 'NIP Harus diisi',
                    'is_unique' => 'NIP Tidak Boleh Sama',
                    'numeric' => 'Format NIP Tidak Dikenali',
                ]
            ],
            'tempat_lahir' => [
                'rules' => 'required|alpha_space',
                'errors' => [
                    'required' => 'Tempat Lahir Harus diisi',
                    'alpha_space' => 'Format Tempat Lahir Tidak Dikenali',
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
                    'numeric' => 'Format Jenis Kelamin Tidak Dikenali',
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
                'rules' => 'required|numeric|is_unique[guru.kontak, id_guru,' . $id . ']',
                'errors' => [
                    'required' => 'Nomor Kontak Harus diisi',
                    'numeric' => 'Format Nomor Kontak Tidak Sesuai Format',
                    'is_unique' => 'Kontak Tidak Boleh Sama',
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }


        if (!empty($_FILES['foto']['name'])) {
            $avatar   = $this->request->getFile('foto');
            $namabaru = str_replace(' ', '-', $avatar->getName());
            $avatar->move(WRITEPATH . '../public/assets/upload/guru/', $namabaru);
            // Create thumb
            $image = \Config\Services::image()
                ->withFile(WRITEPATH . '../public/assets/upload/guru/' . $namabaru)
                ->fit(100, 100, 'center')
                ->save(WRITEPATH . '../public/assets/upload/guru/thumbs/' . $namabaru);

            if ($this->request->getVar('jabatan') == 1) {
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
            } elseif ($this->request->getVar('jabatan') == 2) {
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
        } else {
            if ($this->request->getVar('jabatan') == 1) {
                $data = [
                    'nama'              => $this->request->getVar('nama'),
                    'nik'               => $this->request->getVar('nik'),
                    'nip'               => $this->request->getVar('nip'),
                    'tempat_lahir'      => $this->request->getVar('tempat_lahir'),
                    'tanggal_lahir'     => $this->request->getVar('tanggal_lahir'),
                    'jenis_kelamin'     => $this->request->getVar('jenis_kelamin'),
                    'agama'             => $this->request->getVar('agama'),
                    'pendidikan'        => $this->request->getVar('pendidikan'),
                    'status'            => $this->request->getVar('status'),
                    'mapel'             => $this->request->getVar('mapel'),
                    'alamat'            => $this->request->getVar('alamat'),
                    'pos'               => $this->request->getVar('pos'),
                    'kontak'            => $this->request->getVar('kontak'),
                    'jabatan'           => $this->request->getVar('jabatan'),
                ];
            } elseif ($this->request->getVar('jabatan') == 2) {
                $data = [
                    'nama'              => $this->request->getVar('nama'),
                    'nik'               => $this->request->getVar('nik'),
                    'nip'               => $this->request->getVar('nip'),
                    'tempat_lahir'      => $this->request->getVar('tempat_lahir'),
                    'tanggal_lahir'     => $this->request->getVar('tanggal_lahir'),
                    'jenis_kelamin'     => $this->request->getVar('jenis_kelamin'),
                    'agama'             => $this->request->getVar('agama'),
                    'pendidikan'        => $this->request->getVar('pendidikan'),
                    'status'            => $this->request->getVar('status'),
                    'alamat'            => $this->request->getVar('alamat'),
                    'pos'               => $this->request->getVar('pos'),
                    'kontak'            => $this->request->getVar('kontak'),
                    'jabatan'           => $this->request->getVar('jabatan'),
                ];
            }
        }
        $builder = $this->db->table('guru');
        $builder->where('id_guru', $id);
        $builder->update($data);

        $data['tatausaha'] = 2;

        if (!$builder) {
            session()->setFlashdata('error', 'Silahkan Cek Inputan Anda');
            return redirect()->back()->withInput();
        } else {
            return redirect()->to(base_url('tatausaha/guru/'))->with('message', 'Data Berhasil Diubah');
        }
    }

    public function hapusguru($id)
    {
        $query = $this->db->table('guru');
        $query->where('id_guru', $id);
        $hasilQuery = $query->get();

        if (empty($hasilQuery)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tidak ditemukan !');
        }

        $query = $this->db->table('guru');
        $query->delete(['id_guru' => $id]);

        $data['tatausaha'] = 2;
        $data['title'] = "Guru | tatausaha";
        return redirect()->to(base_url('tatausaha/Guru/'))->with('message', 'Data Berhasil Dihapus');
    }

    public function berkas()
    {
        $data['title'] = "Berkas | Tatausaha";
        $data['tatausaha'] = 3;

        $query = $this->db->table('berkas');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['berkas'] = $hasilQuery->getResult();

        return view('/tatausaha/berkas/berkas', $data);
    }

    public function prosestambahberkas()
    {
        if (!$this->validate([
            'namaBerkas' => [
                'rules' => 'required|is_unique[berkas.namaBerkas]',
                'errors' => [
                    'required' => 'Nama berkas Harus diisi',
                    'is_unique' => 'Nama berkas Tidak Boleh Sama'
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back();
        }

        $data = [
            'namaBerkas'            => $this->request->getVar('namaBerkas'),
            'ket'                   => $this->request->getVar('ket')
        ];

        $builder = $this->db->table('berkas');
        $builder->insert($data);

        $data['tatausaha'] = 3;

        return redirect()->to(base_url('tatausaha/berkas/'))->with('message', 'Data Berhasil Ditambahkan');
    }

    public function prosesubahberkas($id)
    {
        if (!$this->validate([
            'namaBerkas' => [
                'rules' => 'required|is_unique[berkas.namaBerkas, idBerkas,' . $id . ']',
                'errors' => [
                    'required' => 'Nama berkas Harus diisi',
                    'is_unique' => 'Nama berkas Tidak Boleh Sama'
                ]
            ],
        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        }

        $data = [
            'namaBerkas'        => $this->request->getVar('namaBerkas'),
            'ket'               => $this->request->getVar('ket'),
        ];

        $builder = $this->db->table('berkas');
        $builder->where('idBerkas', $id);
        $builder->update($data);

        $data['tatausaha'] = 3;

        return redirect()->to(base_url('tatausaha/berkas/'))->with('message', 'Data Berhasil Diubah');
    }

    public function hapusberkas($id)
    {
        $query = $this->db->table('berkas');
        $query->where('idBerkas', $id);
        $hasilQuery = $query->get();

        if (empty($hasilQuery)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Tidak ditemukan !');
        }

        $query = $this->db->table('berkas');
        $query->delete(['idBerkas' => $id]);

        $data['tatausaha'] = 3;
        $data['title'] = "Berkas | tatausaha";
        return redirect()->to(base_url('tatausaha/berkas/'))->with('message', 'Data Berhasil Dihapus');
    }

    public function berkasguru()
    {
        $data['title'] = "Berkas Guru/Tendik | Tatausaha";
        $data['tatausaha'] = 4;

        $query = $this->db->table('daftarBerkas');
        $query->select('guru.nama as namaGuru, berkas.ket as keterangan, 
                            daftarBerkas.fileberkas as fileBerkas, 
                            daftarBerkas.idDaftarBerkas as idBerkas,
                            berkas.namaBerkas');
        $query->join('berkas', 'daftarBerkas.idBerkas = berkas.idBerkas');
        $query->join('guru', 'daftarBerkas.id_guru =guru.id_guru');
        $hasilQuery = $query->get();
        $data['daftarBerkas'] =  $hasilQuery->getResult();

        $query = $this->db->table('berkas');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['berkas'] = $hasilQuery->getResult();

        $query = $this->db->table('guru');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['guru'] = $hasilQuery->getResult();

        return view('/tatausaha/berkasguru/berkasguru', $data);
    }

    public function prosestambahberkasguru()
    {
        if (!$this->validate([
            'id_guru' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required'  => 'Silahkan Isi Guru/Tendik',
                    'integer'    => 'Guru/Tendik Tidak Dikenali',
                ]
            ],
            'idBerkas' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required'  => 'Silahkan Isi Nama Berkas',
                    'integer'    => 'Nama Berkas Tidak Dikenali',
                ]
            ],
            'fileBerkas' => [
                'rules' => 'mime_in[fileBerkas,application/pdf]|max_size[fileBerkas,512]',
                'errors' => [
                    'mime_in'   => 'Format file berkas tidak dikenali',
                    'max_size'  =>  'Ukuran File Berkas Terlalu Besar'
                ]
            ],

        ])) {
            session()->setFlashdata('error', $this->validator->listErrors());
            return redirect()->back()->withInput();
        } else {
            $avatar   = $this->request->getFile('fileBerkas');
            $namabaru = str_replace(' ', '-', $avatar->getName());
            $avatar->move(WRITEPATH . '../public/assets/upload/berkasGuru/', $namabaru);

            // simpan data ke array 
            $data = [
                'id_guru'                   => $this->request->getVar('id_guru'),
                'idBerkas'                  => $this->request->getVar('idBerkas'),
                'fileBerkas'                => $namabaru,
                'tanggal'                   => date("Y-m-d H:i:s")
            ];
            // input data daftar berkas dari array 
            $builder = $this->db->table('daftarBerkas');
            $builder->insert($data);
        }

        return redirect()->to(base_url('tatausaha/berkasguru'))->with('message', 'Data Berhasil Ditambahkan');
    }
}
