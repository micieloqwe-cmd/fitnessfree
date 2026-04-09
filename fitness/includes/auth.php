<?php
// ============================================
//  FITNESS APP — Auth Helpers
// ============================================
require_once __DIR__ . '/db.php';

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . APP_URL . '/pages/login.php');
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . APP_URL . '/pages/user/dashboard.php');
        exit;
    }
}

function login(string $email, string $password): array {
    $user = Database::queryOne(
        "SELECT * FROM users WHERE email = ?",
        [$email]
    );

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'];
    }

    // Update last login
    Database::execute(
        "UPDATE users SET last_login = NOW() WHERE id = ?",
        [$user['id']]
    );

    // Set session
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['level']     = $user['level'];
    $_SESSION['goal']      = $user['goal'];
    $_SESSION['avatar']    = $user['avatar'];

    return ['success' => true, 'role' => $user['role']];
}

function logout(): void {
    session_destroy();
    header('Location: ' . APP_URL . '/pages/login.php');
    exit;
}

function register(array $data): array {
    // Check email exists
    $exists = Database::queryOne("SELECT id FROM users WHERE email = ?", [$data['email']]);
    if ($exists) {
        return ['success' => false, 'message' => 'อีเมลนี้ถูกใช้งานแล้ว'];
    }

    $hash = password_hash($data['password'], PASSWORD_BCRYPT);
    Database::execute(
        "INSERT INTO users (name, email, password, level, goal) VALUES (?, ?, ?, ?, ?)",
        [$data['name'], $data['email'], $hash, $data['level'] ?? 'beginner', $data['goal'] ?? 'healthy']
    );

    $newId = Database::lastInsertId();

    // Init user_stats
    Database::execute(
        "INSERT INTO user_stats (user_id) VALUES (?)",
        [$newId]
    );

    return ['success' => true, 'user_id' => $newId];
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    return Database::queryOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
}
