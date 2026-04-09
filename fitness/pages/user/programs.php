<?php
// ============================================
//  ELEV8 FITNESS — Programs Page
// ============================================
$pageTitle = 'Programs — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];
$programs = Database::query("SELECT * FROM programs ORDER BY level, name");

$favRows = Database::query("SELECT program_id FROM favorites WHERE user_id = ? AND program_id IS NOT NULL", [$uid]);
$favSet  = array_column($favRows, 'program_id');

$goalIcon = ['lose_weight' => 'fa-fire', 'build_muscle' => 'fa-dumbbell', 'healthy' => 'fa-heart-pulse'];
$goalLabel = ['lose_weight' => 'Lose Weight', 'build_muscle' => 'Build Muscle', 'healthy' => 'Stay Healthy'];
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Programs</span></div>
        <h1>Training <em>Programs</em></h1>
        <p>Structured plans designed to transform your body and exceed your goals.</p>
    </div>

    <!-- ── FILTER TABS ── -->
    <div class="filter-tabs">
        <button class="filter-tab active" data-filter="all">All Programs</button>
        <button class="filter-tab" data-filter="beginner">Beginner</button>
        <button class="filter-tab" data-filter="intermediate">Intermediate</button>
        <button class="filter-tab" data-filter="advanced">Advanced</button>
        <button class="filter-tab" data-filter="lose_weight">Lose Weight</button>
        <button class="filter-tab" data-filter="build_muscle">Build Muscle</button>
        <button class="filter-tab" data-filter="healthy">Healthy</button>
    </div>

    <!-- ── PROGRAM CARDS ── -->
    <?php if (empty($programs)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-person-running"></i>
        <h3>No Programs Available</h3>
        <p>Programs will appear here once added by the admin.</p>
    </div>
    <?php else: ?>
    <div class="filter-grid grid-auto">
        <?php foreach ($programs as $i => $p): ?>
        <div class="item-card fade-up" style="animation-delay:<?= $i * 0.07 ?>s;"
             data-tags="<?= $p['level'] ?>,<?= $p['goal'] ?>">

            <button class="fav-btn <?= in_array($p['id'], $favSet) ? 'active' : '' ?>"
                    onclick="event.stopPropagation(); toggleFavorite(this, 'program', <?= $p['id'] ?>)">
                <i class="<?= in_array($p['id'], $favSet) ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
            </button>

            <div class="item-card-img" style="background:linear-gradient(135deg, var(--bg-3), rgba(201,168,76,0.06));">
                <i class="fa-solid <?= $goalIcon[$p['goal']] ?? 'fa-person-running' ?>" style="font-size:3.5rem; color:var(--gold); opacity:0.6;"></i>
            </div>

            <div class="item-card-body">
                <span class="item-card-tag tag-<?= $p['level'] ?>"><?= ucfirst($p['level']) ?></span>
                <span class="item-card-tag tag-<?= $p['goal'] ?>" style="margin-left:6px;"><?= $goalLabel[$p['goal']] ?? '' ?></span>
                <div class="item-card-title"><?= htmlspecialchars($p['name']) ?></div>
                <?php if ($p['description']): ?>
                <p style="font-size:13px; color:var(--text-3); margin-bottom:12px; line-height:1.5;">
                    <?= htmlspecialchars(mb_substr($p['description'], 0, 90)) . (mb_strlen($p['description']) > 90 ? '...' : '') ?>
                </p>
                <?php endif; ?>
                <div class="item-card-meta">
                    <span><i class="fa-regular fa-calendar"></i><?= $p['duration'] ?> days</span>
                </div>
                <a href="<?= APP_URL ?>/pages/user/program_detail.php?id=<?= $p['id'] ?>"
                   class="btn btn-outline btn-sm mt-2" onclick="event.stopPropagation();">
                   View Program <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
