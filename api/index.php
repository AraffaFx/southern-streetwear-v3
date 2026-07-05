<?php
/**
 * Vercel Serverless Function Entry Point
 * File ini menghandle semua request dan routing ke file PHP yang sesuai
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '0');

// Get request path
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';
$request_path = parse_url($request_uri, PHP_URL_PATH);

// Remove leading slash
$path = ltrim($request_path, '/');

// Change working directory to project root
chdir(dirname(__DIR__));

// Function to require file safely
function requireFile($file) {
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
}

// Routing logic
if (empty($path) || $path === '/' || $path === 'index.php') {
    // Root - load index.php
    requireFile(__DIR__ . '/../index.php');
    
} elseif ($path === 'login.php' || $path === 'login') {
    requireFile(__DIR__ . '/../login.php');
    
} elseif ($path === 'register.php' || $path === 'register') {
    requireFile(__DIR__ . '/../register.php');
    
} elseif ($path === 'logout.php' || $path === 'logout') {
    requireFile(__DIR__ . '/../logout.php');
    
} elseif ($path === 'profile.php' || $path === 'profile') {
    requireFile(__DIR__ . '/../profile.php');
    
} elseif ($path === 'checkout.php' || $path === 'checkout') {
    requireFile(__DIR__ . '/../checkout.php');
    
} elseif ($path === 'search.php' || $path === 'search') {
    requireFile(__DIR__ . '/../search.php');
    
} elseif ($path === 'ajax_cart.php' || $path === 'ajax_cart') {
    requireFile(__DIR__ . '/../ajax_cart.php');
    
} elseif (preg_match('#^cerita-kami/?(.*)$#', $path, $matches)) {
    // Cerita Kami page
    requireFile(__DIR__ . '/../cerita-kami/index.php');
    
} elseif (preg_match('#^kontak/?(.*)$#', $path, $matches)) {
    // Kontak page
    requireFile(__DIR__ . '/../kontak/index.php');
    
} else {
    // Try to find PHP file in root
    $php_file = __DIR__ . '/../' . $path;
    
    // If no extension, try adding .php
    if (!pathinfo($path, PATHINFO_EXTENSION)) {
        $php_file = __DIR__ . '/../' . $path . '.php';
    }
    
    if (file_exists($php_file) && pathinfo($php_file, PATHINFO_EXTENSION) === 'php') {
        requireFile($php_file);
    } else {
        // 404 Not Found
        http_response_code(404);
        echo '<!DOCTYPE html>
<html>
<head>
    <title>404 - Not Found</title>
    <style>
        body { font-family: system-ui, sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 48px; margin: 0; }
        p { color: #666; }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Page Not Found</p>
    <p><a href="/">Back to Home</a></p>
</body>
</html>';
    }
}
