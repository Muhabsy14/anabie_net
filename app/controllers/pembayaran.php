<?php
// ============================================================
//  Controller: Kelola Pembayaran (Pencatatan + bukti)
// ============================================================

function ctl_pembayaran_index(): void
{
    $pembayaran = query_all(
        "SELECT pb.*, t.periode, p.nama AS pelanggan, p.kode, u.nama AS petugas
         FROM pembayaran pb
         JOIN tagihan t   ON t.id = pb.tagihan_id
         JOIN pelanggan p ON p.id = t.pelanggan_id
         LEFT JOIN users u ON u.id = pb.dicatat_oleh
         ORDER BY pb.tgl_bayar DESC, pb.id DESC"
    );
    render('admin/pembayaran_index', compact('pembayaran'),
        ['title' => 'Kelola Pembayaran', 'active' => 'pembayaran.index']);
}

function ctl_pembayaran_create(): void
{
    // Tagihan yang masih belum lunas
    $tagihan = query_all(
        "SELECT t.*, p.nama AS pelanggan, p.kode FROM tagihan t
         JOIN pelanggan p ON p.id = t.pelanggan_id
         WHERE t.status='belum_lunas' ORDER BY p.nama, t.periode"
    );
    $selected = (int) ($_GET['tagihan_id'] ?? 0);
    render('admin/pembayaran_form', compact('tagihan', 'selected'),
        ['title' => 'Catat Pembayaran', 'active' => 'pembayaran.index']);
}

function ctl_pembayaran_store(): void
{
    $tagihan_id = (int) ($_POST['tagihan_id'] ?? 0);
    $tagihan = query_one("SELECT * FROM tagihan WHERE id=?", [$tagihan_id]);
    if (!$tagihan) { set_flash('error', 'Tagihan tidak ditemukan.'); redirect('pembayaran.create'); }

    $tgl_bayar = $_POST['tgl_bayar'] ?: date('Y-m-d');
    $jumlah    = (float) ($_POST['jumlah'] ?? 0);
    $metode    = in_array($_POST['metode'] ?? '', ['tunai','transfer','qris','lainnya'], true) ? $_POST['metode'] : 'tunai';
    $ket       = trim($_POST['keterangan'] ?? '');

    try {
        $bukti = upload_file('bukti', ['jpg','jpeg','png','webp','pdf'], 'bukti');
    } catch (RuntimeException $e) {
        set_flash('error', $e->getMessage());
        redirect('pembayaran.create', ['tagihan_id' => $tagihan_id]);
    }

    db()->beginTransaction();
    try {
        execute("INSERT INTO pembayaran (tagihan_id,tgl_bayar,jumlah,metode,bukti,dicatat_oleh,keterangan)
                 VALUES (?,?,?,?,?,?,?)",
            [$tagihan_id, $tgl_bayar, $jumlah, $metode, $bukti, current_user()['id'], $ket]);
        execute("UPDATE tagihan SET status='lunas' WHERE id=?", [$tagihan_id]);
        db()->commit();
    } catch (Throwable $e) {
        db()->rollBack();
        set_flash('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        redirect('pembayaran.create', ['tagihan_id' => $tagihan_id]);
    }
    set_flash('success', 'Pembayaran berhasil dicatat & tagihan ditandai lunas.');
    redirect('pembayaran.index');
}

function ctl_pembayaran_show(): void
{
    $bayar = query_one(
        "SELECT pb.*, t.periode, t.jumlah AS tagihan_jumlah, p.nama AS pelanggan, p.kode, p.alamat,
                u.nama AS petugas
         FROM pembayaran pb
         JOIN tagihan t   ON t.id = pb.tagihan_id
         JOIN pelanggan p ON p.id = t.pelanggan_id
         LEFT JOIN users u ON u.id = pb.dicatat_oleh
         WHERE pb.id=?", [(int) ($_GET['id'] ?? 0)]
    );
    if (!$bayar) { set_flash('error', 'Data pembayaran tidak ditemukan.'); redirect('pembayaran.index'); }
    render('admin/pembayaran_show', compact('bayar'),
        ['title' => 'Detail Pembayaran', 'active' => 'pembayaran.index']);
}

function ctl_pembayaran_delete(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $bayar = query_one("SELECT * FROM pembayaran WHERE id=?", [$id]);
    if ($bayar) {
        db()->beginTransaction();
        try {
            execute("DELETE FROM pembayaran WHERE id=?", [$id]);
            execute("UPDATE tagihan SET status='belum_lunas' WHERE id=?", [$bayar['tagihan_id']]);
            db()->commit();
            if ($bayar['bukti'] && is_file(UPLOAD_PATH . '/' . $bayar['bukti'])) {
                @unlink(UPLOAD_PATH . '/' . $bayar['bukti']);
            }
        } catch (Throwable $e) {
            db()->rollBack();
        }
        set_flash('success', 'Pembayaran dihapus & tagihan dikembalikan ke belum lunas.');
    }
    redirect('pembayaran.index');
}
