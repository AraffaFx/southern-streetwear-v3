<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
// DYNAMIC BASE URL CONFIGURATION - AUTO-DETECT LOCALHOST/VERCEL
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
    $BASE_URL = $protocol . '://' . $host . '/southern/';
} else {
    // Di Vercel/Production - pakai root domain
    $BASE_URL = '/';
}

// Pastikan BASE_URL selalu berakhir dengan slash
$BASE_URL = rtrim($BASE_URL, '/') . '/';

/**
 * header.php — Bagian "kepala" website Southern
 * ================================================
 * File ini di-include di bagian PALING ATAS setiap halaman.
 * Berisi: tag HTML pembuka, <head> (font, CSS), shared CSS via <style>,
 * link ke style.css halaman, dan elemen <nav> navigasi.
 *
 * CATATAN PENTING (untuk dosen):
 * Website ini 100% menggunakan HTML, PHP, Vanilla CSS, dan Vanilla JS.
 * Semua layout dibuat dengan CSS Flexbox dan Grid murni.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php // Judul halaman dikirim dari file pemanggil via variabel $page_title ?>
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Southern | Urban Streetwear Culture'; ?></title>

    <!-- Google Fonts: Inter (teks biasa) dan Syne (heading/brand) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Syne:wght@100..900&display=swap" rel="stylesheet">
    <!-- Material Symbols: library ikon dari Google (search, shopping_bag, menu, dsb.) -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <style>
        /*
         * ============================================================
         * SHARED CSS — BERLAKU DI SEMUA HALAMAN
         * Ditulis di sini agar tidak perlu diulang di setiap style.css.
         * File ini di-include oleh header.php yang ada di setiap halaman.
         * ============================================================
         */

        /* -------------------------------------------------------
           1. CSS CUSTOM PROPERTIES (Variabel Warna & Spacing)
           Cara kerja: Mendefinisikan "token desain" yang dipakai
           ulang di seluruh CSS. Ubah satu nilai di sini = berubah
           di semua tempat yang memakainya.
           ------------------------------------------------------- */
        :root {
            /* --- Palet Warna Utama --- */
            --warna-hitam:          #000000;  /* Warna primary brand */
            --warna-putih:          #ffffff;
            --warna-latar:          #f9f9f9;  /* Abu-abu sangat terang */
            --warna-teks-utama:     #1a1c1c;  /* Hampir hitam untuk teks body */
            --warna-teks-sekunder:  #4c4546;  /* Abu-abu untuk teks pendukung */
            --warna-garis:          #e2e2e2;  /* Border tipis */

            /* --- Font --- */
            --font-heading: 'Syne', sans-serif;     /* Untuk judul, brand name */
            --font-body:    'Inter', system-ui, -apple-system, sans-serif; /* Untuk paragraf, label */

            /* --- Ukuran Teks --- */
            --ukuran-display-xl:    120px;  /* Judul besar sekali (desktop) */
            --ukuran-display-lg:     80px;  /* Judul besar (desktop) */
            --ukuran-headline:       48px;  /* Headline desktop */
            --ukuran-headline-mob:   32px;  /* Headline mobile */
            --ukuran-label:          12px;  /* Label caps kecil */
            --ukuran-body-lg:        18px;
            --ukuran-body-md:        16px;
            --ukuran-caption:        14px;

            /* --- Spacing Layout --- */
            --margin-desktop:   40px;   /* Padding kiri-kanan di layar lebar */
            --margin-mobile:    20px;   /* Padding kiri-kanan di layar kecil */
            --section-padding:  80px;   /* Padding atas-bawah setiap section */
            --grid-gutter:      24px;   /* Jarak antar kolom grid */
            --tinggi-navbar:    80px;   /* Tinggi navigasi */
        }

        /* -------------------------------------------------------
           2. CSS RESET — Normalisasi tampilan antar browser
           Setiap browser punya default CSS yang berbeda-beda.
           Reset ini menyamakan semuanya dari titik nol.
           ------------------------------------------------------- */
        *, *::before, *::after {
            box-sizing: border-box; /* Padding & border masuk ke dalam ukuran elemen */
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth; /* Scroll halaman dengan animasi mulus */
        }

        body {
            /* Gunakan font Inter sebagai default untuk semua teks */
            font-family: var(--font-body);
            font-size: var(--ukuran-body-md);
            line-height: 1.6;
            color: var(--warna-teks-utama);
            background-color: var(--warna-latar);
            /* Menghaluskan rendering font di layar HiDPI/Retina */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Semua link tidak bergaris bawah secara default */
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Gambar tidak boleh melebihi lebar kontainernya */
        img {
            display: block;
            max-width: 100%;
        }

        /* -------------------------------------------------------
           3. MATERIAL SYMBOLS (Library Ikon Google)
           WAJIB override font-family dengan !important karena
           library ini butuh font khusus untuk menampilkan ikon.
           ------------------------------------------------------- */
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
            line-height: 1;
            display: inline-block;
            user-select: none;     /* Ikon tidak bisa diseleksi seperti teks */
            cursor: inherit;       /* Ikuti cursor parent (pointer jika parent punya cursor:pointer) */
        }

        /* -------------------------------------------------------
           4. NAVIGASI UTAMA (.main-navbar)
           Navigasi menempel di bagian atas layar (fixed) dan
           selalu tampil di semua halaman (karena ada di header.php).
           ------------------------------------------------------- */
        .main-navbar {
            position: fixed;          /* Menempel di atas, tidak ikut scroll */
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;             /* Di atas semua konten lain */
            height: var(--tinggi-navbar);
            background-color: var(--warna-latar);
            border-bottom: 1px solid var(--warna-hitam);
        }

        /* Wadah dalam navbar: mengatur layout tiga kolom (logo | links | icons) */
        .navbar-inner {
            display: flex;
            justify-content: space-between; /* Sebar rata kiri-kanan */
            align-items: center;            /* Rata tengah secara vertikal */
            height: 100%;
            padding: 0 var(--margin-mobile);
        }

        /* Nama brand / logo teks "Southern" */
        .navbar-brand {
            font-family: var(--font-heading);
            font-size: var(--ukuran-headline-mob); /* 32px di mobile */
            font-weight: 800;
            letter-spacing: -0.02em;
            text-transform: uppercase;
            color: var(--warna-hitam);
            text-decoration: none;
        }

        /* Kelompok link navigasi: disembunyikan di mobile, tampil di tablet/desktop */
        .navbar-links {
            display: none; /* Tersembunyi di mobile */
        }

        /* Link navigasi individual (Home, Cerita Kami, Kontak) */
        .navbar-link {
            font-family: var(--font-body);
            font-size: var(--ukuran-label);
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--warna-teks-sekunder);
            text-decoration: none;
            transition: color 0.2s ease; /* Efek warna berubah halus saat hover */
        }

        .navbar-link:hover {
            color: var(--warna-hitam);
        }

        /* Class tambahan untuk link yang sedang aktif (halaman yang sedang dibuka) */
        /* Akan di-set ulang di style.css masing-masing halaman */
        .navbar-link--aktif {
            color: var(--warna-hitam);
            text-decoration: underline;
            text-decoration-thickness: 2px;
            text-underline-offset: 4px;
        }

        /* Kelompok ikon kanan (search, cart, hamburger) */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .navbar-actions .material-symbols-outlined {
            color: var(--warna-hitam);
            cursor: pointer;
        }

        /* Tombol hamburger menu (hanya tampil di mobile) */
        .btn-hamburger {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            color: var(--warna-hitam);
        }

        /* -------------------------------------------------------
           5. FOOTER UTAMA (.main-footer)
           Footer standar yang tampil di halaman Home dan Cerita Kami.
           (Halaman Kontak punya footer sosial media sendiri)
           ------------------------------------------------------- */
        .main-footer {
            width: 100%;
            border-top: 4px solid var(--warna-hitam);
            background-color: var(--warna-latar);
        }

        /* Wadah dalam footer: layout tiga kolom */
        .footer-inner {
            display: flex;
            flex-direction: column;     /* Susun vertikal di mobile */
            align-items: center;
            gap: 32px;
            padding: var(--section-padding) var(--margin-mobile);
        }

        /* Nama brand di footer */
        .footer-brand {
            font-family: var(--font-heading);
            font-size: var(--ukuran-headline);
            font-weight: 800;
            letter-spacing: -0.02em;
            text-transform: uppercase;
            color: var(--warna-hitam);
        }

        /* Kelompok link footer */
        .footer-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        /* Link individual footer */
        .footer-link {
            font-family: var(--font-body);
            font-size: var(--ukuran-label);
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--warna-teks-sekunder);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .footer-link:hover {
            text-decoration: underline;
        }

        .footer-link:active {
            transform: scale(0.95);
        }

        /* Teks hak cipta */
        .footer-copyright {
            font-family: var(--font-body);
            font-size: var(--ukuran-label);
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--warna-teks-sekunder);
            opacity: 0.6;
            text-align: center;
        }

        /* -------------------------------------------------------
           6. MEDIA QUERY: Tablet & Desktop (lebar min. 768px)
           ------------------------------------------------------- */
        @media (min-width: 768px) {
            /* Navbar: tambah padding dan ukuran brand */
            .navbar-inner {
                padding: 0 var(--margin-desktop);
            }

            .navbar-brand {
                font-size: var(--ukuran-headline); /* 48px di desktop */
            }

            /* Tampilkan link navigasi di desktop */
            .navbar-links {
                display: flex;
                align-items: center;
                gap: 32px;
            }

            /* Sembunyikan tombol hamburger di desktop */
            .btn-hamburger {
                display: none;
            }

            /* Footer: susun horizontal di desktop */
            .footer-inner {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: var(--section-padding) var(--margin-desktop);
            }

            .footer-links {
                flex-direction: row;
                gap: 48px;
            }
        }

        /* -------------------------------------------------------
           INLINE NAVBAR SEARCH BAR
           ------------------------------------------------------- */
        /* Navbar actions: flex container yang menampung ikon + search bar */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }

        /* Search bar wrapper — tersembunyi secara default */
        .navbar-search-bar {
            display: flex;
            align-items: center;
            gap: 0;
            width: 0;
            overflow: hidden;
            opacity: 0;
            transition: width 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.25s ease;
            border: 2px solid transparent;
            background: transparent;
        }

        /* Saat aktif: expand menjadi terlihat */
        .navbar-search-bar.search-active {
            width: 260px;
            opacity: 1;
            border: 2px solid var(--warna-hitam);
            background: var(--warna-putih);
        }

        /* Input di dalam search bar */
        .navbar-search-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--warna-hitam);
            padding: 8px 12px;
            border-radius: 0;
            width: 100%;
        }
        .navbar-search-input::placeholder {
            color: #aaa;
            font-weight: 500;
        }

        /* Tombol batal (X) di dalam search bar */
        .btn-search-cancel {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px 8px;
            color: var(--warna-hitam);
            border-left: 1px solid var(--warna-hitam);
            border-radius: 0;
        }
        .btn-search-cancel .material-symbols-outlined {
            font-size: 18px;
        }
        /* Ikon search asli (disembunyikan saat search aktif via JS) */
        #btn-search {
            cursor: pointer;
            transition: opacity 0.2s;
            position: relative;
            z-index: 1;
        }
        #btn-search.search-icon-hidden {
            display: none;
        }
        /* Ikon cart - pastikan bisa diklik */
        #btn-cart {
            cursor: pointer;
            position: relative;
            z-index: 1;
        }
        /* Semua ikon di navbar-actions harus selalu di atas */
        .navbar-actions .material-symbols-outlined {
            cursor: pointer;
            user-select: none;
            -webkit-user-select: none;
        }
        

        /* Cart Drawer */
        .cart-drawer-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9998;
            opacity: 0; pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .cart-drawer-overlay.active {
            opacity: 1; pointer-events: auto;
        }
        .cart-drawer {
            position: fixed; top: 0; right: 0;
            width: 100%; max-width: 400px; height: 100%;
            background-color: var(--warna-putih);
            border-left: 2px solid var(--warna-hitam);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex; flex-direction: column;
            font-family: var(--font-body); /* Receipt style */
        }
        .cart-drawer.active {
            transform: translateX(0);
        }
        .cart-header {
            padding: 24px;
            border-bottom: 2px dashed var(--warna-hitam); /* Receipt look */
            display: flex; justify-content: space-between; align-items: center;
        }
        .cart-title {
            font-family: var(--font-heading);
            font-size: 24px; font-weight: 800; text-transform: uppercase;
        }
        .btn-close-cart {
            background: none; border: none; cursor: pointer;
        }
        .cart-items {
            flex: 1; overflow-y: auto;
            padding: 24px;
            display: flex; flex-direction: column; gap: 24px;
        }
        .cart-item {
            display: flex; gap: 16px;
            border-bottom: 1px dashed var(--warna-hitam);
            padding-bottom: 24px;
        }
        .cart-item-img {
            width: 80px; height: 100px;
            object-fit: cover; border: 2px solid var(--warna-hitam);
        }
        .cart-item-info {
            flex: 1; display: flex; flex-direction: column; gap: 8px;
        }
        .cart-item-title { font-weight: 700; font-size: 14px; text-transform: uppercase; }
        .cart-item-price { font-weight: 400; font-size: 14px; }
        .btn-remove-item {
            align-self: flex-start;
            background: none; border: none;
            color: #d90000; font-weight: 700; font-size: 12px; cursor: pointer;
            text-transform: uppercase; margin-top: auto;
        }
        .cart-footer {
            padding: 24px;
            border-top: 2px solid var(--warna-hitam);
        }
        .cart-subtotal {
            display: flex; justify-content: space-between;
            font-weight: 800; font-size: 18px; margin-bottom: 24px;
            text-transform: uppercase;
        }
        .btn-checkout-drawer {
            display: block; width: 100%; text-align: center;
            background-color: var(--warna-hitam); color: var(--warna-putih);
            padding: 16px; font-weight: 800; font-size: 16px;
            text-transform: uppercase; letter-spacing: 0.1em;
            text-decoration: none; border: 2px solid var(--warna-hitam);
            transition: all 0.2s;
            border-radius: 0;
        }
        .btn-checkout-drawer:hover {
            background-color: var(--warna-putih); color: var(--warna-hitam);
        }
    </style>

    <?php
    /**
     * Link ke CSS halaman yang bersangkutan.
     * Gunakan path relatif agar tetap bekerja di localhost dan Vercel.
     * Contoh: /            → ./style.css
     *         /cerita-kami/ → ./style.css (file CSS di folder yang sama)
     */
    ?>
    <link rel="stylesheet" href="<?php echo $BASE_URL; ?>style.css">
