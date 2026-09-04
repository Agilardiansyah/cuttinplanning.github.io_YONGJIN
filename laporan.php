<?php
// pages/laporan.php
$total_fabric = 0;
$total_util = 0;
$count = 0;
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Laporan Cutting Plan</h5>
  <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
    <i class="bi bi-printer"></i> Cetak
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="text-center mb-4 d-none d-print-block">
      <h4>Laporan Cutting Plan</h4>
      <p class="mb-0">PT Yongjin Javasuka Garment II</p>
      <p class="small text-muted">Dicetak: <?= date('d/m/Y H:i') ?></p>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>No Order</th>
            <th>Style</th>
            <th>Marker (m)</th>
            <th>Lay</th>
            <th>Fabric Used (YD)</th>
            <th>Utilization %</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $q = mysqli_query($conn, "SELECT cp.*, o.no_order, s.kode_style 
              FROM cutting_plans cp 
              JOIN orders o ON cp.order_id = o.id 
              JOIN styles s ON o.style_id = s.id 
              ORDER BY cp.created_at DESC");
          while ($r = mysqli_fetch_assoc($q)):
              $total_fabric += $r['fabric_used'];
              $total_util += $r['utilization'];
              $count++;
          ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($r['no_order']) ?></td>
            <td><?= htmlspecialchars($r['kode_style']) ?></td>
            <td><?= $r['marker_length'] ?></td>
            <td><?= $r['jumlah_lay'] ?></td>
            <td><?= number_format($r['fabric_used'], 2) ?></td>
            <td><?= number_format($r['utilization'], 1) ?>%</td>
            <td><?= ucfirst($r['status']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot>
          <tr class="table-secondary fw-bold">
            <td colspan="5" class="text-end">Total / Rata-rata</td>
            <td><?= number_format($total_fabric, 2) ?> YD</td>
            <td><?= $count > 0 ? number_format($total_util / $count, 1) : 0 ?>%</td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<style>
@media print {
  .sidebar, .topbar, .btn, .d-print-none { display: none !important; }
  .main { margin-left: 0 !important; }
}
</style>
EOF