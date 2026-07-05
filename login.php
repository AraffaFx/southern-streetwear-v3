<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, arahkan ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            // Ambil data user berdasarkan email
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password dengan hash yang ada di database
            if ($user && password_verify($password, $user['password'])) {
                // Berhasil login, set variabel session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect ke index
                header("Location: index.php");
                exit;
            } else {
                $error = 'Email atau password salah.';
            }
        } catch (PDOException $e) {
            $error = 'Terjadi kesalahan sistem: ' . $e->getMessage();
        }
    } else {
        $error = 'Mohon isi email dan password.';
    }
}

$base_path = './';
$page_title = 'Login | Southern';
include 'includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-container">
        <h2 class="auth-title">MASUK</h2>
        <p class="auth-subtitle">Akses akun Southern Anda.</p>

        <?php if (!empty($error)): ?>
            <div class="auth-alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="form-submit">LOGIN</button>
        </form>
        
        <p class="auth-link-text">
            Belum punya akun? <a href="register.php" class="auth-link">Daftar di sini</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
