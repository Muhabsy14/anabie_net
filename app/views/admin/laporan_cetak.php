<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($judul) ?> &middot; <?= APP_NAME ?></title>
  <link rel="icon" href="<?= url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
  <style>
    body{background:#f1f5fb;padding:24px}
    .sheet{max-width:900px;margin:0 auto;background:#fff;padding:32px;border-radius:10px;box-shadow:var(--shadow)}
    @media print{body{background:#fff;padding:0}.sheet{box-shadow:none;max-width:none;padding:0}}
  </style>
</head>
<body>
<div class="toolbar no-print" style="max-width:900px;margin:0 auto 14px;display:flex;gap:10px;justify-content:flex-end">
  <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
  <a class="btn btn-muted" href="<?= route('laporan.index', ['bulan' => $bulan]) ?>">Tutup</a>
</div>

<div class="sheet">
  <!-- KOP SURAT -->
  <div class="kop">
    <img src="<?= url('assets/img/logo.png') ?>" alt="<?= APP_NAME ?>">
    <div class="kop-txt">
      <h1><?= APP_NAME ?></h1>
      <div class="tag"><?= APP_TAGLINE ?></div>
      <p><?= APP_ALAMAT ?></p>
      <p>Telp: <?= APP_TELP ?> &middot; Email: <?= APP_EMAIL ?></p>
    </div>
  </div>

  <h2 style="text-align:center;color:var(--primary-dark);text-transform:uppercase;margin-bottom:2px"><?= e($judul) ?></h2>
  <p style="text-align:center;color:var(--muted);margin-bottom:18px">
    Periode: <?= e(periode_id($bulan)) ?>
  </p>

  <div class="table-wrap"><table class="tbl" style="min-width:auto">
    <thead><tr>
      <th>No</th>
      <?php foreach ($kolom as $k): ?><th class="<?= $k==='Jumlah' ? 'text-right' : '' ?>"><?= e($k) ?></th><?php endforeach; ?>
    </tr></thead>
    <tbody>
    <?php if (!$rows): ?>
      <tr><td colspan="<?= count($kolom)+1 ?>"><div class="empty">Tidak ada data pada periode ini.</div></td></tr>
    <?php else: foreach ($rows as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <?php if ($jenis === 'pendapatan'): ?>
          <td><?= tgl_id($row['tgl_bayar']) ?></td>
          <td><?= e($row['kode']) ?></td>
          <td><?= e($row['pelanggan']) ?></td>
          <td><?= e(periode_id($row['periode'])) ?></td>
          <td><?= ucfirst($row['metode']) ?></td>
          <td class="text-right"><?= rupiah($row['jumlah']) ?></td>
        <?php elseif ($jenis === 'tagihan'): ?>
          <td><?= e(periode_id($row['periode'])) ?></td>
          <td><?= e($row['kode']) ?></td>
          <td><?= e($row['pelanggan']) ?></td>
          <td><?= rupiah($row['jumlah']) ?></td>
          <td><?= status_label($row['status']) ?></td>
          <td><?= tgl_id($row['jatuh_tempo']) ?></td>
        <?php elseif ($jenis === 'pelanggan'): ?>
          <td><?= e($row['kode']) ?></td>
          <td><?= e($row['pelanggan']) ?></td>
          <td><?= e($row['no_hp']) ?></td>
          <td><?= e($row['paket'] ?? '-') ?></td>
          <td><?= status_label($row['status']) ?></td>
          <td><?= tgl_id($row['tgl_pasang']) ?></td>
        <?php elseif ($jenis === 'pengaduan'): ?>
          <td><?= tgl_id($row['created_at']) ?></td>
          <td><?= e($row['kode']) ?></td>
          <td><?= e($row['pelanggan']) ?></td>
          <td><?= e($row['judul']) ?></td>
          <td><?= status_label($row['status']) ?></td>
        <?php endif; ?>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
    <?php if ($total !== null): ?>
      <tfoot><tr>
        <th colspan="<?= count($kolom) ?>" class="text-right">TOTAL</th>
        <th class="text-right"><?= rupiah($total) ?></th>
      </tr></tfoot>
    <?php endif; ?>
  </table></div>

  <!-- Tanda tangan -->
  <div style="margin-top:40px;display:flex;justify-content:flex-end">
    <div style="text-align:center;min-width:240px">
      <p>Surabaya, <?= tgl_id(date('Y-m-d')) ?></p>
      <p>Hormat kami,</p>
      <div style="height:64px"></div>
      <p style="font-weight:700;border-top:1px solid #333;padding-top:4px"><?= APP_NAME ?></p>
    </div>
  </div>
</div>
</body>
</html>
