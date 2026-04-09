<?php
// ============================================
//  FITNESS APP — Header Partial
// ============================================
require_once __DIR__ . '/auth.php';
$currentPage = basename($_SERVER['PHP_SELF']);
$unreadNotifications = 0;
if (isLoggedIn()) {
    $notifRow = Database::queryOne(
        "SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0",
        [$_SESSION['user_id']]
    );
    $unreadNotifications = $notifRow['cnt'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <?= $extraHead ?? '' ?>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="<?= APP_URL ?>/pages/user/dashboard.php" class="nav-logo">
            <span class="logo-icon">⬡</span>
            <span class="logo-text">ELEV<em>8</em></span>
        </a>

        <?php if (isLoggedIn()): ?>
        <ul class="nav-links">
            <li><a href="<?= APP_URL ?>/pages/user/dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="<?= APP_URL ?>/pages/user/programs.php" class="<?= $currentPage === 'programs.php' ? 'active' : '' ?>">Programs</a></li>
            <li><a href="<?= APP_URL ?>/pages/user/exercises.php" class="<?= $currentPage === 'exercises.php' ? 'active' : '' ?>">Exercises</a></li>
            <li><a href="<?= APP_URL ?>/pages/user/challenges.php" class="<?= $currentPage === 'challenges.php' ? 'active' : '' ?>">Challenges</a></li>
            <li><a href="<?= APP_URL ?>/pages/user/body_stats.php" class="<?= $currentPage === 'body_stats.php' ? 'active' : '' ?>">Stats</a></li>
            <?php if (isAdmin()): ?>
            <li><a href="<?= APP_URL ?>/pages/admin/dashboard.php" class="nav-admin">Admin</a></li>
            <?php endif; ?>
        </ul>

        <div class="nav-actions">
            <a href="<?= APP_URL ?>/pages/user/notifications.php" class="nav-notif">
                <i class="fa-regular fa-bell"></i>
                <?php if ($unreadNotifications > 0): ?>
                <span class="notif-badge"><?= $unreadNotifications ?></span>
                <?php endif; ?>
            </a>
            <div class="nav-avatar" onclick="toggleDropdown()">
                <?php if (!empty($_SESSION['avatar'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="avatar">
                <?php else: ?>
                    <span><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="nav-dropdown" id="navDropdown">
                <div class="dropdown-name"><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></div>
                <a href="<?= APP_URL ?>/pages/user/profile.php"><i class="fa-regular fa-user"></i> Profile</a>
                <a href="<?= APP_URL ?>/api/logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <button class="nav-hamburger" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
        <?php endif; ?>
    </div>
</nav>

<div class="page-wrapper">
