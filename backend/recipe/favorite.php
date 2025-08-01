<?php
session_start();
header("Content-Type: application/json");
require_once '../db.php';

$user_id = intval($_POST['user_id'] ?? 0);
$recipe_id = intval($_POST['recipe_id'] ?? 0);

if (!$user_id || !$recipe_id) {
    echo json_encode(['success' => false, 'message' => 'Missing user or recipe ID']);
    exit;
}

// Check if already favorited
$stmt = $pdo->prepare("SELECT id FROM favourites WHERE user_id = ? AND recipe_id = ?");
$stmt->execute([$user_id, $recipe_id]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Already in favorites']);
    exit;
}

// Add to favorites
$stmt = $pdo->prepare("INSERT INTO favourites (user_id, recipe_id) VALUES (?, ?)");
$stmt->execute([$user_id, $recipe_id]);
echo json_encode(['success' => true, 'message' => 'Recipe added to favorites!']);
?>
