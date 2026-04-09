<?php
// ============================================
//  ELEV8 FITNESS — Admin: Users
// ============================================
$pageTitle = 'Admin Users — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireAdmin();

// Delete user
if (isset($_GET['delete']) && (int)$_GET['delete'] !== $_SESSION['user_id']) {
    Database::execute("DELETE FROM users WHERE id = ?", [(int)$_GET['delete']]);
    header('Location: ' . APP_URL . '/pages/admin/users.php?deleted=1'); exit;
}

// Toggle role
if (isset($_GET['toggle_role'])) {
    $uid = (int)$_GET['toggle_role'];
    $current = Database::queryOne("SELECT role FROM users WHERE id = ?", [$uid]);
    $newRole  = $current['role'] === 'admin' ? 'user' : 'admin';
    Database::execute("UPDATE users SET role = ? WHERE id = ?", [$newRole, $uid]);
    header('Location: ' . APP_URL . '/pages/admin/users.php'); exit;
}

$search = trim($_GET['q'] ?? '');
$sql = "SELECT u.*, us.total_workouts, us.streak FROM users u LEFT JOIN user_stats us ON u.id = us.user_id";
$params = [];
if ($search) {
    $sql .= " WHERE u.name LIKE ? OR u.email LIKE ?";
    $params = ["%$search%", "%$search%"];
}
$sql .= " ORDER BY u.created_at DESC";
$users = Database::query($sql, $params);
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <?php
        $adminNav = [
            ['dashboard.php','fa-gauge','Dashboard'],
            ['users.php','fa-users','Users'],
            ['exercises.php','fa-dumbbell','Exercises'],
            ['programs.php','fa-clipboard-list','Programs'],
            ['challenges.php','fa-trophy','Challenges'],
            ['badges.php','fa-medal','Badges'],
        ];
        $cur = basename($_SERVER['PHP_SELF']);
        foreach ($adminNav as [$file,$icon,$label]):
        ?>
        <a href="<?= APP_URL ?>/pages/admin/<?= $file ?>" class="admin-nav-item <?= $cur===$file?'active':'' ?>">
            <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
        </a>
        <?php endforeach; ?>
        <div style="border-top:1px solid var(--border);margin:16px 0;"></div>
        <a href="<?= APP_URL ?>/pages/user/dashboard.php" class="admin-nav-item">
            <i class="fa-solid fa-arrow-left"></i> Back to App
        </a>
    </aside>

    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px;border-bottom:1px solid var(--border);margin-bottom:36px;">
            <h2>Manage <em>Users</em></h2>
        </div>

        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-danger">User deleted.</div><?php endif; ?>

        <!-- Search -->
        <form method="GET" style="margin-bottom:24px;">
            <div class="d-flex gap-2">
                <input type="text" name="q" class="form-control" placeholder="Search by name or email..."
                       value="<?= htmlspecialchars($search) ?>" style="max-width:360px;">
                <button type="submit" class="btn btn-gold btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                <?php if ($search): ?><a href="<?= APP_URL ?>/pages/admin/users.php" class="btn btn-ghost btn-sm">Clear</a><?php endif; ?>
            </div>
        </form>

        <div class="card">
            <div class="section-header">
                <h4 style="font-family:var(--font-display);">All Users (<?= count($users) ?>)</h4>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Level</th>
                            <th>Goal</th>
                            <th>Workouts</th>
                            <th>Streak</th>
                            <th>Joined</th>
                            <th style="width:80px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td style="color:var(--text-1); font-weight:500;"><?= htmlspecialchars($u['name']) ?></td>
                            <td style="color:var(--text-3); font-size:13px;"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <span style="color:<?= $u['role']==='admin'?'var(--gold)':'var(--text-2)' ?>; font-size:11px; text-transform:uppercase; letter-spacing:0.08em;">
                                    <?= $u['role'] ?>
                                </span>
                            </td>
                            <td><span class="item-card-tag tag-<?= $u['level'] ?>"><?= ucfirst($u['level']) ?></span></td>
                            <td style="color:var(--text-3); font-size:13px;"><?= str_replace('_',' ', ucfirst($u['goal'])) ?></td>
                            <td style="text-align:center;"><?= $u['total_workouts'] ?? 0 ?></td>
                            <td style="text-align:center; color:var(--gold);"><?= $u['streak'] ?? 0 ?> 🔥</td>
                            <td style="color:var(--text-3); font-size:12px;"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                                    <a href="?toggle_role=<?= $u['id'] ?>" title="Toggle Role"
                                       style="color:var(--info);">
                                        <i class="fa-solid fa-shield-halved"></i>
                                    </a>
                                    <a href="?delete=<?= $u['id'] ?>" style="color:var(--danger);"
                                       onclick="return confirm('Delete this user?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                    <?php else: ?>
                                    <span style="color:var(--text-3); font-size:11px;">You</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
