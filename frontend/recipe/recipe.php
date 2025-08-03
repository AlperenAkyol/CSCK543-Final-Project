<?php
session_start();
require_once '../../backend/db.php';
$recipeId = intval($_GET['id'] ?? 0);
$userId = $_SESSION['user_id'] ?? null;
$recipe = null;
$ingredients = [];
$steps = [];
$isFav = false;

if ($recipeId > 0) {
    // Main recipe info
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch();

    // Ingredients
    $stmt = $pdo->prepare("SELECT ingredient, quantity FROM recipe_ingredients WHERE recipe_id = ?");
    $stmt->execute([$recipeId]);
    $ingredients = $stmt->fetchAll();

    // Steps
    $stmt = $pdo->prepare("SELECT step_number, description, duration_minutes FROM recipe_steps WHERE recipe_id = ? ORDER BY step_number");
    $stmt->execute([$recipeId]);
    $steps = $stmt->fetchAll();

    // Is this a favorite?
    if ($userId) {
        $stmt = $pdo->prepare("SELECT id FROM favourites WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$userId, $recipeId]);
        $isFav = $stmt->fetch() ? true : false;
    }
}

// Handle favorite add/remove
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId && $recipe) {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("INSERT IGNORE INTO favourites (user_id, recipe_id) VALUES (?, ?)");
            $stmt->execute([$userId, $recipeId]);
            $isFav = true;
        } elseif ($_POST['action'] === 'remove') {
            $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$userId, $recipeId]);
            $isFav = false;
        }
        // Redirect to avoid POST resubmission
        header("Location: recipe.php?id=" . urlencode($recipeId));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recipe Details</title>
  <style>
    :root {
      --primary-color: #808000;
      --secondary-color: #6b6b00;
      --background-color: #f9f9f5;
      --text-color: #333;
      --light-text: #666;
      --card-bg: #fff;
      --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
      line-height: 1.6;
      padding: 20px;
      max-width: 800px;
      margin: 0 auto;
    }
    .back-link {
      display: inline-block;
      color: var(--primary-color);
      text-decoration: none;
      margin-bottom: 20px;
      font-size: 1rem;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    .recipe-container {
      background-color: var(--card-bg);
      border-radius: 8px;
      padding: 25px;
      box-shadow: var(--shadow);
    }
    h2 {
      color: var(--primary-color);
      margin-bottom: 15px;
      font-size: 1.8rem;
    }
    .recipe-meta {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
      color: var(--light-text);
    }
    h3 {
      color: var(--primary-color);
      margin: 20px 0 10px;
      font-size: 1.4rem;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
    }
    ul, ol {
      padding-left: 20px;
    }
    li {
      margin-bottom: 8px;
    }
    .rate-link {
      display: inline-block;
      background-color: var(--primary-color);
      color: white;
      padding: 8px 15px;
      border-radius: 4px;
      text-decoration: none;
      margin-top: 20px;
      font-size: 1rem;
      transition: background-color 0.3s;
    }
    .rate-link:hover {
      background-color: var(--secondary-color);
    }
    .fav-button {
      display: inline-block;
      margin-top: 20px;
      margin-left: 10px;
      padding: 8px 15px;
      background-color: #808000;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
    }
    .fav-button:hover {
      background-color: #6b6b00;
    }
    @media (max-width: 600px) {
      body {
        padding: 15px;
      }
      .recipe-container {
        padding: 15px;
      }
      h2 {
        font-size: 1.5rem;
      }
      .recipe-meta {
        flex-direction: column;
        gap: 5px;
      }
    }
  </style>
</head>
<body>
  <a class="back-link" href="recipes.php">&lt; Back to Recipes</a>
  <div class="recipe-container">
    <?php if (!$recipe): ?>
      <p>Recipe not found.</p>
    <?php else: ?>
      <h2><?= htmlspecialchars($recipe['title']) ?></h2>
      <div class="recipe-meta">
        <span><b>Category:</b> <?= htmlspecialchars($recipe['category']) ?></span>
        <span><b>Score:</b> <?= htmlspecialchars($recipe['score']) ?></span>
      </div>
      <h3>Ingredients</h3>
      <ul>
        <?php foreach ($ingredients as $i): ?>
          <li><?= htmlspecialchars($i['ingredient']) ?>: <?= htmlspecialchars($i['quantity']) ?></li>
        <?php endforeach; ?>
      </ul>
      <h3>Steps</h3>
      <ol>
        <?php foreach ($steps as $s): ?>
          <li><?= htmlspecialchars($s['description']) ?> (<?= htmlspecialchars($s['duration_minutes']) ?> min)</li>
        <?php endforeach; ?>
      </ol>
      <a class="rate-link" href="rate.php?id=<?= htmlspecialchars($recipeId) ?>">Rate this recipe</a>
      <?php if ($userId): ?>
        <form method="post" style="display:inline;">
          <?php if ($isFav): ?>
            <button type="submit" name="action" value="remove" class="fav-button">💔 Remove from Favorites</button>
          <?php else: ?>
            <button type="submit" name="action" value="add" class="fav-button">❤️ Add to Favorites</button>
          <?php endif; ?>
        </form>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>
</html>
