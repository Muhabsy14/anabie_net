<?php
// ============================================================
//  Controller: Autentikasi
// ============================================================

function ctl_login(): void
{
    if (is_logged_in()) {
        redirect(dashboard_route());
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if (attempt_login($username, $password)) {
            redirect(dashboard_route());
        }
        set_flash('error', 'Username atau password salah.');
        redirect('login');
    }
    render_plain('login');
}

function ctl_logout(): void
{
    logout();
    redirect('login');
}
