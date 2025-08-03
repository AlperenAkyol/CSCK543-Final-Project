<?php
session_start();
require_once '../../database-connection/db.php';
$userId = $_SESSION['user_id'] ?? null;
$favorites = [];
if ($userId) {
    $stmt = $pdo->prepare(
        "SELECT r.id, r.title, r.category, r.score
         FROM recipes r
         JOIN favourites f ON f.recipe_id = r.id
         WHERE f.user_id = ?"
    );
    $stmt->execute([$userId]);
    $favorites = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Favorite Recipes</title>
  <style>
    :root {
      --primary-color: #808000;
      --secondary-color: #6b6b00;
      --background-color: #f9f9f5;
      --text-color: #333;
      --light-text: #777;
      --white: #fff;
      --shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    body {
      background-color: var(--background-color);
      color: var(--text-color);
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
    h2 {
      color: var(--primary-color);
      margin-bottom: 20px;
      text-align: center;
    }
    #favoriteList {
      list-style: none;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      padding: 0;
    }
    #favoriteList li {
      background-color: var(--white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
    }
    #favoriteList a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
    }
    #favoriteList .category {
      color: var(--light-text);
      font-size: 0.9rem;
    }
    #favoriteList .score {
      color: var(--primary-color);
      font-weight: bold;
    }
    .back-btn {
      display: block;
      margin: 0 auto 20px;
      width: max-content;
      background-color: var(--primary-color);
      color: var(--white);
      border: none;
      border-radius: 4px;
      padding: 10px 20px;
      font-size: 1rem;
      cursor: pointer;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.3s;
    }
    .back-btn:hover {
      background-color: var(--secondary-color);
    }
    @media (max-width: 768px) {
      #favoriteList {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <a class="back-btn" href="recipes.php">&lt; Back to All Recipes</a>
  <h2>★ My Favorite Recipes</h2>
  <ul id="favoriteList">
    <?php if (!$userId): ?>
      <li>You must be logged in to see favorites.</li>
    <?php elseif (empty($favorites)): ?>
      <li>You have no favorites yet.</li>
    <?php else: ?>
      <?php foreach ($favorites as $r): ?>
        <li>
          <a href="recipe.php?id=<?= htmlspecialchars($r['id']) ?>">
            <?= htmlspecialchars($r['title']) ?>
          </a>
          <span class="category">(<?= htmlspecialchars($r['category']) ?>)</span>
          <span class="score">Score: <?= htmlspecialchars($r['score'] ?? 0) ?></span>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
</body>
</html>
