<?php
require_once("functions-new.php");
// Gestione del login per il backend
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = isset($_POST['username']) ? $_POST['username'] : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';
  backendLogin($username, $password);
  }
?>
<!-- Login form per l'accesso al backend -->
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="graphic/style.css" type="text/css" rel="stylesheet"/>
</head>
<body class="login">

  <div class="comeback">
    <a href="index.php">Torna alla home</a>
  </div>
  <div class="login-container">
    <h2>Accedi</h2>
    <form id="loginForm" action="" method="post">
      <div class="error" id="errorMsg">Per favore compila tutti i campi</div>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" />

      <button type="submit">Login</button>

    </form>
  </div>


</body>
</html>
