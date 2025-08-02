<?php
header("Content-Type: application/json");
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST allowed']);
    exit;
}

$recipe_id = intval($_POST['recipe_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);

if ($recipe_id === 0 || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Recipe rating
$stmt = $pdo->prepare("UPDATE recipes SET total_points = total_points + ?, rate_count = rate_count + 1, score = (total_points + ?) / (rate_count + 1) WHERE id = ?");
$stmt->execute([$rating, $rating, $recipe_id]);

echo json_encode(['success' => true, 'message' => 'Thanks for your rating!']);
?>
