<?php
header("Content-Type: application/json");
require_once '../db.php';

$user_id = intval($_GET['user_id'] ?? 0);
$recipe_id = intval($_GET['recipe_id'] ?? 0);

if (!$user_id || !$recipe_id) {
    echo json_encode(['favorited' => false]);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM favourites WHERE user_id = ? AND recipe_id = ?");
$stmt->execute([$user_id, $recipe_id]);
$is_fav = $stmt->fetch() ? true : false;

echo json_encode(['favorited' => $is_fav]);
?>
