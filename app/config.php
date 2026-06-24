<?php
// ============================================================
//  Konfigurasi aplikasi Anabie Net
// ============================================================

// --- Database (boleh dioverride lewat environment variable) ---
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'anabie_net');
define('DB_USER', getenv('DB_USER') ?: 'anabie');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'anabie123');

// --- Identitas perusahaan (dipakai pada kop surat laporan) ---
define('APP_NAME',    'Anabie Net');
define('APP_TAGLINE', 'Internet Service Provider');
define('APP_ALAMAT',  'Jl. Raya Anabie No. 1, Surabaya, Jawa Timur');
define('APP_TELP',    '0812-0000-0000');
define('APP_EMAIL',   'info@anabie.net');

// --- Path ---
define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH . '/app/views');
define('UPLOAD_PATH', BASE_PATH . '/public/uploads');

// URL dasar (folder public). Dihitung otomatis dari script.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
define('BASE_URL', rtrim($scriptDir, '/'));

date_default_timezone_set('Asia/Jakarta');
