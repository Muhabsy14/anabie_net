<?php
// ============================================================
//  Anabie Net - Front Controller / Router
// ============================================================
session_start();

require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/lib/db.php';
require __DIR__ . '/../app/lib/helpers.php';
require __DIR__ . '/../app/lib/auth.php';
require __DIR__ . '/../app/lib/nav.php';
require __DIR__ . '/../app/lib/view.php';

// Controllers
foreach ([
    'auth','dashboard','admin','pelanggan','paket','tagihan',
    'pembayaran','notif','pengaduan','laporan','profil','my',
] as $c) {
    require __DIR__ . '/../app/controllers/' . $c . '.php';
}

// ------------------------------------------------------------
//  Daftar route => [handler, [role yang diizinkan]]
//  role kosong = boleh tamu (belum login)
// ------------------------------------------------------------
$ALL   = ['owner','admin','pelanggan'];
$STAFF = ['owner','admin'];

$routes = [
    'login'             => ['ctl_login',            []],
    'logout'            => ['ctl_logout',           $ALL],

    'owner.dashboard'     => ['ctl_owner_dashboard',     ['owner']],
    'admin.dashboard'     => ['ctl_admin_dashboard',     ['admin']],
    'pelanggan.dashboard' => ['ctl_pelanggan_dashboard', ['pelanggan']],

    // Kelola Admin (Owner saja)
    'admin.index'  => ['ctl_admin_index',  ['owner']],
    'admin.create' => ['ctl_admin_create', ['owner']],
    'admin.store'  => ['ctl_admin_store',  ['owner']],
    'admin.edit'   => ['ctl_admin_edit',   ['owner']],
    'admin.update' => ['ctl_admin_update', ['owner']],
    'admin.delete' => ['ctl_admin_delete', ['owner']],

    // Kelola Pelanggan
    'pelanggan.index'  => ['ctl_pelanggan_index',  $STAFF],
    'pelanggan.create' => ['ctl_pelanggan_create', $STAFF],
    'pelanggan.store'  => ['ctl_pelanggan_store',  $STAFF],
    'pelanggan.edit'   => ['ctl_pelanggan_edit',   $STAFF],
    'pelanggan.update' => ['ctl_pelanggan_update', $STAFF],
    'pelanggan.delete' => ['ctl_pelanggan_delete', $STAFF],

    // Kelola Paket
    'paket.index'  => ['ctl_paket_index',  $STAFF],
    'paket.create' => ['ctl_paket_create', $STAFF],
    'paket.store'  => ['ctl_paket_store',  $STAFF],
    'paket.edit'   => ['ctl_paket_edit',   $STAFF],
    'paket.update' => ['ctl_paket_update', $STAFF],
    'paket.delete' => ['ctl_paket_delete', $STAFF],

    // Kelola Tagihan
    'tagihan.index'  => ['ctl_tagihan_index',  $STAFF],
    'tagihan.create' => ['ctl_tagihan_create', $STAFF],
    'tagihan.store'  => ['ctl_tagihan_store',  $STAFF],
    'tagihan.edit'   => ['ctl_tagihan_edit',   $STAFF],
    'tagihan.update' => ['ctl_tagihan_update', $STAFF],
    'tagihan.delete' => ['ctl_tagihan_delete', $STAFF],

    // Kelola Pembayaran (pencatatan + bukti)
    'pembayaran.index'  => ['ctl_pembayaran_index',  $STAFF],
    'pembayaran.create' => ['ctl_pembayaran_create', $STAFF],
    'pembayaran.store'  => ['ctl_pembayaran_store',  $STAFF],
    'pembayaran.show'   => ['ctl_pembayaran_show',   $STAFF],
    'pembayaran.delete' => ['ctl_pembayaran_delete', $STAFF],

    // Notifikasi WhatsApp
    'notif.index' => ['ctl_notif_index', $STAFF],
    'notif.send'  => ['ctl_notif_send',  $STAFF],

    // Pengaduan (staff)
    'pengaduan.index'   => ['ctl_pengaduan_index',   $STAFF],
    'pengaduan.show'    => ['ctl_pengaduan_show',    $STAFF],
    'pengaduan.respond' => ['ctl_pengaduan_respond', $STAFF],

    // Laporan
    'laporan.index' => ['ctl_laporan_index', $STAFF],
    'laporan.cetak' => ['ctl_laporan_cetak', $STAFF],

    // Profil (semua role)
    'profil'        => ['ctl_profil',        $ALL],
    'profil.update' => ['ctl_profil_update', $ALL],

    // Self service pelanggan
    'my.tagihan'     => ['ctl_my_tagihan',     ['pelanggan']],
    'my.pembayaran'  => ['ctl_my_pembayaran',  ['pelanggan']],
    'my.pengaduan'   => ['ctl_my_pengaduan',   ['pelanggan']],
    'my.pengaduan.store' => ['ctl_my_pengaduan_store', ['pelanggan']],
];

$r = $_GET['r'] ?? '';

// Halaman default
if ($r === '') {
    $r = is_logged_in() ? dashboard_route() : 'login';
}

if (!isset($routes[$r])) {
    http_response_code(404);
    echo '404 - Halaman tidak ditemukan.';
    exit;
}

[$handler, $roles] = $routes[$r];

// Otorisasi
if (!empty($roles)) {
    require_role($roles);
}

// Proteksi CSRF untuk semua POST
check_csrf();

call_user_func($handler);
