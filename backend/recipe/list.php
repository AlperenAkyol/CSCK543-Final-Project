<?php
header("Content-Type: application/json");
require_once '../db.php';

$stmt = $pdo->query("SELECT id, title, category, score FROM recipes");
echo json_encode($stmt->fetchAll());
?>
