<?php
header("Content-Type: application/json");
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
        exit;
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Username or email already taken."]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $full_name]);

    echo json_encode(["success" => true, "message" => "Registration successful!"]);
}
?>
