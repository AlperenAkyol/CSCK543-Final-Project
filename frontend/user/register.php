<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Register for your recipe account">
  <meta name="keywords" content="register, signup, account"> 
  <title>CSCK543-GB Register</title>
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
    <h2>Register</h2>
    <form id="registerForm">
      <input name="username" type="text" placeholder="&nbsp;Username" required /><br>
      <input name="full_name" type="text" placeholder="&nbsp;Full Name" /><br>
      <input name="email" type="email" placeholder="&nbsp;Email" required /><br>
      <input name="password" type="password" placeholder="&nbsp;Password" required /><br>
      <button type="submit">Register</button>
    </form>
    <div id="result" style="margin-top: 10px; font-weight: bold;"></div>
    <p>Already have an account? &nbsp;<a href="login.php"><strong>Login here</strong></a></p>
  </div>

  <div class="uolfmarfooter">
    <p>&copy; 2025 CSCK543-GB Recipes. All rights reserved.</p>
  </div>

  <script>
    document.getElementById("registerForm").onsubmit = async function(e) {
      e.preventDefault();

      const resultDiv = document.getElementById("result");
      resultDiv.textContent = "Registering...";
      resultDiv.style.color = "blue";

      const formData = new FormData(this);

      try {
        const resp = await fetch("../../backend/user/register.php", {
          method: "POST",
          body: formData
        });

        const json = await resp.json();
        resultDiv.textContent = json.message || "Registration complete.";

        if (json.success) {
          resultDiv.style.color = "green";
          setTimeout(() => {
            window.location.href = "login.php";
          }, 1000);
        } else {
          resultDiv.style.color = "red";
        }

      } catch (error) {
        console.error("Registration error:", error);
        resultDiv.style.color = "red";
        resultDiv.textContent = "Error connecting to server. Please try again.";
      }
    };
  </script>

</body>
</html>
