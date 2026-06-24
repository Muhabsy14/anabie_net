<?php
// ============================================================
//  Controller: Kelola Admin (Owner saja)
// ============================================================

function ctl_admin_index(): void
{
    $admins = query_all("SELECT * FROM users WHERE role='admin' ORDER BY nama");
    render('admin/admin_index', compact('admins'),
        ['title' => 'Kelola Admin', 'active' => 'admin.index']);
}

function ctl_admin_create(): void
{
    $admin = null;
    render('admin/admin_form', compact('admin'),
        ['title' => 'Tambah Admin', 'active' => 'admin.index']);
}

function ctl_admin_store(): void
{
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $username === '' || strlen($password) < 6) {
        set_flash('error', 'Nama, username wajib diisi & password minimal 6 karakter.');
        redirect('admin.create');
    }
    if (query_one("SELECT id FROM users WHERE username=?", [$username])) {
        set_flash('error', 'Username sudah digunakan.');
        redirect('admin.create');
    }
    execute(
        "INSERT INTO users (nama,username,password,email,no_hp,role) VALUES (?,?,?,?,?,'admin')",
        [$nama, $username, password_hash($password, PASSWORD_DEFAULT), $email, $no_hp]
    );
    set_flash('success', 'Admin berhasil ditambahkan.');
    redirect('admin.index');
}

function ctl_admin_edit(): void
{
    $admin = query_one("SELECT * FROM users WHERE id=? AND role='admin'", [(int) ($_GET['id'] ?? 0)]);
    if (!$admin) { set_flash('error', 'Admin tidak ditemukan.'); redirect('admin.index'); }
    render('admin/admin_form', compact('admin'),
        ['title' => 'Edit Admin', 'active' => 'admin.index']);
}

function ctl_admin_update(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    $admin = query_one("SELECT * FROM users WHERE id=? AND role='admin'", [$id]);
    if (!$admin) { set_flash('error', 'Admin tidak ditemukan.'); redirect('admin.index'); }

    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $password = $_POST['password'] ?? '';

    $dup = query_one("SELECT id FROM users WHERE username=? AND id<>?", [$username, $id]);
    if ($dup) { set_flash('error', 'Username sudah digunakan.'); redirect('admin.edit', ['id' => $id]); }

    if ($password !== '') {
        execute("UPDATE users SET nama=?,username=?,email=?,no_hp=?,password=? WHERE id=?",
            [$nama, $username, $email, $no_hp, password_hash($password, PASSWORD_DEFAULT), $id]);
    } else {
        execute("UPDATE users SET nama=?,username=?,email=?,no_hp=? WHERE id=?",
            [$nama, $username, $email, $no_hp, $id]);
    }
    set_flash('success', 'Data admin diperbarui.');
    redirect('admin.index');
}

function ctl_admin_delete(): void
{
    $id = (int) ($_POST['id'] ?? 0);
    execute("DELETE FROM users WHERE id=? AND role='admin'", [$id]);
    set_flash('success', 'Admin dihapus.');
    redirect('admin.index');
}
