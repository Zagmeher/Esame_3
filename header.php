<!-- Questo è il file header.html che contiene la parte iniziale e la barra di navigazione, nonchè l'integrazione dei file necessari -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="GRAPHIC/style.css" type="text/css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <title>Header</title>
</head>
<body>
  <?php include("functions.php"); ?>
  <nav class="navbar" id="navbar">
    <a href="index.php">
      <img src="IMG/logo.png" alt="Logo" class="logo" />
    </a>
    <ul class="nav-list" id="nav-list">
      <li><a href="#home">Home</a></li>
      <li><a href="#chi-sono">Chi sono</a></li>
      <li><a href="#portfolio">Portfolio</a></li>
      <li><a href="#contatti">Contatti</a></li>
    </ul>
    <!-- bottone per l'accesso -->
    <a href="login.php" class="login-button">Accedi</a>
    <script src="scripts.js"></script>
  </nav>
</body>
