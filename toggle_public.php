<?php
require './db.php';

// トグル操作データを取得
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['is_public'])) {
    http_response_code(400); // 不正なリクエスト
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$id = $data['id'];
$is_public = $data['is_public'];

// 公開状態を更新
$stmt = $pdo->prepare("UPDATE ck_news SET is_public = ?, modified = ? WHERE id = ?");
if ($stmt->execute([$is_public, date('Y-m-d\TH:i') , $id])) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500); // サーバーエラー
    echo json_encode(['error' => 'Failed to update']);
}
