<?php
session_start();

// Proteksi Halaman: Jika belum login, paksa kembali ke login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$base_path = './';
$page_title = 'Checkout | Southern';
include 'includes/header.php';
?>

<div class="checkout-wrapper">
    <div class="checkout-container">
        <div class="checkout-header">
            <h2 class="checkout-title">RINGKASAN PESANAN</h2>
            <p class="checkout-order-id">ORDER #<?php echo rand(10000, 99999); ?></p>
        </div>

        <div class="checkout-items">
            <!-- Item 1 -->
            <div class="checkout-item">
                <div class="item-info">
                    <span class="item-name">Heavyweight Canvas Jacket</span>
                    <span class="item-variant">Size: L / Color: Black</span>
                </div>
                <span class="item-price">Rp 850.000</span>
            </div>
            <!-- Item 2 -->
            <div class="checkout-item">
                <div class="item-info">
                    <span class="item-name">Signature Boxy Tee</span>
                    <span class="item-variant">Size: XL / Color: White</span>
                </div>
                <span class="item-price">Rp 350.000</span>
            </div>
        </div>

        <div class="checkout-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp 1.200.000</span>
            </div>
            <div class="summary-row">
                <span>Pengiriman</span>
                <span>Rp 50.000</span>
            </div>
            <div class="summary-row total-row">
                <span>TOTAL</span>
                <span>Rp 1.250.000</span>
            </div>
        </div>

        <div class="checkout-actions">
            <button class="btn-pay" onclick="alert('Simulasi pembayaran berhasil!')">BAYAR SEKARANG</button>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
