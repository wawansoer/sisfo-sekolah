<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        helper('auth');
        helper('tgl');
        $pager = \Config\Services::pager();
    }

    public function index()
    {
        $data['title'] = "SMA Muhammadiyah Kendari";
        $data['aktif'] = 1;
        $query = $this->db->table('berita');
        $query->select('judul_berita, slug_judul, gambar, ringkasan');
        $query->where('berita.status', 'Publish');
        $query->like('kategori', 'Berita');
        $query->orlike('kategori', 'Umum');
        $query->orlike('kategori', 'Pengumuman');
        $query->orderBy('prioritas', 'DESC');
        $query->limit(3);
        $hasilQuery = $query->get();
        $data['berita'] = $hasilQuery->getResult();

        $query = $this->db->table('kepsek');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['sambutan'] = $hasilQuery->getResult();

        $query = $this->db->table('berita');
        $query->select('*');
        $query->where('berita.status', 'Publish');
        $query->where('berita.status', 'Publish');
        $query->like('kategori', 'Berita');
        $query->orlike('kategori', 'Umum');
        $query->orlike('kategori', 'Pengumuman');
        $query->limit(12);
        $hasilQuery = $query->get();
        $data['berita2nd'] = $hasilQuery->getResult();

        $query = $this->db->table('tendik');
        $query->select('*');
        $hasilQuery = $query->get();
        $data['tendik'] = $hasilQuery->getResult();

        $query = $this->db->table('sarpras');
        $query->select('*');
        $query->limit(6);
        $hasilQuery = $query->get();
        $data['sarpras'] = $hasilQuery->getResult();

        return view('/home/halaman_utama', $data);
    }

    public function berita($id)
    {
        $data['title'] = "Berita | SMA Muhammadiyah Kendari";
        $data['aktif'] = 1;
        $query = $this->db->table('berita');
        $query->select('judul_berita, slug_judul, gambar, ringkasan');
        $query->where('berita.status', 'Publish');
        $query->like('kategori', 'Berita');
        $query->orlike('kategori', 'Umum');
        $query->orlike('kategori', 'Pengumuman');
        $query->orderBy('tanggal', 'DESC');
        $hasilQuery = $query->get();

        $data['berita'] = $hasilQuery->getResult();

        return view('/home/berita', $data);
    }

    public function daftarberita()
    {
        $model = new \App\Models\BeritaModel();

        $data = [
            'berita' => $model->where('status', 'Publish')
                ->whereIn('kategori', ['Berita', 'Umum'])
                ->paginate(12, 'berita'),
            'pager' => $model->pager,
            'title' => "Berita | SMA Muhammadiyah Kendari",
            'aktif' => 3
        ];
        return view('/home/daftarberita', $data);
    }
}
