<?php
// ============================================================
//  Controller: Kelola Tagihan
// ============================================================

function ctl_tagihan_index(): void
{
    $filter = $_GET['status'] ?? '';
    $where = in_array($filter, ['lunas','belum_lunas'], true) ? "WHERE t.status='$filter'" : '';
    $tagihan = query_all(
        "SELECT t.*, p.nama AS pelanggan, p.kode FROM tagihan t
         JOIN pelanggan p ON p.id = t.pelanggan_id
         $where ORDER BY t.periode DESC, p.nama"
    );
    render('admin/tagihan_index', compact('tagihan', 'filter'),
        ['title' => 'Kelola Tagihan', 'active' => 'tagihan.index']);
}

function ctl_tagihan_create(): void
{
    $tagihan = null;
    $pelanggan = query_all(
        "SELECT p.*, pk.harga, pk.nama AS paket_nama FROM pelanggan p
         LEFT JOIN paket pk ON pk.id = p.paket_id ORDER BY p.nama"
    );
    render('admin/tagihan_form', compact('tagihan', 'pelanggan'),
        ['title' => 'Buat Tagihan', 'active' => 'tagihan.index']);
}

function ctl_tagihan_store(): void
{
    $d = tagihan_input();
    if (!$d['pelanggan_id'] || $d['periode'] === '') {
        set_flash('error', 'Pelanggan & periode wajib diisi.');
        redirect('tagihan.create');
    }
    $dup = query_one("SELECT id FROM tagihan WHERE pelanggan_id=? AND periode=?",
        [$d['pelanggan_id'], $d['periode']]);
    if ($dup) {
        set_flash('error', 'Tagihan untuk pelanggan & periode tersebut sudah ada.');
        redirect('tagihan.create');
    }
    execute("INSERT INTO tagihan (pelanggan_id,periode,jumlah,jatuh_tempo,status,keterangan)
             VALUES (?,?,?,?,?,?)",
        [$d['pelanggan_id'], $d['periode'], $d['jumlah'], $d['jatuh_tempo'], $d['status'], $d['keterangan']]);
    set_flash('success', 'Tagihan berhasil dibuat.');
    redirect('tagihan.index');
}

function ctl_tagihan_edit(): void
{
    $tagihan = query_one("SELECT * FROM tagihan WHERE id=?", [(int) ($_GET['id'] ?? 0)]);
    if (!$tagihan) { set_flash('error', 'Tagihan tidak ditemukan.'); redirect('tagihan.index'); }
    $pelanggan = query_all("SELECT p.*, pk.harga FROM pelanggan p LEFT JOIN paket pk ON pk.id=p.paket_id ORDER BY p.nama");
    render('admin/tagihan_form', compact('tagihan', 'pelanggan'),
        ['title' => 'Edit Tagihan', 'active' => 'tagihan.index']);
}

function ctl_tagihan_update(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $d = tagihan_input();
    execute("UPDATE tagihan SET pelanggan_id=?,periode=?,jumlah=?,jatuh_tempo=?,status=?,keterangan=? WHERE id=?",
        [$d['pelanggan_id'], $d['periode'], $d['jumlah'], $d['jatuh_tempo'], $d['status'], $d['keterangan'], $id]);
    set_flash('success', 'Tagihan diperbarui.');
    redirect('tagihan.index');
}

function ctl_tagihan_delete(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    execute("DELETE FROM tagihan WHERE id=?", [$id]);
    set_flash('success', 'Tagihan dihapus.');
    redirect('tagihan.index');
}

function tagihan_input(): array
{
    return [
        'pelanggan_id' => (int) ($_POST['pelanggan_id'] ?? 0),
        'periode'      => trim($_POST['periode'] ?? ''),
        'jumlah'       => (float) ($_POST['jumlah'] ?? 0),
        'jatuh_tempo'  => $_POST['jatuh_tempo'] ?: null,
        'status'       => in_array($_POST['status'] ?? '', ['belum_lunas','lunas'], true) ? $_POST['status'] : 'belum_lunas',
        'keterangan'   => trim($_POST['keterangan'] ?? ''),
    ];
}
