<?php
// Placeholder for user session
$user_id = 1;
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
    }
    #searchInput {
      flex: 1;
      padding: 12px 15px;
      border: 2px solid #ddd;
      border-radius: 4px;
      font-size: 1rem;
      transition: border-color 0.3s;
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
    }
    #recipeList li {
      background-color: var(--white);
      border-radius: 8px;
      padding: 20px;
      box-shadow: var(--shadow);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    #recipeList li:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    #recipeList a {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: bold;
      font-size: 1.1rem;
      display: block;
      margin-bottom: 5px;
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
    }
    #recipeList .rate-link:hover {
      background-color: var(--secondary-color);
    }
    .recipe-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 4px;
      margin-bottom: 10px;
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
    }
  </style>
</head>
<body>
  <h2>All Recipes</h2>
  <p style="text-align: center; margin-bottom: 30px;">
    <a href="favorites.php" style="color: var(--primary-color); font-size: 1.05rem; text-decoration: underline;">★ My Favorites</a>
  </p>
  <form id="searchForm">
    <input id="searchInput" placeholder="Search by title or category" />
    <button type="submit">Search</button>
  </form>
  <ul id="recipeList"></ul>
  <button class="logout-btn" onclick="logout()">Logout</button>

  <script>
    const sampleRecipes = [
            {
                id: 1,
                title: "Spaghetti Bolognese",
                category: "Italian",
                score: 4.5,
                image: "../user/media/spaghtti.jpg",
                ingredients: [
                    { ingredient: "Spaghetti", quantity: "400g" },
                    { ingredient: "Ground beef", quantity: "500g" },
                    { ingredient: "Tomato sauce", quantity: "400ml" },
                    { ingredient: "Onions", quantity: "2 medium" },
                    { ingredient: "Garlic", quantity: "3 cloves" },
                    { ingredient: "Italian herbs", quantity: "2 tsp" }
                ],
                steps: [
                    { description: "Cook spaghetti according to package instructions in salted boiling water", duration_minutes: 10 },
                    { description: "Meanwhile, brown ground beef in a large pan over medium heat", duration_minutes: 5 },
                    { description: "Add finely chopped onions and garlic, cook until softened", duration_minutes: 3 },
                    { description: "Add tomato sauce and herbs, simmer for 15 minutes", duration_minutes: 15 },
                    { description: "Drain spaghetti and combine with sauce", duration_minutes: 2 }
                ]
            },
            {
                id: 2,
                title: "Vegan Pancakes",
                category: "Vegan",
                score: 4.2,
                image: "../user/media/vpancake.jpg",
                ingredients: [
                    { ingredient: "All-purpose flour", quantity: "200g" },
                    { ingredient: "Almond milk", quantity: "300ml" },
                    { ingredient: "Baking powder", quantity: "2 tsp" },
                    { ingredient: "Maple syrup", quantity: "2 tbsp" },
                    { ingredient: "Vanilla extract", quantity: "1 tsp" }
                ],
                steps: [
                    { description: "Mix flour and baking powder in a large bowl", duration_minutes: 3 },
                    { description: "Add almond milk, maple syrup and vanilla extract, whisk until smooth", duration_minutes: 3 },
                    { description: "Heat a non-stick pan over medium heat", duration_minutes: 2 },
                    { description: "Pour small amounts of batter to form pancakes", duration_minutes: 5 },
                    { description: "Flip when bubbles form on the surface", duration_minutes: 3 }
                ]
            },
            {
                id: 3,
                title: "Healthy Pizza",
                category: "Healthy",
                score: 4.0,
                image: "../user/media/Hpizza.jpg",
                ingredients: [
                    { ingredient: "Whole wheat pizza dough", quantity: "1 ball" },
                    { ingredient: "Tomato passata", quantity: "150ml" },
                    { ingredient: "Low-fat mozzarella", quantity: "150g" },
                    { ingredient: "Mixed bell peppers", quantity: "1 cup" },
                    { ingredient: "Mushrooms", quantity: "100g" },
                    { ingredient: "Olive oil", quantity: "1 tbsp" }
                ],
                steps: [
                    { description: "Preheat oven to 220°C (425°F)", duration_minutes: 10 },
                    { description: "Roll out dough on a floured surface to desired thickness", duration_minutes: 5 },
                    { description: "Spread tomato passata evenly over the dough", duration_minutes: 2 },
                    { description: "Add sliced vegetables and cheese", duration_minutes: 5 },
                    { description: "Bake for 12-15 minutes until crust is golden", duration_minutes: 15 }
                ]
            },
            {
                id: 4,
                title: "Easy Lamb Biryani",
                category: "Indian",
                score: 4.7,
                image: "../user/media/lamb.jpg",
                ingredients: [
                    { ingredient: "Basmati rice", quantity: "300g" },
                    { ingredient: "Lamb pieces", quantity: "500g" },
                    { ingredient: "Plain yogurt", quantity: "200g" },
                    { ingredient: "Biryani masala", quantity: "3 tbsp" },
                    { ingredient: "Onions", quantity: "2 large" },
                    { ingredient: "Ghee", quantity: "2 tbsp" }
                ],
                steps: [
                    { description: "Marinate lamb in yogurt and biryani masala for at least 2 hours", duration_minutes: 120 },
                    { description: "Wash and soak rice for 30 minutes", duration_minutes: 30 },
                    { description: "Cook rice until 70% done, then drain", duration_minutes: 10 },
                    { description: "In a heavy pot, layer rice and lamb mixture", duration_minutes: 5 },
                    { description: "Cover and cook on low heat for 30 minutes (dum cooking)", duration_minutes: 30 }
                ]
            },
            {
                id: 5,
                title: "Couscous Salad",
                category: "Mediterranean",
                score: 4.3,
                image: "../user/media/couscous.jpg",
                ingredients: [
                    { ingredient: "Couscous", quantity: "200g" },
                    { ingredient: "Cherry tomatoes", quantity: "200g" },
                    { ingredient: "Cucumber", quantity: "1 medium" },
                    { ingredient: "Feta cheese", quantity: "100g" },
                    { ingredient: "Kalamata olives", quantity: "50g" },
                    { ingredient: "Lemon juice", quantity: "2 tbsp" },
                    { ingredient: "Olive oil", quantity: "3 tbsp" }
                ],
                steps: [
                    { description: "Prepare couscous according to package instructions", duration_minutes: 10 },
                    { description: "Chop tomatoes, cucumber and olives", duration_minutes: 10 },
                    { description: "Crumble feta cheese", duration_minutes: 2 },
                    { description: "Mix all ingredients in a large bowl", duration_minutes: 5 },
                    { description: "Whisk together lemon juice and olive oil for dressing", duration_minutes: 2 },
                    { description: "Add dressing to salad and toss gently", duration_minutes: 2 }
                ]
            },
            {
                id: 6,
                title: "Plum clafoutis",
                category: "Dessert",
                score: 4.1,
                image: "../user/media/Plum_clafoutis.jpg",
                ingredients: [
                    { ingredient: "Plums", quantity: "500g" },
                    { ingredient: "Eggs", quantity: "3" },
                    { ingredient: "Milk", quantity: "250ml" },
                    { ingredient: "Flour", quantity: "100g" },
                    { ingredient: "Sugar", quantity: "100g" },
                    { ingredient: "Butter", quantity: "50g" }
                ],
                steps: [
                    { description: "Preheat oven to 180°C (350°F)", duration_minutes: 10 },
                    { description: "Wash and halve plums, removing stones", duration_minutes: 10 },
                    { description: "Whisk eggs, sugar and flour together", duration_minutes: 5 },
                    { description: "Gradually add milk while whisking", duration_minutes: 3 },
                    { description: "Arrange plums in buttered dish, pour batter over", duration_minutes: 5 },
                    { description: "Bake for 40 minutes until golden", duration_minutes: 40 }
                ]
            },
            {
                id: 7,
                title: "Mango Pie",
                category: "Dessert",
                score: 4.4,
                image: "../user/media/mpie.jpg",
                ingredients: [
                    { ingredient: "Mangoes", quantity: "3 large" },
                    { ingredient: "Pie crust", quantity: "1" },
                    { ingredient: "Sugar", quantity: "100g" },
                    { ingredient: "Cornstarch", quantity: "2 tbsp" },
                    { ingredient: "Lemon juice", quantity: "1 tbsp" },
                    { ingredient: "Egg", quantity: "1 (for egg wash)" }
                ],
                steps: [
                    { description: "Preheat oven to 190°C (375°F)", duration_minutes: 10 },
                    { description: "Peel and slice mangoes", duration_minutes: 10 },
                    { description: "Mix mangoes with sugar, cornstarch and lemon juice", duration_minutes: 5 },
                    { description: "Fill pie crust with mango mixture", duration_minutes: 5 },
                    { description: "Add lattice top, brush with egg wash", duration_minutes: 5 },
                    { description: "Bake for 45 minutes until golden", duration_minutes: 45 }
                ]
            },
            {
                id: 8,
                title: "Mushroom Doner",
                category: "Vegetarian",
                score: 4.0,
                image: "../user/media/musroom.jpg",
                ingredients: [
                    { ingredient: "Mushrooms", quantity: "500g" },
                    { ingredient: "Pita bread", quantity: "4" },
                    { ingredient: "Yogurt", quantity: "200g" },
                    { ingredient: "Garlic", quantity: "2 cloves" },
                    { ingredient: "Lemon juice", quantity: "1 tbsp" },
                    { ingredient: "Spices", quantity: "1 tbsp" }
                ],
                steps: [
                    { description: "Marinate mushrooms in spices for 30 minutes", duration_minutes: 30 },
                    { description: "Grill mushrooms until tender", duration_minutes: 10 },
                    { description: "Mix yogurt with crushed garlic and lemon juice", duration_minutes: 5 },
                    { description: "Warm pita bread", duration_minutes: 2 },
                    { description: "Assemble doner with mushrooms and sauce", duration_minutes: 5 }
                ]
            }
        ];

    const imageMap = {};
    sampleRecipes.forEach(recipe => {
      imageMap[recipe.title.toLowerCase()] = recipe.image;
    });

    async function loadRecipes(q = '') {
      try {
        let url = q ? "../../backend/recipe/search.php?q=" + encodeURIComponent(q) : "../../backend/recipe/list.php";
        let res = await fetch(url);
        if (!res.ok) throw new Error('Network error');
        let data = await res.json();

        data = data.map(recipe => ({
          ...recipe,
          image: imageMap[recipe.title.toLowerCase()] || 'placeholder.jpg'
        }));

        renderRecipes(data);
      } catch (error) {
        console.error('Error fetching recipes:', error);
        renderRecipes(q ? filterRecipes(q) : sampleRecipes);
      }
    }

    function renderRecipes(recipes) {
      const list = document.getElementById('recipeList');
      list.innerHTML = '';
      recipes.forEach(recipe => {
        const li = document.createElement('li');
        li.innerHTML = `
          <img src="${recipe.image}" alt="${recipe.title}" class="recipe-image">
          <a href="recipe.php?id=${recipe.id}">${recipe.title}</a>
          <span class="category">(${recipe.category})</span>
          <span class="score">Score: ${recipe.score || 0}</span>
          <a class="rate-link" href="rate.php?id=${recipe.id}">Rate</a>
        `;
        list.appendChild(li);
      });
    }

    function filterRecipes(query) {
      const lower = query.toLowerCase();
      return sampleRecipes.filter(r => 
        r.title.toLowerCase().includes(lower) || 
        r.category.toLowerCase().includes(lower)
      );
    }

    document.getElementById('searchForm').addEventListener('submit', e => {
      e.preventDefault();
      loadRecipes(document.getElementById('searchInput').value);
    });

    function logout() {
      alert("Logging out...");
      window.location.href = "../user/login.php";
    }

    loadRecipes();
  </script>

  <footer style="text-align: center; font-size: 0.9rem; color: #999; margin-top: 40px;">
    <p>&copy; 2025 Recipes. All rights reserved.</p>
    <p>Contact us: info@Recipes.com</p>
  </footer>
</body>
</html>
