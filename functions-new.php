<?php
include ('crd/crd.php');
// Attivazione anti-cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
// Altre funzioni (es. dbConnect, ecc...)
function dbConnect() {
    include ('crd/crd.php');
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $hostname = $config['hostname'];
    $username = $config['username'];
    $password = $config['password'];
    $dbname = $config['dbname'];
    $conn = new mysqli($hostname, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connessione fallita: {$conn->connect_error}");
    }
    return $conn;
}


// Funzione di recupero e popolazione della voce portfolio
function elemPortfolio($conn) {
    $sql = "SELECT * FROM Sql1872203_1.portfolio";
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
    }
}

// Funzione per recuperare e visualizzare le competenze e creare le barre di progresso
function elemCompetenze($conn) {
    include ('crd/crd.php');
    $sql = "SELECT * FROM Sql1872203_1.competenze";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $titolo = htmlspecialchars($row['Titolo_comp']);
            $val_comp = (int)$row['Val_comp'];
            echo "<li style='margin-bottom:30px;'>";
            echo "<span style='font-size:18px;'>{$titolo}</span>";
            echo "<div class='progress-bar-container' style='background:#eee; border-radius:10px; width:800px; height:20px; overflow:hidden; margin-top:8px;'>";
            echo "<div class='progress-bar' style='background:#e74c3c; width:0; height:100%; transition:width 1.5s;'></div>";
            echo "</div>";
            echo "<span style='font-size:14px;'>{$val_comp}%</span>";
            echo "</li>";
            // Script JS per animare la barra di progresso
            echo "<script>
                (function(){
                    var bars = document.querySelectorAll('.progress-bar');
                    var idx = bars.length - 1;
                    setTimeout(function(){
                        bars[idx].style.width = '{$val_comp}%';
                    }, 100);
                })();
            </script>";
        }
    } else {
        echo "<p>Nessuna competenza trovata.</p>";
    }
}

// Funzione per il login del backend
function backendLogin($username, $password) {
    $conn = dbConnect();
    $stmt = $conn->prepare("SELECT nome, password, admin FROM Sql1872203_1.utenti WHERE nome = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ((int)$row['admin'] === 1) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['admin'] = true;
            header("Location: backend.php");
            exit();
        } elseif ((int)$row['admin'] === 0) {
            echo "<script>alert('Non sei un admin, accesso non autorizzato.');</script>";
        }
    } else {
        echo "<script>alert('Credenziali errate, accesso non autorizzato.');</script>";
    }

}

$conn = dbConnect();
dbConnect();


?>