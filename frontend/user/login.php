<!DOCTYPE html>
<html lang="en">
<head>  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Login to your recipe account">
  <meta name="keywords" content="login, user, account"> 
  <title>Login</title>
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

  <div class="rglobal">
    <h2>Login</h2>

    <form id="loginForm">
      <input name="username_or_email" type="text" placeholder="Username or Email" required /><br>
      <input name="password" type="password" placeholder="Password" required /><br>
      <button type="submit">Login</button>
    </form>

    <div id="result" style="margin-top: 10px; font-weight: bold;"></div>

    <p>No account? <a href="register.php">Register here</a></p>
  </div>

  <div class="uolfmarfooter">
    <p>&copy; 2025 CSCK543-GB Recipes. All rights reserved.</p>
  </div>

  <script>
    document.getElementById("loginForm").onsubmit = async function(e) {
      e.preventDefault();
      const resultDiv = document.getElementById("result");
      resultDiv.textContent = "Logging in...";
      resultDiv.style.color = "blue";

      const formData = new FormData(this);

      try {
        const resp = await fetch("../../backend/user/login.php", {
          method: "POST",
          body: formData
        });

        const json = await resp.json();

        resultDiv.textContent = json.message || "Login processed.";

        if (json.success) {
          resultDiv.style.color = "green";
          setTimeout(() => {
            window.location.href = "../recipe/recipes.php";
          }, 1000);
        } else {
          resultDiv.style.color = "red";
        }

      } catch (err) {
        console.error("Login error:", err);
        resultDiv.style.color = "red";
        resultDiv.textContent = "Error logging in. Please try again.";
      }
    };
  </script>

</body>
</html>
