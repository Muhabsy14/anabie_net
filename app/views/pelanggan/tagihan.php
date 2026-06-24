<div class="page-head">
  <div><h1>Tagihan Saya</h1><p class="sub">Daftar tagihan internet Anda.</p></div>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Periode</th><th class="text-right">Jumlah</th><th>Jatuh Tempo</th><th>Status</th></tr></thead>
    <tbody>
    <?php if (!$tagihan): ?>
      <tr><td colspan="4"><div class="empty">Belum ada tagihan.</div></td></tr>
    <?php else: foreach ($tagihan as $t): ?>
      <tr>
        <td><?= e(periode_id($t['periode'])) ?></td>
        <td class="text-right"><?= rupiah($t['jumlah']) ?></td>
        <td><?= tgl_id($t['jatuh_tempo']) ?></td>
        <td><span class="badge <?= status_badge($t['status']) ?>"><?= status_label($t['status']) ?></span></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
  <p class="form-hint" style="margin-top:12px">Untuk pembayaran, silakan hubungi/temui petugas <?= APP_NAME ?>. Pembayaran akan dicatat oleh admin.</p>
</div>
