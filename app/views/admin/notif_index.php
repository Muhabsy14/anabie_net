<div class="page-head">
  <div><h1>Kirim Notifikasi Tagihan via WhatsApp</h1><p class="sub">Kirim pengingat tagihan ke pelanggan yang belum lunas.</p></div>
</div>

<div class="alert alert-info">
  Klik <b>Kirim WhatsApp</b> untuk membuka WhatsApp dengan pesan tagihan yang sudah otomatis terisi, lalu tekan kirim.
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Pelanggan</th><th>No. HP</th><th>Periode</th><th class="text-right">Jumlah</th><th>Jatuh Tempo</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$rows): ?>
      <tr><td colspan="6"><div class="empty">Tidak ada tagihan yang perlu ditagih. 🎉</div></td></tr>
    <?php else: foreach ($rows as $r): ?>
      <tr>
        <td><?= e($r['nama']) ?><div class="text-muted" style="font-size:.8rem"><?= e($r['kode']) ?></div></td>
        <td><?= e($r['no_hp']) ?></td>
        <td><?= e(periode_id($r['periode'])) ?></td>
        <td class="text-right"><?= rupiah($r['jumlah']) ?></td>
        <td><?= tgl_id($r['jatuh_tempo']) ?></td>
        <td>
          <a class="btn btn-sm btn-wa" href="<?= e($r['wa']) ?>" target="_blank" rel="noopener">📲 Kirim WhatsApp</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
