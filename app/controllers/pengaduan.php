<?php
// ============================================================
//  Controller: Kelola Pengaduan (staff)
// ============================================================

function ctl_pengaduan_index(): void
{
    $pengaduan = query_all(
        "SELECT pg.*, p.nama AS pelanggan, p.kode, p.no_hp FROM pengaduan pg
         JOIN pelanggan p ON p.id = pg.pelanggan_id
         ORDER BY FIELD(pg.status,'baru','diproses','selesai'), pg.created_at DESC"
    );
    render('admin/pengaduan_index', compact('pengaduan'),
        ['title' => 'Kelola Pengaduan', 'active' => 'pengaduan.index']);
}

function ctl_pengaduan_show(): void
{
    $pg = query_one(
        "SELECT pg.*, p.nama AS pelanggan, p.kode, p.no_hp, p.alamat FROM pengaduan pg
         JOIN pelanggan p ON p.id = pg.pelanggan_id WHERE pg.id=?", [(int) ($_GET['id'] ?? 0)]
    );
    if (!$pg) { set_flash('error', 'Pengaduan tidak ditemukan.'); redirect('pengaduan.index'); }
    render('admin/pengaduan_show', compact('pg'),
        ['title' => 'Detail Pengaduan', 'active' => 'pengaduan.index']);
}

function ctl_pengaduan_respond(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['baru','diproses','selesai'], true) ? $_POST['status'] : 'diproses';
    $tanggapan = trim($_POST['tanggapan'] ?? '');
    execute("UPDATE pengaduan SET status=?, tanggapan=? WHERE id=?", [$status, $tanggapan, $id]);
    set_flash('success', 'Tanggapan pengaduan disimpan.');
    redirect('pengaduan.show', ['id' => $id]);
}
