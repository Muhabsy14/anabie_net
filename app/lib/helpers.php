<?php
// ============================================================
//  Fungsi bantu umum
// ============================================================

/** Escape output HTML */
function e($v): string
{
    return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
}

/** Bangun URL relatif terhadap folder public */
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL aksi router (?r=...) */
function route(string $r, array $params = []): string
{
    $qs = array_merge(['r' => $r], $params);
    return url('index.php') . '?' . http_build_query($qs);
}

function redirect(string $r, array $params = []): void
{
    header('Location: ' . route($r, $params));
    exit;
}

function redirect_url(string $u): void
{
    header('Location: ' . $u);
    exit;
}

/** Format rupiah */
function rupiah($n): string
{
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}

/** Format tanggal Indonesia */
function tgl_id($d): string
{
    if (empty($d)) return '-';
    $ts = strtotime($d);
    $bulan = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
}

/** Nama periode YYYY-MM -> "Juni 2026" */
function periode_id(string $p): string
{
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    [$y, $m] = array_pad(explode('-', $p), 2, '1');
    return ($bulan[(int) $m] ?? $m) . ' ' . $y;
}

/** Flash message */
function set_flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/** Normalisasi nomor HP ke format internasional (62...) */
function normalize_phone(string $hp): string
{
    $hp = preg_replace('/[^0-9]/', '', $hp);
    if (str_starts_with($hp, '0')) {
        $hp = '62' . substr($hp, 1);
    } elseif (str_starts_with($hp, '8')) {
        $hp = '62' . $hp;
    }
    return $hp;
}

/** Bangun link wa.me dengan pesan terformat */
function wa_link(string $hp, string $pesan): string
{
    return 'https://wa.me/' . normalize_phone($hp) . '?text=' . rawurlencode($pesan);
}

/** Upload file bukti/foto, kembalikan nama file atau null */
function upload_file(string $field, array $allowed = ['jpg','jpeg','png','pdf','webp'], string $prefix = 'file'): ?string
{
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Gagal mengunggah file.');
    }
    if ($f['size'] > 4 * 1024 * 1024) {
        throw new RuntimeException('Ukuran file maksimal 4 MB.');
    }
    $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) {
        throw new RuntimeException('Tipe file tidak diizinkan (' . implode(', ', $allowed) . ').');
    }
    if (!is_dir(UPLOAD_PATH)) {
        mkdir(UPLOAD_PATH, 0775, true);
    }
    $name = $prefix . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], UPLOAD_PATH . '/' . $name)) {
        throw new RuntimeException('Gagal menyimpan file.');
    }
    return $name;
}

/** Validasi token CSRF */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function check_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['_csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['_csrf'])) {
            http_response_code(419);
            die('Sesi kedaluwarsa, silakan muat ulang halaman.');
        }
    }
}

/** Badge status -> kelas css */
function status_badge(string $status): string
{
    $map = [
        'lunas' => 'badge-success', 'belum_lunas' => 'badge-danger',
        'aktif' => 'badge-success', 'isolir' => 'badge-warning', 'nonaktif' => 'badge-muted',
        'baru' => 'badge-info', 'diproses' => 'badge-warning', 'selesai' => 'badge-success',
    ];
    return $map[$status] ?? 'badge-muted';
}

function status_label(string $status): string
{
    $map = [
        'belum_lunas' => 'Belum Lunas', 'lunas' => 'Lunas',
        'aktif' => 'Aktif', 'isolir' => 'Isolir', 'nonaktif' => 'Nonaktif',
        'baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai',
    ];
    return $map[$status] ?? ucfirst(str_replace('_', ' ', $status));
}
