<?php
// ============================================
//  ELEV8 FITNESS — Admin: Challenges
// ============================================
$pageTitle = 'Admin Challenges — ELEV8';
require_once __DIR__ . '/../../includes/header.php';
requireAdmin();

$success = '';
if (isset($_GET['delete'])) {
    Database::execute("DELETE FROM challenges WHERE id=?", [(int)$_GET['delete']]);
    header('Location: ' . APP_URL . '/pages/admin/challenges.php?deleted=1'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id']??0);
    $d  = [$_POST['name']??'', $_POST['description']??'', (int)($_POST['goal']??0), $_POST['type']??'days'];
    if ($id) {
        Database::execute("UPDATE challenges SET name=?,description=?,goal=?,type=? WHERE id=?", [...$d, $id]);
        $success = 'Challenge updated!';
    } else {
        Database::execute("INSERT INTO challenges (name,description,goal,type) VALUES (?,?,?,?)", $d);
        $success = 'Challenge added!';
    }
}
$editing = isset($_GET['edit']) ? Database::queryOne("SELECT * FROM challenges WHERE id=?",[(int)$_GET['edit']]) : null;
$challenges = Database::query(
    "SELECT c.*, COUNT(uc.id) as participants FROM challenges c
     LEFT JOIN user_challenges uc ON c.id=uc.challenge_id GROUP BY c.id ORDER BY c.created_at DESC"
);
$typeIcon = ['days'=>'fa-calendar','reps'=>'fa-rotate','time'=>'fa-clock','calories'=>'fa-fire'];
?>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <?php $adminNav=[['dashboard.php','fa-gauge','Dashboard'],['users.php','fa-users','Users'],['exercises.php','fa-dumbbell','Exercises'],['programs.php','fa-clipboard-list','Programs'],['challenges.php','fa-trophy','Challenges'],['badges.php','fa-medal','Badges']]; $cur=basename($_SERVER['PHP_SELF']); foreach($adminNav as[$f,$i,$l]): ?>
        <a href="<?=APP_URL?>/pages/admin/<?=$f?>" class="admin-nav-item <?=$cur===$f?'active':''?>"><i class="fa-solid <?=$i?>"></i> <?=$l?></a>
        <?php endforeach; ?>
        <div style="border-top:1px solid var(--border);margin:16px 0;"></div>
        <a href="<?=APP_URL?>/pages/user/dashboard.php" class="admin-nav-item"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </aside>
    <main class="admin-main">
        <div class="page-header" style="padding:0 0 28px;border-bottom:1px solid var(--border);margin-bottom:36px;">
            <h2>Manage <em>Challenges</em></h2>
        </div>
        <?php if($success):?><div class="alert alert-success"><?=$success?></div><?php endif;?>
        <?php if(isset($_GET['deleted'])):?><div class="alert alert-danger">Challenge deleted.</div><?php endif;?>
        <div class="grid-2">
            <div class="card">
                <h4 style="font-family:var(--font-display);margin-bottom:20px;"><?=$editing?'Edit':'Add'?> Challenge</h4>
                <form method="POST">
                    <?php if($editing):?><input type="hidden" name="id" value="<?=$editing['id']?>"><?php endif;?>
                    <div class="form-group">
                        <label class="form-label">Challenge Name *</label>
                        <input type="text" name="name" class="form-control" required value="<?=htmlspecialchars($editing['name']??'')?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?=htmlspecialchars($editing['description']??'')?></textarea>
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <?php foreach(['days','reps','time','calories'] as $t):?>
                                <option value="<?=$t?>" <?=($editing['type']??'days')===$t?'selected':''?>><?=ucfirst($t)?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Goal (number)</label>
                            <input type="number" name="goal" class="form-control" min="1" value="<?=$editing['goal']??''?>">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold"><i class="fa-solid fa-<?=$editing?'floppy-disk':'plus'?>"></i> <?=$editing?'Update':'Add'?></button>
                        <?php if($editing):?><a href="<?=APP_URL?>/pages/admin/challenges.php" class="btn btn-ghost">Cancel</a><?php endif;?>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="section-header"><h4 style="font-family:var(--font-display);">All Challenges (<?=count($challenges)?>)</h4></div>
                <div style="display:flex;flex-direction:column;gap:12px;max-height:560px;overflow-y:auto;">
                    <?php foreach($challenges as $ch):?>
                    <div style="padding:14px;background:var(--bg-3);border:1px solid var(--border);border-radius:10px;">
                        <div class="d-flex align-center gap-2">
                            <i class="fa-solid <?=$typeIcon[$ch['type']]??'fa-bolt'?>" style="color:var(--gold);"></i>
                            <span style="color:var(--text-1);font-weight:500;flex:1;"><?=htmlspecialchars($ch['name'])?></span>
                            <span style="font-size:11px;color:var(--text-3);"><?=$ch['participants']?> joined</span>
                            <a href="?edit=<?=$ch['id']?>" style="color:var(--gold);padding:4px;"><i class="fa-solid fa-pen"></i></a>
                            <a href="?delete=<?=$ch['id']?>" style="color:var(--danger);padding:4px;" onclick="return confirm('Delete?')"><i class="fa-solid fa-trash"></i></a>
                        </div>
                        <div style="font-size:12px;color:var(--text-3);margin-top:4px;">Goal: <?=$ch['goal']?> <?=$ch['type']?></div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
