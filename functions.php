<?php
function dbConnect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sitopers";

    // Crea connessione
    $conn = new mysqli($servername, $username, $password, $dbname);
    echo "<p style='color:green;'>Connessione riuscita</p>";

    // Controlla la connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
        echo "<p style='color:red;'>Connessione fallita</p>";
    }
    return $conn;
}

dbConnect();

?>