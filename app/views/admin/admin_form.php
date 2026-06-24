<?php $edit = (bool) $admin; ?>
<div class="page-head">
  <div><h1><?= $edit ? 'Edit' : 'Tambah' ?> Admin</h1></div>
  <a class="btn btn-muted" href="<?= route('admin.index') ?>">&larr; Kembali</a>
</div>

<div class="card" style="max-width:640px">
  <form method="post" action="<?= $edit ? route('admin.update') : route('admin.store') ?>">
    <?= csrf_field() ?>
    <?php if ($edit): ?><input type="hidden" name="id" value="<?= $admin['id'] ?>"><?php endif; ?>
    <div class="form-group">
      <label>Nama Lengkap</label>
      <input class="form-control" name="nama" required value="<?= e($admin['nama'] ?? '') ?>">
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Username</label>
        <input class="form-control" name="username" required value="<?= e($admin['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>No. HP</label>
        <input class="form-control" name="no_hp" value="<?= e($admin['no_hp'] ?? '') ?>" placeholder="08xxx">
      </div>
    </div>
    <div class="form-group">
      <label>Email</label>
      <input class="form-control" type="email" name="email" value="<?= e($admin['email'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Password <?= $edit ? '(kosongkan jika tidak diubah)' : '' ?></label>
      <input class="form-control" type="password" name="password" <?= $edit ? '' : 'required' ?> placeholder="Minimal 6 karakter">
    </div>
    <button class="btn btn-primary" type="submit"><?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
  </form>
</div>
