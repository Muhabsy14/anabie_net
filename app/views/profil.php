<div class="page-head">
  <div><h1>Profil Saya</h1><p class="sub">Kelola informasi akun & kata sandi.</p></div>
</div>

<div class="grid-2">
  <div class="card">
    <div class="card-title">Informasi Akun</div>
    <form method="post" action="<?= route('profil.update') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input class="form-control" name="nama" required value="<?= e($user['nama']) ?>">
      </div>
      <div class="form-group">
        <label>Username</label>
        <input class="form-control" value="<?= e($user['username']) ?>" disabled>
        <p class="form-hint">Username tidak dapat diubah.</p>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Email</label>
          <input class="form-control" type="email" name="email" value="<?= e($user['email']) ?>">
        </div>
        <div class="form-group">
          <label>No. HP</label>
          <input class="form-control" name="no_hp" value="<?= e($user['no_hp']) ?>">
        </div>
      </div>
      <hr style="border:none;border-top:1px solid var(--border);margin:14px 0">
      <p class="card-title" style="font-size:.9rem">Ubah Password</p>
      <div class="form-row">
        <div class="form-group">
          <label>Password Baru</label>
          <input class="form-control" type="password" name="password" placeholder="Kosongkan jika tidak diubah">
        </div>
        <div class="form-group">
          <label>Konfirmasi Password</label>
          <input class="form-control" type="password" name="password2">
        </div>
      </div>
      <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
    </form>
  </div>

  <div class="card">
    <div class="card-title">Ringkasan</div>
    <div style="text-align:center;padding:10px 0 16px">
      <div class="avatar" style="width:80px;height:80px;border-radius:50%;background:var(--primary);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700">
        <?= e(strtoupper(substr($user['nama'], 0, 1))) ?>
      </div>
      <h3 style="margin-top:10px;color:var(--primary-dark)"><?= e($user['nama']) ?></h3>
      <span class="badge badge-info"><?= e(role_label($user['role'])) ?></span>
    </div>
    <?php if (!empty($pelanggan)): ?>
      <dl class="detail">
        <dt>Kode Pelanggan</dt><dd><?= e($pelanggan['kode']) ?></dd>
        <dt>Status</dt><dd><span class="badge <?= status_badge($pelanggan['status']) ?>"><?= status_label($pelanggan['status']) ?></span></dd>
        <dt>Alamat</dt><dd><?= e($pelanggan['alamat']) ?></dd>
      </dl>
    <?php endif; ?>
  </div>
</div>
