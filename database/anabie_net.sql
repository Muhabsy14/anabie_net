-- ============================================================
--  Anabie Net - Sistem Manajemen Billing WiFi/ISP
--  Skema database + data awal (seed)
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
--  Tabel users (Owner, Admin, Pelanggan)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `pengaduan`;
DROP TABLE IF EXISTS `pembayaran`;
DROP TABLE IF EXISTS `tagihan`;
DROP TABLE IF EXISTS `pelanggan`;
DROP TABLE IF EXISTS `paket`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(120) NOT NULL,
  `username`   VARCHAR(60) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `email`      VARCHAR(120) DEFAULT NULL,
  `no_hp`      VARCHAR(25)  DEFAULT NULL,
  `role`       ENUM('owner','admin','pelanggan') NOT NULL DEFAULT 'admin',
  `foto`       VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabel paket / layanan internet
-- ------------------------------------------------------------
CREATE TABLE `paket` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(120) NOT NULL,
  `kecepatan`  VARCHAR(60) NOT NULL,
  `harga`      DECIMAL(12,2) NOT NULL DEFAULT 0,
  `deskripsi`  TEXT DEFAULT NULL,
  `status`     ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabel pelanggan (data langganan, terhubung ke user pelanggan)
-- ------------------------------------------------------------
CREATE TABLE `pelanggan` (
  `id`          INT AUTO_INCREMENT PRIMARY KEY,
  `user_id`     INT DEFAULT NULL,
  `kode`        VARCHAR(30) NOT NULL UNIQUE,
  `nama`        VARCHAR(120) NOT NULL,
  `no_hp`       VARCHAR(25) NOT NULL,
  `email`       VARCHAR(120) DEFAULT NULL,
  `alamat`      TEXT NOT NULL,
  `paket_id`    INT DEFAULT NULL,
  `status`      ENUM('aktif','isolir','nonaktif') NOT NULL DEFAULT 'aktif',
  `tgl_pasang`  DATE DEFAULT NULL,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pelanggan_user`  FOREIGN KEY (`user_id`)  REFERENCES `users`(`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_pelanggan_paket` FOREIGN KEY (`paket_id`) REFERENCES `paket`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabel tagihan
-- ------------------------------------------------------------
CREATE TABLE `tagihan` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `pelanggan_id` INT NOT NULL,
  `periode`      VARCHAR(7) NOT NULL,            -- format YYYY-MM
  `jumlah`       DECIMAL(12,2) NOT NULL DEFAULT 0,
  `jatuh_tempo`  DATE DEFAULT NULL,
  `status`       ENUM('belum_lunas','lunas') NOT NULL DEFAULT 'belum_lunas',
  `keterangan`   VARCHAR(255) DEFAULT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_tagihan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabel pembayaran (pencatatan + bukti pembayaran)
-- ------------------------------------------------------------
CREATE TABLE `pembayaran` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `tagihan_id`   INT NOT NULL,
  `tgl_bayar`    DATE NOT NULL,
  `jumlah`       DECIMAL(12,2) NOT NULL DEFAULT 0,
  `metode`       ENUM('tunai','transfer','qris','lainnya') NOT NULL DEFAULT 'tunai',
  `bukti`        VARCHAR(255) DEFAULT NULL,      -- file bukti pembayaran
  `dicatat_oleh` INT DEFAULT NULL,
  `keterangan`   VARCHAR(255) DEFAULT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_bayar_tagihan` FOREIGN KEY (`tagihan_id`)   REFERENCES `tagihan`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bayar_user`    FOREIGN KEY (`dicatat_oleh`) REFERENCES `users`(`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Tabel pengaduan
-- ------------------------------------------------------------
CREATE TABLE `pengaduan` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `pelanggan_id` INT NOT NULL,
  `judul`        VARCHAR(150) NOT NULL,
  `isi`          TEXT NOT NULL,
  `status`       ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  `tanggapan`    TEXT DEFAULT NULL,
  `created_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_pengaduan_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
--  DATA AWAL (SEED)
--  Password default semua akun: "password123"
--  (hash bcrypt di bawah dihasilkan dengan password_hash PHP)
-- ============================================================
-- hash untuk "password123"
SET @pw := '$2y$10$.86xMEB5KG41Qj77eOJ2fOHJ4ENF915YbNhyJP3bSDYBDaDgrUZIq';

INSERT INTO `users` (`nama`,`username`,`password`,`email`,`no_hp`,`role`) VALUES
  ('Owner Anabie',  'owner', @pw, 'owner@anabie.net', '6281200000001', 'owner'),
  ('Admin Anabie',  'admin', @pw, 'admin@anabie.net', '6281200000002', 'admin'),
  ('Budi Santoso',  'budi',  @pw, 'budi@mail.com',    '6281200000003', 'pelanggan'),
  ('Siti Aminah',   'siti',  @pw, 'siti@mail.com',    '6281200000004', 'pelanggan');

INSERT INTO `paket` (`nama`,`kecepatan`,`harga`,`deskripsi`,`status`) VALUES
  ('Paket Home 10', '10 Mbps', 150000, 'Cocok untuk rumah tangga, browsing & streaming.', 'aktif'),
  ('Paket Home 20', '20 Mbps', 200000, 'Streaming HD lancar untuk keluarga.', 'aktif'),
  ('Paket Pro 50',  '50 Mbps', 350000, 'Untuk WFH, gaming & usaha kecil.', 'aktif'),
  ('Paket Bisnis 100','100 Mbps', 600000, 'Untuk kantor & kebutuhan bisnis.', 'aktif');

INSERT INTO `pelanggan` (`user_id`,`kode`,`nama`,`no_hp`,`alamat`,`paket_id`,`status`,`tgl_pasang`) VALUES
  (3, 'PLG-0001', 'Budi Santoso', '6281200000003', 'Jl. Mawar No. 10, Surabaya', 1, 'aktif', '2025-01-15'),
  (4, 'PLG-0002', 'Siti Aminah',  '6281200000004', 'Jl. Melati No. 5, Surabaya', 3, 'aktif', '2025-02-20');

INSERT INTO `tagihan` (`pelanggan_id`,`periode`,`jumlah`,`jatuh_tempo`,`status`) VALUES
  (1, '2026-05', 150000, '2026-05-10', 'lunas'),
  (1, '2026-06', 150000, '2026-06-10', 'belum_lunas'),
  (2, '2026-05', 350000, '2026-05-10', 'lunas'),
  (2, '2026-06', 350000, '2026-06-10', 'belum_lunas');

INSERT INTO `pembayaran` (`tagihan_id`,`tgl_bayar`,`jumlah`,`metode`,`dicatat_oleh`,`keterangan`) VALUES
  (1, '2026-05-08', 150000, 'transfer', 2, 'Pembayaran periode Mei 2026'),
  (3, '2026-05-09', 350000, 'tunai',    2, 'Pembayaran periode Mei 2026');

INSERT INTO `pengaduan` (`pelanggan_id`,`judul`,`isi`,`status`) VALUES
  (1, 'Internet lambat', 'Sejak kemarin koneksi terasa lambat di malam hari.', 'baru'),
  (2, 'Tidak bisa konek', 'WiFi sempat mati total pagi tadi sekitar 1 jam.', 'diproses');
