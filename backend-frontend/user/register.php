<?php
session_start();
require_once '../../database-connection/db.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');

    if (empty($username) || empty($email) || empty($password)) {
        $message = "Missing required fields.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $message = "Username or email already taken.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $full_name]);
            $message = "Registration successful! Please <a href='login.php'>login</a>.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="stylesheet" href="uolfma.css">
</head>
<body>
  <ul class="uolfmaMainMenu">
    <a href="index.php">
      <img class="logo" src="LogoFiles/gblogo.png" alt="GB Logo">
    </a>
  </ul>

  <div class="rglobal">
    <h2>Register</h2>
    <?php if ($message): ?>
      <div id="result" style="margin-bottom: 10px; color: <?= strpos($message, 'success') ? 'green' : 'red' ?>; font-weight: bold;">
        <?= $message ?>
      </div>
    <?php endif; ?>
    <form method="post" action="">
      <input name="username" type="text" placeholder="Username" required /><br>
      <input name="full_name" type="text" placeholder="Full Name" /><br>
      <input name="email" type="email" placeholder="Email" required /><br>
      <input name="password" type="password" placeholder="Password" required /><br>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? &nbsp;<a href="login.php"><strong>Login here</strong></a></p>
  </div>
  <div class="uolfmarfooter">
    <p>&copy; 2025 CSCK543-GB Recipes. All rights reserved.</p>
  </div>
</body>
</html>
