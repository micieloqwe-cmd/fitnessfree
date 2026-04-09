<?php
// ============================================
//  ELEV8 FITNESS — Register Page
// ============================================
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/pages/user/dashboard.php');
    exit;
}

$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $error = 'รหัสผ่านไม่ตรงกัน';
    } elseif (strlen($_POST['password']) < 6) {
        $error = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
    } else {
        $result = register([
            'name'     => trim($_POST['name'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'],
            'level'    => $_POST['level'] ?? 'beginner',
            'goal'     => $_POST['goal'] ?? 'healthy',
        ]);
        if ($result['success']) {
            header('Location: ' . APP_URL . '/pages/login.php?registered=1');
            exit;
        }
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — ELEV8</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card fade-up" style="max-width:520px;">
        <div class="auth-logo"><span class="logo-icon">⬡</span>ELEV<em>8</em></div>
        <div class="auth-subtitle">Create Your Account</div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" placeholder="Your Name" required
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label">Fitness Level</label>
                    <select name="level" class="form-control">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Your Goal</label>
                    <select name="goal" class="form-control">
                        <option value="healthy">Stay Healthy</option>
                        <option value="lose_weight">Lose Weight</option>
                        <option value="build_muscle">Build Muscle</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%; justify-content:center;">
                <i class="fa-solid fa-bolt"></i> Begin My Journey
            </button>
        </form>

        <p class="text-center mt-3" style="font-size:13px; color:var(--text-3);">
            Already have an account?
            <a href="<?= APP_URL ?>/pages/login.php" class="text-gold">Sign In</a>
        </p>
    </div>
</div>
<script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>
