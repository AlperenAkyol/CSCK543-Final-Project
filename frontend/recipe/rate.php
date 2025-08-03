<?php
session_start();
require_once '../../backend/db.php';
$recipeId = intval($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'] ?? null;
$message = null;

// Fetch the recipe to display title and basic info
$recipe = null;
if ($recipeId > 0) {
    $stmt = $pdo->prepare("SELECT id, title, category, score FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch();
}

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $recipe) {
    $rating = intval($_POST['rating'] ?? 0);

    if ($rating < 1 || $rating > 5) {
        $message = "Please enter a rating from 1 to 5.";
    } else {
        // Update recipe rating logic (as in your backend)
        $stmt = $pdo->prepare("UPDATE recipes SET total_points = total_points + ?, rate_count = rate_count + 1, score = (total_points + ?) / (rate_count + 1) WHERE id = ?");
        $stmt->execute([$rating, $rating, $recipeId]);
        $message = "Thank you for rating!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rate Recipe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary-color: #808000;
      --secondary-color: #6b6b00;
      --background-color: #f9f9f5;
      --text-color: #333;
      --white: #fff;
      --shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      background-color: var(--background-color);
      color: var(--text-color);
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: var(--primary-color);
      font-size: 2rem;
    }
    a {
      display: inline-block;
      margin-bottom: 20px;
      color: var(--primary-color);
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    form {
      background-color: var(--white);
      padding: 30px;
      border-radius: 8px;
      box-shadow: var(--shadow);
    }
    label {
      font-size: 1rem;
      margin-bottom: 10px;
      display: block;
    }
    input[type="number"] {
      width: 100%;
      padding: 12px;
      border: 2px solid #ddd;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }
    input[type="number"]:focus {
      border-color: var(--primary-color);
      outline: none;
    }
    button {
      width: 100%;
      background-color: var(--primary-color);
      color: var(--white);
      padding: 12px;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: var(--secondary-color);
    }
    #result {
      margin-top: 20px;
      text-align: center;
      color: var(--primary-color);
      font-weight: bold;
    }
    footer {
      margin-top: 40px;
      text-align: center;
      font-size: 0.9rem;
      color: #999;
    }
  </style>
</head>
<body>
  <a href="recipes.php">&larr; Back to Recipes</a>
  <h2>Rate This Recipe</h2>

  <?php if (!$recipe): ?>
    <div id="result">Recipe not found.</div>
  <?php elseif ($message): ?>
    <div id="result"><?= htmlspecialchars($message) ?></div>
    <p style="text-align: center; margin-top: 20px;">
      <a href="recipe.php?id=<?= htmlspecialchars($recipeId) ?>">&#8592; Back to Recipe</a>
    </p>
  <?php else: ?>
    <form method="post" action="rate.php?id=<?= htmlspecialchars($recipeId) ?>">
      <label for="rating">How would you rate "<?= htmlspecialchars($recipe['title']) ?>"?</label>
      <input type="number" id="rating" name="rating" min="1" max="5" required>
      <button type="submit">Submit Rating</button>
    </form>
  <?php endif; ?>

  <footer>
    <p>&copy; 2025 Recipes. All rights reserved.</p>
    <p>Contact us: info@recipes.com</p>
  </footer>
</body>
</html>
