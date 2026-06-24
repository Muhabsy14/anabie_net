<div class="page-head">
  <div>
    <h1>Halo, <?= e($u['nama']) ?> 👋</h1>
    <p class="sub">Selamat datang di portal pelanggan <?= APP_NAME ?>.</p>
  </div>
</div>

<?php if ($pelanggan): ?>
<div class="stats">
  <div class="stat"><div class="si blue">📶</div><div><div class="sv" style="font-size:1.1rem"><?= e($paket['nama'] ?? '-') ?></div><div class="sl">Paket Anda <?= $paket ? '('.e($paket['kecepatan']).')' : '' ?></div></div></div>
  <div class="stat"><div class="si <?= $total_tunggakan > 0 ? 'red' : 'green' ?>">🧾</div><div><div class="sv" style="font-size:1.15rem"><?= rupiah($total_tunggakan) ?></div><div class="sl">Total Tagihan Belum Lunas</div></div></div>
  <div class="stat"><div class="si orange">📣</div><div><div class="sv"><?= $jml_pengaduan ?></div><div class="sl">Pengaduan Diajukan</div></div></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title">Tagihan Belum Lunas</div>
    <?php if (!$tagihan_belum): ?>
      <div class="empty">Tidak ada tagihan tertunggak. Terima kasih! 🎉</div>
    <?php else: ?>
      <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Periode</th><th>Jatuh Tempo</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
        <?php foreach ($tagihan_belum as $t): ?>
          <tr>
            <td><?= e(periode_id($t['periode'])) ?></td>
            <td><?= tgl_id($t['jatuh_tempo']) ?></td>
            <td class="text-right"><?= rupiah($t['jumlah']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table></div>
      <div style="margin-top:12px"><a class="btn btn-primary btn-sm" href="<?= route('my.tagihan') ?>">Lihat Semua Tagihan</a></div>
    <?php endif; ?>
  </div>

  <div class="card">
    <div class="card-title">Detail Langganan</div>
    <dl class="detail">
      <dt>Kode Pelanggan</dt><dd><?= e($pelanggan['kode']) ?></dd>
      <dt>Nama</dt><dd><?= e($pelanggan['nama']) ?></dd>
      <dt>No. HP</dt><dd><?= e($pelanggan['no_hp']) ?></dd>
      <dt>Alamat</dt><dd><?= e($pelanggan['alamat']) ?></dd>
      <dt>Status</dt><dd><span class="badge <?= status_badge($pelanggan['status']) ?>"><?= status_label($pelanggan['status']) ?></span></dd>
      <dt>Tgl Pasang</dt><dd><?= tgl_id($pelanggan['tgl_pasang']) ?></dd>
    </dl>
    <div style="margin-top:14px" class="flex gap wrap">
      <a class="btn btn-outline btn-sm" href="<?= route('my.pembayaran') ?>">💳 Riwayat Pembayaran</a>
      <a class="btn btn-outline btn-sm" href="<?= route('my.pengaduan') ?>">📣 Ajukan Pengaduan</a>
    </div>
  </div>
</div>
<?php else: ?>
  <div class="card"><div class="empty">Akun Anda belum terhubung ke data langganan. Silakan hubungi admin <?= APP_NAME ?>.</div></div>
<?php endif; ?>
