<?php
header("Content-Type: application/json");
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_or_email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Missing credentials."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch();

    if ($user && $user['password'] === $password) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(["success" => true, "message" => "Login successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials."]);
    }
}
?>
