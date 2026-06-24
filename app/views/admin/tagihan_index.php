<div class="page-head">
  <div><h1>Kelola Tagihan</h1><p class="sub">Tagihan bulanan pelanggan.</p></div>
  <a class="btn btn-primary" href="<?= route('tagihan.create') ?>">+ Buat Tagihan</a>
</div>

<div class="card">
  <div class="card-title">
    <span>Daftar Tagihan</span>
    <div class="flex gap wrap">
      <a class="btn btn-sm <?= $filter==='' ? 'btn-primary':'btn-outline' ?>" href="<?= route('tagihan.index') ?>">Semua</a>
      <a class="btn btn-sm <?= $filter==='belum_lunas' ? 'btn-primary':'btn-outline' ?>" href="<?= route('tagihan.index',['status'=>'belum_lunas']) ?>">Belum Lunas</a>
      <a class="btn btn-sm <?= $filter==='lunas' ? 'btn-primary':'btn-outline' ?>" href="<?= route('tagihan.index',['status'=>'lunas']) ?>">Lunas</a>
    </div>
  </div>
  <div class="table-wrap"><table class="tbl">
    <thead><tr><th>Pelanggan</th><th>Periode</th><th class="text-right">Jumlah</th><th>Jatuh Tempo</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if (!$tagihan): ?>
      <tr><td colspan="6"><div class="empty">Belum ada tagihan.</div></td></tr>
    <?php else: foreach ($tagihan as $t): ?>
      <tr>
        <td><?= e($t['pelanggan']) ?><div class="text-muted" style="font-size:.8rem"><?= e($t['kode']) ?></div></td>
        <td><?= e(periode_id($t['periode'])) ?></td>
        <td class="text-right"><?= rupiah($t['jumlah']) ?></td>
        <td><?= tgl_id($t['jatuh_tempo']) ?></td>
        <td><span class="badge <?= status_badge($t['status']) ?>"><?= status_label($t['status']) ?></span></td>
        <td><div class="actions">
          <?php if ($t['status'] === 'belum_lunas'): ?>
            <a class="btn btn-sm btn-success" href="<?= route('pembayaran.create', ['tagihan_id' => $t['id']]) ?>">Bayar</a>
          <?php endif; ?>
          <a class="btn btn-sm btn-outline" href="<?= route('tagihan.edit', ['id' => $t['id']]) ?>">Edit</a>
          <form method="post" action="<?= route('tagihan.delete') ?>" data-confirm="Hapus tagihan ini?" style="display:inline">
            <?= csrf_field() ?><input type="hidden" name="id" value="<?= $t['id'] ?>">
            <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
          </form>
        </div></td>
      </tr>
    <?php endforeach; endif; ?>
    </tbody>
  </table></div>
</div>
