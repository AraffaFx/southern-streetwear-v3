<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';
$user_id = $_SESSION['user_id'];
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

$message = '';
$message_type = ''; // 'success' or 'error'

// Handle POST Requests (CRUD Profile)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // 1. Update Profile (Nama & Telepon)
        if ($action === 'update_profile' && $tab === 'dashboard') {
            $full_name = $_POST['full_name'] ?? '';
            $phone_number = $_POST['phone_number'] ?? '';
            
            try {
                $stmt = $pdo->prepare("UPDATE users SET full_name = :full_name, phone_number = :phone_number WHERE id = :id");
                $stmt->execute([
                    'full_name' => $full_name,
                    'phone_number' => $phone_number,
                    'id' => $user_id
                ]);
                $message = "Profile updated successfully.";
                $message_type = "success";
            } catch (PDOException $e) {
                $message = "Error updating profile: " . $e->getMessage();
                $message_type = "error";
            }
        } 
        // 2. Update Address
        elseif ($action === 'update_address' && $tab === 'address') {
            $address = $_POST['address'] ?? '';
            
            try {
                $stmt = $pdo->prepare("UPDATE users SET address = :address WHERE id = :id");
                $stmt->execute([
                    'address' => $address,
                    'id' => $user_id
                ]);
                $message = "Shipping address updated successfully.";
                $message_type = "success";
            } catch (PDOException $e) {
                $message = "Error updating address: " . $e->getMessage();
                $message_type = "error";
            }
        } 
        // 3. Change Password
        elseif ($action === 'change_password' && $tab === 'security') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if ($new_password !== $confirm_password) {
                $message = "New passwords do not match.";
                $message_type = "error";
            } else {
                try {
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
                    $stmt->execute(['id' => $user_id]);
                    $user_db = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (password_verify($current_password, $user_db['password'])) {
                        $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
                        $upd = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                        $upd->execute(['password' => $hashed_new, 'id' => $user_id]);
                        $message = "Password changed successfully.";
                        $message_type = "success";
                    } else {
                        $message = "Current password is incorrect.";
                        $message_type = "error";
                    }
                } catch (PDOException $e) {
                    $message = "Error changing password: " . $e->getMessage();
                    $message_type = "error";
                }
            }
        }
    }
}

// Fetch user data for forms
try {
    $stmt = $pdo->prepare("SELECT username, email, full_name, phone_number, address FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

$base_path = './';
$page_title = 'My Profile | Southern';
include 'includes/header.php';
?>

<style>
/* =================================================================
   PROFILE SPECIFIC STYLES (URBAN BRUTALIST)
   ================================================================= */
.profile-wrapper {
    min-height: calc(100vh - var(--tinggi-navbar));
    padding: var(--section-padding) var(--margin-mobile);
    margin-top: var(--tinggi-navbar);
    display: flex;
    flex-direction: column;
    gap: 32px;
}

@media (min-width: 768px) {
    .profile-wrapper {
        flex-direction: row;
        padding: var(--section-padding) var(--margin-desktop);
        align-items: flex-start;
    }
}

/* SIDEBAR NAV (COL 1) */
.profile-sidebar {
    flex: 0 0 100%;
    border: 2px solid var(--warna-hitam);
    background-color: var(--warna-putih);
}

@media (min-width: 768px) {
    .profile-sidebar {
        flex: 0 0 280px; /* Lebar ~25-30% di desktop */
    }
}

.profile-nav-link {
    display: block;
    padding: 16px 24px;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    font-size: var(--ukuran-body-md);
    text-transform: uppercase;
    color: var(--warna-hitam);
    border-bottom: 2px solid var(--warna-hitam);
    text-decoration: none;
    transition: all 0.2s ease-in-out;
}

.profile-nav-link:last-child {
    border-bottom: none;
}

.profile-nav-link:hover, .profile-nav-link.active {
    background-color: var(--warna-hitam);
    color: var(--warna-putih);
}

.profile-nav-link.logout-link {
    background-color: #f5f5f5;
    color: #000;
}

.profile-nav-link.logout-link:hover {
    background-color: #d90000;
    color: var(--warna-putih);
}

/* CONTENT AREA (COL 2) */
.profile-content {
    flex: 1;
    border: 2px solid var(--warna-hitam);
    background-color: var(--warna-putih);
    padding: 32px;
}

.profile-title {
    font-family: 'Syne', sans-serif;
    font-size: 32px;
    font-weight: 800;
    text-transform: uppercase;
    margin-bottom: 32px;
    border-bottom: 4px solid var(--warna-hitam);
    padding-bottom: 16px;
    letter-spacing: -0.02em;
}

/* ALERT MESSAGES (Brutalist Style) */
.profile-alert {
    padding: 16px;
    border: 2px solid var(--warna-hitam);
    margin-bottom: 32px;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    text-transform: uppercase;
    font-size: var(--ukuran-caption);
    letter-spacing: 0.05em;
}

.profile-alert.success {
    background-color: var(--warna-hitam);
    color: var(--warna-putih);
}

.profile-alert.error {
    background-color: #ffcccc;
    color: #cc0000;
    border-color: #cc0000;
}

/* FORMS */
.profile-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
    max-width: 600px;
}

.p-form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.p-form-label {
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-label);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.p-form-input {
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-body-md);
    padding: 16px;
    border: 2px solid var(--warna-hitam);
    background-color: transparent;
    outline: none;
    transition: background-color 0.2s;
    border-radius: 0; /* Tajam sempurna */
}

