<?php
// ============================================================
//  Menu navigasi per role
//  Format: [route, label, icon, group]
// ============================================================

function nav_items(string $role): array
{
    $owner = [
        ['owner.dashboard',   'Dashboard',                 '🏠', 'Menu'],
        ['admin.index',       'Kelola Admin',              '🛡️', 'Manajemen'],
        ['pelanggan.index',   'Kelola Pelanggan',          '👥', 'Manajemen'],
        ['paket.index',       'Kelola Paket/Layanan',      '📶', 'Manajemen'],
        ['tagihan.index',     'Kelola Tagihan',            '🧾', 'Transaksi'],
        ['pembayaran.index',  'Kelola Pembayaran',         '💳', 'Transaksi'],
        ['notif.index',       'Notifikasi WhatsApp',       '📲', 'Transaksi'],
        ['pengaduan.index',   'Kelola Pengaduan',          '📣', 'Layanan'],
        ['laporan.index',     'Laporan',                   '📊', 'Layanan'],
        ['profil',            'Profil',                    '👤', 'Akun'],
    ];

    $admin = [
        ['admin.dashboard',   'Dashboard',                 '🏠', 'Menu'],
        ['pelanggan.index',   'Kelola Pelanggan',          '👥', 'Manajemen'],
        ['paket.index',       'Kelola Paket/Layanan',      '📶', 'Manajemen'],
        ['tagihan.index',     'Kelola Tagihan',            '🧾', 'Transaksi'],
        ['pembayaran.index',  'Kelola Pembayaran',         '💳', 'Transaksi'],
        ['notif.index',       'Notifikasi WhatsApp',       '📲', 'Transaksi'],
        ['pengaduan.index',   'Kelola Pengaduan',          '📣', 'Layanan'],
        ['laporan.index',     'Laporan Operasional',       '📊', 'Layanan'],
        ['profil',            'Profil',                    '👤', 'Akun'],
    ];

    $pelanggan = [
        ['pelanggan.dashboard','Dashboard',                '🏠', 'Menu'],
        ['my.tagihan',        'Tagihan Saya',              '🧾', 'Layanan'],
        ['my.pembayaran',     'Riwayat Pembayaran',        '💳', 'Layanan'],
        ['my.pengaduan',      'Ajukan Pengaduan',          '📣', 'Layanan'],
        ['profil',            'Profil',                    '👤', 'Akun'],
    ];

    return match ($role) {
        'owner'     => $owner,
        'admin'     => $admin,
        'pelanggan' => $pelanggan,
        default     => [],
    };
}

function role_label(string $role): string
{
    return match ($role) {
        'owner' => 'Owner', 'admin' => 'Admin', 'pelanggan' => 'Pelanggan', default => $role,
    };
}
