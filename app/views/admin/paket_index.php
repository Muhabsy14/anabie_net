<div class="page-head">
  <div><h1>Kelola Paket/Layanan</h1><p class="sub">Daftar paket internet yang ditawarkan.</p></div>
  <a class="btn btn-primary" href="<?= route('paket.create') ?>">+ Tambah Paket</a>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Nama</th><th>Kecepatan</th><th class="text-right">Harga/Bulan</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$paket): ?>
      <tr><td colspan="5"><div class="empty">Belum ada paket.</div></td></tr>
    <?php else: foreach ($paket as $p): ?>
      <tr>
        <td><?= e($p['nama']) ?><div class="text-muted" style="font-size:.8rem"><?= e($p['deskripsi']) ?></div></td>
        <td><?= e($p['kecepatan']) ?></td>
        <td class="text-right"><?= rupiah($p['harga']) ?></td>
        <td><span class="badge <?= status_badge($p['status']) ?>"><?= status_label($p['status']) ?></span></td>
        <td><div class="actions">
          <a class="btn btn-sm btn-outline" href="<?= route('paket.edit', ['id' => $p['id']]) ?>">Edit</a>
          <form method="post" action="<?= route('paket.delete') ?>" data-confirm="Hapus paket <?= e($p['nama']) ?>?" style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </div></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
