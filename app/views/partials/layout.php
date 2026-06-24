<?php
$u = current_user();
$role = current_role();
$items = nav_items($role);
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?> &middot; <?= APP_NAME ?></title>
  <link rel="icon" href="<?= url('assets/img/logo.png') ?>">
  <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="sidebar-head">
      <img src="<?= url('assets/img/logo.png') ?>" alt="<?= APP_NAME ?>">
      <div class="role-tag"><?= e(role_label($role)) ?></div>
    </div>
    <nav class="nav">
      <?php
      $lastGroup = null;
      foreach ($items as [$r, $label, $ico, $group]):
          if ($group !== $lastGroup): $lastGroup = $group; ?>
            <div class="nav-sep"><?= e($group) ?></div>
      <?php endif; ?>
        <a href="<?= route($r) ?>" class="<?= $active === $r ? 'active' : '' ?>">
          <span class="ico"><?= $ico ?></span><span><?= e($label) ?></span>
        </a>
      <?php endforeach; ?>
      <div class="nav-sep">Akun</div>
      <a href="<?= route('logout') ?>"><span class="ico">🚪</span><span>Logout</span></a>
    </nav>
  </aside>

  <div class="main">
    <header class="topbar">
      <button class="menu-btn" aria-label="Menu">&#9776;</button>
      <h2><?= e($title) ?></h2>
      <div class="user-chip">
        <span class="avatar"><?= e(strtoupper(substr($u['nama'], 0, 1))) ?></span>
        <span class="no-print"><?= e($u['nama']) ?></span>
      </div>
    </header>

    <main class="content">
      <?php if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?>"><?= e($flash['msg']) ?></div>
      <?php endif; ?>
      <?= $content ?>
    </main>
  </div>
</div>
<div class="backdrop"></div>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>
