<div class="page-head">
  <div><h1>Kelola Pelanggan</h1><p class="sub">Data pelanggan & langganan internet.</p></div>
  <a class="btn btn-primary" href="<?= route('pelanggan.create') ?>">+ Tambah Pelanggan</a>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Kode</th><th>Nama</th><th>No. HP</th><th>Paket</th><th>Status</th><th>Login</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$pelanggan): ?>
      <tr><td colspan="7"><div class="empty">Belum ada data pelanggan.</div></td></tr>
    <?php else: foreach ($pelanggan as $p): ?>
      <tr>
        <td><?= e($p['kode']) ?></td>
        <td><?= e($p['nama']) ?><div class="text-muted" style="font-size:.8rem"><?= e($p['alamat']) ?></div></td>
        <td><?= e($p['no_hp']) ?></td>
        <td><?= e($p['paket_nama'] ?? '-') ?><?= $p['kecepatan'] ? ' ('.e($p['kecepatan']).')' : '' ?></td>
        <td><span class="badge <?= status_badge($p['status']) ?>"><?= status_label($p['status']) ?></span></td>
        <td><?= e($p['username'] ?? '-') ?></td>
        <td><div class="actions">
          <a class="btn btn-sm btn-outline" href="<?= route('pelanggan.edit', ['id' => $p['id']]) ?>">Edit</a>
          <form method="post" action="<?= route('pelanggan.delete') ?>" data-confirm="Hapus pelanggan <?= e($p['nama']) ?>? Akun login & tagihan ikut terhapus." style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </div></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
