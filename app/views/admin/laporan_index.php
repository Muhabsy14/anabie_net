<div class="page-head">
  <div><h1>Laporan</h1><p class="sub">Cetak laporan operasional dengan kop surat resmi.</p></div>
</div>

<div class="card">
  <form method="get" action="<?= url('index.php') ?>" class="flex gap center wrap">
    <input type="hidden" name="r" value="laporan.index">
    <div class="form-group" style="margin-bottom:0">
      <label>Periode Bulan</label>
      <input class="form-control" type="month" name="bulan" value="<?= e($bulan) ?>">
    </div>
    <div style="align-self:flex-end"><button class="btn btn-primary" type="submit">Tampilkan</button></div>
  </form>
</div>

<div class="stats">
  <div class="stat"><div class="si green">💰</div><div><div class="sv" style="font-size:1.1rem"><?= rupiah($ringkasan['pendapatan']) ?></div><div class="sl">Total Pendapatan</div></div></div>
  <div class="stat"><div class="si blue">💳</div><div><div class="sv"><?= $ringkasan['jml_pembayaran'] ?></div><div class="sl">Transaksi Pembayaran</div></div></div>
  <div class="stat"><div class="si green">✅</div><div><div class="sv"><?= $ringkasan['tagihan_lunas'] ?></div><div class="sl">Tagihan Lunas</div></div></div>
  <div class="stat"><div class="si orange">🧾</div><div><div class="sv"><?= $ringkasan['tagihan_belum'] ?></div><div class="sl">Tagihan Belum Lunas</div></div></div>
  <div class="stat"><div class="si red">📣</div><div><div class="sv"><?= $ringkasan['pengaduan'] ?></div><div class="sl">Pengaduan</div></div></div>
</div>

<div class="card">
  <div class="card-title">Cetak Laporan (<?= e(periode_id($bulan)) ?>)</div>
  <div class="flex gap wrap">
    <a class="btn btn-primary" target="_blank" href="<?= route('laporan.cetak', ['jenis'=>'pendapatan','bulan'=>$bulan]) ?>">💰 Laporan Pendapatan</a>
    <a class="btn btn-outline" target="_blank" href="<?= route('laporan.cetak', ['jenis'=>'tagihan','bulan'=>$bulan]) ?>">🧾 Laporan Tagihan</a>
    <a class="btn btn-outline" target="_blank" href="<?= route('laporan.cetak', ['jenis'=>'pelanggan','bulan'=>$bulan]) ?>">👥 Laporan Pelanggan</a>
    <a class="btn btn-outline" target="_blank" href="<?= route('laporan.cetak', ['jenis'=>'pengaduan','bulan'=>$bulan]) ?>">📣 Laporan Pengaduan</a>
  </div>
  <p class="form-hint" style="margin-top:12px">Laporan dibuka di tab baru lengkap dengan logo & kop surat, siap dicetak / disimpan sebagai PDF.</p>
</div>
