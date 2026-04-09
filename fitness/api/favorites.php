<?php
// ============================================
//  ELEV8 FITNESS — API: Favorites
// ============================================
require_once __DIR__ . '/../includes/auth.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']); exit;
}

$uid  = $_SESSION['user_id'];
$body = json_decode(file_get_contents('php://input'), true);

$type   = $body['type']   ?? '';   // 'exercise' | 'program'
$id     = (int)($body['id'] ?? 0);
$action = $body['action'] ?? '';   // 'add' | 'remove'

if (!in_array($type, ['exercise', 'program']) || !$id || !in_array($action, ['add', 'remove'])) {
    echo json_encode(['error' => 'Invalid input']); exit;
}

$field = $type === 'exercise' ? 'exercise_id' : 'program_id';

if ($action === 'add') {
    $exists = Database::queryOne(
        "SELECT id FROM favorites WHERE user_id = ? AND $field = ?",
        [$uid, $id]
    );
    if (!$exists) {
        Database::execute("INSERT INTO favorites (user_id, $field) VALUES (?, ?)", [$uid, $id]);
    }
    echo json_encode(['success' => true, 'action' => 'added']);
} else {
    Database::execute("DELETE FROM favorites WHERE user_id = ? AND $field = ?", [$uid, $id]);
    echo json_encode(['success' => true, 'action' => 'removed']);
}
