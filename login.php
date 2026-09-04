<?php
require_once 'config/database.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - SI Cutting Planning</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #1a2b4a 0%, #2b6cb0 100%);
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      background: #fff;
      border-radius: 16px;
      padding: 2.5rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 25px 50px rgba(0,0,0,.3);
    }
  </style>
</head>
<body>
  <div class="login-card text-center">
    <div class="mb-4">
      <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:70px;height:70px">
        <i class="bi bi-scissors fs-2 text-primary"></i>
      </div>
      <h4 class="mt-3 mb-1 fw-bold">SI Cutting Planning</h4>
      <p class="text-muted small">PT Yongjin Javasuka Garment II</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2 small"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3 text-start">
        <label class="form-label small">Username</label>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
      <div class="mb-3 text-start">
        <label class="form-label small">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Masuk</button>
    </form>
  </div>
</body>
</html>
EOF