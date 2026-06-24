<div class="page-head">
  <div><h1>Kelola Admin</h1><p class="sub">Akun admin yang dapat mengelola operasional.</p></div>
  <a class="btn btn-primary" href="<?= route('admin.create') ?>">+ Tambah Admin</a>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>#</th><th>Nama</th><th>Username</th><th>Email</th><th>No. HP</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$admins): ?>
      <tr><td colspan="6"><div class="empty">Belum ada data admin.</div></td></tr>
    <?php else: foreach ($admins as $i => $a): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= e($a['nama']) ?></td>
        <td><?= e($a['username']) ?></td>
        <td><?= e($a['email'] ?: '-') ?></td>
        <td><?= e($a['no_hp'] ?: '-') ?></td>
        <td><div class="actions">
          <a class="btn btn-sm btn-outline" href="<?= route('admin.edit', ['id' => $a['id']]) ?>">Edit</a>
          <form method="post" action="<?= route('admin.delete') ?>" data-confirm="Hapus admin <?= e($a['nama']) ?>?" style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $a['id'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </div></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
