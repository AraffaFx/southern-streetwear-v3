/*
 * script.js — Halaman Home (Root)
 * =================================
 * Vanilla JavaScript murni — tidak ada library/framework apapun.
 * Berisi animasi scroll reveal untuk card produk.
 */

/* -------------------------------------------------------
   ANIMASI SCROLL REVEAL — Card Produk
   Menggunakan IntersectionObserver API (bawaan browser modern)
   untuk mendeteksi kapan card masuk ke area tampilan layar.
   ------------------------------------------------------- */

// Opsi observer: animasi trigger saat 10% elemen terlihat
const opsiObserver = {
    threshold: 0.1
};

// Buat observer yang memantau elemen
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Elemen masuk viewport → tambah class 'muncul'
            // Class 'muncul' didefinisikan di style.css untuk mengubah opacity & transform
            entry.target.classList.add('muncul');
        }
    });
}, opsiObserver);

// Daftarkan semua card produk ke observer
document.querySelectorAll('.product-card').forEach(card => {
    observer.observe(card);
});
