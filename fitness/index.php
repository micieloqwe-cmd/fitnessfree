<?php
// ============================================
//  ELEV8 FITNESS — Root Index
// ============================================
require_once __DIR__ . '/includes/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_SESSION['user_id'])) {
    $redirect = ($_SESSION['role'] ?? 'user') === 'admin'
        ? APP_URL . '/pages/admin/dashboard.php'
        : APP_URL . '/pages/user/dashboard.php';
    header('Location: ' . $redirect);
} else {
    header('Location: ' . APP_URL . '/pages/login.php');
}
exit;
