<?php
// ============================================
//  ELEV8 FITNESS — Exercises Page
// ============================================
$pageTitle = 'Exercises — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$uid = $_SESSION['user_id'];

// Get filter params
$level = $_GET['level'] ?? 'all';
$muscle = $_GET['muscle'] ?? 'all';
$search = trim($_GET['q'] ?? '');

// Build query
$sql = "SELECT * FROM exercises WHERE 1=1";
$params = [];

if ($level !== 'all') { $sql .= " AND level = ?"; $params[] = $level; }
if ($muscle !== 'all') { $sql .= " AND muscle_group = ?"; $params[] = $muscle; }
if ($search !== '')   { $sql .= " AND (name LIKE ? OR description LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
$sql .= " ORDER BY name ASC";

$exercises = Database::query($sql, $params);

// Muscle groups for filter
$muscles = Database::query("SELECT DISTINCT muscle_group FROM exercises WHERE muscle_group IS NOT NULL ORDER BY muscle_group");

// User favorites
$favRows = Database::query("SELECT exercise_id FROM favorites WHERE user_id = ? AND exercise_id IS NOT NULL", [$uid]);
$favSet  = array_column($favRows, 'exercise_id');
?>

<div class="container page-content">
    <div class="page-header">
        <div class="breadcrumb">ELEV8 / <span>Exercises</span></div>
        <h1>Exercise <em>Library</em></h1>
        <p>Master every movement. Browse our complete collection of exercises.</p>
    </div>

    <!-- ── SEARCH + FILTERS ── -->
    <form method="GET" style="margin-bottom:28px;">
        <div class="d-flex gap-2 align-center" style="flex-wrap:wrap;">
            <div style="position:relative; flex:1; min-width:200px;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-3); font-size:13px;"></i>
                <input type="text" name="q" class="form-control" placeholder="Search exercises..."
                       value="<?= htmlspecialchars($search) ?>"
                       style="padding-left:38px;">
            </div>
            <select name="level" class="form-control" style="width:auto;">
                <option value="all">All Levels</option>
                <option value="beginner"     <?= $level === 'beginner'     ? 'selected' : '' ?>>Beginner</option>
                <option value="intermediate" <?= $level === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                <option value="advanced"     <?= $level === 'advanced'     ? 'selected' : '' ?>>Advanced</option>
            </select>
            <select name="muscle" class="form-control" style="width:auto;">
                <option value="all">All Muscles</option>
                <?php foreach ($muscles as $m): ?>
                <option value="<?= htmlspecialchars($m['muscle_group']) ?>"
                    <?= $muscle === $m['muscle_group'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['muscle_group']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-gold btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
            <?php if ($search || $level !== 'all' || $muscle !== 'all'): ?>
            <a href="<?= APP_URL ?>/pages/user/exercises.php" class="btn btn-ghost btn-sm">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- ── RESULTS COUNT ── -->
    <p class="text-muted mb-3" style="font-size:13px;">
        <?= count($exercises) ?> exercise<?= count($exercises) !== 1 ? 's' : '' ?> found
    </p>

    <!-- ── EXERCISE GRID ── -->
    <?php if (empty($exercises)): ?>
    <div class="empty-state">
        <i class="fa-solid fa-person-running"></i>
        <h3>No Exercises Found</h3>
        <p>Try adjusting your search or filters.</p>
    </div>
    <?php else: ?>
    <div class="grid-auto">
        <?php foreach ($exercises as $i => $ex): ?>
        <div class="item-card fade-up" style="animation-delay:<?= $i * 0.06 ?>s;"
             onclick="openExerciseModal(<?= htmlspecialchars(json_encode($ex)) ?>)">

            <!-- Fav button -->
            <button class="fav-btn <?= in_array($ex['id'], $favSet) ? 'active' : '' ?>"
                    onclick="event.stopPropagation(); toggleFavorite(this, 'exercise', <?= $ex['id'] ?>)"
                    title="Favorite">
                <i class="<?= in_array($ex['id'], $favSet) ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
            </button>

            <!-- Image / Icon -->
            <div class="item-card-img">
                <?php if ($ex['image_url']): ?>
                <img src="<?= htmlspecialchars($ex['image_url']) ?>" alt="<?= htmlspecialchars($ex['name']) ?>" loading="lazy">
                <?php else: ?>
                <i class="fa-solid fa-dumbbell"></i>
                <?php endif; ?>
            </div>

            <div class="item-card-body">
                <span class="item-card-tag tag-<?= $ex['level'] ?>"><?= ucfirst($ex['level'] ?? 'beginner') ?></span>
                <div class="item-card-title"><?= htmlspecialchars($ex['name']) ?></div>
                <div class="item-card-meta">
                    <?php if ($ex['muscle_group']): ?>
                    <span><i class="fa-solid fa-circle-dot"></i><?= htmlspecialchars($ex['muscle_group']) ?></span>
                    <?php endif; ?>
                    <?php if ($ex['equipment']): ?>
                    <span><i class="fa-solid fa-toolbox"></i><?= htmlspecialchars($ex['equipment']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ── EXERCISE MODAL ── -->
<div class="modal-overlay" id="exerciseModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title" style="font-family:var(--font-display);"></h3>
            <button class="modal-close" onclick="closeModal('exerciseModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div id="modal-body"></div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<script>
function openExerciseModal(ex) {
    document.getElementById('modal-title').textContent = ex.name;
    const levelColor = { beginner: '#4caf88', intermediate: '#5b9bd5', advanced: '#c9a84c' };
    const html = `
        <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
            <span class="item-card-tag tag-${ex.level}">${(ex.level||'').charAt(0).toUpperCase()+(ex.level||'').slice(1)}</span>
            ${ex.muscle_group ? `<span class="badge-pill"><i class="fa-solid fa-circle-dot"></i>${ex.muscle_group}</span>` : ''}
            ${ex.equipment    ? `<span class="badge-pill"><i class="fa-solid fa-toolbox"></i>${ex.equipment}</span>`    : ''}
        </div>
        ${ex.image_url ? `<img src="${ex.image_url}" style="width:100%;border-radius:10px;margin-bottom:16px;max-height:260px;object-fit:cover;" alt="">` : ''}
        ${ex.description ? `<p style="color:var(--text-2);font-size:14.5px;line-height:1.7;margin-bottom:16px;">${ex.description}</p>` : ''}
        ${ex.video_url ? `<a href="${ex.video_url}" target="_blank" class="btn btn-outline btn-sm"><i class="fa-brands fa-youtube"></i> Watch Video</a>` : ''}
    `;
    document.getElementById('modal-body').innerHTML = html;
    openModal('exerciseModal');
}
</script>
