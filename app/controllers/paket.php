<?php
// ============================================================
//  Controller: Kelola Paket / Layanan
// ============================================================

function ctl_paket_index(): void
{
    $paket = query_all("SELECT * FROM paket ORDER BY harga");
    render('admin/paket_index', compact('paket'),
        ['title' => 'Kelola Paket/Layanan', 'active' => 'paket.index']);
}

function ctl_paket_create(): void
{
    $paket = null;
    render('admin/paket_form', compact('paket'),
        ['title' => 'Tambah Paket', 'active' => 'paket.index']);
}

function ctl_paket_store(): void
{
    $d = paket_input();
    if ($d['nama'] === '' || $d['kecepatan'] === '') {
        set_flash('error', 'Nama & kecepatan wajib diisi.');
        redirect('paket.create');
    }
    execute("INSERT INTO paket (nama,kecepatan,harga,deskripsi,status) VALUES (?,?,?,?,?)",
        [$d['nama'], $d['kecepatan'], $d['harga'], $d['deskripsi'], $d['status']]);
    set_flash('success', 'Paket berhasil ditambahkan.');
    redirect('paket.index');
}

function ctl_paket_edit(): void
{
    $paket = query_one("SELECT * FROM paket WHERE id=?", [(int) ($_GET['id'] ?? 0)]);
    if (!$paket) { set_flash('error', 'Paket tidak ditemukan.'); redirect('paket.index'); }
    render('admin/paket_form', compact('paket'),
        ['title' => 'Edit Paket', 'active' => 'paket.index']);
}

function ctl_paket_update(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $d = paket_input();
    execute("UPDATE paket SET nama=?,kecepatan=?,harga=?,deskripsi=?,status=? WHERE id=?",
        [$d['nama'], $d['kecepatan'], $d['harga'], $d['deskripsi'], $d['status'], $id]);
    set_flash('success', 'Paket diperbarui.');
    redirect('paket.index');
}

function ctl_paket_delete(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $used = (int) query_one("SELECT COUNT(*) c FROM pelanggan WHERE paket_id=?", [$id])['c'];
    if ($used > 0) {
        set_flash('error', 'Paket sedang dipakai pelanggan, tidak dapat dihapus.');
    } else {
        execute("DELETE FROM paket WHERE id=?", [$id]);
        set_flash('success', 'Paket dihapus.');
    }
    redirect('paket.index');
}

function paket_input(): array
{
    return [
        'nama'      => trim($_POST['nama'] ?? ''),
        'kecepatan' => trim($_POST['kecepatan'] ?? ''),
        'harga'     => (float) ($_POST['harga'] ?? 0),
        'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        'status'    => in_array($_POST['status'] ?? '', ['aktif','nonaktif'], true) ? $_POST['status'] : 'aktif',
    ];
}
