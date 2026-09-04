<?php
// config/database.php
// Koneksi Database SI Cutting Planning

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Sesuaikan jika pakai password
define('DB_NAME', 'db_cutting_planning');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
EOF