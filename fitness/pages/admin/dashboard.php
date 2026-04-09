<?php
// ============================================
//  ELEV8 FITNESS — Admin Dashboard
// ============================================
$pageTitle = 'Admin — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireAdmin();

// Counts
$userCount     = Database::queryOne("SELECT COUNT(*) as c FROM users WHERE role = 'user'")['c'];
$exerciseCount = Database::queryOne("SELECT COUNT(*) as c FROM exercises")['c'];
$programCount  = Database::queryOne("SELECT COUNT(*) as c FROM programs")['c'];
$sessionCount  = Database::queryOne("SELECT COUNT(*) as c FROM workout_sessions")['c'];

// Recent users
$recentUsers = Database::query("SELECT * FROM users ORDER BY created_at DESC LIMIT 8");

// Top exercises (by workout count)
$topExercises = Database::query(
    "SELECT e.name, COUNT(w.id) as cnt
     FROM exercises e
     LEFT JOIN workouts w ON e.id = w.exercise_id
     GROUP BY e.id ORDER BY cnt DESC LIMIT 5"
);
?>

<div class="admin-layout">

    <!-- ── SIDEBAR ── -->
    <aside class="admin-sidebar">
        <div style="padding:8px; margin-bottom:20px;">
            <div style="font-size:11px; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-3); margin-bottom:12px;">Administration</div>
        </div>
        <?php
        $adminNav = [
            ['dashboard.php',  'fa-gauge',         'Dashboard'],
            ['users.php',      'fa-users',          'Users'],
            ['exercises.php',  'fa-dumbbell',       'Exercises'],
            ['programs.php',   'fa-clipboard-list', 'Programs'],
            ['challenges.php', 'fa-trophy',         'Challenges'],
            ['badges.php',     'fa-medal',          'Badges'],
        ];
        $cur = basename($_SERVER['PHP_SELF']);
        foreach ($adminNav as [$file, $icon, $label]):
        ?>
        <a href="<?= APP_URL ?>/pages/admin/<?= $file ?>"
           class="admin-nav-item <?= $cur === $file ? 'active' : '' ?>">
            <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
        </a>
        <?php endforeach; ?>

        <div style="border-top:1px solid var(--border); margin:16px 0;"></div>
        <a href="<?= APP_URL ?>/pages/user/dashboard.php" class="admin-nav-item">
            <i class="fa-solid fa-arrow-left"></i> Back to App
        </a>
    </aside>

    <!-- ── MAIN ── -->
    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px; border-bottom:1px solid var(--border); margin-bottom:36px;">
            <h2>Admin <em>Dashboard</em></h2>
            <p class="text-muted">Platform overview and management.</p>
        </div>

        <!-- Stats -->
        <div class="stats-grid fade-up" style="grid-template-columns:repeat(4,1fr);">
            <div class="stat-card">
                <i class="fa-solid fa-users stat-icon"></i>
                <div class="stat-value"><?= $userCount ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-dumbbell stat-icon"></i>
                <div class="stat-value"><?= $exerciseCount ?></div>
                <div class="stat-label">Exercises</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-clipboard-list stat-icon"></i>
                <div class="stat-value"><?= $programCount ?></div>
                <div class="stat-label">Programs</div>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-fire stat-icon"></i>
                <div class="stat-value"><?= $sessionCount ?></div>
                <div class="stat-label">Sessions</div>
            </div>
        </div>

        <div class="grid-2 mt-4 fade-up delay-1">

            <!-- Recent Users -->
            <div class="card">
                <div class="section-header">
                    <h4 style="font-family:var(--font-display);">Recent Users</h4>
                    <a href="<?= APP_URL ?>/pages/admin/users.php" class="see-all">Manage →</a>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th>Name</th><th>Level</th><th>Joined</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentUsers as $u): ?>
                            <tr>
                                <td style="color:var(--text-1);">
                                    <?= htmlspecialchars($u['name']) ?>
                                    <?php if ($u['role'] === 'admin'): ?>
                                    <span style="font-size:10px; color:var(--gold); margin-left:6px;">ADMIN</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="item-card-tag tag-<?= $u['level'] ?>"><?= ucfirst($u['level']) ?></span></td>
                                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Exercises -->
            <div class="card">
                <div class="section-header">
                    <h4 style="font-family:var(--font-display);">Most Used Exercises</h4>
                </div>
                <?php if (empty($topExercises)): ?>
                <div class="empty-state" style="padding:24px 0;">
                    <p>No workout data yet.</p>
                </div>
                <?php else: ?>
                <div style="display:flex; flex-direction:column; gap:14px;">
                    <?php foreach ($topExercises as $i => $ex): ?>
                    <div>
                        <div class="d-flex align-center gap-2 mb-1">
                            <span style="font-size:14px; color:var(--text-1);"><?= htmlspecialchars($ex['name']) ?></span>
                            <span style="margin-left:auto; font-family:var(--font-display); color:var(--gold);"><?= $ex['cnt'] ?></span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill"
                                 data-width="<?= $topExercises[0]['cnt'] > 0 ? round($ex['cnt'] / $topExercises[0]['cnt'] * 100) : 0 ?>"
                                 style="width:0%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="card fade-up delay-2 mt-4">
            <h4 style="font-family:var(--font-display); margin-bottom:20px;">Quick Actions</h4>
            <div class="d-flex gap-2" style="flex-wrap:wrap;">
                <a href="<?= APP_URL ?>/pages/admin/exercises.php?action=add" class="btn btn-gold btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Exercise
                </a>
                <a href="<?= APP_URL ?>/pages/admin/programs.php?action=add" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Program
                </a>
                <a href="<?= APP_URL ?>/pages/admin/challenges.php?action=add" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Challenge
                </a>
                <a href="<?= APP_URL ?>/pages/admin/badges.php?action=add" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-plus"></i> Add Badge
                </a>
            </div>
        </div>

    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
