<?php
// ============================================
//  ELEV8 FITNESS — Notifications Page
// ============================================
$pageTitle = 'Notifications — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];

// Mark all as read
Database::execute("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$uid]);

$notifs = Database::query(
    "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC",
    [$uid]
);
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Notifications</span></div>
        <h1><em>Notifications</em></h1>
    </div>

    <?php if (empty($notifs)): ?>
    <div class="empty-state">
        <i class="fa-regular fa-bell-slash"></i>
        <h3>No Notifications</h3>
        <p>You're all caught up. Check back later.</p>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="notif-list">
            <?php foreach ($notifs as $n): ?>
            <div class="notif-item <?= !$n['is_read'] ? 'unread' : '' ?>">
                <div class="notif-dot"></div>
                <div>
                    <div class="notif-text"><?= htmlspecialchars($n['message']) ?></div>
                    <div class="notif-time">
                        <i class="fa-regular fa-clock"></i> <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
