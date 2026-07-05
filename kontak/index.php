<?php
/**
 * index.php - Halaman Kontak
 * ===========================
 * Halaman kontak Southern dengan desain tema GELAP (hitam).
 * Letaknya di folder 'kontak/', sehingga $base_path = '../'
 */

$base_path = '../';
$page_title = 'Kontak | Southern Streetwear';
$body_class = 'kontak-body';

include '../includes/header.php';
?>

<!-- ============================================================
     KONTEN UTAMA HALAMAN KONTAK
     Tema: Hitam total, tipografi besar, interaktif
     ============================================================ -->

<!-- Mobile Navigation Overlay (muncul saat tombol Menu diklik) -->
<div class="mobile-nav-overlay hidden" id="mobile-nav">
    <button class="btn-close-nav" id="btn-close-mobile-nav">Close</button>
    
    <a class="mobile-nav-link" href="<?php echo $base_path; ?>" id="mobile-nav-home">Home</a>
    <a class="mobile-nav-link" href="<?php echo $base_path; ?>cerita-kami/" id="mobile-nav-cerita-kami">Cerita Kami</a>
    <a class="mobile-nav-link mobile-nav-link--aktif" href="<?php echo $base_path; ?>kontak/" id="mobile-nav-kontak">Kontak</a>
</div>

<!-- Canvas Konten Utama -->
<main class="kontak-main">

    <!-- Judul Brand Besar + Pesan Kontak -->
    <section class="kontak-hero">

        <!-- Judul besar "SOUTHERN" - terang saat di-hover -->
        <h1 class="kontak-brand-title" id="kontak-brand-title">SOUTHERN</h1>

        <!-- Pesan dan Alamat Email Kontak -->
        <div class="kontak-message-box">
            <p class="kontak-message" id="contact-text">
                Mau ngobrol, tanya-tanya, atau ngajak kolaborasi? Langsung aja sapa kita di:
                <span class="kontak-email-wrapper">
                    <a class="kontak-email" href="mailto:info@southern.co" id="kontak-email">
                        info@southern.co
                    </a>
                </span>
            </p>
        </div>

    </section>

    <!-- Footer Khusus Kontak: Link Media Sosial -->
    <footer class="kontak-social-footer" id="kontak-social-footer">
        <a class="social-link" href="https://instagram.com" id="kontak-instagram" target="_blank" rel="noopener noreferrer">
            Instagram
            <span class="social-link-underline"></span>
        </a>
        <a class="social-link" href="https://tiktok.com" id="kontak-tiktok" target="_blank" rel="noopener noreferrer">
            TikTok
            <span class="social-link-underline"></span>
        </a>
    </footer>

</main>

<!-- Elemen Efek Cahaya Kursor (digerakkan oleh script.js) -->
<div class="cursor-glow" id="cursor-glow"></div>

<?php
// Footer standar disembunyikan via CSS, tapi tetap di-include
include '../includes/footer.php';
?>
