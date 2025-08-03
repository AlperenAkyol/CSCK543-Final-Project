<?php
session_start();
require_once '../../backend/db.php'; // Adjust path as needed

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

        if ($user && $user['password'] === $password) { // use password_verify if hashed!
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
  <meta name="description" content="Login to your recipe account">
  <meta name="keywords" content="login, user, account"> 
  <title>CSCK543-GB Recipes</title>
  <link rel="stylesheet" href="uolfma.css">
  <link rel="icon" type="image/x-icon" href="FaviconLogo.ico">
  <link rel="icon" type="image/x-icon" href="/LogoFiles/FaviconLogo.ico">
</head>
<body>
  <ul class="uolfmaMainMenu">
    <a href="index.php">
      <img class="logo" src="LogoFiles/gblogo.png" alt="GB Logo">
    </a>
  </ul>

  <div class="global">
    <div class="left">
      <h1>Welcome to Group <strong>B</strong> recipe catalogue Online</h1>
      <p>Your one-stop solution for delicious and nutritious food recipes.</p><br>

      <h2>Tired of Takeout? Your Weeknight Dinner Dilemma—Solved.</h2>
      <p>Say goodbye to boring meals and long waits for delivery. Introducing our weekend recipe catalogue</p><br><br>
    </div>

    <div class="right">
      <h2>Login or Register</h2>
      <p>Please login or register to view your order history,<br>save favorite dishes, and manage your account.</p>

      <?php if ($error): ?>
        <div id="result" style="margin-top: 10px; color: red; font-weight: bold;">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <input name="username_or_email" type="text" placeholder=" Username or Email" required /><br>
        <input name="password" type="password" placeholder=" Password" required /><br>
        <button type="submit">Login</button>
      </form>
      <p>No account? <a href="register.php">Register here</a></p>
    </div>
  </div>

  <h2>CSCK543 G - B Recipes Menu</h2>
  <p>Below is our recipe dishes. Please login to access the Recipes for each menu:</p>

  <div class="row">
    <?php
    $recipes = [
      ["src" => "media/Hpizza.jpg", "alt" => "Healthy Pizza", "caption" => "Healthy Pizza"],
      ["src" => "media/vpancake.jpg", "alt" => "Vegan Pancakes", "caption" => "Vegan Pancakes"],
      ["src" => "media/couscous.jpg", "alt" => "Couscous Salad", "caption" => "Couscous Salad"],
      ["src" => "media/vpancake.jpg", "alt" => "Pizza", "caption" => "Vegan Pancakes"],
      ["src" => "media/Plum_clafoutis.jpg", "alt" => "Plum clafoutis", "caption" => "Plum clafoutis"],
      ["src" => "media/lamb.jpg", "alt" => "Easy Lamb Biryani", "caption" => "Easy Lamb Biryani"],
      ["src" => "media/mpie.jpg", "alt" => "Mango Pie", "caption" => "Mango Pie"],
      ["src" => "media/musroom.jpg", "alt" => "Mushroom Doner", "caption" => "Mushroom Doner"]
    ];

    foreach ($recipes as $r) {
      echo '<div class="GbRecipe">
              <figure class="uolfmaFigure">
                <img src="' . $r["src"] . '" class="uolfmaImage" alt="' . $r["alt"] . '">
                <figcaption>' . $r["caption"] . '</figcaption>
              </figure>
            </div>';
    }
    ?>
  </div><br><br>

  <p class="endp">
    Thank you for visiting Group B recipe catalogue<br>
    Your one-stop solution for delicious and nutritious<br>
    food recipes.<br><br>
    We hope you enjoyed your stay!<br>
    To get more delicious recipes, please login or register<br>
    to try our recipes!
  </p><br><br>

  <div class="uolfmafooter">
    <p>&copy; 2025 CSCK543-GB Recipes. All rights reserved.</p>
  </div>
</body>
</html>