.p-form-input:focus {
    background-color: #f5f5f5;
}

.p-form-input:disabled, .p-form-input[readonly] {
    background-color: #e0e0e0;
    cursor: not-allowed;
    color: var(--warna-teks-sekunder);
    border-color: var(--warna-teks-sekunder);
}

.p-form-textarea {
    resize: vertical;
    min-height: 150px;
    /* Logistics label style */
    font-family: monospace;
    font-size: var(--ukuran-body-md);
    text-transform: uppercase;
    border: 4px solid var(--warna-hitam); /* Lebih tebal ala form resi */
    padding: 24px;
    background-color: #fdfdfd;
    background-image: repeating-linear-gradient(transparent, transparent 31px, rgba(0,0,0,0.1) 31px, rgba(0,0,0,0.1) 32px);
    line-height: 32px;
}

.p-btn-submit {
    align-self: flex-start;
    background-color: var(--warna-hitam);
    color: var(--warna-putih);
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-body-md);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 16px 32px;
    border: 2px solid var(--warna-hitam);
    cursor: pointer;
    transition: all 0.2s;
    border-radius: 0;
    margin-top: 8px;
}

/* Hover Invert Effect */
.p-btn-submit:hover {
    background-color: var(--warna-putih);
    color: var(--warna-hitam);
}

/* ORDERS TABLE */
.orders-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-caption);
}

.orders-table th, .orders-table td {
    border: 2px solid var(--warna-hitam);
    padding: 16px;
    text-align: left;
}

.orders-table th {
    background-color: var(--warna-hitam);
    color: var(--warna-putih);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.orders-table tr:hover td {
    background-color: #f9f9f9;
}

.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border: 2px solid var(--warna-hitam);
    font-weight: 800;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.1em;
}

.no-data {
    text-align: center;
    font-family: 'Syne', sans-serif;
    font-size: 24px;
    font-weight: 800;
    padding: 48px;
    border: 2px dashed var(--warna-hitam);
    text-transform: uppercase;
}
</style>

