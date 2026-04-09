<?php
// ============================================
//  FITNESS APP — Database Configuration
// ============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fitness');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME', 'ELEV8');
define('APP_URL', 'http://localhost/fitness_elev8/fitness');
define('APP_VERSION', '1.0.0');

// Session
define('SESSION_LIFETIME', 86400); // 24 hours

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
