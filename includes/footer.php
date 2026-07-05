<?php
/**
 * footer.php — Bagian "kaki" website Southern
 * =============================================
 * File ini di-include di bagian PALING BAWAH setiap halaman.
 * Berisi: elemen <footer> HTML standar, pemanggilan script.js,
 * dan penutup tag </body></html>.
 */

// Pastikan $BASE_URL tersedia (jika belum ada dari header.php)
if (!isset($BASE_URL)) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        $BASE_URL = $protocol . '://' . $host . '/southern/';
    } else {
        $BASE_URL = '/';
    }
    $BASE_URL = rtrim($BASE_URL, '/') . '/';
}
?>

<!-- ============================================================
     FOOTER UTAMA — Tampil di semua halaman (kecuali Kontak)
     Halaman Kontak men-set display:none untuk .main-footer
     dan menggantinya dengan footer sosial media sendiri.
     ============================================================ -->
<footer class="main-footer" id="main-footer">
    <div class="footer-inner">

        <!-- Nama brand -->
        <div class="footer-brand" id="footer-brand">Southern</div>

        <!-- Link footer -->
        <nav class="footer-links">
            <a href="#" class="footer-link" id="footer-instagram">Instagram</a>
            <a href="#" class="footer-link" id="footer-privacy">Privacy Policy</a>
            <a href="#" class="footer-link" id="footer-terms">Terms of Service</a>
        </nav>

        <!-- Hak cipta — tahun otomatis dari PHP -->
        <div class="footer-copyright" id="footer-copyright">
            &copy; <?php echo date('Y'); ?> Southern Streetwear. All rights reserved.
        </div>

    </div>
</footer>
<!-- ============================================================
     AKHIR FOOTER UTAMA
     ============================================================ -->

<?php
/**
 * Memanggil JavaScript halaman yang bersangkutan.
 * Atribut 'defer' = script dijalankan setelah HTML selesai dimuat.
 * Gunakan path relatif agar tetap bekerja di localhost dan Vercel.
 */
?>
<script src="<?php echo $BASE_URL; ?>script.js" defer></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ----------------------------------------------------
    // INLINE NAVBAR SEARCH BAR LOGIC
    // ----------------------------------------------------
    const btnSearch         = document.getElementById('btn-search');
    const navbarSearchBar   = document.getElementById('navbar-search-bar');
    const navbarSearchInput = document.getElementById('navbar-search-input');
    const btnSearchCancel   = document.getElementById('btn-search-cancel');

    function openSearch() {
        if (!btnSearch || !navbarSearchBar) return;
        btnSearch.classList.add('search-icon-hidden');
        navbarSearchBar.classList.add('search-active');
        setTimeout(() => navbarSearchInput && navbarSearchInput.focus(), 50);
    }

    function closeSearch() {
        if (!btnSearch || !navbarSearchBar) return;
        navbarSearchBar.classList.remove('search-active');
        btnSearch.classList.remove('search-icon-hidden');
        if (navbarSearchInput) navbarSearchInput.value = '';
    }

    if (btnSearch)       btnSearch.addEventListener('click', openSearch);
    if (btnSearchCancel) btnSearchCancel.addEventListener('click', closeSearch);

    // Tutup search bar saat Escape ditekan
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSearch();
    });


    // ----------------------------------------------------
    // CART DRAWER LOGIC
    // ----------------------------------------------------
    const btnCart = document.getElementById('btn-cart');
    const btnCloseCart = document.getElementById('btn-close-cart');
    const cartDrawer = document.getElementById('cart-drawer');
    const cartOverlay = document.getElementById('cart-drawer-overlay');
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cartSubtotal = document.getElementById('cart-subtotal-price');

    function openCart() {
        if(cartDrawer && cartOverlay) {
            cartDrawer.classList.add('active');
            cartOverlay.classList.add('active');
            loadCartItems();
        }
    }

    function closeCart() {
        if(cartDrawer && cartOverlay) {
            cartDrawer.classList.remove('active');
            cartOverlay.classList.remove('active');
        }
    }

    if (btnCart && cartDrawer && cartOverlay && btnCloseCart) {
        btnCart.addEventListener('click', openCart);
        btnCloseCart.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);
    }

    function loadCartItems() {
        fetch('<?php echo $base_path; ?>ajax_cart.php?action=get')
            .then(res => res.json())
            .then(data => {
                renderCart(data);
            })
            .catch(err => console.error(err));
    }

    function renderCart(cartData) {
        if (!cartItemsContainer) return;
        
        cartItemsContainer.innerHTML = '';
        let total = 0;

        if (!cartData || Object.keys(cartData).length === 0) {
            cartItemsContainer.innerHTML = '<div style="text-align:center; padding: 24px; font-weight:700;">KERANJANG KOSONG</div>';
            cartSubtotal.innerText = 'Rp 0';
            return;
        }

        Object.keys(cartData).forEach(id => {
            const item = cartData[id];
            total += (item.price * item.quantity);
            
            const itemHTML = `
                <div class="cart-item">
                    <img src="${'<?php echo $BASE_URL; ?>' + item.image}" alt="${item.name}" class="cart-item-img">
                    <div class="cart-item-info">
                        <div class="cart-item-title">${item.name}</div>
                        <div class="cart-item-price">Rp ${parseInt(item.price).toLocaleString('id-ID')} (x${item.quantity})</div>
                        <button class="btn-remove-item" data-id="${id}">REMOVE [X]</button>
                    </div>
                </div>
            `;
            cartItemsContainer.insertAdjacentHTML('beforeend', itemHTML);
        });

        cartSubtotal.innerText = `Rp ${total.toLocaleString('id-ID')}`;

        // Bind remove buttons
        document.querySelectorAll('.btn-remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                removeFromCart(id);
            });
        });
    }

    function removeFromCart(id) {
        const formData = new FormData();
        formData.append('action', 'remove');
        formData.append('id', id);

        fetch('<?php echo $base_path; ?>ajax_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            renderCart(data);
        });
    }

    // Expose Add to Cart globally so other scripts can call it
    window.addToCart = function(id, name, price, image) {
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('id', id);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('image', image);

        fetch('<?php echo $base_path; ?>ajax_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            // Open the cart automatically when item added
            openCart();
        });
    };
});
</script>
</body>
</html>
