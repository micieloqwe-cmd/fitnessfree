<?php
// ============================================
//  ELEV8 FITNESS — User Profile
// ============================================
$pageTitle = 'Profile — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'profile';

    if ($action === 'profile') {
        Database::execute(
            "UPDATE users SET name=?, level=?, goal=?, avatar=? WHERE id=?",
            [
                trim($_POST['name']   ?? ''),
                $_POST['level']       ?? 'beginner',
                $_POST['goal']        ?? 'healthy',
                trim($_POST['avatar'] ?? ''),
                $uid
            ]
        );
        $_SESSION['user_name'] = trim($_POST['name'] ?? '');
        $_SESSION['level']     = $_POST['level'] ?? 'beginner';
        $_SESSION['goal']      = $_POST['goal']  ?? 'healthy';
        $_SESSION['avatar']    = trim($_POST['avatar'] ?? '');
        $success = 'Profile updated!';

    } elseif ($action === 'password') {
        $user = Database::queryOne("SELECT password FROM users WHERE id=?", [$uid]);
        if (!password_verify($_POST['current_password'] ?? '', $user['password'])) {
            $error = 'Current password is incorrect.';
        } elseif (strlen($_POST['new_password'] ?? '') < 6) {
            $error = 'New password must be at least 6 characters.';
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = 'New passwords do not match.';
        } else {
            Database::execute("UPDATE users SET password=? WHERE id=?",
                [password_hash($_POST['new_password'], PASSWORD_BCRYPT), $uid]);
            $success = 'Password changed!';
        }
    }
}

$user  = Database::queryOne("SELECT * FROM users WHERE id=?", [$uid]);
$stats = Database::queryOne("SELECT * FROM user_stats WHERE user_id=?", [$uid]);
$stats = $stats ?? ['total_workouts'=>0,'total_calories'=>0,'streak'=>0,'exp'=>0];

$badgeCount  = Database::queryOne("SELECT COUNT(*) as c FROM user_badges WHERE user_id=?", [$uid])['c'];
$favCount    = Database::queryOne("SELECT COUNT(*) as c FROM favorites WHERE user_id=?", [$uid])['c'];
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Profile</span></div>
        <h1>My <em>Profile</em></h1>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error):   ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="grid-2">
        <!-- ── PROFILE INFO ── -->
        <div>
            <!-- Avatar & summary -->
            <div class="card card-gold mb-3" style="text-align:center; padding:36px;">
                <div style="width:80px;height:80px;border-radius:50%;border:2px solid var(--gold);background:var(--bg-3);display:flex;align-items:center;justify-content:center;font-size:2rem;font-family:var(--font-display);color:var(--gold);margin:0 auto 16px;overflow:hidden;">
                    <?php if ($user['avatar']): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <h3 style="font-family:var(--font-display);"><?= htmlspecialchars($user['name']) ?></h3>
                <p class="text-muted" style="font-size:13px;"><?= htmlspecialchars($user['email']) ?></p>
                <div class="d-flex gap-2 mt-3" style="justify-content:center;flex-wrap:wrap;">
                    <span class="item-card-tag tag-<?= $user['level'] ?>"><?= ucfirst($user['level']) ?></span>
                    <span class="item-card-tag tag-<?= $user['goal'] ?>"><?= str_replace('_',' ',ucfirst($user['goal'])) ?></span>
                </div>

                <!-- mini stats -->
                <div class="grid-3 mt-3" style="gap:12px;text-align:center;">
                    <div>
                        <div style="font-family:var(--font-display);font-size:1.6rem;color:var(--gold);"><?= $stats['total_workouts'] ?></div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-3);">Workouts</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display);font-size:1.6rem;color:var(--gold);"><?= $badgeCount ?></div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-3);">Badges</div>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display);font-size:1.6rem;color:var(--gold);"><?= $favCount ?></div>
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-3);">Favorites</div>
                    </div>
                </div>
                <p class="text-muted mt-2" style="font-size:11px;">Member since <?= date('F Y', strtotime($user['created_at'])) ?></p>
            </div>

            <!-- Edit Profile -->
            <div class="card">
                <h4 style="font-family:var(--font-display);margin-bottom:20px;">Edit Profile</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="profile">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Avatar URL</label>
                        <input type="url" name="avatar" class="form-control" placeholder="https://..."
                               value="<?= htmlspecialchars($user['avatar'] ?? '') ?>">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Fitness Level</label>
                            <select name="level" class="form-control">
                                <?php foreach(['beginner','intermediate','advanced'] as $l): ?>
                                <option value="<?=$l?>" <?=$user['level']===$l?'selected':''?>><?=ucfirst($l)?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Goal</label>
                            <select name="goal" class="form-control">
                                <option value="healthy"      <?=$user['goal']==='healthy'?'selected':''?>>Stay Healthy</option>
                                <option value="lose_weight"  <?=$user['goal']==='lose_weight'?'selected':''?>>Lose Weight</option>
                                <option value="build_muscle" <?=$user['goal']==='build_muscle'?'selected':''?>>Build Muscle</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </form>
            </div>
        </div>

        <!-- ── PASSWORD & SECURITY ── -->
        <div>
            <div class="card mb-3">
                <h4 style="font-family:var(--font-display);margin-bottom:20px;">Change Password</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="password">
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-outline"><i class="fa-solid fa-lock"></i> Update Password</button>
                </form>
            </div>

            <!-- Account info -->
            <div class="card">
                <h4 style="font-family:var(--font-display);margin-bottom:16px;">Account Details</h4>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div class="d-flex align-center gap-2">
                        <span class="text-muted text-sm" style="width:120px;">Email</span>
                        <span style="color:var(--text-1);"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <span class="text-muted text-sm" style="width:120px;">Role</span>
                        <span style="color:<?= $user['role']==='admin'?'var(--gold)':'var(--text-2)'?>;"><?= ucfirst($user['role']) ?></span>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <span class="text-muted text-sm" style="width:120px;">EXP Points</span>
                        <span style="color:var(--gold);font-family:var(--font-display);"><?= number_format($stats['exp']) ?> XP</span>
                    </div>
                    <div class="d-flex align-center gap-2">
                        <span class="text-muted text-sm" style="width:120px;">Last Login</span>
                        <span style="color:var(--text-2);font-size:13px;">
                            <?= $user['last_login'] ? date('d M Y H:i', strtotime($user['last_login'])) : 'N/A' ?>
                        </span>
                    </div>
                </div>
                <div style="border-top:1px solid var(--border);margin:20px 0;"></div>
                <a href="<?= APP_URL ?>/api/logout.php" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
