<?php
// ============================================
//  ELEV8 FITNESS — Challenges Page
// ============================================
$pageTitle = 'Challenges — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$success = '';

// Join a challenge
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_challenge'])) {
    $cid = (int)$_POST['challenge_id'];
    $exists = Database::queryOne("SELECT id FROM user_challenges WHERE user_id = ? AND challenge_id = ?", [$uid, $cid]);
    if (!$exists) {
        Database::execute("INSERT INTO user_challenges (user_id, challenge_id) VALUES (?, ?)", [$uid, $cid]);
        $success = 'Challenge joined! Let\'s go!';
    }
}

// All challenges with user status
$all = Database::query(
    "SELECT c.*, uc.progress, uc.completed,
            IF(uc.id IS NOT NULL, 1, 0) as joined
     FROM challenges c
     LEFT JOIN user_challenges uc ON c.id = uc.challenge_id AND uc.user_id = ?
     ORDER BY c.created_at DESC",
    [$uid]
);

$typeIcon = ['days' => 'fa-calendar', 'reps' => 'fa-rotate', 'time' => 'fa-clock', 'calories' => 'fa-fire'];
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Challenges</span></div>
        <h1>Fitness <em>Challenges</em></h1>
        <p>Push beyond your limits. Compete with yourself and earn rewards.</p>
    </div>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <!-- Stats row -->
    <?php
    $joined    = count(array_filter($all, fn($c) => $c['joined']));
    $completed = count(array_filter($all, fn($c) => $c['completed']));
    ?>
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr); margin-bottom:36px;">
        <div class="stat-card">
            <i class="fa-solid fa-flag stat-icon"></i>
            <div class="stat-value"><?= count($all) ?></div>
            <div class="stat-label">Total Challenges</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-person-running stat-icon"></i>
            <div class="stat-value"><?= $joined ?></div>
            <div class="stat-label">Joined</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-trophy stat-icon"></i>
            <div class="stat-value"><?= $completed ?></div>
            <div class="stat-label">Completed</div>
        </div>
    </div>

    <!-- Challenge Cards -->
    <?php if (empty($all)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-trophy"></i>
        <h3>No Challenges Yet</h3>
        <p>Challenges will appear here once created by the admin.</p>
    </div>
    <?php else: ?>
    <div class="grid-auto">
        <?php foreach ($all as $i => $ch): ?>
        <div class="card fade-up <?= $ch['completed'] ? 'card-gold' : '' ?>" style="animation-delay:<?= $i * 0.07 ?>s; position:relative;">

            <?php if ($ch['completed']): ?>
            <div style="position:absolute; top:16px; right:16px; color:var(--gold); font-size:1.3rem;"><i class="fa-solid fa-check-circle"></i></div>
            <?php endif; ?>

            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="width:44px; height:44px; border-radius:10px; background:var(--gold-dim); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center; color:var(--gold);">
                    <i class="fa-solid <?= $typeIcon[$ch['type']] ?? 'fa-bolt' ?>"></i>
                </div>
                <div>
                    <h4 style="font-family:var(--font-display);"><?= htmlspecialchars($ch['name']) ?></h4>
                    <span class="text-muted text-sm"><?= ucfirst($ch['type']) ?> · Goal: <?= $ch['goal'] ?></span>
                </div>
            </div>

            <?php if ($ch['description']): ?>
            <p class="text-muted mb-3" style="font-size:13.5px; line-height:1.6;"><?= htmlspecialchars($ch['description']) ?></p>
            <?php endif; ?>

            <?php if ($ch['joined'] && !$ch['completed']): ?>
            <!-- Progress -->
            <div class="d-flex align-center gap-2 mb-2">
                <span class="text-muted text-sm">Progress</span>
                <span style="margin-left:auto; color:var(--gold); font-family:var(--font-display); font-size:1rem;">
                    <?= $ch['progress'] ?> / <?= $ch['goal'] ?>
                </span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill"
                     data-width="<?= min(100, round($ch['progress'] / max(1,$ch['goal']) * 100)) ?>"
                     style="width:0%"></div>
            </div>
            <span class="badge-pill mt-2" style="color:var(--info); border-color:rgba(91,155,213,0.3);">
                <i class="fa-solid fa-person-running"></i> In Progress
            </span>

            <?php elseif ($ch['completed']): ?>
            <span class="badge-pill" style="color:var(--gold); border-color:rgba(201,168,76,0.3);">
                <i class="fa-solid fa-trophy"></i> Completed!
            </span>

            <?php else: ?>
            <!-- Join button -->
            <form method="POST">
                <input type="hidden" name="challenge_id" value="<?= $ch['id'] ?>">
                <button type="submit" name="join_challenge" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-plus"></i> Join Challenge
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
