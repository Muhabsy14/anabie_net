<?php
// ============================================================
//  Controller: Layanan mandiri Pelanggan
// ============================================================

function require_pelanggan_data(): array
{
    $p = current_pelanggan();
    if (!$p) {
        set_flash('error', 'Akun Anda belum terhubung ke data langganan. Hubungi admin.');
        redirect('pelanggan.dashboard');
    }
    return $p;
}

function ctl_my_tagihan(): void
{
    $p = require_pelanggan_data();
    $tagihan = query_all(
        "SELECT * FROM tagihan WHERE pelanggan_id=? ORDER BY periode DESC", [$p['id']]
    );
    render('pelanggan/tagihan', compact('p', 'tagihan'),
        ['title' => 'Tagihan Saya', 'active' => 'my.tagihan']);
}

function ctl_my_pembayaran(): void
{
    $p = require_pelanggan_data();
    $pembayaran = query_all(
        "SELECT pb.*, t.periode FROM pembayaran pb
         JOIN tagihan t ON t.id = pb.tagihan_id
         WHERE t.pelanggan_id=? ORDER BY pb.tgl_bayar DESC", [$p['id']]
    );
    render('pelanggan/pembayaran', compact('p', 'pembayaran'),
        ['title' => 'Riwayat Pembayaran', 'active' => 'my.pembayaran']);
}

function ctl_my_pengaduan(): void
{
    $p = require_pelanggan_data();
    $pengaduan = query_all(
        "SELECT * FROM pengaduan WHERE pelanggan_id=? ORDER BY created_at DESC", [$p['id']]
    );
    render('pelanggan/pengaduan', compact('p', 'pengaduan'),
        ['title' => 'Ajukan Pengaduan', 'active' => 'my.pengaduan']);
}

function ctl_my_pengaduan_store(): void
{
    $p = require_pelanggan_data();
    $judul = trim($_POST['judul'] ?? '');
    $isi   = trim($_POST['isi'] ?? '');
    if ($judul === '' || $isi === '') {
        set_flash('error', 'Judul & isi pengaduan wajib diisi.');
        redirect('my.pengaduan');
    }
    execute("INSERT INTO pengaduan (pelanggan_id,judul,isi,status) VALUES (?,?,?,'baru')",
        [$p['id'], $judul, $isi]);
    set_flash('success', 'Pengaduan Anda berhasil dikirim. Tim kami akan segera menindaklanjuti.');
    redirect('my.pengaduan');
}
