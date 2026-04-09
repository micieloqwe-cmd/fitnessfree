<?php
// ============================================
//  ELEV8 FITNESS — Admin: Badges
// ============================================
$pageTitle = 'Admin Badges — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireAdmin();

$success = '';
if (isset($_GET['delete'])) {
    Database::execute("DELETE FROM badges WHERE id=?", [(int)$_GET['delete']]);
    header('Location: ' . APP_URL . '/pages/admin/badges.php?deleted=1'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id']??0);
    $d  = [$_POST['name']??'', $_POST['icon']??'fa-solid fa-medal', $_POST['description']??''];
    if ($id) {
        Database::execute("UPDATE badges SET name=?,icon=?,description=? WHERE id=?", [...$d,$id]);
        $success = 'Badge updated!';
    } else {
        Database::execute("INSERT INTO badges (name,icon,description) VALUES (?,?,?)", $d);
        $success = 'Badge added!';
    }
}
$editing = isset($_GET['edit']) ? Database::queryOne("SELECT * FROM badges WHERE id=?",[(int)$_GET['edit']]) : null;
$badges  = Database::query("SELECT b.*, COUNT(ub.id) as holders FROM badges b LEFT JOIN user_badges ub ON b.id=ub.badge_id GROUP BY b.id ORDER BY b.name");
?>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <?php $adminNav=[['dashboard.php','fa-gauge','Dashboard'],['users.php','fa-users','Users'],['exercises.php','fa-dumbbell','Exercises'],['programs.php','fa-clipboard-list','Programs'],['challenges.php','fa-trophy','Challenges'],['badges.php','fa-medal','Badges']]; $cur=basename($_SERVER['PHP_SELF']); foreach($adminNav as[$f,$i,$l]):?>
        <a href="<?=APP_URL?>/pages/admin/<?=$f?>" class="admin-nav-item <?=$cur===$f?'active':''?>"><i class="fa-solid <?=$i?>"></i> <?=$l?></a>
        <?php endforeach;?>
        <div style="border-top:1px solid var(--border);margin:16px 0;"></div>
        <a href="<?=APP_URL?>/pages/user/dashboard.php" class="admin-nav-item"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </aside>
    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px;border-bottom:1px solid var(--border);margin-bottom:36px;">
            <h2>Manage <em>Badges</em></h2>
        </div>
        <?php if($success):?><div class="alert alert-success"><?=$success?></div><?php endif;?>
        <?php if(isset($_GET['deleted'])):?><div class="alert alert-danger">Badge deleted.</div><?php endif;?>
        <div class="grid-2">
            <div class="card">
                <h4 style="font-family:var(--font-display);margin-bottom:20px;"><?=$editing?'Edit':'Add'?> Badge</h4>
                <form method="POST">
                    <?php if($editing):?><input type="hidden" name="id" value="<?=$editing['id']?>"><?php endif;?>
                    <div class="form-group">
                        <label class="form-label">Badge Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?=htmlspecialchars($editing['name']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Icon (Font Awesome class)</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa-solid fa-medal"
                               value="<?=htmlspecialchars($editing['icon']??'fa-solid fa-medal')?>">
                        <p style="font-size:11px;color:var(--text-3);margin-top:4px;">e.g. fa-solid fa-trophy, fa-solid fa-fire</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($editing['description']??'')?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold"><i class="fa-solid fa-<?=$editing?'floppy-disk':'plus'?>"></i> <?=$editing?'Update':'Add Badge'?></button>
                        <?php if($editing):?><a href="<?=APP_URL?>/pages/admin/badges.php" class="btn btn-ghost">Cancel</a><?php endif;?>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="section-header"><h4 style="font-family:var(--font-display);">All Badges (<?=count($badges)?>)</h4></div>
                <div style="display:flex;flex-direction:column;gap:10px;max-height:500px;overflow-y:auto;">
                    <?php foreach($badges as $b):?>
                    <div style="display:flex;align-items:center;gap:12px;padding:12px;background:var(--bg-3);border:1px solid var(--border);border-radius:10px;">
                        <div style="width:38px;height:38px;border-radius:8px;background:var(--gold-dim);display:flex;align-items:center;justify-content:center;color:var(--gold);">
                            <i class="<?=htmlspecialchars($b['icon'])?>"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="color:var(--text-1);font-weight:500;"><?=htmlspecialchars($b['name'])?></div>
                            <div style="font-size:11px;color:var(--text-3);"><?=$b['holders']?> holders</div>
                        </div>
                        <a href="?edit=<?=$b['id']?>" style="color:var(--gold);padding:4px;"><i class="fa-solid fa-pen"></i></a>
                        <a href="?delete=<?=$b['id']?>" style="color:var(--danger);padding:4px;" onclick="return confirm('Delete badge?')"><i class="fa-solid fa-trash"></i></a>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
