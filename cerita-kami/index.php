<?php
/**
 * index.php — Halaman Cerita Kami
 * =================================
 * Letaknya di folder 'cerita-kami/', sehingga $base_path = '../'
 * (naik satu level ke atas untuk link navigasi ke root).
 */

$base_path  = '../';
$page_title = 'Cerita Kami | Southern Streetwear';

// Naik satu level untuk menemukan folder 'includes'
include '../includes/header.php';

// Pastikan $BASE_URL tersedia
if (!isset($BASE_URL)) {
    $BASE_URL = '/';
}
?>

<!-- ============================================================
     KONTEN UTAMA — HALAMAN CERITA KAMI
     ============================================================ -->
<main class="page-main">

    <!-- ── STORY HERO SECTION ────────────────────────────────── -->
    <!-- Dua kolom: teks di kiri, foto workshop di kanan -->
    <section class="story-hero">
        <div class="story-hero-inner">

            <!-- Kolom Teks -->
            <div class="story-text-col">
                <h1 class="story-title">CERITA<br>KAMI.</h1>
                <div class="story-divider"></div>
                <p class="story-description">
                    Southern lahir dari kultur jalanan dan semangat buat tampil beda.
                    Kita percaya kalau pakaian itu bukan cuma soal nutupin badan,
                    tapi cara lo berekspresi. Semua koleksi kita dibuat dengan bahan
                    premium dan potongan yang dirancang khusus buat gaya hidup urban
                    yang aktif.
                </p>
            </div>

            <!-- Kolom Gambar Workshop Utama -->
            <div class="story-image-col">
                <div class="image-frame story-image-frame">
                    <?php /* Taruh file 'gambar-cerita-1.jpg' di folder cerita-kami/ */ ?>
                    <img src="<?php echo $BASE_URL; ?>cerita-kami/gambar-cerita-1.jpg"
                         alt="Workshop Southern - pengrajin bekerja di bawah lampu industri dengan latar beton">
                </div>
            </div>

        </div>
    </section>

    <!-- ── PROCESS SECTION ───────────────────────────────────── -->
    <!-- Tiga kolom: manifesto di kiri, 2 detail foto di kanan -->
    <section class="process-section">
        <div class="process-inner">

            <!-- Kolom Manifesto (teks) -->
            <div class="manifesto-col">
                <p class="manifesto-label">MANIFESTO</p>
                <h2 class="manifesto-title">BEYOND THE FABRIC. IT'S THE CULTURE.</h2>
            </div>

            <!-- Detail 1: Materiality -->
            <div class="process-detail-col">
                <div class="image-frame">
                    <?php /* Taruh file 'gambar-cerita-2.jpg' di folder cerita-kami/ */ ?>
                    <img src="<?php echo $BASE_URL; ?>cerita-kami/gambar-cerita-2.jpg"
                         alt="Close-up tekstur kain katun berat premium Southern dengan jahitan presisi">
                </div>
                <h3 class="detail-label">01 / MATERIALITY</h3>
                <p class="detail-description">
                    Setiap serat dipilih untuk daya tahan dan kenyamanan maksimal
                    di iklim urban tropis.
                </p>
            </div>

            <!-- Detail 2: Silhouette -->
            <div class="process-detail-col">
                <div class="image-frame">
                    <?php /* Taruh file 'gambar-cerita-3.jpg' di folder cerita-kami/ */ ?>
                    <img src="<?php echo $BASE_URL; ?>cerita-kami/gambar-cerita-3.jpg"
                         alt="Ruang arsitektur urban Jakarta - inspirasi potongan Southern">
                </div>
                <h3 class="detail-label">02 / SILHOUETTE</h3>
                <p class="detail-description">
                    Potongan boxy dan oversized yang mendefinisikan ulang proporsi modern.
                </p>
            </div>

        </div>
    </section>

    <!-- ── FULL BLEED SECTION ────────────────────────────────── -->
    <!-- Foto lebar penuh dengan teks overlay "AUTHENTICITY" -->
    <section class="fullbleed-section">
        <?php /* Taruh file 'gambar-cerita-4.jpg' di folder cerita-kami/ */ ?>
        <img class="fullbleed-image"
             src="<?php echo $BASE_URL; ?>cerita-kami/gambar-cerita-4.jpg"
             alt="Lantai produksi Southern yang luas dengan mesin jahit industri berjajar rapi">
        <div class="fullbleed-overlay">
            <span class="fullbleed-text">AUTHENTICITY</span>
        </div>
    </section>

</main>
<!-- ============================================================
     AKHIR KONTEN UTAMA CERITA KAMI
     ============================================================ -->

<?php
include '../includes/footer.php';
?>
