<?php
$this->extend('layouts/admin');
$this->section('content');
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1"><?= $title ?></h1>
                    <p class="text-muted mb-0">Kelola template pesan WhatsApp Anda</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg"></i> Tambah Template
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i>
            <strong>Sukses!</strong> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabel Template -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (!empty($templates)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-bold">No</th>
                                        <th class="fw-bold">Nama Template</th>
                                        <th class="fw-bold">Kategori</th>
                                        <th class="fw-bold">Isi Pesan</th>
                                        <th class="fw-bold">Status</th>
                                        <th class="fw-bold text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($templates as $template): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <strong><?= esc($template['nama_template']) ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark"><?= esc($template['kategori']) ?></span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;" title="<?= esc($template['isi_pesan']) ?>">
                                                    <?= esc(substr($template['isi_pesan'], 0, 50)) ?><?= strlen($template['isi_pesan']) > 50 ? '...' : '' ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($template['status'] === 'aktif'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Aktif
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-x-circle"></i> Nonaktif
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                                        onclick="editTemplate(<?= $template['id'] ?>)"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                        title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteTemplate(<?= $template['id'] ?>, '<?= esc($template['nama_template']) ?>')"
                                                        title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada template. <a href="#" onclick="document.querySelector('[data-bs-target=\'#modalTambah\']').click(); return false;">Tambah template baru</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Template -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-plus-circle text-primary"></i> Tambah Template Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambah" method="post" action="<?= base_url('admin/template-whatsapp') ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_template" class="form-label fw-bold">Nama Template</label>
                        <input type="text" class="form-control" id="nama_template" name="nama_template" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="isi_pesan" class="form-label fw-bold">Isi Pesan</label>
                        <textarea class="form-control" id="isi_pesan" name="isi_pesan" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Template -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-pencil-square text-primary"></i> Edit Template
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="post">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama_template" class="form-label fw-bold">Nama Template</label>
                        <input type="text" class="form-control" id="edit_nama_template" name="nama_template" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kategori" class="form-label fw-bold">Kategori</label>
                        <input type="text" class="form-control" id="edit_kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_isi_pesan" class="form-label fw-bold">Isi Pesan</label>
                        <textarea class="form-control" id="edit_isi_pesan" name="isi_pesan" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
    let currentTemplate = {};

    // Edit Template
    function editTemplate(id) {
        // Ambil data dari tabel
        const row = document.querySelector(`tr:has(button[onclick*="editTemplate(${id})"])`);
        if (row) {
            const cells = row.querySelectorAll('td');
            currentTemplate = {
                id: id,
                nama_template: cells[1].textContent.trim(),
                kategori: cells[2].textContent.trim(),
                isi_pesan: cells[3].title || cells[3].textContent.trim(),
                status: cells[4].textContent.includes('Aktif') ? 'aktif' : 'nonaktif'
            };

            document.getElementById('edit_id').value = currentTemplate.id;
            document.getElementById('edit_nama_template').value = currentTemplate.nama_template;
            document.getElementById('edit_kategori').value = currentTemplate.kategori;
            document.getElementById('edit_isi_pesan').value = currentTemplate.isi_pesan;
            document.getElementById('edit_status').value = currentTemplate.status;

            document.getElementById('formEdit').onsubmit = function(e) {
                e.preventDefault();
                updateTemplate(currentTemplate.id);
            };
        }
    }

    // Update Template via AJAX
    function updateTemplate(id) {
        const formData = new FormData(document.getElementById('formEdit'));
        const data = Object.fromEntries(formData);

        fetch(`<?= base_url('admin/template-whatsapp') ?>/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status) {
                showAlert('success', result.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', result.message);
            }
            bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memperbarui template');
        });
    }

    // Delete Template
    function deleteTemplate(id, nama) {
        if (confirm(`Apakah Anda yakin ingin menghapus template "${nama}"?`)) {
            fetch(`<?= base_url('admin/template-whatsapp') ?>/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.status) {
                    showAlert('success', result.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menghapus template');
            });
        }
    }

    // Show Alert
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.row').nextSibling);
    }
</script>
<?php $this->endSection(); ?>
