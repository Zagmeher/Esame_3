<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="graphic/style.css" type="text/css" rel="stylesheet"/>
</head>
<body class="login">

  <div class="login-container">
    <h2>Accedi</h2>
    <form id="loginForm" action="process_login.php" method="post">
      <div class="error" id="errorMsg">Per favore compila tutti i campi</div>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" />

      <button type="submit">Login</button>
    </form>
  </div>

  <script>
    const form = document.getElementById("loginForm");
    const errorMsg = document.getElementById("errorMsg");

    form.addEventListener("submit", function (e) {
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      if (username === "" || password === "") {
        e.preventDefault(); // blocca invio
        errorMsg.style.display = "block";
      }
    });
  </script>
</body>
</html>