<div class="profile-wrapper">
    <!-- COL 1: SIDEBAR NAVIGASI -->
    <aside class="profile-sidebar">
        <a href="profile.php?tab=dashboard" class="profile-nav-link <?php echo $tab === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
        <a href="profile.php?tab=address" class="profile-nav-link <?php echo $tab === 'address' ? 'active' : ''; ?>">Alamat Pengiriman</a>
        <a href="profile.php?tab=orders" class="profile-nav-link <?php echo $tab === 'orders' ? 'active' : ''; ?>">Riwayat Pesanan</a>
        <a href="profile.php?tab=security" class="profile-nav-link <?php echo $tab === 'security' ? 'active' : ''; ?>">Keamanan Akun</a>
        <a href="logout.php" class="profile-nav-link logout-link">Logout</a>
    </aside>

    <!-- COL 2: KONTEN UTAMA -->
    <main class="profile-content">
        <?php if (!empty($message)): ?>
            <div class="profile-alert <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'dashboard'): ?>
            <h2 class="profile-title">ACCOUNT DASHBOARD</h2>
            <form action="profile.php?tab=dashboard" method="POST" class="profile-form">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="p-form-group">
                    <label class="p-form-label">Username</label>
                    <input type="text" class="p-form-input" value="<?php echo htmlspecialchars($user['username']); ?>" readonly disabled>
                </div>
                
                <div class="p-form-group">
                    <label class="p-form-label">Email</label>
                    <input type="email" class="p-form-input" value="<?php echo htmlspecialchars($user['email']); ?>" readonly disabled>
                </div>
                
                <div class="p-form-group">
                    <label for="full_name" class="p-form-label">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" class="p-form-input" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                </div>
                
                <div class="p-form-group">
                    <label for="phone_number" class="p-form-label">Nomor Telepon</label>
                    <input type="text" id="phone_number" name="phone_number" class="p-form-input" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required>
                </div>
                
                <button type="submit" class="p-btn-submit">SAVE CHANGES</button>
            </form>

        <?php elseif ($tab === 'address'): ?>
            <h2 class="profile-title">SHIPPING ADDRESS</h2>
            <form action="profile.php?tab=address" method="POST" class="profile-form" style="max-width: 100%;">
                <input type="hidden" name="action" value="update_address">
                
                <div class="p-form-group">
                    <label for="address" class="p-form-label">Alamat Lengkap Pengiriman</label>
                    <textarea id="address" name="address" class="p-form-input p-form-textarea" placeholder="NAMA JALAN, RT/RW, KELURAHAN, KECAMATAN, KOTA, KODE POS..." required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="p-btn-submit">UPDATE ADDRESS</button>
            </form>

        <?php elseif ($tab === 'orders'): ?>
            <h2 class="profile-title">ORDER HISTORY</h2>
            <?php
                // Fetch orders menggunakan Prepared Statement
                try {
                    $stmt_orders = $pdo->prepare("SELECT order_number, item_name, status, tracking_number, created_at FROM orders WHERE user_id = :id ORDER BY created_at DESC");
                    $stmt_orders->execute(['id' => $user_id]);
                    $orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    $orders = []; // Fallback jika tabel orders tidak ada, hindari crash fatal
                    echo "<div class='profile-alert error'>Error fetching orders: " . htmlspecialchars($e->getMessage()) . "</div>";
                }
            ?>
            
            <?php if (empty($orders)): ?>
                <div class="no-data">NO ORDERS FOUND.</div>
            <?php else: ?>
                <div class="orders-table-wrapper">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Tracking Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                    <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                                    <td>
                                        <span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($order['tracking_number'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php elseif ($tab === 'security'): ?>
            <h2 class="profile-title">SECURITY SETTINGS</h2>
            <form action="profile.php?tab=security" method="POST" class="profile-form">
                <input type="hidden" name="action" value="change_password">
                
                <div class="p-form-group">
                    <label for="current_password" class="p-form-label">Current Password</label>
                    <input type="password" id="current_password" name="current_password" class="p-form-input" required>
                </div>
                
                <div class="p-form-group">
                    <label for="new_password" class="p-form-label">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="p-form-input" required>
                </div>
                
                <div class="p-form-group">
                    <label for="confirm_password" class="p-form-label">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="p-form-input" required>
                </div>
                
                <button type="submit" class="p-btn-submit">CHANGE PASSWORD</button>
            </form>
            
        <?php else: ?>
            <h2 class="profile-title">PAGE NOT FOUND</h2>
            <p>The requested profile tab does not exist.</p>
        <?php endif; ?>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
