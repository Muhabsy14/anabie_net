<div class="page-head">
  <div><h1>Detail Pembayaran</h1></div>
  <div class="flex gap">
    <a class="btn btn-muted" href="<?= route('pembayaran.index') ?>">&larr; Kembali</a>
    <button class="btn btn-primary no-print" onclick="window.print()">🖨️ Cetak</button>
  </div>
</div>

<div class="card" style="max-width:760px">
  <div class="kop">
    <img src="<?= url('assets/img/logo.png') ?>" alt="<?= APP_NAME ?>">
    <div class="kop-txt">
      <h1><?= APP_NAME ?></h1>
      <div class="tag"><?= APP_TAGLINE ?></div>
      <p><?= APP_ALAMAT ?> &middot; <?= APP_TELP ?></p>
    </div>
  </div>
  <h2 style="text-align:center;color:var(--primary-dark);margin-bottom:16px">BUKTI PEMBAYARAN</h2>

  <dl class="detail">
    <dt>No. Pembayaran</dt><dd>#PAY-<?= str_pad((string)$bayar['id'],5,'0',STR_PAD_LEFT) ?></dd>
    <dt>Pelanggan</dt><dd><?= e($bayar['pelanggan']) ?> (<?= e($bayar['kode']) ?>)</dd>
    <dt>Alamat</dt><dd><?= e($bayar['alamat']) ?></dd>
    <dt>Periode Tagihan</dt><dd><?= e(periode_id($bayar['periode'])) ?></dd>
    <dt>Tanggal Bayar</dt><dd><?= tgl_id($bayar['tgl_bayar']) ?></dd>
    <dt>Metode</dt><dd><?= ucfirst($bayar['metode']) ?></dd>
    <dt>Jumlah Dibayar</dt><dd><b style="color:var(--primary-dark);font-size:1.1rem"><?= rupiah($bayar['jumlah']) ?></b></dd>
    <dt>Keterangan</dt><dd><?= e($bayar['keterangan'] ?: '-') ?></dd>
    <dt>Dicatat oleh</dt><dd><?= e($bayar['petugas'] ?? '-') ?></dd>
  </dl>

  <?php if ($bayar['bukti']): ?>
    <div style="margin-top:18px">
      <div class="card-title">Bukti Terlampir</div>
      <?php $ext = strtolower(pathinfo($bayar['bukti'], PATHINFO_EXTENSION)); ?>
      <?php if ($ext === 'pdf'): ?>
        <a class="btn btn-outline" href="<?= url('uploads/' . $bayar['bukti']) ?>" target="_blank">📄 Buka Bukti (PDF)</a>
      <?php else: ?>
        <a href="<?= url('uploads/' . $bayar['bukti']) ?>" target="_blank">
          <img src="<?= url('uploads/' . $bayar['bukti']) ?>" alt="Bukti" style="max-width:320px;border:1px solid var(--border);border-radius:10px">
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>
