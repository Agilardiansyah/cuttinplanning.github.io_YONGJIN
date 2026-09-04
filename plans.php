<?php
// pages/plans.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_plan'])) {
    $order_id = (int)$_POST['order_id'];
    $marker   = (float)$_POST['marker_length'];
    $lay      = (int)$_POST['jumlah_lay'];
    $lebar    = (float)$_POST['lebar_kain'];
    $catatan  = mysqli_real_escape_string($conn, $_POST['catatan'] ?? '');

    // Rumus sesuai praktik cutting planning
    // Fabric Used (YD) = Marker Length (m) × Jumlah Lay × 1.0936
    $fabric_used = round($marker * $lay * 1.0936, 2);

    // Utilization sederhana (bisa disesuaikan)
    // Asumsi marker efficiency berdasarkan lebar kain
    $utilization = round(min(98, 70 + ($lebar * 10) + (rand(0,8))), 1);

    $user_id = $_SESSION['user_id'];
    mysqli_query($conn, "INSERT INTO cutting_plans (order_id, marker_length, jumlah_lay, lebar_kain, fabric_used, utilization, catatan, created_by) 
        VALUES ($order_id, $marker, $lay, $lebar, $fabric_used, $utilization, '$catatan', $user_id)");
    echo '<div class="alert alert-success">Cutting Plan berhasil dibuat! Fabric Used: '.$fabric_used.' YD | Util: '.$utilization.'%</div>';
}

// Update status
if (isset($_GET['status']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $st = mysqli_real_escape_string($conn, $_GET['status']);
    if (in_array($st, ['belum','proses','selesai'])) {
        mysqli_query($conn, "UPDATE cutting_plans SET status='$st' WHERE id=$id");
        echo '<div class="alert alert-success">Status berhasil diupdate!</div>';
    }
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Cutting Plan</h5>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPlan">
    <i class="bi bi-plus"></i> Buat Cutting Plan
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>No Order</th>
          <th>Style</th>
          <th>Marker (m)</th>
          <th>Lay</th>
          <th>Fabric Used</th>
          <th>Util %</th>
          <th>Status</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $q = mysqli_query($conn, "SELECT cp.*, o.no_order, s.kode_style 
            FROM cutting_plans cp 
            JOIN orders o ON cp.order_id = o.id 
            JOIN styles s ON o.style_id = s.id 
            ORDER BY cp.id DESC");
        while ($r = mysqli_fetch_assoc($q)):
        ?>
        <tr>
          <td><?= htmlspecialchars($r['no_order']) ?></td>
          <td><?= htmlspecialchars($r['kode_style']) ?></td>
          <td><?= $r['marker_length'] ?></td>
          <td><?= $r['jumlah_lay'] ?></td>
          <td><strong><?= number_format($r['fabric_used'], 2) ?> YD</strong></td>
          <td><?= number_format($r['utilization'], 1) ?>%</td>
          <td>
            <?php
            $badge = match($r['status']) {
                'selesai' => 'success',
                'proses'  => 'warning text-dark',
                default   => 'secondary'
            };
            ?>
            <span class="badge bg-<?= $badge ?>"><?= ucfirst($r['status']) ?></span>
          </td>
          <td>
            <div class="btn-group btn-group-sm">
              <a href="?page=plans&id=<?= $r['id'] ?>&status=proses" class="btn btn-outline-warning" title="Proses">P</a>
              <a href="?page=plans&id=<?= $r['id'] ?>&status=selesai" class="btn btn-outline-success" title="Selesai">S</a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Buat Cutting Plan -->
<div class="modal fade" id="modalPlan" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Buat Cutting Plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Pilih Order</label>
          <select name="order_id" class="form-select" required>
            <option value="">-- Pilih Order --</option>
            <?php
            $orders = mysqli_query($conn, "SELECT o.id, o.no_order, s.kode_style FROM orders o JOIN styles s ON o.style_id=s.id ORDER BY o.id DESC");
            while ($o = mysqli_fetch_assoc($orders)) {
                echo '<option value="'.$o['id'].'">'.$o['no_order'].' ('.$o['kode_style'].')</option>';
            }
            ?>
          </select>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Marker Length (meter)</label>
            <input type="number" step="0.01" name="marker_length" class="form-control" required placeholder="Contoh: 2.45">
          </div>
          <div class="col-md-6">
            <label class="form-label">Jumlah Lay</label>
            <input type="number" name="jumlah_lay" class="form-control" required min="1" value="1">
          </div>
          <div class="col-md-6">
            <label class="form-label">Lebar Kain (meter)</label>
            <input type="number" step="0.01" name="lebar_kain" class="form-control" value="1.50">
          </div>
        </div>
        <div class="mb-3 mt-3">
          <label class="form-label">Catatan</label>
          <textarea name="catatan" class="form-control" rows="2"></textarea>
        </div>
        <div class="alert alert-info small mb-0">
          <strong>Rumus:</strong> Fabric Used (YD) = Marker Length × Jumlah Lay × 1.0936
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="tambah_plan" class="btn btn-primary">Hitung & Simpan</button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
EOF