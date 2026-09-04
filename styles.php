<?php
// pages/styles.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_style'])) {
    $kode = mysqli_real_escape_string($conn, $_POST['kode_style']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_style']);
    $desk = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    mysqli_query($conn, "INSERT INTO styles (kode_style, nama_style, deskripsi) VALUES ('$kode','$nama','$desk')");
    echo '<div class="alert alert-success">Style berhasil ditambahkan!</div>';
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM styles WHERE id=$id");
    echo '<div class="alert alert-success">Style berhasil dihapus!</div>';
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Data Style / Artikel</h5>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalStyle">
    <i class="bi bi-plus"></i> Tambah Style
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Style</th>
          <th>Nama Style</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $q = mysqli_query($conn, "SELECT * FROM styles ORDER BY id DESC");
        while ($r = mysqli_fetch_assoc($q)):
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><strong><?= htmlspecialchars($r['kode_style']) ?></strong></td>
          <td><?= htmlspecialchars($r['nama_style']) ?></td>
          <td><?= htmlspecialchars($r['deskripsi'] ?? '-') ?></td>
          <td>
            <a href="?page=styles&hapus=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus style ini?')">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalStyle" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Style</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Kode Style</label>
          <input type="text" name="kode_style" class="form-control" required placeholder="Contoh: UWA-CGSS">
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Style</label>
          <input type="text" name="nama_style" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" name="tambah_style" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
EOF