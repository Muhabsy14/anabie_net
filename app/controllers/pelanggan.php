<?php
// ============================================================
//  Controller: Kelola Pelanggan
// ============================================================

function ctl_pelanggan_index(): void
{
    $pelanggan = query_all(
        "SELECT p.*, pk.nama AS paket_nama, pk.kecepatan, u.username
         FROM pelanggan p
         LEFT JOIN paket pk ON pk.id = p.paket_id
         LEFT JOIN users u ON u.id = p.user_id
         ORDER BY p.created_at DESC"
    );
    render('admin/pelanggan_index', compact('pelanggan'),
        ['title' => 'Kelola Pelanggan', 'active' => 'pelanggan.index']);
}

function ctl_pelanggan_create(): void
{
    $pelanggan = null;
    $paket = query_all("SELECT * FROM paket WHERE status='aktif' ORDER BY harga");
    $kode_baru = next_kode_pelanggan();
    render('admin/pelanggan_form', compact('pelanggan', 'paket', 'kode_baru'),
        ['title' => 'Tambah Pelanggan', 'active' => 'pelanggan.index']);
}

function ctl_pelanggan_store(): void
{
    $data = pelanggan_input();
    if ($data['nama'] === '' || $data['no_hp'] === '' || $data['alamat'] === '') {
        set_flash('error', 'Nama, No. HP, dan alamat wajib diisi.');
        redirect('pelanggan.create');
    }
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || strlen($password) < 6) {
        set_flash('error', 'Username & password akun login wajib diisi (password min. 6 karakter).');
        redirect('pelanggan.create');
    }
    if (query_one("SELECT id FROM users WHERE username=?", [$username])) {
        set_flash('error', 'Username sudah digunakan.');
        redirect('pelanggan.create');
    }

    db()->beginTransaction();
    try {
        execute("INSERT INTO users (nama,username,password,email,no_hp,role) VALUES (?,?,?,?,?,'pelanggan')",
            [$data['nama'], $username, password_hash($password, PASSWORD_DEFAULT), $data['email'], $data['no_hp']]);
        $uid = last_id();
        execute("INSERT INTO pelanggan (user_id,kode,nama,no_hp,alamat,paket_id,status,tgl_pasang)
                 VALUES (?,?,?,?,?,?,?,?)",
            [$uid, $data['kode'], $data['nama'], $data['no_hp'], $data['alamat'],
             $data['paket_id'], $data['status'], $data['tgl_pasang']]);
        db()->commit();
    } catch (Throwable $e) {
        db()->rollBack();
        set_flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        redirect('pelanggan.create');
    }
    set_flash('success', 'Pelanggan berhasil ditambahkan.');
    redirect('pelanggan.index');
}

function ctl_pelanggan_edit(): void
{
    $pelanggan = query_one("SELECT * FROM pelanggan WHERE id=?", [(int) ($_GET['id'] ?? 0)]);
    if (!$pelanggan) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('pelanggan.index'); }
    $paket = query_all("SELECT * FROM paket ORDER BY harga");
    $kode_baru = $pelanggan['kode'];
    render('admin/pelanggan_form', compact('pelanggan', 'paket', 'kode_baru'),
        ['title' => 'Edit Pelanggan', 'active' => 'pelanggan.index']);
}

function ctl_pelanggan_update(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $p = query_one("SELECT * FROM pelanggan WHERE id=?", [$id]);
    if (!$p) { set_flash('error', 'Pelanggan tidak ditemukan.'); redirect('pelanggan.index'); }
    $data = pelanggan_input();
    execute("UPDATE pelanggan SET nama=?,no_hp=?,alamat=?,email=?,paket_id=?,status=?,tgl_pasang=? WHERE id=?",
        [$data['nama'], $data['no_hp'], $data['alamat'], $data['email'],
         $data['paket_id'], $data['status'], $data['tgl_pasang'], $id]);
    // Sinkron data dasar ke akun user terkait
    if ($p['user_id']) {
        execute("UPDATE users SET nama=?,no_hp=?,email=? WHERE id=?",
            [$data['nama'], $data['no_hp'], $data['email'], $p['user_id']]);
    }
    set_flash('success', 'Data pelanggan diperbarui.');
    redirect('pelanggan.index');
}

function ctl_pelanggan_delete(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $p = query_one("SELECT * FROM pelanggan WHERE id=?", [$id]);
    if ($p) {
        execute("DELETE FROM pelanggan WHERE id=?", [$id]);
        if ($p['user_id']) execute("DELETE FROM users WHERE id=? AND role='pelanggan'", [$p['user_id']]);
        set_flash('success', 'Pelanggan dihapus.');
    }
    redirect('pelanggan.index');
}

// --- helper ---
function pelanggan_input(): array
{
    return [
        'kode'       => trim($_POST['kode'] ?? '') ?: next_kode_pelanggan(),
        'nama'       => trim($_POST['nama'] ?? ''),
        'no_hp'      => trim($_POST['no_hp'] ?? ''),
        'email'      => trim($_POST['email'] ?? ''),
        'alamat'     => trim($_POST['alamat'] ?? ''),
        'paket_id'   => ($_POST['paket_id'] ?? '') !== '' ? (int) $_POST['paket_id'] : null,
        'status'     => in_array($_POST['status'] ?? '', ['aktif','isolir','nonaktif'], true) ? $_POST['status'] : 'aktif',
        'tgl_pasang' => $_POST['tgl_pasang'] ?? date('Y-m-d'),
    ];
}

function next_kode_pelanggan(): string
{
    $last = query_one("SELECT kode FROM pelanggan ORDER BY id DESC LIMIT 1");
    $n = 1;
    if ($last && preg_match('/(\d+)$/', $last['kode'], $m)) $n = (int) $m[1] + 1;
    return 'PLG-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT);
}
