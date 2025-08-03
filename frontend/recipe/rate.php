<?php
// Simulated user/session handling (extend with $_SESSION as needed)
$user_id = 1;
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

  <form id="rateForm">
    <label for="rating">Rating (1 to 5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" required>
    <button type="submit">Submit Rating</button>
  </form>

  <div id="result"></div>

  <footer>
    <p>&copy; 2025 Recipes. All rights reserved.</p>
    <p>Contact us: info@recipes.com</p>
  </footer>

  <script>
    document.getElementById("rateForm").onsubmit = async function(e) {
      e.preventDefault();
      const urlParams = new URLSearchParams(window.location.search);
      const id = urlParams.get('id');
      const rating = document.getElementById('rating').value;

      if (!id) {
        document.getElementById('result').innerText = "No recipe ID provided.";
        return;
      }

      try {
        const formData = new FormData();
        formData.append('recipe_id', id);
        formData.append('rating', rating);

        const resp = await fetch("../../backend/recipe/rate.php", {
          method: "POST",
          body: formData
        });

        const json = await resp.json();
        document.getElementById('result').innerText = json.message || "Rating submitted!";
      } catch (error) {
        console.error("Rating submission failed:", error);
        document.getElementById('result').innerText = "Error submitting rating.";
      }
    };
  </script>

</body>
</html>
