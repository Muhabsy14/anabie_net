<?php $edit = (bool) $tagihan; ?>
<div class="page-head">
  <div><h1><?= $edit ? 'Edit' : 'Buat' ?> Tagihan</h1></div>
  <a class="btn btn-muted" href="<?= route('tagihan.index') ?>">&larr; Kembali</a>
</div>

<div class="card" style="max-width:640px">
  <form method="post" action="<?= $edit ? route('tagihan.update') : route('tagihan.store') ?>">
    <?= csrf_field() ?>
    <?php if ($edit): ?><input type="hidden" name="id" value="<?= $tagihan['id'] ?>"><?php endif; ?>

    <div class="form-group">
      <label>Pelanggan</label>
      <select class="form-control" name="pelanggan_id" id="pelanggan_id" required>
        <option value="">- Pilih Pelanggan -</option>
        <?php foreach ($pelanggan as $p): ?>
          <option value="<?= $p['id'] ?>" data-harga="<?= (int) ($p['harga'] ?? 0) ?>"
            <?= ($tagihan['pelanggan_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
            <?= e($p['kode']) ?> - <?= e($p['nama']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Periode</label>
        <input class="form-control" type="month" name="periode" required value="<?= e($tagihan['periode'] ?? date('Y-m')) ?>">
      </div>
      <div class="form-group">
        <label>Jatuh Tempo</label>
        <input class="form-control" type="date" name="jatuh_tempo" value="<?= e($tagihan['jatuh_tempo'] ?? date('Y-m-10')) ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Jumlah (Rp)</label>
        <input class="form-control" type="number" min="0" step="1000" name="jumlah" id="jumlah" required value="<?= e($tagihan['jumlah'] ?? 0) ?>">
        <p class="form-hint">Terisi otomatis dari harga paket saat memilih pelanggan.</p>
      </div>
      <div class="form-group">
        <label>Status</label>
        <select class="form-control" name="status">
          <?php foreach (['belum_lunas','lunas'] as $s): ?>
            <option value="<?= $s ?>" <?= ($tagihan['status'] ?? 'belum_lunas') === $s ? 'selected' : '' ?>><?= status_label($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Keterangan</label>
      <input class="form-control" name="keterangan" value="<?= e($tagihan['keterangan'] ?? '') ?>">
    </div>

    <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
  </form>
</div>

<script>
  document.getElementById('pelanggan_id').addEventListener('change', function () {
    var opt = this.options[this.selectedIndex];
    var harga = opt.getAttribute('data-harga');
    var jml = document.getElementById('jumlah');
    if (harga && (!jml.value || jml.value === '0')) jml.value = harga;
  });
</script>
