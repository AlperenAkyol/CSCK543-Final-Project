<?php
// In a real session, you'd pull $userId from $_SESSION
$userId = 1;
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
  <div class="recipe-container" id="main"></div>
  <a class="rate-link" id="rateLink" href="#">Rate this recipe</a>

  <script>
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get("id");
    const userId = <?php echo json_encode($userId); ?>;

    async function loadRecipe() {
      const main = document.getElementById("main");

      if (!id) {
        main.innerHTML = "<p>No recipe ID given.</p>";
        return;
      }

      try {
        const res = await fetch(`../../backend/recipe/detail.php?id=${id}`);
        if (res.ok) {
          const r = await res.json();
          if (r.error) throw new Error(r.error);

          let html = `
            <h2>${r.title}</h2>
            <div class="recipe-meta">
              <span><b>Category:</b> ${r.category}</span>
              <span><b>Score:</b> ${r.score}</span>
            </div>
            <h3>Ingredients</h3>
            <ul>`;
          r.ingredients.forEach(i => {
            html += `<li>${i.ingredient}: ${i.quantity}</li>`;
          });
          html += `</ul><h3>Steps</h3><ol>`;
          r.steps.forEach(s => {
            html += `<li>${s.description} (${s.duration_minutes} min)</li>`;
          });
          html += `</ol>`;

          main.innerHTML = html;
          document.getElementById("rateLink").href = `rate.php?id=${id}`;
          addFavoriteButton();
          return;
        }
      } catch (err) {
        console.error("Error loading recipe:", err);
      }

      main.innerHTML = "<p>Recipe not found.</p>";
    }

    async function isFavorited(userId, recipeId) {
      try {
        const res = await fetch(`../../backend/recipe/is_favorited.php?user_id=${userId}&recipe_id=${recipeId}`);
        if (!res.ok) throw new Error("Check failed");
        const result = await res.json();
        return result.favorited === true;
      } catch {
        return false;
      }
    }

    async function addFavoriteButton() {
      const main = document.getElementById("main");
      const btn = document.createElement("button");
      btn.className = "fav-button";
      btn.textContent = "❤️ Add to Favorites";

      let isFav = await isFavorited(userId, id);
      btn.textContent = isFav ? "💔 Remove from Favorites" : "❤️ Add to Favorites";

      btn.onclick = async () => {
        const endpoint = isFav
          ? "../../backend/recipe/unfavorite.php"
          : "../../backend/recipe/favorite.php";

        try {
          const response = await fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `user_id=${userId}&recipe_id=${id}`
          });

          const result = await response.json();
          alert(result.message);
          isFav = !isFav;
          btn.textContent = isFav ? "💔 Remove from Favorites" : "❤️ Add to Favorites";
        } catch (err) {
          alert("Something went wrong!");
          console.error(err);
        }
      };

      main.appendChild(btn);
    }

    loadRecipe();
  </script>
</body>
</html>
