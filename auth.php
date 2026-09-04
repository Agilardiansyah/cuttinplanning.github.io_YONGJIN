<?php
// includes/auth.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function getUser() {
    return [
        'id'       => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? null,
        'nama'     => $_SESSION['nama_lengkap'] ?? null,
        'role'     => $_SESSION['role'] ?? null
    ];
}

function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
EOF