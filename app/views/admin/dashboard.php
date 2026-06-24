<div class="page-head">
  <div>
    <h1>Selamat datang, <?= e($u['nama']) ?> 👋</h1>
    <p class="sub">Ringkasan operasional <?= APP_NAME ?>.</p>
  </div>
</div>

<div class="stats">
  <div class="stat"><div class="si blue">👥</div><div><div class="sv"><?= $stats['total_pelanggan'] ?></div><div class="sl">Total Pelanggan</div></div></div>
  <div class="stat"><div class="si green">✅</div><div><div class="sv"><?= $stats['pelanggan_aktif'] ?></div><div class="sl">Pelanggan Aktif</div></div></div>
  <div class="stat"><div class="si">📶</div><div><div class="sv"><?= $stats['total_paket'] ?></div><div class="sl">Paket Layanan</div></div></div>
  <div class="stat"><div class="si orange">🧾</div><div><div class="sv"><?= $stats['tagihan_belum'] ?></div><div class="sl">Tagihan Belum Lunas</div></div></div>
  <div class="stat"><div class="si red">📣</div><div><div class="sv"><?= $stats['pengaduan_baru'] ?></div><div class="sl">Pengaduan Aktif</div></div></div>
  <div class="stat"><div class="si green">💰</div><div><div class="sv" style="font-size:1.15rem"><?= rupiah($stats['pendapatan_bulan']) ?></div><div class="sl">Pendapatan Bulan Ini</div></div></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title">Akses Cepat</div>
    <div class="flex gap wrap">
      <a class="btn btn-primary" href="<?= route('pelanggan.create') ?>">👥 Tambah Pelanggan</a>
      <a class="btn btn-outline" href="<?= route('tagihan.create') ?>">🧾 Buat Tagihan</a>
      <a class="btn btn-outline" href="<?= route('pembayaran.create') ?>">💳 Catat Pembayaran</a>
      <a class="btn btn-outline" href="<?= route('notif.index') ?>">📲 Kirim Notifikasi</a>
      <a class="btn btn-outline" href="<?= route('laporan.index') ?>">📊 Laporan</a>
    </div>
  </div>
  <div class="card">
    <div class="card-title">Pengaduan Terbaru</div>
    <?php if (!$pengaduan_baru): ?>
      <div class="empty">Tidak ada pengaduan aktif. 🎉</div>
    <?php else: ?>
      <div class="table-wrap"><table class="tbl">
        <thead><tr><th>Pelanggan</th><th>Judul</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($pengaduan_baru as $pg): ?>
          <tr>
            <td><?= e($pg['pelanggan']) ?></td>
            <td><a href="<?= route('pengaduan.show', ['id' => $pg['id']]) ?>"><?= e($pg['judul']) ?></a></td>
            <td><span class="badge <?= status_badge($pg['status']) ?>"><?= status_label($pg['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table></div>
    <?php endif; ?>
  </div>
</div>