</head>

<?php
/**
 * Tag <body> dibuka di sini.
 * Tidak ada class Tailwind — semua styling lewat vanilla CSS di atas dan style.css.
 */
?>
<body>

<!-- ============================================================
     NAVIGASI UTAMA — Tampil di semua halaman
     $base_path digunakan untuk link agar benar dari subfolder manapun.
     Contoh:
       - Dari root:        $base_path = './'    → ./cerita-kami/
       - Dari cerita-kami: $base_path = '../'   → ../cerita-kami/
     ============================================================ -->
<nav class="main-navbar" id="main-nav">
    <div class="navbar-inner">

        <!-- Logo teks brand -->
        <a href="<?php echo $base_path; ?>" class="navbar-brand" id="nav-logo">
            Southern
        </a>

        <!-- Link navigasi (tampil di tablet/desktop) -->
        <div class="navbar-links">
            <a href="<?php echo $base_path; ?>"
               class="navbar-link" id="nav-home">Home</a>

            <a href="<?php echo $base_path; ?>cerita-kami/"
               class="navbar-link" id="nav-cerita-kami">Cerita Kami</a>

            <a href="<?php echo $base_path; ?>kontak/"
               class="navbar-link" id="nav-kontak">Kontak</a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo $base_path; ?>profile.php"
                   class="navbar-link" id="nav-profile">Profile</a>
                <a href="<?php echo $base_path; ?>logout.php"
                   class="navbar-link" id="nav-logout">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base_path; ?>login.php"
                   class="navbar-link" id="nav-login">Login</a>
            <?php endif; ?>
        </div>

        <!-- Ikon kanan navbar -->
        <div class="navbar-actions">
            <!-- Ikon Search asli (disembunyikan saat search bar aktif) -->
            <span class="material-symbols-outlined" id="btn-search" title="Cari">search</span>

            <!-- Inline search bar (tersembunyi secara default) -->
            <form action="<?php echo $base_path; ?>search.php" method="GET" class="navbar-search-bar" id="navbar-search-bar" role="search">
                <input
                    type="search"
                    name="q"
                    id="navbar-search-input"
                    class="navbar-search-input"
                    placeholder="CARI GEAR..."
                    autocomplete="off"
                    required>
                <button type="button" class="btn-search-cancel" id="btn-search-cancel" title="Batal">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </form>

            <span class="material-symbols-outlined" id="btn-cart" title="Keranjang">shopping_bag</span>
            <button class="btn-hamburger" id="btn-mobile-menu" title="Menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</nav>
<!-- ============================================================
     AKHIR NAVIGASI UTAMA
     ============================================================ -->

<!-- ============================================================
     CART DRAWER (SIDEBAR) — tetap ada
     ============================================================ -->
<div class="cart-drawer-overlay" id="cart-drawer-overlay"></div>
<div class="cart-drawer" id="cart-drawer">
    <div class="cart-header">
        <h2 class="cart-title">MANIFEST // BAG</h2>
        <button class="btn-close-cart" id="btn-close-cart" title="Tutup Keranjang">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="cart-items" id="cart-items-container">
        <!-- Items diisi via JS/AJAX -->
    </div>
    <div class="cart-footer">
        <div class="cart-subtotal">
            <span>SUBTOTAL</span>
            <span id="cart-subtotal-price">Rp 0</span>
        </div>
        <a href="<?php echo $base_path; ?>checkout.php" class="btn-checkout-drawer">PROCEED TO CHECKOUT</a>
    </div>
</div>
