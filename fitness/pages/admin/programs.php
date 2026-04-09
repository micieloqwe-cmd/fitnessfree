<?php
// ============================================
//  ELEV8 FITNESS — Admin: Programs
// ============================================
$pageTitle = 'Admin Programs — ELEV8';
require_once __DIR__ . '/../../includes/header.php';

requireAdmin();

$editing  = isset($_GET['edit']) ? Database::queryOne("SELECT * FROM programs WHERE id=?", [(int)$_GET['edit']]) : null;

$success = '';

if (isset($_GET['delete'])) {
    $toDelete = Database::queryOne("SELECT * FROM programs WHERE id=?", [(int)$_GET['delete']]);
    if ($toDelete && !empty($toDelete['image_url'])) {
        $filePath = __DIR__ . '/../../assets/images/programs/' . $toDelete['image_url'];
        if (is_file($filePath)) @unlink($filePath);
    }
    Database::execute("DELETE FROM programs WHERE id = ?", [(int)$_GET['delete']]);
    header('Location: ' . APP_URL . '/pages/admin/programs.php?deleted=1'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    // handle image upload
    $imageUrl = $editing['image_url'] ?? null;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadsDir = __DIR__ . '/../../assets/images/programs/';
        if (!is_dir($uploadsDir)) @mkdir($uploadsDir, 0755, true);
        $tmp = $_FILES['image']['tmp_name'];
        $origName = $_FILES['image']['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed, true) && filesize($tmp) <= 2 * 1024 * 1024) {
            $filename = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = $uploadsDir . $filename;
            if (move_uploaded_file($tmp, $dest)) {
                // remove old file if editing
                if (!empty($imageUrl)) {
                    $old = $uploadsDir . $imageUrl;
                    if (is_file($old)) @unlink($old);
                }
                $imageUrl = $filename;
            }
        }
    }

    $data = [
        $_POST['name']        ?? '',
        $_POST['level']       ?? 'beginner',
        $_POST['goal']        ?? 'healthy',
        (int)($_POST['duration'] ?? 0),
        $_POST['description'] ?? '',
        $imageUrl,
    ];
    if ($id) {
        Database::execute("UPDATE programs SET name=?,level=?,goal=?,duration=?,description=?,image_url=? WHERE id= ?", [...$data, $id]);
        $success = 'Program updated!';
    } else {
        Database::execute("INSERT INTO programs (name,level,goal,duration,description,image_url) VALUES (?,?,?,?,?,?)", $data);
        $success = 'Program added!';
    }
    // reload editing record after save
    $editing  = $id ? Database::queryOne("SELECT * FROM programs WHERE id=?", [$id]) : null;
}

$editing  = isset($_GET['edit']) ? Database::queryOne("SELECT * FROM programs WHERE id=?", [(int)$_GET['edit']]) : null;
$programs = Database::query(
    "SELECT p.*, COUNT(DISTINCT pd.id) as day_count FROM programs p
     LEFT JOIN program_days pd ON p.id = pd.program_id
     GROUP BY p.id ORDER BY p.name"
);
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
        foreach ($adminNav as [$f,$i,$l]):
        ?>
        <a href="<?= APP_URL ?>/pages/admin/<?= $f ?>" class="admin-nav-item <?= $cur===$f?'active':'' ?>">
            <i class="fa-solid <?= $i ?>"></i> <?= $l ?>
        </a>
        <?php endforeach; ?>
        <div style="border-top:1px solid var(--border);margin:16px 0;"></div>
        <a href="<?= APP_URL ?>/pages/user/dashboard.php" class="admin-nav-item"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </aside>

    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px;border-bottom:1px solid var(--border);margin-bottom:36px;">
            <h2><?= $editing ? 'Edit' : 'Manage' ?> <em>Programs</em></h2>
        </div>

        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-danger">Program deleted.</div><?php endif; ?>

        <div class="grid-2">
            <!-- Form -->
            <div class="card">
                <h4 style="font-family:var(--font-display);margin-bottom:20px;"><?= $editing?'Edit':'Add New' ?> Program</h4>
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($editing): ?><input type="hidden" name="id" value="<?= $editing['id'] ?>"><?php endif; ?>
                    <div class="form-group">
                        <label class="form-label">Program Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($editing['name']??'') ?>">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-control">
                                <?php foreach(['beginner','intermediate','advanced'] as $l): ?>
                                <option value="<?=$l?>" <?=($editing['level']??'beginner')===$l?'selected':''?>><?=ucfirst($l)?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Goal</label>
                            <select name="goal" class="form-control">
                                <option value="healthy"      <?=($editing['goal']??'')==='healthy'?'selected':''?>>Stay Healthy</option>
                                <option value="lose_weight"  <?=($editing['goal']??'')==='lose_weight'?'selected':''?>>Lose Weight</option>
                                <option value="build_muscle" <?=($editing['goal']??'')==='build_muscle'?'selected':''?>>Build Muscle</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration (days)</label>
                        <input type="number" name="duration" class="form-control" min="1" value="<?= $editing['duration']??'' ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image</label>
                        <?php if (!empty($editing['image_url'])): ?>
                        <div style="margin-bottom:8px;"><img src="<?= APP_URL ?>/assets/images/programs/<?= htmlspecialchars($editing['image_url']) ?>" alt="program" style="max-width:150px;border-radius:6px;border:1px solid var(--border);"></div>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($editing['description']??'') ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold">
                            <i class="fa-solid fa-<?=$editing?'floppy-disk':'plus'?>"></i> <?=$editing?'Update':'Add Program'?>
                        </button>
                        <?php if ($editing): ?><a href="<?=APP_URL?>/pages/admin/programs.php" class="btn btn-ghost">Cancel</a><?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="card">
                <div class="section-header">
                    <h4 style="font-family:var(--font-display);">All Programs (<?=count($programs)?>)</h4>
                </div>
                <div class="table-wrap" style="max-height:560px;overflow-y:auto;">
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Level</th><th>Duration</th><th>Days</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach($programs as $p): ?>
                        <tr>
                            <td style="color:var(--text-1);"><?=htmlspecialchars($p['name'])?></td>
                            <td><span class="item-card-tag tag-<?=$p['level']?>"><?=ucfirst($p['level'])?></span></td>
                            <td><?=$p['duration']?> d</td>
                            <td style="color:var(--text-3);"><?=$p['day_count']?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="?edit=<?=$p['id']?>" style="color:var(--gold);"><i class="fa-solid fa-pen"></i></a>
                                    <a href="?delete=<?=$p['id']?>" style="color:var(--danger);" onclick="return confirm('Delete this program?')"><i class="fa-solid fa-trash"></i></a>
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
