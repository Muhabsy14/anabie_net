<div class="page-head">
  <div><h1>Detail Pengaduan</h1></div>
  <a class="btn btn-muted" href="<?= route('pengaduan.index') ?>">&larr; Kembali</a>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title"><?= e($pg['judul']) ?> <span class="badge <?= status_badge($pg['status']) ?>"><?= status_label($pg['status']) ?></span></div>
    <dl class="detail">
      <dt>Pelanggan</dt><dd><?= e($pg['pelanggan']) ?> (<?= e($pg['kode']) ?>)</dd>
      <dt>No. HP</dt><dd><?= e($pg['no_hp']) ?></dd>
      <dt>Alamat</dt><dd><?= e($pg['alamat']) ?></dd>
      <dt>Tanggal</dt><dd><?= tgl_id($pg['created_at']) ?></dd>
    </dl>
    <div style="margin-top:14px">
      <div class="card-title" style="font-size:.9rem">Isi Pengaduan</div>
      <p style="white-space:pre-line"><?= e($pg['isi']) ?></p>
    </div>
    <div style="margin-top:14px">
      <a class="btn btn-sm btn-wa" target="_blank" rel="noopener"
         href="<?= e(wa_link($pg['no_hp'], 'Halo ' . $pg['pelanggan'] . ', terkait pengaduan Anda "' . $pg['judul'] . '" di ' . APP_NAME . ' ...')) ?>">
        📲 Balas via WhatsApp
      </a>
    </div>
  </div>

  <div class="card">
    <div class="card-title">Tanggapan Petugas</div>
    <form method="post" action="<?= route('pengaduan.respond') ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $pg['id'] ?>">
      <div class="form-group">
        <label>Status</label>
        <select class="form-control" name="status">
          <?php foreach (['baru','diproses','selesai'] as $s): ?>
            <option value="<?= $s ?>" <?= $pg['status'] === $s ? 'selected' : '' ?>><?= status_label($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Tanggapan</label>
        <textarea class="form-control" name="tanggapan" placeholder="Tulis tanggapan untuk pelanggan..."><?= e($pg['tanggapan'] ?? '') ?></textarea>
      </div>
      <button class="btn btn-primary" type="submit">Simpan Tanggapan</button>
    </form>
  </div>
</div>
