<?php
// ============================================
//  ELEV8 FITNESS — User Dashboard
// ============================================
$pageTitle = 'Dashboard — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];

// Stats
$stats = Database::queryOne("SELECT * FROM user_stats WHERE user_id = ?", [$uid]);
$stats = $stats ?? ['total_workouts' => 0, 'total_calories' => 0, 'streak' => 0, 'exp' => 0];

// Recent sessions (last 5)
$sessions = Database::query(
    "SELECT * FROM workout_sessions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
    [$uid]
);

// Recommended programs
$programs = Database::query(
    "SELECT * FROM programs WHERE level = ? OR goal = ? LIMIT 3",
    [$_SESSION['level'], $_SESSION['goal']]
);

// Latest body stat
$bodyLatest = Database::queryOne(
    "SELECT * FROM body_stats WHERE user_id = ? ORDER BY recorded_at DESC LIMIT 1",
    [$uid]
);

// Badges
$badges = Database::query(
    "SELECT b.* FROM badges b
     JOIN user_badges ub ON b.id = ub.badge_id
     WHERE ub.user_id = ?",
    [$uid]
);

// Active challenges
$challenges = Database::query(
    "SELECT c.*, uc.progress, uc.completed FROM challenges c
     JOIN user_challenges uc ON c.id = uc.challenge_id
     WHERE uc.user_id = ? AND uc.completed = 0 LIMIT 4",
    [$uid]
);

$levelLabels = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'];
$goalLabels  = ['lose_weight' => 'Lose Weight', 'build_muscle' => 'Build Muscle', 'healthy' => 'Stay Healthy'];
$expNext = 1000;
$expPct  = min(100, round(($stats['exp'] % $expNext) / $expNext * 100));
?>

