<?php
// Start output buffering to prevent header issues
ob_start();

// Mulai session hanya jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset(); // Hapus semua variabel session
session_destroy(); // Hancurkan session

// Clear any output and redirect ke halaman login
ob_end_clean();
header("Location: login.php");
exit;
?>
