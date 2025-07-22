<?php

// Casistiche dei pulsanti backend per la gestione delle competenze, portfolio e utenti
require_once "befunctions.php";

if (isset($_POST['azione'])) {
    switch ($_POST['azione']) {
        case 'adduser': adduser(); break;
        case 'upduser': upduser(); break;
        case 'deluser': deluser(); break;
        case 'addcomp': addcomp(); break;
        case 'updcomp': updcomp(); break;
        case 'delcomp': delcomp(); break;
        case 'addport': addport(); break;
        case 'updport': updport(); break;
        case 'delport': delport(); break;
    }
} elseif (isset($_POST['submit_adduser'])) {
    adduser();
} elseif (isset($_POST['submit_upduser'])) {
    upduser();
} elseif (isset($_POST['submit_deluser'])) {
    deluser();
} elseif (isset($_POST['submit_addcomp'])) {
    addcomp();
} elseif (isset($_POST['submit_updcomp'])) {
    updcomp();
} elseif (isset($_POST['submit_delcomp'])) {
    delcomp();
} elseif (isset($_POST['submit_addport'])) {
    addport();
} elseif (isset($_POST['submit_updport'])) {
    updport();
} elseif (isset($_POST['submit_delport'])) {
    delport();
}

?>

<!-- Struttura HTML per il backend -->
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>backend</title>
    <link href="graphic/style.css" type="text/css" rel="stylesheet" />
    <link rel="icon" href="ia.ico" type="ia/ico" />
</head>
<body class="backend">
<!-- Pulsanti backend per la gestione delle competenze, portfolio e utenti -->
    <h3 class="comeback"><a href="index.php">Torna indietro</a></h3>
    <h1 class="beTitle" >Backend management</h1>
    <div class="backend-container">
        <div class="backend-competenze">
            <h2>Competenze</h2>
            <form method="POST">
                <ul>
                    <li>
                        <h3>Aggiungi competenza</h3>
                        <button type="submit" name="azione" value="addcomp" id="addcomp">Aggiungi</button>
                    </li>
                    <li>
                        <h3>Modifica competenza</h3>
                        <button type="submit" name="azione" value="updcomp">Modifica</button>
                    </li>
                    <li>
                        <h3>Elimina competenza</h3>
                        <button type="submit" name="azione" value="delcomp">Elimina</button>
                    </li>
                </ul>
            </form>
        </div>
        <div class="backend-portfolio">
            <h2>Portfolio</h2>
            <form method="POST">
                <ul>
                    <li>
                        <h3>Aggiungi progetto</h3>
                        <button type="submit" name="azione" value="addport">Aggiungi</button>
                    </li>
                    <li>
                        <h3>Modifica progetto</h3>
                        <button type="submit" name="azione" value="updport">Modifica</button>
                    </li>
                    <li>
                        <h3>Elimina progetto</h3>
                        <button type="submit" name="azione" value="delport">Elimina</button>
                    </li>
                </ul>
            </form>
        </div>
        <div class="backend-utenti">
            <h2>Utenti</h2>
            <form method="POST">
                <ul>
                    <li>
                        <h3>Aggiungi utente</h3>
                        <button type="submit" name="azione" value="adduser">Aggiungi</button>
                    </li>
                    <li>
                        <h3>Modifica utente</h3>
                        <button type="submit" name="azione" value="upduser">Modifica</button>
                    </li>
                    <li>
                        <h3>Elimina utente</h3>
                        <button type="submit" name="azione" value="deluser">Elimina</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>

</body>
</html>