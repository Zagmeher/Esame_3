<?php
function dbConnect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sitopers";

    // Crea connessione
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Controlla la connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }
    return $conn;
}

// Funzione di recupero e popolazione della voce portfolio
function elemPortfolio($conn) {
    $sql = "SELECT * FROM sitopers.portfolio";
    $result = $conn->query($sql);
    $i = 1;

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='portfolio-item' data-aos='flip-left' data-aos-duration='2000'>";
            echo "<img src='" . htmlspecialchars($row['img_p']) . "' alt='p{$i}' class='p{$i}' />";
            echo "<h3 class='tp'>" . htmlspecialchars($row['titolo_p']) . "</h3>";
            echo "<p class='pp' >" . htmlspecialchars($row['descr_p']) . "</p>";
            echo "</div>";
            $i++;
        }
    } else {
        echo "<p>Nessun progetto trovato.</p>";
    }
}

function elemCompetenze($conn) {
    $sql = "SELECT * FROM sitopers.competenze";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['Titolo_comp']) . "</li>";
        }
    } else {
        echo "<p>Nessuna competenza trovata.</p>";
    }
}

dbConnect();

?>