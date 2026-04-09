<?php
// ============================================
//  ELEV8 FITNESS — Program Detail Page
// ============================================
$pageTitle = 'Program Detail — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: ' . APP_URL . '/pages/user/programs.php'); exit; }

$program = Database::queryOne("SELECT * FROM programs WHERE id = ?", [$id]);
if (!$program) { header('Location: ' . APP_URL . '/pages/user/programs.php'); exit; }

// Program days with exercises
$days = Database::query(
    "SELECT pd.*, GROUP_CONCAT(e.name ORDER BY pe.id SEPARATOR '||') as exercise_names,
            GROUP_CONCAT(pe.sets ORDER BY pe.id SEPARATOR '||') as sets,
            GROUP_CONCAT(pe.reps ORDER BY pe.id SEPARATOR '||') as reps,
            GROUP_CONCAT(pe.duration ORDER BY pe.id SEPARATOR '||') as durations
     FROM program_days pd
     LEFT JOIN program_exercises pe ON pd.id = pe.program_day_id
     LEFT JOIN exercises e ON pe.exercise_id = e.id
     WHERE pd.program_id = ?
     GROUP BY pd.id
     ORDER BY pd.day_number",
    [$id]
);

$goalLabel = ['lose_weight' => 'Lose Weight', 'build_muscle' => 'Build Muscle', 'healthy' => 'Stay Healthy'];
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="<?= APP_URL ?>/pages/user/programs.php">Programs</a> / <span><?= htmlspecialchars($program['name']) ?></span>
        </div>
        <h1 style="display:flex;align-items:center;gap:16px;">
            <?php if (!empty($program['image_url'])): ?>
                <img src="<?= APP_URL ?>/assets/images/programs/<?= htmlspecialchars($program['image_url']) ?>" alt="<?= htmlspecialchars($program['name']) ?>" style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
            <?php endif; ?>
            <span><?= htmlspecialchars($program['name']) ?></span>
        </h1>
        <div class="d-flex gap-2 mt-2 align-center" style="flex-wrap:wrap;">
            <span class="item-card-tag tag-<?= $program['level'] ?>"><?= ucfirst($program['level']) ?></span>
            <span class="item-card-tag tag-<?= $program['goal'] ?>"><?= $goalLabel[$program['goal']] ?? '' ?></span>
            <span class="badge-pill"><i class="fa-regular fa-calendar"></i><?= $program['duration'] ?> Days</span>
        </div>
        <?php if ($program['description']): ?>
        <p class="mt-2"><?= htmlspecialchars($program['description']) ?></p>
        <?php endif; ?>
    </div>

    <!-- Days -->
    <?php if (empty($days)): ?>
    <div class="empty-state" style="text-align:center;padding:28px 0;">
        <?php if (!empty($program['image_url'])): ?>
            <img src="<?= APP_URL ?>/assets/images/programs/<?= htmlspecialchars($program['image_url']) ?>" alt="<?= htmlspecialchars($program['name']) ?>" style="max-width:240px;border-radius:8px;margin-bottom:12px;border:1px solid var(--border);">
        <?php else: ?>
            <i class="fa-solid fa-calendar-days" style="font-size:40px;margin-bottom:8px;color:var(--text-3);"></i>
        <?php endif; ?>
        <h3>No days configured yet</h3>
        <p>This program's schedule is being built.</p>
    </div>
    <?php else: ?>
    <div style="display:flex; flex-direction:column; gap:20px;">
        <?php foreach ($days as $i => $day): ?>
        <div class="card fade-up" style="animation-delay:<?= $i * 0.08 ?>s;">
            <div style="display:flex; align-items:center; gap:16px; margin-bottom:16px;">
                <div style="width:48px; height:48px; border-radius:10px; background:var(--gold-dim); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1.3rem; color:var(--gold); flex-shrink:0;">
                    <?= $day['day_number'] ?>
                </div>
                <div>
                    <h4 style="font-family:var(--font-display);"><?= htmlspecialchars($day['name'] ?? 'Day ' . $day['day_number']) ?></h4>
                    <span class="text-muted text-sm">Day <?= $day['day_number'] ?></span>
                </div>
            </div>

            <?php
            $names    = $day['exercise_names'] ? explode('||', $day['exercise_names']) : [];
            $sets_arr = $day['sets']           ? explode('||', $day['sets'])           : [];
            $reps_arr = $day['reps']           ? explode('||', $day['reps'])           : [];
            ?>
            <?php if (empty($names)): ?>
            <p class="text-muted text-sm">Rest day — no exercises scheduled.</p>
            <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Exercise</th>
                            <th>Sets</th>
                            <th>Reps</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($names as $j => $name): ?>
                        <tr>
                            <td style="color:var(--text-3);"><?= $j + 1 ?></td>
                            <td style="color:var(--text-1);"><?= htmlspecialchars($name) ?></td>
                            <td><?= $sets_arr[$j] ?? '—' ?></td>
                            <td><?= $reps_arr[$j] ?? '—' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
