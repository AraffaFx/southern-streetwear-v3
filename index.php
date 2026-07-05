<?php
session_start();
/**
 * index.php — Halaman Home (Root)
 * ================================
 * Halaman utama website Southern. Letaknya di ROOT.
 * $base_path = './' karena file ini ada di root.
 *
 * Struktur halaman:
 * 1. Konfigurasi variabel PHP
 * 2. include header.php → menampilkan <head> dan <nav>
 * 3. Konten utama: hero, katalog, lookbook
 * 4. include footer.php → menutup </body></html>
 */

// $base_path akan digantikan dengan $BASE_URL dari header.php
$base_path  = './';                              // Untuk kompatibilitas
$page_title = 'Southern | Urban Streetwear Culture';

include 'includes/header.php';

// Pastikan $BASE_URL tersedia
if (!isset($BASE_URL)) {
    $BASE_URL = '/';
}
?>

<!-- ============================================================
     KONTEN UTAMA — HALAMAN HOME
     ============================================================ -->

<!-- ── HERO SECTION ──────────────────────────────────────────── -->
<!-- Gambar penuh layar dengan judul brand di tengah -->
<header class="hero-section">

    <!-- Gambar latar belakang (full cover) -->
    <div class="hero-bg">
        <?php /* Taruh file 'gambar-hero.jpg' di folder root (/southern/) */ ?>
        <img class="hero-image"
             src="<?php echo $BASE_URL; ?>gambar-hero.jpg"
             alt="Model editorial Southern Streetwear di depan struktur beton brutalisme">
        <div class="hero-overlay"></div>
    </div>

    <!-- Teks judul di tengah layar -->
    <div class="hero-content">
        <h1 class="hero-title">Southern</h1>
        <p class="hero-subtitle">Kultur Streetwear Autentik.</p>
    </div>

    <!-- Ikon panah memantul di bawah, mengisyaratkan "scroll ke bawah" -->
    <div class="hero-scroll-indicator">
        <span class="material-symbols-outlined">expand_more</span>
    </div>

</header>

<!-- Garis pembatas tipis 1px hitam -->
<div class="editorial-divider"></div>

