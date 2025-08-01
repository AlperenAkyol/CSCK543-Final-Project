<?php
header("Content-Type: application/json");
require_once '../db.php';

$user_id = intval($_POST['user_id'] ?? 0);
$recipe_id = intval($_POST['recipe_id'] ?? 0);

if (!$user_id || !$recipe_id) {
    echo json_encode(['success' => false, 'message' => 'Missing user or recipe ID']);
    exit;
}

// Remove from favorites
$stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
$stmt->execute([$user_id, $recipe_id]);
echo json_encode(['success' => true, 'message' => 'Recipe removed from favorites!']);
?>
