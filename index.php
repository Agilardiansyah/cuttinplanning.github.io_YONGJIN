<?php
require_once 'config/database.php';
require_once 'includes/auth.php';
requireLogin();

$user = getUser();
$page = $_GET['page'] ?? 'dashboard';

// Statistik Dashboard
$total_style = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM styles"))['jml'];
$total_order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM orders"))['jml'];
$total_plan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM cutting_plans"))['jml'];
$avg_util    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(utilization) as avg FROM cutting_plans"))['avg'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SI Cutting Planning | PT Yongjin Javasuka Garment II</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    :root { --sidebar-bg: #1a2b4a; --sidebar-active: #2b6cb0; }
    body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f0f2f5; }
    .sidebar {
      width: 240px; min-height: 100vh; background: var(--sidebar-bg);
      position: fixed; top: 0; left: 0; z-index: 1000;
    }
    .sidebar .brand { padding: 1.25rem 1rem; color: #fff; border-bottom: 1px solid rgba(255,255,255,.1); }
    .sidebar .nav-link {
      color: rgba(255,255,255,.75); padding: .7rem 1.25rem; font-size: .9rem;
      display: flex; align-items: center; gap: .6rem;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: var(--sidebar-active); }
    .main { margin-left: 240px; min-height: 100vh; }
    .topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: .8rem 1.5rem; }
    .stat-card { background: #fff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; }
    .stat-card .value { font-size: 1.8rem; font-weight: 700; }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="brand">
      <h5 class="mb-0"><i class="bi bi-scissors"></i> SI Cutting</h5>
      <small class="opacity-75">Yongjin Javasuka</small>
    </div>
    <nav class="nav flex-column mt-2">
      <a class="nav-link <?= $page=='dashboard'?'active':'' ?>" href="?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a class="nav-link <?= $page=='styles'?'active':'' ?>" href="?page=styles"><i class="bi bi-tags"></i> Data Style</a>
      <a class="nav-link <?= $page=='orders'?'active':'' ?>" href="?page=orders"><i class="bi bi-cart"></i> Data Order</a>
      <a class="nav-link <?= $page=='plans'?'active':'' ?>" href="?page=plans"><i class="bi bi-scissors"></i> Cutting Plan</a>
      <a class="nav-link <?= $page=='laporan'?'active':'' ?>" href="?page=laporan"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a>
      <a class="nav-link mt-3" href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
  </div>

  <div class="main">
    <div class="topbar d-flex justify-content-between align-items-center">
      <h5 class="mb-0 text-capitalize"><?= htmlspecialchars($page) ?></h5>
      <div class="d-flex align-items-center gap-2">
        <span class="small text-muted"><?= htmlspecialchars($user['nama']) ?></span>
        <span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span>
      </div>
    </div>

    <div class="p-4">
      <?php if ($page === 'dashboard'): ?>
        <div class="row g-3 mb-4">
          <div class="col-md-3">
            <div class="stat-card">
              <div class="value text-primary"><?= $total_style ?></div>
              <div class="small text-muted">Total Style</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="value text-success"><?= $total_order ?></div>
              <div class="small text-muted">Total Order</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="value text-warning"><?= $total_plan ?></div>
              <div class="small text-muted">Cutting Plan</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="stat-card">
              <div class="value text-info"><?= number_format($avg_util, 1) ?>%</div>
              <div class="small text-muted">Avg Utilization</div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white">
            <h6 class="mb-0">Cutting Plan Terbaru</h6>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>No Order</th>
                  <th>Style</th>
                  <th>Fabric Used</th>
                  <th>Util %</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $q = mysqli_query($conn, "SELECT cp.*, o.no_order, s.kode_style 
                    FROM cutting_plans cp 
                    JOIN orders o ON cp.order_id = o.id 
                    JOIN styles s ON o.style_id = s.id 
                    ORDER BY cp.created_at DESC LIMIT 10");
                if (mysqli_num_rows($q) == 0) {
                    echo '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data cutting plan</td></tr>';
                }
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                  <td><?= htmlspecialchars($r['no_order']) ?></td>
                  <td><?= htmlspecialchars($r['kode_style']) ?></td>
                  <td><?= number_format($r['fabric_used'], 2) ?> YD</td>
                  <td><?= number_format($r['utilization'], 1) ?>%</td>
                  <td>
                    <?php
                    $badge = match($r['status']) {
                        'selesai' => 'success',
                        'proses'  => 'warning',
                        default   => 'secondary'
                    };
                    ?>
                    <span class="badge bg-<?= $badge ?>"><?= ucfirst($r['status']) ?></span>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

      <?php elseif ($page === 'styles'): ?>
        <?php include 'pages/styles.php'; ?>

      <?php elseif ($page === 'orders'): ?>
        <?php include 'pages/orders.php'; ?>

      <?php elseif ($page === 'plans'): ?>
        <?php include 'pages/plans.php'; ?>

      <?php elseif ($page === 'laporan'): ?>
        <?php include 'pages/laporan.php'; ?>

      <?php else: ?>
        <div class="alert alert-info">Halaman tidak ditemukan.</div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
EOF