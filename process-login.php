<?php
// Simulazione accesso (puoi collegarlo a un database)
$utente_valido = "admin";
$password_valida = "123456";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    if ($username === $utente_valido && $password === $password_valida) {
        echo "<h1>Benvenuto, $username!</h1>";
        // Qui potresti fare un redirect, o salvare una sessione
    } else {
        echo "<h2>Credenziali errate</h2><p><a href='login.php'>Riprova</a></p>";
    }
} else {
    header("Location: login.php");
    exit();
}
?>
