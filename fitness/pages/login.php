<?php
// ============================================
//  ELEV8 FITNESS — Login Page
// ============================================
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/pages/user/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '');
    if ($result['success']) {
        $redirect = $result['role'] === 'admin'
            ? APP_URL . '/pages/admin/dashboard.php'
            : APP_URL . '/pages/user/dashboard.php';
        header('Location: ' . $redirect);
        exit;
    }
    $error = $result['message'];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ELEV8</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/main.css">
    <style>
        .auth-bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(201,168,76,0.06); top: -100px; right: -100px; }
        .orb-2 { width: 400px; height: 400px; background: rgba(91,155,213,0.04); bottom: -80px; left: -80px; }
        .divider-line {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-3);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin: 24px 0;
        }
        .divider-line::before, .divider-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
    </style>
</head>
<body>
<div class="auth-bg-orb orb-1"></div>
<div class="auth-bg-orb orb-2"></div>

<div class="auth-page">
    <div class="auth-card fade-up">
        <div class="auth-logo">
            <span class="logo-icon">⬡</span>ELEV<em>8</em>
        </div>
        <div class="auth-subtitle">Your Premium Fitness Journey</div>

        <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control"
                       placeholder="your@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-gold" style="width:100%; justify-content:center; margin-top:8px;">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                Sign In
            </button>
        </form>

        <div class="divider-line">or</div>

        <a href="<?= APP_URL ?>/pages/register.php" class="btn btn-outline" style="width:100%; justify-content:center;">
            Create Your Account
        </a>

        <p class="text-center text-muted mt-3" style="font-size:12px;">
            Start your transformation today. No excuses.
        </p>
    </div>
</div>
<script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>
