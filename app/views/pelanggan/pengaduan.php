<div class="page-head">
  <div><h1>Ajukan Pengaduan</h1><p class="sub">Sampaikan keluhan / kendala layanan Anda.</p></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title">Form Pengaduan Baru</div>
    <form method="post" action="<?= route('my.pengaduan.store') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label>Judul</label>
        <input class="form-control" name="judul" required placeholder="mis. Internet sering putus">
      </div>
      <div class="form-group">
        <label>Isi Pengaduan</label>
        <textarea class="form-control" name="isi" required placeholder="Jelaskan kendala yang Anda alami..."></textarea>
      </div>
      <button class="btn btn-primary" type="submit">Kirim Pengaduan</button>
    </form>
  </div>

  <div class="card">
    <div class="card-title">Riwayat Pengaduan</div>
    <?php if (!$pengaduan): ?>
      <div class="empty">Belum ada pengaduan.</div>
    <?php else: foreach ($pengaduan as $pg): ?>
      <div style="border:1px solid var(--border);border-radius:10px;padding:12px;margin-bottom:10px">
        <div class="flex between center wrap" style="gap:8px">
          <b><?= e($pg['judul']) ?></b>
          <span class="badge <?= status_badge($pg['status']) ?>"><?= status_label($pg['status']) ?></span>
        </div>
        <p class="text-muted" style="font-size:.85rem;margin:6px 0"><?= tgl_id($pg['created_at']) ?></p>
        <p style="white-space:pre-line"><?= e($pg['isi']) ?></p>
        <?php if (!empty($pg['tanggapan'])): ?>
          <div class="alert alert-info" style="margin-top:8px;margin-bottom:0">
            <b>Tanggapan:</b> <?= e($pg['tanggapan']) ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
