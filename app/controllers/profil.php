<?php
// ============================================================
//  Controller: Profil (semua role)
// ============================================================

function ctl_profil(): void
{
    $user = query_one("SELECT * FROM users WHERE id=?", [current_user()['id']]);
    $pelanggan = current_role() === 'pelanggan' ? current_pelanggan() : null;
    render('profil', compact('user', 'pelanggan'),
        ['title' => 'Profil Saya', 'active' => 'profil']);
}

function ctl_profil_update(): void
{
    $id = current_user()['id'];
    $nama  = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($nama === '') { set_flash('error', 'Nama wajib diisi.'); redirect('profil'); }

    if ($password !== '') {
        if (strlen($password) < 6) { set_flash('error', 'Password minimal 6 karakter.'); redirect('profil'); }
        if ($password !== $password2) { set_flash('error', 'Konfirmasi password tidak cocok.'); redirect('profil'); }
        execute("UPDATE users SET nama=?,email=?,no_hp=?,password=? WHERE id=?",
            [$nama, $email, $no_hp, password_hash($password, PASSWORD_DEFAULT), $id]);
    } else {
        execute("UPDATE users SET nama=?,email=?,no_hp=? WHERE id=?", [$nama, $email, $no_hp, $id]);
    }

    // Sinkron ke data pelanggan jika ada
    if (current_role() === 'pelanggan') {
        execute("UPDATE pelanggan SET nama=?,no_hp=?,email=? WHERE user_id=?", [$nama, $no_hp, $email, $id]);
    }

    $_SESSION['user']['nama'] = $nama;
    set_flash('success', 'Profil berhasil diperbarui.');
    redirect('profil');
}
