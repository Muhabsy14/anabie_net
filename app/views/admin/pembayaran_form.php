<div class="page-head">
  <div><h1>Catat Pembayaran</h1><p class="sub">Form pencatatan pembayaran + unggah bukti.</p></div>
  <a class="btn btn-muted" href="<?= route('pembayaran.index') ?>">&larr; Kembali</a>
</div>

<div class="card" style="max-width:680px">
  <?php if (!$tagihan): ?>
    <div class="empty">Tidak ada tagihan yang belum lunas. <a href="<?= route('tagihan.create') ?>">Buat tagihan dulu</a>.</div>
  <?php else: ?>
  <form method="post" action="<?= route('pembayaran.store') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-group">
      <label>Tagihan</label>
      <select class="form-control" name="tagihan_id" id="tagihan_id" required>
        <option value="">- Pilih Tagihan -</option>
        <?php foreach ($tagihan as $t): ?>
          <option value="<?= $t['id'] ?>" data-jumlah="<?= (int) $t['jumlah'] ?>"
            <?= $selected === (int) $t['id'] ? 'selected' : '' ?>>
            <?= e($t['kode']) ?> - <?= e($t['pelanggan']) ?> | <?= e(periode_id($t['periode'])) ?> | <?= rupiah($t['jumlah']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Tanggal Bayar</label>
        <input class="form-control" type="date" name="tgl_bayar" value="<?= date('Y-m-d') ?>" required>
      </div>
      <div class="form-group">
        <label>Jumlah Dibayar (Rp)</label>
        <input class="form-control" type="number" min="0" step="1000" name="jumlah" id="jumlah" required>
      </div>
    </div>

    <div class="form-group">
      <label>Metode Pembayaran</label>
      <select class="form-control" name="metode">
        <option value="tunai">Tunai</option>
        <option value="transfer">Transfer Bank</option>
        <option value="qris">QRIS</option>
        <option value="lainnya">Lainnya</option>
      </select>
    </div>

    <div class="form-group">
      <label>Bukti Pembayaran</label>
      <input class="form-control" type="file" name="bukti" accept=".jpg,.jpeg,.png,.webp,.pdf">
      <p class="form-hint">Format: JPG, PNG, WEBP, atau PDF. Maks 4 MB. (opsional namun disarankan)</p>
    </div>

    <div class="form-group">
      <label>Keterangan</label>
      <input class="form-control" name="keterangan" placeholder="mis. Lunas via transfer BCA">
    </div>

    <button class="btn btn-success" type="submit">💾 Simpan Pembayaran</button>
  </form>
  <?php endif; ?>
</div>

<script>
  var sel = document.getElementById('tagihan_id');
  if (sel) {
    function fillJumlah() {
      var opt = sel.options[sel.selectedIndex];
      var j = opt && opt.getAttribute('data-jumlah');
      if (j) document.getElementById('jumlah').value = j;
    }
    sel.addEventListener('change', fillJumlah);
    if (sel.value) fillJumlah();
  }
</script>
