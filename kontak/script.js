/*
 * script.js - Halaman Kontak
 * File JavaScript khusus untuk halaman kontak/index.php.
 * Berisi interaksi efek kursor dan manajemen mobile menu.
 */

// -------------------------------------------------------
// 1. Efek Cahaya Kursor (Cursor Glow)
// -------------------------------------------------------
const glow = document.getElementById('cursor-glow');

if (glow) {
    document.addEventListener('mousemove', (e) => {
        glow.style.opacity = '1';
        // Mengurangi 300px karena lebar/tinggi elemen glow adalah 600px
        // agar posisinya tepat di tengah kursor
        glow.style.transform = `translate(${e.clientX - 300}px, ${e.clientY - 300}px)`;
    });

    document.addEventListener('mouseleave', () => {
        glow.style.opacity = '0';
    });
}

// -------------------------------------------------------
// 2. Hover Background pada Judul "SOUTHERN"
// -------------------------------------------------------
const title = document.getElementById('kontak-brand-title');
if (title) {
    title.addEventListener('mouseenter', () => {
        document.body.style.transition = 'background-color 0.5s ease';
    });
}

// -------------------------------------------------------
// 3. Toggle Mobile Navigation Overlay
// -------------------------------------------------------
const btnOpenNav = document.getElementById('btn-mobile-menu'); // Dari header.php
const btnCloseNav = document.getElementById('btn-close-mobile-nav');
const mobileNav = document.getElementById('mobile-nav');

if (btnOpenNav && mobileNav) {
    btnOpenNav.addEventListener('click', () => {
        // Hapus class 'hidden' untuk menampilkan menu
        mobileNav.classList.remove('hidden');
    });
}

if (btnCloseNav && mobileNav) {
    btnCloseNav.addEventListener('click', () => {
        // Tambahkan class 'hidden' untuk menyembunyikan menu
        mobileNav.classList.add('hidden');
    });
}
