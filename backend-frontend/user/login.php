<?php
session_start();
require_once '../../database-connection/db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username_or_email) || empty($password)) {
        $error = "Missing credentials.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username_or_email, $username_or_email]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: ../recipe/recipes.php");
            exit;
        } else {
            $error = "Invalid credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="uolfma.css">
</head>
<body>
  <ul class="uolfmaMainMenu">
    <a href="index.php">
      <img class="logo" src="LogoFiles/gblogo.png" alt="GB Logo">
    </a>
  </ul>

  <div class="rglobal">
    <h2>Login</h2>
    <?php if ($error): ?>
      <div id="result" style="margin-bottom: 10px; color: red; font-weight: bold;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form method="post" action="">
      <input name="username_or_email" type="text" placeholder="Username or Email" required /><br>
      <input name="password" type="password" placeholder="Password" required /><br>
      <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
  </div>
  <div class="uolfmarfooter">
    <p>&copy; 2025 CSCK543-GB Recipes. All rights reserved.</p>
  </div>
</body>
</html>