<!-- ── CATALOG SECTION ───────────────────────────────────────── -->
<!-- Grid 4 produk unggulan. Di mobile jadi 1-2 kolom. -->
<section class="catalog-section">

    <!-- Baris judul section dan label -->
    <div class="catalog-header">
        <h2 class="catalog-title">Katalog / 24</h2>
        <span class="catalog-label">EDISI TERBATAS &amp; KOLEKSI INTI</span>
    </div>

    <!-- Grid produk: 1 kolom (mobile) → 2 kolom (tablet) → 4 kolom (desktop) -->
    <div class="catalog-grid">

        <!-- Produk 1 -->
        <div class="product-card">
            <div class="product-image-wrapper">
                <?php /* Taruh file 'gambar-katalog-1.jpg' di folder root */ ?>
                <img class="product-image"
                     src="<?php echo $BASE_URL; ?>gambar-katalog-1.jpg"
                     alt="Heavyweight Canvas Jacket - Jaket kanvas premium boxy Southern">
            </div>
            <div class="product-info">
                <h3 class="product-name">Heavyweight Canvas Jacket</h3>
                <p class="product-detail">Material: 100% Kanvas Premium | Fit: Boxy Structured</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="product-price">Rp 850.000</div>
                    <button class="btn-checkout btn-add-to-bag"
                        data-id="1"
                        data-name="Heavyweight Canvas Jacket"
                        data-price="850000"
                        data-image="gambar-katalog-1.jpg"
                        onclick="addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.image)">
                        ADD TO BAG
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-login-price">LOGIN UNTUK MELIHAT HARGA</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Produk 2 -->
        <div class="product-card">
            <div class="product-image-wrapper">
                <?php /* Taruh file 'gambar-katalog-2.jpg' di folder root */ ?>
                <img class="product-image"
                     src="<?php echo $BASE_URL; ?>gambar-katalog-2.jpg"
                     alt="Signature Boxy Tee - Kaos katun oversize Southern">
            </div>
            <div class="product-info">
                <h3 class="product-name">Signature Boxy Tee</h3>
                <p class="product-detail">Material: 100% Katun Premium | Fit: Oversize</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="product-price">Rp 350.000</div>
                    <button class="btn-checkout btn-add-to-bag"
                        data-id="2"
                        data-name="Signature Boxy Tee"
                        data-price="350000"
                        data-image="gambar-katalog-2.jpg"
                        onclick="addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.image)">
                        ADD TO BAG
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-login-price">LOGIN UNTUK MELIHAT HARGA</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Produk 3 -->
        <div class="product-card">
            <div class="product-image-wrapper">
                <?php /* Taruh file 'gambar-katalog-3.jpg' di folder root */ ?>
                <img class="product-image"
                     src="<?php echo $BASE_URL; ?>gambar-katalog-3.jpg"
                     alt="Utility Work Pants - Celana drill wide-leg Southern">
            </div>
            <div class="product-info">
                <h3 class="product-name">Utility Work Pants</h3>
                <p class="product-detail">Material: Drill Katun Berat | Fit: Wide-Leg Relaxed</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="product-price">Rp 450.000</div>
                    <button class="btn-checkout btn-add-to-bag"
                        data-id="3"
                        data-name="Utility Work Pants"
                        data-price="450000"
                        data-image="gambar-katalog-3.jpg"
                        onclick="addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.image)">
                        ADD TO BAG
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-login-price">LOGIN UNTUK MELIHAT HARGA</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Produk 4 -->
        <div class="product-card">
            <div class="product-image-wrapper">
                <?php /* Taruh file 'gambar-katalog-4.jpg' di folder root */ ?>
                <img class="product-image"
                     src="<?php echo $BASE_URL; ?>gambar-katalog-4.jpg"
                     alt="Premium Cotton Hoodie - Hoodie 450GSM oversize Southern">
            </div>
            <div class="product-info">
                <h3 class="product-name">Premium Cotton Hoodie</h3>
                <p class="product-detail">Material: 450GSM Fleece | Fit: True to Size Oversize</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="product-price">Rp 650.000</div>
                    <button class="btn-checkout btn-add-to-bag"
                        data-id="4"
                        data-name="Premium Cotton Hoodie"
                        data-price="650000"
                        data-image="gambar-katalog-4.jpg"
                        onclick="addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.image)">
                        ADD TO BAG
                    </button>
                <?php else: ?>
                    <a href="login.php" class="btn-login-price">LOGIN UNTUK MELIHAT HARGA</a>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /.catalog-grid -->
</section>

<!-- ── LOOKBOOK SECTION ──────────────────────────────────────── -->
<!-- Banner hitam dengan teks dan gambar editorial -->
<section class="lookbook-section">
    <div class="lookbook-inner">

        <!-- Kolom teks (kiri di desktop) -->
        <div class="lookbook-text">
            <h2 class="lookbook-title">Mendefinisikan Ulang Arsitektur Tubuh</h2>
            <p class="lookbook-description">
                Koleksi kami bukan sekadar pakaian ini adalah eksplorasi bentuk,
                fungsi, dan identitas urban. Dibuat dengan presisi di Bandung untuk dunia.
            </p>
            <span class="lookbook-cta">LIHAT LOOKBOOK 2024</span>
        </div>

        <!-- Kolom gambar (kanan di desktop) -->
        <div class="lookbook-image-wrapper">
            <?php /* Taruh file 'gambar-lookbook.jpg' di folder root */ ?>
            <img src="<?php echo $BASE_URL; ?>gambar-lookbook.jpg"
                 alt="Suasana urban malam hari dengan model di kejauhan - lookbook Southern">
        </div>

    </div>
</section>

<!-- ============================================================
     AKHIR KONTEN UTAMA HOME
     ============================================================ -->

<?php
// Memanggil footer — akan menampilkan <footer> dan menutup </body></html>
include 'includes/footer.php';
?>
