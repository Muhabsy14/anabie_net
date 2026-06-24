<div class="page-head">
  <div><h1>Kelola Pengaduan</h1><p class="sub">Daftar pengaduan dari pelanggan.</p></div>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Tanggal</th><th>Pelanggan</th><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$pengaduan): ?>
      <tr><td colspan="5"><div class="empty">Belum ada pengaduan.</div></td></tr>
    <?php else: foreach ($pengaduan as $pg): ?>
      <tr>
        <td><?= tgl_id($pg['created_at']) ?></td>
        <td><?= e($pg['pelanggan']) ?><div class="text-muted" style="font-size:.8rem"><?= e($pg['kode']) ?></div></td>
        <td><?= e($pg['judul']) ?></td>
        <td><span class="badge <?= status_badge($pg['status']) ?>"><?= status_label($pg['status']) ?></span></td>
        <td><a class="btn btn-sm btn-primary" href="<?= route('pengaduan.show', ['id' => $pg['id']]) ?>">Tanggapi</a></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
