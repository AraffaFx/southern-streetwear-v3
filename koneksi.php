<?php
/**
 * koneksi.php
 * Script koneksi ke PostgreSQL via PDO (Supabase Cloud Pooler)
 * Juga berisi konfigurasi dynamic base URL untuk localhost dan Vercel
 */

// ============================================================
// DYNAMIC BASE URL CONFIGURATION
// ============================================================
/**
 * Mendeteksi environment secara otomatis:
 * - Di localhost: BASE_URL = 'http://localhost/southern/'
 * - Di Vercel (production): BASE_URL = '/'
 */
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Cek apakah ini localhost
if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    define('BASE_URL', $protocol . '://' . $host . '/southern/');
} else {
    // Di Vercel/Production - pakai root domain
    define('BASE_URL', '/');
}

// Pastikan BASE_URL selalu berakhir dengan slash
$BASE_URL = rtrim(BASE_URL, '/') . '/';

// Data ini diambil persis dari foto Supabase kamu!
$host     = "aws-1-ap-south-1.pooler.supabase.com"; 
$port     = "6543";                                
$dbname   = "postgres";                       
$user     = "postgres.nawvqzysckecvyuvlvfs"; 
$password = "Araffapramana";                  

try {
    // Format DSN PostgreSQL Pooler
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    
    // Membuat instance koneksi PDO baru
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hapus tanda // di bawah ini jika kamu mau mengetes koneksinya
    // echo "Koneksi ke Supabase Berhasil!";
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>