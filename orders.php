<?php
// pages/orders.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_order'])) {
    $no     = mysqli_real_escape_string($conn, $_POST['no_order']);
    $style  = (int)$_POST['style_id'];
    $warna  = mysqli_real_escape_string($conn, $_POST['warna']);
    $xs = (int)$_POST['qty_xs']; $s = (int)$_POST['qty_s']; $m = (int)$_POST['qty_m'];
    $l = (int)$_POST['qty_l']; $xl = (int)$_POST['qty_xl']; $xxl = (int)$_POST['qty_2xl']; $xxxl = (int)$_POST['qty_3xl'];
    $tgl = $_POST['tanggal_order'];

    mysqli_query($conn, "INSERT INTO orders (no_order, style_id, warna, qty_xs, qty_s, qty_m, qty_l, qty_xl, qty_2xl, qty_3xl, tanggal_order) 
        VALUES ('$no',$style,'$warna',$xs,$s,$m,$l,$xl,$xxl,$xxxl,'$tgl')");
    echo '<div class="alert alert-success">Order berhasil ditambahkan!</div>';
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Data Order</h5>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalOrder">
    <i class="bi bi-plus"></i> Tambah Order
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 table-sm">
      <thead class="table-light">
        <tr>
          <th>No Order</th>
          <th>Style</th>
          <th>Warna</th>
          <th>XS</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>2XL</th><th>3XL</th>
          <th>Total</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $q = mysqli_query($conn, "SELECT o.*, s.kode_style FROM orders o JOIN styles s ON o.style_id=s.id ORDER BY o.id DESC");
        while ($r = mysqli_fetch_assoc($q)):
        ?>
        <tr>
          <td><strong><?= htmlspecialchars($r['no_order']) ?></strong></td>
          <td><?= htmlspecialchars($r['kode_style']) ?></td>
          <td><?= htmlspecialchars($r['warna']) ?></td>
          <td><?= $r['qty_xs'] ?></td>
          <td><?= $r['qty_s'] ?></td>
          <td><?= $r['qty_m'] ?></td>
          <td><?= $r['qty_l'] ?></td>
          <td><?= $r['qty_xl'] ?></td>
          <td><?= $r['qty_2xl'] ?></td>
          <td><?= $r['qty_3xl'] ?></td>
          <td><strong><?= $r['total_qty'] ?></strong></td>
          <td><?= $r['tanggal_order'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah Order -->
<div class="modal fade" id="modalOrder" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Order + Size Ratio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">No Order</label>
            <input type="text" name="no_order" class="form-control" required placeholder="ORD-2026-00X">
          </div>
          <div class="col-md-4">
            <label class="form-label">Style</label>
            <select name="style_id" class="form-select" required>
              <option value="">-- Pilih Style --</option>
              <?php
              $styles = mysqli_query($conn, "SELECT * FROM styles ORDER BY kode_style");
              while ($s = mysqli_fetch_assoc($styles)) {
                  echo '<option value="'.$s['id'].'">'.$s['kode_style'].' - '.$s['nama_style'].'</option>';
              }
              ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Warna</label>
            <input type="text" name="warna" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Tanggal Order</label>
            <input type="date" name="tanggal_order" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
        </div>
        <hr>
        <h6 class="mb-3">Size Ratio</h6>
        <div class="row g-2">
          <?php foreach (['xs','s','m','l','xl','2xl','3xl'] as $sz): ?>
          <div class="col">
            <label class="form-label text-uppercase small"><?= $sz ?></label>
            <input type="number" name="qty_<?= $sz ?>" class="form-control form-control-sm" value="0" min="0">
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="tambah_order" class="btn btn-primary">Simpan Order</button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
EOF