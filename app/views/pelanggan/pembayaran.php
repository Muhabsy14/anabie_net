<div class="page-head">
  <div><h1>Riwayat Pembayaran</h1><p class="sub">Pembayaran yang telah Anda lakukan.</p></div>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Tgl Bayar</th><th>Periode</th><th class="text-right">Jumlah</th><th>Metode</th><th>Bukti</th></tr></thead>
    <tbody>
    <?php if (!$pembayaran): ?>
      <tr><td colspan="5"><div class="empty">Belum ada riwayat pembayaran.</div></td></tr>
    <?php else: foreach ($pembayaran as $pb): ?>
      <tr>
        <td><?= tgl_id($pb['tgl_bayar']) ?></td>
        <td><?= e(periode_id($pb['periode'])) ?></td>
        <td class="text-right"><?= rupiah($pb['jumlah']) ?></td>
        <td><span class="badge badge-info"><?= ucfirst($pb['metode']) ?></span></td>
        <td><?php if ($pb['bukti']): ?><a href="<?= url('uploads/' . $pb['bukti']) ?>" target="_blank">Lihat</a><?php else: ?><span class="text-muted">-</span><?php endif; ?></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
