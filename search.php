<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi.php';

$query = trim($_GET['q'] ?? '');
$results = [];
$error = '';

if ($query !== '') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE name ILIKE :query ORDER BY id ASC");
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Pencarian gagal: " . $e->getMessage();
    }
}

$base_path = './';
$page_title = 'Search: ' . htmlspecialchars($query) . ' | Southern';
include 'includes/header.php';
?>

<style>
.search-page-wrapper {
    padding: var(--section-padding) var(--margin-mobile);
    margin-top: var(--tinggi-navbar);
    min-height: 70vh;
}
@media (min-width: 768px) {
    .search-page-wrapper { padding: var(--section-padding) var(--margin-desktop); }
}

.search-page-header {
    border-bottom: 4px solid var(--warna-hitam);
    padding-bottom: 24px;
    margin-bottom: 48px;
}

.search-query-label {
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-label);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--warna-teks-sekunder);
    margin-bottom: 8px;
}

.search-query-title {
    font-family: 'Syne', sans-serif;
    font-size: clamp(32px, 6vw, 64px);
    font-weight: 800;
    text-transform: uppercase;
    line-height: 1;
}

.search-result-count {
    font-family: 'Inter', sans-serif;
    font-size: var(--ukuran-body-md);
    margin-top: 16px;
    font-weight: 400;
}

.search-no-result {
    text-align: center;
    padding: 80px 0;
    font-family: 'Syne', sans-serif;
    font-size: 48px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    border: 2px dashed var(--warna-hitam);
}

.search-catalog-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--grid-gutter);
}
@media (min-width: 768px) {
    .search-catalog-grid { grid-template-columns: repeat(4, 1fr); }
}

.search-error {
    padding: 16px;
    border: 2px solid #cc0000;
    background: #ffeeee;
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    color: #cc0000;
    margin-bottom: 24px;
}
</style>

<div class="search-page-wrapper">
    <div class="search-page-header">
        <p class="search-query-label">Hasil Pencarian Untuk:</p>
        <h1 class="search-query-title">"<?php echo htmlspecialchars($query); ?>"</h1>
        <?php if ($query && !$error): ?>
            <p class="search-result-count"><?php echo count($results); ?> produk ditemukan.</p>
        <?php endif; ?>
    </div>

    <?php if ($error): ?>
        <div class="search-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($query === ''): ?>
        <div class="search-no-result">MASUKKAN KATA KUNCI PENCARIAN.</div>
    <?php elseif (empty($results) && !$error): ?>
        <div class="search-no-result">TIDAK ADA HASIL UNTUK "<?php echo htmlspecialchars($query); ?>"</div>
    <?php else: ?>
        <div class="search-catalog-grid">
            <?php foreach ($results as $product): ?>
                <div class="product-card">
                    <div class="product-image-wrapper">
                        <img class="product-image"
                             src="<?php echo htmlspecialchars($product['image'] ?? 'gambar-katalog-1.jpg'); ?>"
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-detail"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                            <button class="btn-checkout btn-add-to-bag"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-image="<?php echo htmlspecialchars($product['image'] ?? ''); ?>"
                                onclick="addToCart(this.dataset.id, this.dataset.name, this.dataset.price, this.dataset.image)">
                                ADD TO BAG
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn-login-price">LOGIN UNTUK MELIHAT HARGA</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
