<?php $flash = get_flash(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login &middot; <?= APP_NAME ?></title>
  <link rel="icon" href="<?= url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<div class="auth-wrap">
  <div class="auth-card">
    <img class="logo" src="<?= url('assets/img/logo.png') ?>" alt="<?= APP_NAME ?>">
    <h1><?= APP_NAME ?></h1>
    <p class="sub"><?= APP_TAGLINE ?> &mdash; Sistem Manajemen Billing</p>

    <?php if ($flash): ?>
      <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?>"><?= e($flash['msg']) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= route('login') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" id="username" name="username" required autofocus value="<?= e($_POST['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input class="form-control" type="password" id="password" name="password" required>
      </div>
      <button class="btn btn-primary btn-block" type="submit">Masuk</button>
    </form>

    <p class="form-hint" style="margin-top:18px">
      Demo: <b>owner</b> / <b>admin</b> / <b>budi</b> &middot; password: <b>password123</b>
    </p>
  </div>
</div>
</body>
</html>
