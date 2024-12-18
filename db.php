<?php
$sql = 'mysql';
$host = 'localhost'; // のホスト名
$db = 'db_new_global_test';  // データベース名
$user = 'root';      // ユーザー名
$pass = '';          // パスワード


date_default_timezone_set ('Asia/Tokyo');

try {
    $pdo = new PDO("$sql:host=$host;dbname=$db;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続エラー: " . $e->getMessage());
}


?>
