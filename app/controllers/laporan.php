<?php
// ============================================================
//  Controller: Laporan (dengan logo & kop surat)
// ============================================================

function ctl_laporan_index(): void
{
    $bulan = $_GET['bulan'] ?? date('Y-m');
    $ringkasan = laporan_ringkasan($bulan);
    render('admin/laporan_index', compact('bulan', 'ringkasan'),
        ['title' => 'Laporan', 'active' => 'laporan.index']);
}

function ctl_laporan_cetak(): void
{
    $jenis = $_GET['jenis'] ?? 'pendapatan';
    $bulan = $_GET['bulan'] ?? date('Y-m');
    [$y, $m] = array_pad(explode('-', $bulan), 2, date('m'));
    $awal = "$y-$m-01";
    $akhir = date('Y-m-t', strtotime($awal));

    $judul = '';
    $rows = [];
    $kolom = [];
    $total = null;

    switch ($jenis) {
        case 'pendapatan':
            $judul = 'Laporan Pendapatan';
            $rows = query_all(
                "SELECT pb.tgl_bayar, p.kode, p.nama AS pelanggan, t.periode, pb.metode, pb.jumlah
                 FROM pembayaran pb
                 JOIN tagihan t ON t.id = pb.tagihan_id
                 JOIN pelanggan p ON p.id = t.pelanggan_id
                 WHERE pb.tgl_bayar BETWEEN ? AND ?
                 ORDER BY pb.tgl_bayar", [$awal, $akhir]
            );
            $kolom = ['Tanggal','Kode','Pelanggan','Periode','Metode','Jumlah'];
            $total = array_sum(array_column($rows, 'jumlah'));
            break;

        case 'tagihan':
            $judul = 'Laporan Tagihan';
            $rows = query_all(
                "SELECT t.periode, p.kode, p.nama AS pelanggan, t.jumlah, t.status, t.jatuh_tempo
                 FROM tagihan t JOIN pelanggan p ON p.id = t.pelanggan_id
                 WHERE t.periode = ? ORDER BY p.nama", ["$y-$m"]
            );
            $kolom = ['Periode','Kode','Pelanggan','Jumlah','Status','Jatuh Tempo'];
            $total = array_sum(array_column($rows, 'jumlah'));
            break;

        case 'pelanggan':
            $judul = 'Laporan Data Pelanggan';
            $rows = query_all(
                "SELECT p.kode, p.nama AS pelanggan, p.no_hp, pk.nama AS paket, p.status, p.tgl_pasang
                 FROM pelanggan p LEFT JOIN paket pk ON pk.id = p.paket_id ORDER BY p.nama"
            );
            $kolom = ['Kode','Pelanggan','No. HP','Paket','Status','Tgl Pasang'];
            break;

        case 'pengaduan':
            $judul = 'Laporan Pengaduan';
            $rows = query_all(
                "SELECT pg.created_at, p.kode, p.nama AS pelanggan, pg.judul, pg.status
                 FROM pengaduan pg JOIN pelanggan p ON p.id = pg.pelanggan_id
                 WHERE pg.created_at BETWEEN ? AND ?
                 ORDER BY pg.created_at", [$awal . ' 00:00:00', $akhir . ' 23:59:59']
            );
            $kolom = ['Tanggal','Kode','Pelanggan','Judul','Status'];
            break;

        default:
            set_flash('error', 'Jenis laporan tidak dikenal.');
            redirect('laporan.index');
    }

    render_plain('admin/laporan_cetak',
        compact('jenis', 'bulan', 'judul', 'rows', 'kolom', 'total', 'awal', 'akhir'));
}

function laporan_ringkasan(string $bulan): array
{
    [$y, $m] = array_pad(explode('-', $bulan), 2, date('m'));
    return [
        'pendapatan' => (float) query_one(
            "SELECT COALESCE(SUM(jumlah),0) s FROM pembayaran WHERE DATE_FORMAT(tgl_bayar,'%Y-%m')=?", ["$y-$m"]
        )['s'],
        'jml_pembayaran' => (int) query_one(
            "SELECT COUNT(*) c FROM pembayaran WHERE DATE_FORMAT(tgl_bayar,'%Y-%m')=?", ["$y-$m"]
        )['c'],
        'tagihan_lunas' => (int) query_one(
            "SELECT COUNT(*) c FROM tagihan WHERE periode=? AND status='lunas'", ["$y-$m"]
        )['c'],
        'tagihan_belum' => (int) query_one(
            "SELECT COUNT(*) c FROM tagihan WHERE periode=? AND status='belum_lunas'", ["$y-$m"]
        )['c'],
        'pengaduan' => (int) query_one(
            "SELECT COUNT(*) c FROM pengaduan WHERE DATE_FORMAT(created_at,'%Y-%m')=?", ["$y-$m"]
        )['c'],
    ];
}
