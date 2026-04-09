<?php
// ============================================
//  ELEV8 FITNESS — Admin: Exercises
// ============================================
$pageTitle = 'Admin Exercises — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireAdmin();

$success = $error = '';

// ── DELETE ──
if (isset($_GET['delete'])) {
    $del = (int)$_GET['delete'];
    Database::execute("DELETE FROM exercises WHERE id = ?", [$del]);
    header('Location: ' . APP_URL . '/pages/admin/exercises.php?deleted=1');
    exit;
}

// ── ADD / EDIT ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)($_POST['id'] ?? 0);
    $data  = [
        $_POST['name']         ?? '',
        $_POST['muscle_group'] ?? '',
        $_POST['equipment']    ?? '',
        $_POST['description']  ?? '',
        $_POST['image_url']    ?? '',
        $_POST['video_url']    ?? '',
        $_POST['level']        ?? 'beginner',
    ];
    if ($id) {
        Database::execute(
            "UPDATE exercises SET name=?,muscle_group=?,equipment=?,description=?,image_url=?,video_url=?,level=? WHERE id=?",
            [...$data, $id]
        );
        $success = 'Exercise updated!';
    } else {
        Database::execute(
            "INSERT INTO exercises (name,muscle_group,equipment,description,image_url,video_url,level) VALUES (?,?,?,?,?,?,?)",
            $data
        );
        $success = 'Exercise added!';
    }
}

$editing = null;
if (isset($_GET['edit'])) {
    $editing = Database::queryOne("SELECT * FROM exercises WHERE id = ?", [(int)$_GET['edit']]);
}

$exercises = Database::query("SELECT * FROM exercises ORDER BY name ASC");
?>

<div class="admin-layout">
    <aside class="admin-sidebar">
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

    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px; border-bottom:1px solid var(--border); margin-bottom:36px;">
            <h2><?= $editing ? 'Edit' : 'Manage' ?> <em>Exercises</em></h2>
        </div>

        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-danger">Exercise deleted.</div><?php endif; ?>

        <div class="grid-2">
            <!-- ── FORM ── -->
            <div class="card">
                <h4 style="font-family:var(--font-display); margin-bottom:20px;">
                    <?= $editing ? 'Edit Exercise' : 'Add New Exercise' ?>
                </h4>
                <form method="POST">
                    <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?= $editing['id'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label">Exercise Name *</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?= htmlspecialchars($editing['name'] ?? '') ?>">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Muscle Group</label>
                            <input type="text" name="muscle_group" class="form-control" placeholder="e.g. Chest"
                                   value="<?= htmlspecialchars($editing['muscle_group'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Equipment</label>
                            <input type="text" name="equipment" class="form-control" placeholder="e.g. Barbell"
                                   value="<?= htmlspecialchars($editing['equipment'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level</label>
                        <select name="level" class="form-control">
                            <?php foreach (['beginner','intermediate','advanced'] as $lvl): ?>
                            <option value="<?= $lvl ?>" <?= ($editing['level'] ?? 'beginner') === $lvl ? 'selected' : '' ?>><?= ucfirst($lvl) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="image_url" class="form-control" placeholder="https://..."
                               value="<?= htmlspecialchars($editing['image_url'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Video URL</label>
                        <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/..."
                               value="<?= htmlspecialchars($editing['video_url'] ?? '') ?>">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold">
                            <i class="fa-solid fa-<?= $editing ? 'floppy-disk' : 'plus' ?>"></i>
                            <?= $editing ? 'Update' : 'Add Exercise' ?>
                        </button>
                        <?php if ($editing): ?>
                        <a href="<?= APP_URL ?>/pages/admin/exercises.php" class="btn btn-ghost">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- ── LIST ── -->
            <div class="card">
                <div class="section-header">
                    <h4 style="font-family:var(--font-display);">All Exercises (<?= count($exercises) ?>)</h4>
                </div>
                <div class="table-wrap" style="max-height:600px; overflow-y:auto;">
                    <table class="data-table">
                        <thead>
                            <tr><th>Name</th><th>Muscle</th><th>Level</th><th style="width:80px;"></th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $ex): ?>
                            <tr>
                                <td style="color:var(--text-1);"><?= htmlspecialchars($ex['name']) ?></td>
                                <td><?= htmlspecialchars($ex['muscle_group'] ?? '—') ?></td>
                                <td><span class="item-card-tag tag-<?= $ex['level'] ?>"><?= ucfirst($ex['level'] ?? '') ?></span></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="?edit=<?= $ex['id'] ?>" style="color:var(--gold);" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="?delete=<?= $ex['id'] ?>" style="color:var(--danger);" title="Delete"
                                           onclick="return confirm('Delete this exercise?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
