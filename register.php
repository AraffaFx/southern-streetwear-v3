<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, arahkan ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        try {
            // Cek apakah email sudah digunakan
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->fetch()) {
                $error = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
            } else {
                // Enkripsi password sebelum disimpan ke database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert data user baru
                $insertStmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $insertStmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password
                ]);
                
                $success = 'Pendaftaran berhasil. Silakan login.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    } else {
        $error = 'Mohon isi semua bidang.';
    }
}

$base_path = './';
$page_title = 'Register | Southern';
include 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <h2 class="auth-title">DAFTAR</h2>
        <p class="auth-subtitle">Buat akun untuk akses eksklusif.</p>

        <?php if (!empty($error)): ?>
            <div class="auth-alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="auth-alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="form-submit">BUAT AKUN</button>
        </form>
        
        <p class="auth-link-text">
            Sudah punya akun? <a href="login.php" class="auth-link">Login di sini</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
