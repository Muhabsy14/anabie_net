<?php
// ============================================================
//  Render view dengan layout
// ============================================================

/** Render view di dalam layout dashboard */
function render(string $view, array $data = [], array $opts = []): void
{
    extract($data, EXTR_SKIP);
    $title  = $opts['title']  ?? APP_NAME;
    $active = $opts['active'] ?? '';
    $u    = current_user();
    $role = current_role();

    ob_start();
    include VIEW_PATH . '/' . $view . '.php';
    $content = ob_get_clean();

    include VIEW_PATH . '/partials/layout.php';
}

/** Render halaman polos tanpa layout (mis. login, cetak laporan) */
function render_plain(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    include VIEW_PATH . '/' . $view . '.php';
}
