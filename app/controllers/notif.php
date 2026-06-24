<?php
// ============================================================
//  Controller: Kirim Notifikasi Tagihan via WhatsApp
// ============================================================

function ctl_notif_index(): void
{
    // Pelanggan dengan tagihan belum lunas
    $rows = query_all(
        "SELECT t.id AS tagihan_id, t.periode, t.jumlah, t.jatuh_tempo,
                p.nama, p.no_hp, p.kode
         FROM tagihan t
         JOIN pelanggan p ON p.id = t.pelanggan_id
         WHERE t.status='belum_lunas'
         ORDER BY t.jatuh_tempo, p.nama"
    );
    // Siapkan pesan default + link wa
    foreach ($rows as &$r) {
        $r['pesan'] = pesan_tagihan($r);
        $r['wa']    = wa_link($r['no_hp'], $r['pesan']);
    }
    unset($r);
    render('admin/notif_index', ['rows' => $rows],
        ['title' => 'Notifikasi WhatsApp', 'active' => 'notif.index']);
}

/** Endpoint kirim massal: tampilkan halaman berisi tombol buka WA per pelanggan */
function ctl_notif_send(): void
{
    redirect('notif.index');
}

function pesan_tagihan(array $r): string
{
    $lines = [
        "Yth. Bapak/Ibu *{$r['nama']}*,",
        "",
        "Kami informasikan tagihan internet *" . APP_NAME . "* Anda:",
        "Kode Pelanggan: {$r['kode']}",
        "Periode: " . periode_id($r['periode']),
        "Jumlah: " . rupiah($r['jumlah']),
        "Jatuh Tempo: " . tgl_id($r['jatuh_tempo']),
        "",
        "Mohon segera melakukan pembayaran sebelum jatuh tempo. Terima kasih. 🙏",
        "- " . APP_NAME . " (" . APP_TELP . ")",
    ];
    return implode("\n", $lines);
}
