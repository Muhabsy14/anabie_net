<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateWhatsappModel extends Model
{
    protected $table            = 'template_whatsapp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_template',
        'kategori',
        'isi_pesan',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Mengambil semua template WhatsApp yang aktif
     *
     * @return array
     */
    public function getTemplateAktif()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    /**
     * Mengambil template WhatsApp aktif berdasarkan kategori
     *
     * @param string $kategori
     * @return array
     */
    public function getTemplateAktifByKategori($kategori)
    {
        return $this->where('status', 'aktif')
                    ->where('kategori', $kategori)
                    ->findAll();
    }

    /**
     * Mengambil template WhatsApp berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getTemplateById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil template WhatsApp aktif berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getTemplateAktifById($id)
    {
        return $this->where('id', $id)
                    ->where('status', 'aktif')
                    ->first();
    }

    /**
     * Menghitung jumlah template berdasarkan status
     *
     * @param string $status
     * @return int
     */
    public function countByStatus($status)
    {
        return $this->where('status', $status)->countAllResults();
    }

    /**
     * Mengubah status template menjadi aktif
     *
     * @param int $id
     * @return bool
     */
    public function aktivasiTemplate($id)
    {
        return $this->update($id, ['status' => 'aktif']);
    }

    /**
     * Mengubah status template menjadi nonaktif
     *
     * @param int $id
     * @return bool
     */
    public function nonaktifkanTemplate($id)
    {
        return $this->update($id, ['status' => 'nonaktif']);
    }
}
