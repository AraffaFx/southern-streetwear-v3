<?php
/**
 * Vercel Serverless Function Entry Point
 * File ini menghandle semua request dan routing ke file PHP yang sesuai
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

// Get request URI and method
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Parse the path
$parsed_url = parse_url($request_uri);
$path = $parsed_url['path'] ?? '/';

// Set query string if exists
if (isset($parsed_url['query'])) {
    parse_str($parsed_url['query'], $_GET);
}

// Change to project root directory
$root_dir = dirname(__DIR__);
chdir($root_dir);

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to safely require a file
function loadPHP($filepath) {
    if (file_exists($filepath)) {
        require $filepath;
        exit;
    }
    return false;
}

// Remove leading slash for easier matching
$path = ltrim($path, '/');

// Route to appropriate file
try {
    // Root or index
    if (empty($path) || $path === 'index.php') {
        loadPHP($root_dir . '/index.php');
    }
    
    // Authentication pages
    if ($path === 'login' || $path === 'login.php') {
        loadPHP($root_dir . '/login.php');
    }
    if ($path === 'register' || $path === 'register.php') {
        loadPHP($root_dir . '/register.php');
    }
    if ($path === 'logout' || $path === 'logout.php') {
        loadPHP($root_dir . '/logout.php');
    }
    if ($path === 'profile' || $path === 'profile.php') {
        loadPHP($root_dir . '/profile.php');
    }
    
    // E-commerce pages
    if ($path === 'checkout' || $path === 'checkout.php') {
        loadPHP($root_dir . '/checkout.php');
    }
    if ($path === 'search' || $path === 'search.php') {
        loadPHP($root_dir . '/search.php');
    }
    if ($path === 'ajax_cart' || $path === 'ajax_cart.php') {
        loadPHP($root_dir . '/ajax_cart.php');
    }
    
    // Subfolder pages - cerita-kami
    if (strpos($path, 'cerita-kami') === 0) {
        loadPHP($root_dir . '/cerita-kami/index.php');
    }
    
    // Subfolder pages - kontak
    if (strpos($path, 'kontak') === 0) {
        loadPHP($root_dir . '/kontak/index.php');
    }
    
    // Try generic PHP file in root
    $generic_file = $root_dir . '/' . $path;
    if (file_exists($generic_file) && pathinfo($generic_file, PATHINFO_EXTENSION) === 'php') {
        loadPHP($generic_file);
    }
    
    // Try adding .php extension
    if (!pathinfo($path, PATHINFO_EXTENSION) && file_exists($root_dir . '/' . $path . '.php')) {
        loadPHP($root_dir . '/' . $path . '.php');
    }
    
    // If nothing matched, return 404
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>404 - Halaman Tidak Ditemukan | Southern</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background: #f9f9f9;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }
            .container {
                text-align: center;
                max-width: 600px;
            }
            h1 {
                font-size: 120px;
                font-weight: 800;
                color: #000;
                line-height: 1;
                margin-bottom: 20px;
            }
            h2 {
                font-size: 24px;
                font-weight: 600;
                color: #000;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            p {
                font-size: 16px;
                color: #666;
                margin-bottom: 32px;
                line-height: 1.6;
            }
            a {
                display: inline-block;
                background: #000;
                color: #fff;
                text-decoration: none;
                padding: 16px 32px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 14px;
                border: 2px solid #000;
                transition: all 0.2s;
            }
            a:hover {
                background: #fff;
                color: #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>404</h1>
            <h2>Halaman Tidak Ditemukan</h2>
            <p>Maaf, halaman yang Anda cari tidak ada atau sudah dipindahkan.</p>
            <a href="/">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
    exit;
    
} catch (Exception $e) {
    // Catch any errors
    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>500 - Server Error | Southern</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { 
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                background: #f9f9f9;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }
            .container {
                text-align: center;
                max-width: 600px;
            }
            h1 {
                font-size: 120px;
                font-weight: 800;
                color: #000;
                line-height: 1;
                margin-bottom: 20px;
            }
            h2 {
                font-size: 24px;
                font-weight: 600;
                color: #000;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            p {
                font-size: 16px;
                color: #666;
                margin-bottom: 32px;
                line-height: 1.6;
            }
            .error-details {
                background: #fff;
                border: 2px solid #000;
                padding: 20px;
                margin-bottom: 32px;
                text-align: left;
                font-family: monospace;
                font-size: 14px;
                color: #d90000;
                word-break: break-all;
            }
            a {
                display: inline-block;
                background: #000;
                color: #fff;
                text-decoration: none;
                padding: 16px 32px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                font-size: 14px;
                border: 2px solid #000;
                transition: all 0.2s;
            }
            a:hover {
                background: #fff;
                color: #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>500</h1>
            <h2>Server Error</h2>
            <p>Terjadi kesalahan pada server. Silakan coba lagi nanti.</p>
            <div class="error-details">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <a href="/">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
