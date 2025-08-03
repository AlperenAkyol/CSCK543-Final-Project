<?php
session_start();
require_once '../../backend/db.php'; // Make sure this path is correct

// Search logic
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT id, title, category, score FROM recipes WHERE title LIKE ? OR category LIKE ?");
    $search = "%" . $q . "%";
    $stmt->execute([$search, $search]);
    $recipes = $stmt->fetchAll();
} else {
    $stmt = $pdo->query("SELECT id, title, category, score FROM recipes");
    $recipes = $stmt->fetchAll();
}

$imageMap = [
    'Spaghetti Bolognese' => '../user/media/spaghtti.jpg',
    'Vegan Pancakes'      => '../user/media/vpancake.jpg',
    'Healthy Pizza'       => '../user/media/Hpizza.jpg',
    'Easy Lamb Biryani'   => '../user/media/lamb.jpg',
    'Couscous Salad'      => '../user/media/couscous.jpg',
    'Plum clafoutis'      => '../user/media/Plum_clafoutis.jpg',
    'Mango Pie'           => '../user/media/mpie.jpg',
    'Mushroom Doner'      => '../user/media/musroom.jpg',
];
$placeholder = '../user/media/Plum_clafoutis.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recipe List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      max-width: 1200px;
      margin: 0 auto;
    }
    h2 {
      color: var(--primary-color);
      margin-bottom: 20px;
      text-align: center;
      font-size: 2rem;
    }
    #searchForm {
      display: flex;
      margin-bottom: 30px;
      gap: 10px;
      justify-content: center;
      align-items: center;
    }
    #searchInput {
      flex: 1;
      padding: 12px 15px;
      border: 2px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
      transition: border-color 0.3s;
      max-width: 350px;
    }
    #searchInput:focus {
      border-color: var(--primary-color);
      outline: none;
    }
    #searchForm button {
      background-color: var(--primary-color);
      color: var(--white);
      border: none;
      padding: 12px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s;
    }
    #searchForm button:hover {
      background-color: var(--secondary-color);
    }
    #recipeList {
      list-style: none;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
      padding: 0;
    }
    #recipeList li {
      background-color: var(--white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
      transition: transform 0.3s, box-shadow 0.3s;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #recipeList li:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .recipe-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px;
      margin-bottom: 10px;
      background: #eee;
      max-width: 280px;
    }
    #recipeList a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
      display: block;
      margin-bottom: 5px;
      text-align: center;
    }
    #recipeList a:hover {
      text-decoration: underline;
    }
    #recipeList .category {
      color: var(--light-text);
      font-size: 0.9rem;
      display: inline-block;
      margin-right: 10px;
    }
    #recipeList .score {
      color: var(--primary-color);
      font-weight: bold;
      margin-bottom: 10px;
      display: inline-block;
    }
    #recipeList .rate-link {
      display: inline-block;
      margin-top: 10px;
      color: var(--white);
      background-color: var(--primary-color);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.9rem;
      text-decoration: none;
      text-align: center;
    }
    #recipeList .rate-link:hover {
      background-color: var(--secondary-color);
    }
    .logout-btn {
      display: block;
      width: 100%;
      max-width: 200px;
      margin: 30px auto 0;
      padding: 12px;
      background-color: var(--primary-color);
      color: var(--white);
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .logout-btn:hover {
      background-color: var(--secondary-color);
    }
    @media (max-width: 768px) {
      #recipeList { grid-template-columns: 1fr; }
      #searchForm { flex-direction: column; }
      h2 { font-size: 1.5rem; }
      .recipe-image { max-width: 100%; height: 180px; }
    }
  </style>
</head>
<body>
  <h2>All Recipes</h2>
  <p style="text-align: center; margin-bottom: 30px;">
    <a href="favorites.php" style="color: var(--primary-color); font-size: 1.05rem; text-decoration: underline;">★ My Favorites</a>
  </p>
  <form id="searchForm" method="get" action="recipes.php">
    <input id="searchInput" name="q" placeholder="Search by title or category" value="<?= htmlspecialchars($q) ?>" />
    <button type="submit">Search</button>
  </form>
  <ul id="recipeList">
    <?php if (empty($recipes)): ?>
      <li>No recipes found.</li>
    <?php else: ?>
      <?php foreach ($recipes as $recipe): ?>
        <?php
          $img = isset($imageMap[$recipe['title']]) ? $imageMap[$recipe['title']] : $placeholder;
        ?>
        <li>
          <img src="<?= $img ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
          <a href="recipe.php?id=<?= htmlspecialchars($recipe['id']) ?>">
            <?= htmlspecialchars($recipe['title']) ?>
          </a>
          <span class="category">(<?= htmlspecialchars($recipe['category']) ?>)</span>
          <span class="score">Score: <?= htmlspecialchars($recipe['score']) ?></span>
          <a class="rate-link" href="rate.php?id=<?= htmlspecialchars($recipe['id']) ?>">Rate</a>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
  </ul>
  <button class="logout-btn" onclick="window.location.href='../user/login.php'">Logout</button>
  <footer style="text-align: center; font-size: 0.9rem; color: #999; margin-top: 40px;">
    <p>&copy; 2025 Recipes. All rights reserved.</p>
    <p>Contact us: info@Recipes.com</p>
  </footer>
</body>
</html>
