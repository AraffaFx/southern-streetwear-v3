/*
 * script.js - Halaman Cerita Kami
 * File JavaScript khusus untuk halaman cerita-kami/index.php.
 * Berisi dua micro-interaction:
 * 1. Efek press/klik pada link dan tombol
 * 2. Animasi reveal gambar saat di-scroll
 */

// -------------------------------------------------------
// Micro-interaction: Efek "tertekan" saat klik link/tombol
// -------------------------------------------------------
document.querySelectorAll('a, button').forEach(el => {
    // Saat tombol mouse ditekan: perkecil elemen sedikit
    el.addEventListener('mousedown', () => {
        el.style.transform = 'scale(0.95)';
    });
    // Saat tombol mouse dilepas: kembalikan ukuran normal
    el.addEventListener('mouseup', () => {
        el.style.transform = 'scale(1)';
    });
    // Saat mouse keluar dari elemen: kembalikan ukuran normal
    el.addEventListener('mouseleave', () => {
        el.style.transform = 'scale(1)';
    });
});

// -------------------------------------------------------
// Animasi Scroll Reveal: Gambar muncul saat di-scroll
// -------------------------------------------------------

// Opsi observer: trigger saat 10% elemen terlihat di layar
const observerOptions = {
    threshold: 0.1
};

// Buat IntersectionObserver untuk memantau elemen
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Elemen terlihat: hapus kelas awal, tambah kelas terlihat
            entry.target.classList.remove('opacity-0', 'translate-y-10');
            entry.target.classList.add('opacity-100', 'translate-y-0');
        }
    });
}, observerOptions);

// Sembunyikan semua elemen .image-frame saat pertama kali dimuat dengan kelas utilitas
document.querySelectorAll('.image-frame').forEach(el => {
    el.classList.add('transition-all', 'opacity-0', 'translate-y-10');
    observer.observe(el);
});
