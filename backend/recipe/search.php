<?php
header("Content-Type: application/json");
require_once '../db.php';

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, title, category, score FROM recipes WHERE title LIKE ? OR category LIKE ?");
$search = "%" . $q . "%";
$stmt->execute([$search, $search]);
echo json_encode($stmt->fetchAll());
?>
