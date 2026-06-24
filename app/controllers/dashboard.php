<?php
// ============================================================
//  Controller: Dashboard per role
// ============================================================

function ctl_owner_dashboard(): void
{
    $stats = staff_stats();
    $stats['total_admin'] = (int) query_one("SELECT COUNT(*) c FROM users WHERE role='admin'")['c'];
    $pengaduan_baru = query_all(
        "SELECT pg.*, p.nama AS pelanggan FROM pengaduan pg
         JOIN pelanggan p ON p.id = pg.pelanggan_id
         WHERE pg.status != 'selesai' ORDER BY pg.created_at DESC LIMIT 5"
    );
    render('owner/dashboard', compact('stats', 'pengaduan_baru'),
        ['title' => 'Dashboard Owner', 'active' => 'owner.dashboard']);
}

function ctl_admin_dashboard(): void
{
    $stats = staff_stats();
    $pengaduan_baru = query_all(
        "SELECT pg.*, p.nama AS pelanggan FROM pengaduan pg
         JOIN pelanggan p ON p.id = pg.pelanggan_id
         WHERE pg.status != 'selesai' ORDER BY pg.created_at DESC LIMIT 5"
    );
    render('admin/dashboard', compact('stats', 'pengaduan_baru'),
        ['title' => 'Dashboard Admin', 'active' => 'admin.dashboard']);
}

function ctl_pelanggan_dashboard(): void
{
    $pelanggan = current_pelanggan();
    if (!$pelanggan) {
        set_flash('error', 'Akun pelanggan Anda belum terhubung ke data langganan. Hubungi admin.');
        $tagihan_belum = []; $paket = null; $total_tunggakan = 0; $jml_pengaduan = 0;
    } else {
        $tagihan_belum = query_all(
            "SELECT * FROM tagihan WHERE pelanggan_id=? AND status='belum_lunas' ORDER BY periode",
            [$pelanggan['id']]
        );
        $paket = $pelanggan['paket_id']
            ? query_one("SELECT * FROM paket WHERE id=?", [$pelanggan['paket_id']]) : null;
        $total_tunggakan = array_sum(array_column($tagihan_belum, 'jumlah'));
        $jml_pengaduan = (int) query_one(
            "SELECT COUNT(*) c FROM pengaduan WHERE pelanggan_id=?", [$pelanggan['id']]
        )['c'];
    }
    render('pelanggan/dashboard',
        compact('pelanggan', 'tagihan_belum', 'paket', 'total_tunggakan', 'jml_pengaduan'),
        ['title' => 'Dashboard', 'active' => 'pelanggan.dashboard']);
}

/** Statistik umum untuk owner & admin */
function staff_stats(): array
{
    return [
        'total_pelanggan' => (int) query_one("SELECT COUNT(*) c FROM pelanggan")['c'],
        'pelanggan_aktif' => (int) query_one("SELECT COUNT(*) c FROM pelanggan WHERE status='aktif'")['c'],
        'total_paket'     => (int) query_one("SELECT COUNT(*) c FROM paket")['c'],
        'tagihan_belum'   => (int) query_one("SELECT COUNT(*) c FROM tagihan WHERE status='belum_lunas'")['c'],
        'pengaduan_baru'  => (int) query_one("SELECT COUNT(*) c FROM pengaduan WHERE status!='selesai'")['c'],
        'pendapatan_bulan'=> (float) query_one(
            "SELECT COALESCE(SUM(jumlah),0) s FROM pembayaran WHERE DATE_FORMAT(tgl_bayar,'%Y-%m')=?",
            [date('Y-m')]
        )['s'],
    ];
}