<div class="container page-content">

    <!-- ── HERO ── -->
    <section class="hero-section fade-up">
        <div class="hero-greeting">Good <?= (date('H') < 12) ? 'Morning' : ((date('H') < 18) ? 'Afternoon' : 'Evening') ?></div>
        <h1 class="hero-title"><?= htmlspecialchars($_SESSION['user_name']) ?>,<br><em>Let's get moving.</em></h1>
        <p class="hero-subtitle">
            <?= $levelLabels[$_SESSION['level']] ?> · <?= $goalLabels[$_SESSION['goal']] ?>
        </p>
        <div class="d-flex gap-2 align-center">
            <a href="<?= APP_URL ?>/pages/user/programs.php" class="btn btn-gold">
                <i class="fa-solid fa-play"></i> Start Workout
            </a>
            <a href="<?= APP_URL ?>/pages/user/body_stats.php" class="btn btn-outline">
                <i class="fa-solid fa-chart-line"></i> Log Stats
            </a>
        </div>
    </section>

    <!-- ── STAT CARDS ── -->
    <div class="stats-grid fade-up delay-1">
        <div class="stat-card">
            <i class="fa-solid fa-fire stat-icon"></i>
            <div class="stat-value"><?= number_format($stats['total_calories']) ?></div>
            <div class="stat-label">Total Calories</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-dumbbell stat-icon"></i>
            <div class="stat-value"><?= $stats['total_workouts'] ?></div>
            <div class="stat-label">Workouts Done</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-bolt stat-icon"></i>
            <div class="stat-value"><?= $stats['streak'] ?></div>
            <div class="stat-label">Day Streak</div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-star stat-icon"></i>
            <div class="stat-value"><?= $stats['exp'] ?></div>
            <div class="stat-label">EXP Points</div>
        </div>
    </div>

    <!-- ── EXP BAR ── -->
    <div class="card fade-up delay-2 mb-3" style="padding:20px 28px;">
        <div class="d-flex align-center gap-2 mb-2">
            <span style="font-size:13px; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-3);">Level Progress</span>
            <span class="text-gold" style="margin-left:auto; font-family:var(--font-display); font-size:1.1rem;"><?= $expPct ?>%</span>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" data-width="<?= $expPct ?>" style="width:0%"></div>
        </div>
        <p class="text-muted mt-1" style="font-size:12px;"><?= ($stats['exp'] % $expNext) ?> / <?= $expNext ?> EXP to next level</p>
    </div>

    <!-- ── BODY STATS + RECENT WORKOUTS ── -->
    <div class="grid-2 fade-up delay-2">

        <!-- Recent Workouts -->
        <div>
            <div class="section-header">
                <h2>Recent Sessions</h2>
                <a href="<?= APP_URL ?>/pages/user/workouts.php" class="see-all">View All →</a>
            </div>
            <?php if (empty($sessions)): ?>
            <div class="empty-state" style="padding:40px 0;">
                <i class="fa-regular fa-calendar-xmark"></i>
                <h3>No workouts yet</h3>
                <p>Your completed sessions will appear here.</p>
            </div>
            <?php else: ?>
            <div class="workout-list">
                <?php foreach ($sessions as $s): ?>
                <div class="workout-item">
                    <div class="workout-item-icon">
                        <i class="fa-solid fa-dumbbell"></i>
                    </div>
                    <div class="workout-item-info">
                        <div class="workout-item-name">Workout Session</div>
                        <div class="workout-item-meta">
                            <i class="fa-regular fa-clock"></i> <?= $s['total_duration'] ?? 0 ?> min
                            · <?= date('d M', strtotime($s['created_at'])) ?>
                        </div>
                    </div>
                    <div class="workout-item-cal"><?= $s['total_calories'] ?? 0 ?> <span style="font-size:11px;color:var(--text-3);">kcal</span></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Body Stats -->
        <div>
            <div class="section-header">
                <h2>Body Stats</h2>
                <a href="<?= APP_URL ?>/pages/user/body_stats.php" class="see-all">Update →</a>
            </div>
            <?php if ($bodyLatest): ?>
            <div class="card card-gold">
                <div class="grid-3" style="gap:16px;">
                    <div class="text-center">
                        <div style="font-family:var(--font-display);font-size:2rem;color:var(--gold);"><?= $bodyLatest['weight'] ?></div>
                        <div class="stat-label">Weight (kg)</div>
                    </div>
                    <div class="text-center">
                        <div style="font-family:var(--font-display);font-size:2rem;color:var(--gold);"><?= $bodyLatest['body_fat'] ?>%</div>
                        <div class="stat-label">Body Fat</div>
                    </div>
                    <div class="text-center">
                        <div style="font-family:var(--font-display);font-size:2rem;color:var(--gold);"><?= $bodyLatest['muscle_mass'] ?></div>
                        <div class="stat-label">Muscle (kg)</div>
                    </div>
                </div>
                <p class="text-muted mt-2" style="font-size:12px; text-align:center;">
                    Recorded: <?= date('d M Y', strtotime($bodyLatest['recorded_at'])) ?>
                </p>
            </div>
            <?php else: ?>
            <div class="empty-state" style="padding:40px 0;">
                <i class="fa-solid fa-weight-scale"></i>
                <h3>No stats recorded</h3>
                <a href="<?= APP_URL ?>/pages/user/body_stats.php" class="btn btn-gold btn-sm mt-2">Log First Stats</a>
            </div>
            <?php endif; ?>

            <!-- Badges -->
            <?php if (!empty($badges)): ?>
            <div class="mt-3">
                <div class="section-header" style="margin-bottom:16px;">
                    <h4 style="font-family:var(--font-display);">Your Badges</h4>
                </div>
                <div class="d-flex" style="gap:10px;flex-wrap:wrap;">
                    <?php foreach ($badges as $b): ?>
                    <div class="badge-pill" title="<?= htmlspecialchars($b['description']) ?>">
                        <i class="<?= htmlspecialchars($b['icon'] ?? 'fa-solid fa-medal') ?>"></i>
                        <?= htmlspecialchars($b['name']) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- ── RECOMMENDED PROGRAMS ── -->
    <?php if (!empty($programs)): ?>
    <div class="fade-up delay-3 mt-4">
        <div class="section-header">
            <h2>Recommended Programs</h2>
            <a href="<?= APP_URL ?>/pages/user/programs.php" class="see-all">All Programs →</a>
        </div>
        <div class="grid-3">
            <?php foreach ($programs as $p): ?>
            <a href="<?= APP_URL ?>/pages/user/program_detail.php?id=<?= $p['id'] ?>" class="item-card">
                <div class="item-card-img">
                    <?php if (!empty($p['image_url'])): ?>
                        <img src="<?= APP_URL ?>/assets/images/programs/<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
                    <?php else: ?>
                        <i class="fa-solid fa-person-running"></i>
                    <?php endif; ?>
                </div>
                <div class="item-card-body">
                    <span class="item-card-tag tag-<?= $p['level'] ?>"><?= ucfirst($p['level']) ?></span>
                    <span class="item-card-tag tag-<?= $p['goal'] ?>" style="margin-left:6px;"><?= str_replace('_', ' ', ucfirst($p['goal'])) ?></span>
                    <div class="item-card-title"><?= htmlspecialchars($p['name']) ?></div>
                    <div class="item-card-meta">
                        <span><i class="fa-regular fa-calendar"></i><?= $p['duration'] ?> days</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── CHALLENGES ── -->
    <?php if (!empty($challenges)): ?>
    <div class="fade-up delay-4 mt-4">
        <div class="section-header">
            <h2>Active Challenges</h2>
            <a href="<?= APP_URL ?>/pages/user/challenges.php" class="see-all">View All →</a>
        </div>
        <div class="grid-2">
            <?php foreach ($challenges as $ch): ?>
            <div class="card">
                <h4 style="font-family:var(--font-display); margin-bottom:6px;"><?= htmlspecialchars($ch['name']) ?></h4>
                <p class="text-muted" style="font-size:13px; margin-bottom:14px;"><?= htmlspecialchars($ch['description']) ?></p>
                <div class="d-flex align-center gap-2" style="margin-bottom:8px;">
                    <span style="font-size:12px; color:var(--text-3);">Progress</span>
                    <span style="margin-left:auto; color:var(--gold); font-family:var(--font-display);">
                        <?= $ch['progress'] ?> / <?= $ch['goal'] ?> <?= $ch['type'] ?>
                    </span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill"
                         data-width="<?= min(100, round($ch['progress'] / max(1,$ch['goal']) * 100)) ?>"
                         style="width:0%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div><!-- .container -->

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
