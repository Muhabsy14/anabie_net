<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TemplateWhatsappModel;
use CodeIgniter\HTTP\ResponseInterface;

class TemplateWhatsappController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TemplateWhatsappModel();
    }

    /**
     * Menampilkan daftar template WhatsApp
     */
    public function index()
    {
        $data = [
            'title'     => 'Template WhatsApp',
            'templates' => $this->model->findAll(),
        ];

        return view('admin/template_whatsapp/index', $data);
    }

    /**
     * Menyimpan template WhatsApp baru
     */
    public function store()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'status'  => false,
                'message' => 'Method not allowed'
            ]);
        }

        $data = [
            'nama_template' => $this->request->getPost('nama_template'),
            'kategori'      => $this->request->getPost('kategori'),
            'isi_pesan'     => $this->request->getPost('isi_pesan'),
            'status'        => $this->request->getPost('status') ?? 'aktif',
        ];

        try {
            $this->model->insert($data);
            return $this->response->setStatusCode(201)->setJSON([
                'status'  => true,
                'message' => 'Template berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Gagal menyimpan template: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mengupdate template WhatsApp
     */
    public function update($id)
    {
        if ($this->request->getMethod() !== 'put') {
            return $this->response->setStatusCode(405)->setJSON([
                'status'  => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Cek apakah template ada
        $template = $this->model->find($id);
        if (!$template) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Template tidak ditemukan'
            ]);
        }

        $data = [
            'nama_template' => $this->request->getVar('nama_template') ?? $template['nama_template'],
            'kategori'      => $this->request->getVar('kategori') ?? $template['kategori'],
            'isi_pesan'     => $this->request->getVar('isi_pesan') ?? $template['isi_pesan'],
            'status'        => $this->request->getVar('status') ?? $template['status'],
        ];

        try {
            $this->model->update($id, $data);
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => true,
                'message' => 'Template berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Gagal memperbarui template: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Menghapus template WhatsApp
     */
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON([
                'status'  => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Cek apakah template ada
        $template = $this->model->find($id);
        if (!$template) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => false,
                'message' => 'Template tidak ditemukan'
            ]);
        }

        try {
            $this->model->delete($id);
            return $this->response->setStatusCode(200)->setJSON([
                'status'  => true,
                'message' => 'Template berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => false,
                'message' => 'Gagal menghapus template: ' . $e->getMessage()
            ]);
        }
    }
}
