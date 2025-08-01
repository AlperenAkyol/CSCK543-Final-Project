<?php
header("Content-Type: application/json");
require_once '../db.php';

$user_id = intval($_GET['user_id'] ?? 0);
if (!$user_id) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare(
    "SELECT r.id, r.title, r.category, r.score
     FROM recipes r
     JOIN favourites f ON f.recipe_id = r.id
     WHERE f.user_id = ?"
);
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll());
?>
