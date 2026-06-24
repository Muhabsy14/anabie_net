<div class="page-head">
  <div><h1>Kelola Pembayaran</h1><p class="sub">Pencatatan pembayaran tagihan & bukti.</p></div>
  <a class="btn btn-primary" href="<?= route('pembayaran.create') ?>">+ Catat Pembayaran</a>
</div>

<div class="card">
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Tgl Bayar</th><th>Pelanggan</th><th>Periode</th><th class="text-right">Jumlah</th><th>Metode</th><th>Bukti</th><th>Petugas</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$pembayaran): ?>
      <tr><td colspan="8"><div class="empty">Belum ada pembayaran tercatat.</div></td></tr>
    <?php else: foreach ($pembayaran as $pb): ?>
      <tr>
        <td><?= tgl_id($pb['tgl_bayar']) ?></td>
        <td><?= e($pb['pelanggan']) ?><div class="text-muted" style="font-size:.8rem"><?= e($pb['kode']) ?></div></td>
        <td><?= e(periode_id($pb['periode'])) ?></td>
        <td class="text-right"><?= rupiah($pb['jumlah']) ?></td>
        <td><span class="badge badge-info"><?= ucfirst($pb['metode']) ?></span></td>
        <td><?php if ($pb['bukti']): ?><a href="<?= url('uploads/' . $pb['bukti']) ?>" target="_blank">Lihat</a><?php else: ?><span class="text-muted">-</span><?php endif; ?></td>
        <td><?= e($pb['petugas'] ?? '-') ?></td>
        <td><div class="actions">
          <a class="btn btn-sm btn-outline" href="<?= route('pembayaran.show', ['id' => $pb['id']]) ?>">Detail</a>
          <form method="post" action="<?= route('pembayaran.delete') ?>" data-confirm="Hapus pembayaran ini? Tagihan akan kembali belum lunas." style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $pb['id'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </div></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
