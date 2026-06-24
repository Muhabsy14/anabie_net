<?php $edit = (bool) $paket; ?>
<div class="page-head">
  <div><h1><?= $edit ? 'Edit' : 'Tambah' ?> Paket</h1></div>
  <a class="btn btn-muted" href="<?= route('paket.index') ?>">&larr; Kembali</a>
</div>

<div class="card" style="max-width:640px">
  <form method="post" action="<?= $edit ? route('paket.update') : route('paket.store') ?>">
    <?= csrf_field() ?>
    <?php if ($edit): ?><input type="hidden" name="id" value="<?= $paket['id'] ?>"><?php endif; ?>
    <div class="form-group">
      <label>Nama Paket</label>
      <input class="form-control" name="nama" required value="<?= e($paket['nama'] ?? '') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Kecepatan</label>
        <input class="form-control" name="kecepatan" required value="<?= e($paket['kecepatan'] ?? '') ?>" placeholder="mis. 20 Mbps">
      </div>
      <div class="form-group">
        <label>Harga / Bulan (Rp)</label>
        <input class="form-control" type="number" min="0" step="1000" name="harga" required value="<?= e($paket['harga'] ?? 0) ?>">
      </div>
    </div>
    <div class="form-group">
      <label>Deskripsi</label>
      <textarea class="form-control" name="deskripsi"><?= e($paket['deskripsi'] ?? '') ?></textarea>
    </div>
    <div class="form-group">
      <label>Status</label>
      <select class="form-control" name="status">
        <?php foreach (['aktif','nonaktif'] as $s): ?>
          <option value="<?= $s ?>" <?= ($paket['status'] ?? 'aktif') === $s ? 'selected' : '' ?>><?= status_label($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
  </form>
</div>
