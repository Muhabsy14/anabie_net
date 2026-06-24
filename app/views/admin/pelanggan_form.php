<?php $edit = (bool) $pelanggan; ?>
<div class="page-head">
  <div><h1><?= $edit ? 'Edit' : 'Tambah' ?> Pelanggan</h1></div>
  <a class="btn btn-muted" href="<?= route('pelanggan.index') ?>">&larr; Kembali</a>
</div>

<div class="card" style="max-width:760px">
  <form method="post" action="<?= $edit ? route('pelanggan.update') : route('pelanggan.store') ?>">
    <?= csrf_field() ?>
    <?php if ($edit): ?><input type="hidden" name="id" value="<?= $pelanggan['id'] ?>"><?php endif; ?>

    <div class="form-row">
      <div class="form-group">
        <label>Kode Pelanggan</label>
        <input class="form-control" name="kode" value="<?= e($kode_baru) ?>" <?= $edit ? 'readonly' : '' ?>>
      </div>
      <div class="form-group">
        <label>Status</label>
        <select class="form-control" name="status">
          <?php foreach (['aktif','isolir','nonaktif'] as $s): ?>
            <option value="<?= $s ?>" <?= ($pelanggan['status'] ?? 'aktif') === $s ? 'selected' : '' ?>><?= status_label($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Nama Lengkap</label>
      <input class="form-control" name="nama" required value="<?= e($pelanggan['nama'] ?? '') ?>">
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>No. HP (WhatsApp)</label>
        <input class="form-control" name="no_hp" required value="<?= e($pelanggan['no_hp'] ?? '') ?>" placeholder="08xxxxxxxxxx">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input class="form-control" type="email" name="email" value="<?= e($pelanggan['email'] ?? '') ?>">
      </div>
    </div>

    <div class="form-group">
      <label>Alamat</label>
      <textarea class="form-control" name="alamat" required><?= e($pelanggan['alamat'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Paket/Layanan</label>
        <select class="form-control" name="paket_id">
          <option value="">- Pilih Paket -</option>
          <?php foreach ($paket as $pk): ?>
            <option value="<?= $pk['id'] ?>" <?= ($pelanggan['paket_id'] ?? '') == $pk['id'] ? 'selected' : '' ?>>
              <?= e($pk['nama']) ?> - <?= e($pk['kecepatan']) ?> (<?= rupiah($pk['harga']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggal Pasang</label>
        <input class="form-control" type="date" name="tgl_pasang" value="<?= e($pelanggan['tgl_pasang'] ?? date('Y-m-d')) ?>">
      </div>
    </div>

    <?php if (!$edit): ?>
      <hr style="border:none;border-top:1px solid var(--border);margin:18px 0">
      <p class="card-title" style="margin-bottom:10px">Akun Login Pelanggan</p>
      <div class="form-row">
        <div class="form-group">
          <label>Username</label>
          <input class="form-control" name="username" required placeholder="mis. nama pelanggan">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input class="form-control" type="text" name="password" required placeholder="Minimal 6 karakter">
        </div>
      </div>
      <p class="form-hint">Akun ini dipakai pelanggan untuk login & melihat tagihan.</p>
    <?php endif; ?>

    <div style="margin-top:6px">
      <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
    </div>
  </form>
</div>
