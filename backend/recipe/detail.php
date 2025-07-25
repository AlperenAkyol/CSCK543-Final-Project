<?php
header("Content-Type: application/json");
require_once '../db.php';

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    echo json_encode(["error" => "Missing id"]);
    exit;
}

// Main recipe info
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$id]);
$recipe = $stmt->fetch();
if (!$recipe) {
    echo json_encode(["error" => "Recipe not found"]);
    exit;
}

// Ingredients
$stmt = $pdo->prepare("SELECT ingredient, quantity FROM recipe_ingredients WHERE recipe_id = ?");
$stmt->execute([$id]);
$recipe['ingredients'] = $stmt->fetchAll();

// Steps
$stmt = $pdo->prepare("SELECT step_number, description, duration_minutes FROM recipe_steps WHERE recipe_id = ? ORDER BY step_number");
$stmt->execute([$id]);
$recipe['steps'] = $stmt->fetchAll();

echo json_encode($recipe);
?>
