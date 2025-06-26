<?php
// Base configuration
define('SITE_NAME', 'VaiComigo');
define('SITE_URL', ''); // Leave empty for relative paths
define('PRIMARY_COLOR', '#004AAD');

// Database configuration
define('DB_HOST', $_ENV['DB_HOST'] ?? 'db');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'vaicomigo');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? 'root');
define('DB_CHARSET', 'utf8mb4');

// Database connection function
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        die("Erro de conexão com o banco de dados. Tente novamente mais tarde.");
    }
}

// Function to get active class for current page
function isActive($page) {
    $current_page = basename($_SERVER['PHP_SELF']);
    return ($current_page == $page) ? 'active' : '';
}

// Function to format date for Brazilian format
function formatDateBR($date) {
    return date('d/m/Y', strtotime($date));
}

// Function to format time for Brazilian format
function formatTimeBR($time) {
    return date('H:i', strtotime($time));
}

// Function to format currency for Brazilian format
function formatCurrency($value) {
    return 'R$ ' . number_format($value, 2, ',', '.');
}
?>
