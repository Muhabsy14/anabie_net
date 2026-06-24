<?php
// ============================================================
//  Autentikasi & otorisasi
// ============================================================

function attempt_login(string $username, string $password): bool
{
    $user = query_one('SELECT * FROM users WHERE username = ?', [$username]);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'   => (int) $user['id'],
            'nama' => $user['nama'],
            'role' => $user['role'],
        ];
        session_regenerate_id(true);
        return true;
    }
    return false;
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function current_role(): ?string
{
    return $_SESSION['user']['role'] ?? null;
}

/** Wajib login, jika belum -> halaman login */
function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login');
    }
}

/** Wajib salah satu role */
function require_role(array $roles): void
{
    require_login();
    if (!in_array(current_role(), $roles, true)) {
        http_response_code(403);
        die('403 - Anda tidak memiliki akses ke halaman ini.');
    }
}

/** Ambil data pelanggan terkait user yang login (untuk role pelanggan) */
function current_pelanggan(): ?array
{
    $u = current_user();
    if (!$u) return null;
    return query_one('SELECT * FROM pelanggan WHERE user_id = ?', [$u['id']]);
}

/** Dashboard default sesuai role */
function dashboard_route(): string
{
    return match (current_role()) {
        'owner'     => 'owner.dashboard',
        'admin'     => 'admin.dashboard',
        'pelanggan' => 'pelanggan.dashboard',
        default     => 'login',
    };
}
