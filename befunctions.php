<?php

// Funzione per nominare il $img_p
function nameImg_P(){
    $img_po = null;
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT MAX(ID_po) AS max_id FROM portfolio");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $img_po = isset($row['max_id']) ? $row['max_id'] + 1 : 1;
            mysqli_free_result($result);
        }
        mysqli_close($conn);
    }
    return $img_po;
}

//funzione per aggiungere un utente
function adduser() {
    // Script per fare insert into nel database
    if (isset($_POST['submit_adduser'])) {
        $nome = $_POST['addNome'];
        $cognome = $_POST['addCognome'];
        $password = $_POST['addPsw'];
        $admin = isset($_POST['addAdmin']) ? intval($_POST['addAdmin']) : 0;

        // Controllo complessità password lato server
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($pattern, $password)) {
            echo '
            <div id="error-message" style="
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 18px 32px;
                border-radius: 8px;
                font-weight: bold;
                font-size: 1.2em;
                z-index: 9999;
                box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                text-align: center;
            ">
                La password non rispetta la complessità richiesta.<br>
                Minimo 8 caratteri, almeno una maiuscola, una minuscola, un numero e un carattere speciale.
            </div>
            <script>
                setTimeout(function() {
                    var msg = document.getElementById("error-message");
                    if(msg) msg.remove();
                }, 3000);
            </script>
            ';
        } else {
            include("crd/crd.php");
            $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            //connessione al database e query per inserire l'utente
            if ($conn) {
                $stmt = mysqli_prepare($conn, "INSERT INTO utenti (nome, cognome, password, admin) VALUES (?, ?, ?, ?)");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $nome, $cognome, $password, $admin);
                    $executed = mysqli_stmt_execute($stmt);
                    // Se l'esecuzione è andata a buon fine, mostra un messaggio di successo
                    if ($executed) {
                        echo '
                        <div id="success-message" style="
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            background-color: #d4edda;
                            color: #155724;
                            border: 1px solid #c3e6cb;
                            padding: 18px 32px;
                            border-radius: 8px;
                            font-weight: bold;
                            font-size: 1.2em;
                            z-index: 9999;
                            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                            text-align: center;
                        ">
                            Utente aggiunto con successo!
                        </div>
                        <script>
                            setTimeout(function() {
                                var msg = document.getElementById("success-message");
                                if(msg) msg.remove();
                            }, 3000);
                        </script>
                        ';
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        return;
                    } else {
                        echo '<p style="color:red;">Errore nell\'inserimento: ' . mysqli_stmt_error($stmt) . '</p>';
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
                }
                mysqli_close($conn);
            } else {
                echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
            }
        }
    }
    // Form per aggiungere un utente
    echo '
    <div id="adduser-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 600px;
        background: #b1e48aff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <form class="adduser" action="backend.php" method="post" style="display: flex; flex-direction: column; gap: 12px;" onsubmit="return checkPasswordComplexity();">
            <label for="addNome">Nome:</label>
            <input type="text" name="addNome" id="addNome" required style="width: 60%;">

            <label for="addCognome">Cognome:</label>
            <input type="text" name="addCognome" id="addCognome" required style="width: 60%;">

            <label for="addPsw">Password:</label>
            <input type="password" name="addPsw" id="addPsw" required style="width: 60%;" autocomplete="new-password">
            <div id="pswHelp" style="color:#888; font-size:0.95em; margin-bottom:4px;">
                Minimo 8 caratteri, almeno una maiuscola, una minuscola, un numero e un carattere speciale.
            </div>
            <div id="pswError" style="color:#dc3545; font-size:0.95em; display:none;"></div>

            <label for="addAdmin">Admin:</label>
            <select name="addAdmin" id="addAdmin" style="width: 60%;">
                <option value="0">No</option>
                <option value="1">Sì</option>
            </select>

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="submit" name="submit_adduser" style="width: 120px; background-color: #28a745; color: #fff; border: none; border-radius: 4px; padding: 10px 0;">Aggiungi Utente</button>
                <button type="button" class="closebtn" onclick="document.getElementById(\'adduser-form\').remove();" style="width: 120px; background-color: #dc3545; color: #fff; border: none; border-radius: 4px; padding: 10px 0;">Chiudi</button>
            </div>
        </form>
        <script>
        function checkPasswordComplexity() {
            var psw = document.getElementById("addPsw").value;
            var errorDiv = document.getElementById("pswError");
            var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W_]).{8,}$/;
            if (!pattern.test(psw)) {
                errorDiv.style.display = "block";
                errorDiv.textContent = "La password non rispetta la complessità richiesta.";
                return false;
            }
            errorDiv.style.display = "none";
            return true;
        }
        </script>
    </div>';
}
// Funzione per modificare gli utenti
function upduser() {
    // Gestione submit per aggiornamento
    if (isset($_POST['submit_upduser'])) {
        $id = $_POST['updId'];
        $nome = $_POST['updNome'];
        $cognome = $_POST['updCognome'];
        $password = $_POST['updPsw'];
        $admin = $_POST['updAdmin'];

        // Controllo complessità password lato server
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
        if (!preg_match($pattern, $password)) {
            echo '
            <div id="error-message" style="
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
                padding: 18px 32px;
                border-radius: 8px;
                font-weight: bold;
                font-size: 1.2em;
                z-index: 9999;
                box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                text-align: center;
            ">
                La password non rispetta la complessità richiesta.<br>
                Minimo 8 caratteri, almeno una maiuscola, una minuscola, un numero e un carattere speciale.
            </div>
            <script>
                setTimeout(function() {
                    var msg = document.getElementById("error-message");
                    if(msg) msg.remove();
                }, 3000);
            </script>
            ';
        } else {
            include("crd/crd.php");
            $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
            if ($conn) {
                $stmt = mysqli_prepare($conn, "UPDATE utenti SET nome=?, cognome=?, password=?, admin=? WHERE ID_Utente=?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssii", $nome, $cognome, $password, $admin, $id);
                    $executed = mysqli_stmt_execute($stmt);
                    if ($executed) {
                        echo '
                        <div id="success-message" style="
                            position: fixed;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%);
                            background-color: #d4edda;
                            color: #818e36ff;
                            border: 1px solid #c3e6cb;
                            padding: 18px 32px;
                            border-radius: 8px;
                            font-weight: bold;
                            font-size: 1.2em;
                            z-index: 9999;
                            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                            text-align: center;
                        ">
                            Utente Aggiornato con successo!
                        </div>
                        <script>
                            setTimeout(function() {
                                var msg = document.getElementById("success-message");
                                if(msg) msg.remove();
                            }, 3000);
                        </script>
                        ';
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        return;
                    } else {
                        echo '<p style="color:red;">Errore nell\'aggiornamento: ' . mysqli_stmt_error($stmt) . '</p>';
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
                }
                mysqli_close($conn);
            } else {
                echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
            }
        }
    }
    // Form per modificare gli utenti
    echo '
    <div id="upduser-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #d2df6bff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Gestione Utenti</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID</th>
                    <th style="padding:8px; border:1px solid #ccc;">Nome</th>
                    <th style="padding:8px; border:1px solid #ccc;">Cognome</th>
                    <th style="padding:8px; border:1px solid #ccc;">Password</th>
                    <th style="padding:8px; border:1px solid #ccc;">Admin</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';
    // Connessione al database e query per ottenere gli utenti
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM utenti");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_Utente'];
                $nome = htmlspecialchars($row['Nome']);
                $cognome = htmlspecialchars($row['Cognome']);
                $password = htmlspecialchars($row['Password']);
                $admin = $row['Admin'];
                echo '
                <form method="post" action="backend.php" onsubmit="return checkPasswordComplexityUpd('.$id.');">
                    <tr id="row-'.$id.'">
                        <td style="padding:8px; border:1px solid #ccc;">'.$id.'</td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="updNome" value="'.$nome.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="updCognome" value="'.$cognome.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="password" name="updPsw" id="updPsw-'.$id.'" value="'.$password.'" style="width:90%;" disabled autocomplete="new-password">
                            <div id="pswHelp-'.$id.'" style="color:#888; font-size:0.95em; margin-bottom:4px; display:none;">
                                Minimo 8 caratteri, almeno una maiuscola, una minuscola, un numero e un carattere speciale.
                            </div>
                            <div id="pswError-'.$id.'" style="color:#dc3545; font-size:0.95em; display:none;"></div>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <select name="updAdmin" disabled style="width:90%;">
                                <option value="0" '.($admin==0?'selected':'').'>No</option>
                                <option value="1" '.($admin==1?'selected':'').'>Sì</option>
                            </select>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="hidden" name="updId" value="'.$id.'">
                            <button type="button" onclick="enableEdit('.$id.')" style="background:#007bff; color:#fff; border:none; border-radius:4px; padding:6px 12px;">Seleziona</button>
                            <button type="submit" name="submit_upduser" style="background:#28a745; color:#fff; border:none; border-radius:4px; padding:6px 12px;" disabled id="submit-'.$id.'">Conferma</button>
                        </td>
                    </tr>
                </form>
                ';
            }
            mysqli_free_result($result);
        } else {
            echo '<tr><td colspan="6">Errore nella query: '.mysqli_error($conn).'</td></tr>';
        }
        mysqli_close($conn);
    } else {
        echo '<tr><td colspan="6">Connessione fallita: '.mysqli_connect_error().'</td></tr>';
    }
    // Chiudi la tabella e il form
    echo '
        </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'upduser-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    <script>
    function enableEdit(id) {
        var row = document.getElementById("row-"+id);
        var inputs = row.querySelectorAll("input, select");
        for (var i=0; i<inputs.length; i++) {
            if (inputs[i].name !== "updId") inputs[i].disabled = false;
        }
        document.getElementById("submit-"+id).disabled = false;
        // Mostra help password
        var pswHelp = document.getElementById("pswHelp-"+id);
        if(pswHelp) pswHelp.style.display = "block";
    }

    function checkPasswordComplexityUpd(id) {
        var psw = document.getElementById("updPsw-"+id).value;
        var errorDiv = document.getElementById("pswError-"+id);
        var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W_]).{8,}$/;
        if (!pattern.test(psw)) {
            errorDiv.style.display = "block";
            errorDiv.textContent = "La password non rispetta la complessità richiesta.";
            return false;
        }
        errorDiv.style.display = "none";
        return true;
    }
    </script>
    ';
}
// function per cancellare gli utenti
function deluser() {
    // Gestione submit per cancellazione
    if (isset($_POST['submit_deluser'])) {
        $id = $_POST['delId'];
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            $stmt = mysqli_prepare($conn, "DELETE FROM utenti WHERE ID_Utente=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                $executed = mysqli_stmt_execute($stmt);
                // Se l'esecuzione è andata a buon fine, mostra un messaggio di successo
                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #d17359ff;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Utente Rimosso con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return;
                } else {
                    echo '<p style="color:red;">Errore nella rimozione: ' . mysqli_stmt_error($stmt) . '</p>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
            }
            mysqli_close($conn);
        } else {
            echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
        }
    }
    // Form per cancellare gli utenti
    echo '
    <div id="deluser-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #e6a46dff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Rimozione Utenti</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID</th>
                    <th style="padding:8px; border:1px solid #ccc;">Nome</th>
                    <th style="padding:8px; border:1px solid #ccc;">Cognome</th>
                    <th style="padding:8px; border:1px solid #ccc;">Password</th>
                    <th style="padding:8px; border:1px solid #ccc;">Admin</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';
    // Connessione al database e query per ottenere gli utenti
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM utenti");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_Utente'];
                $nome = htmlspecialchars($row['Nome']);
                $cognome = htmlspecialchars($row['Cognome']);
                $password = htmlspecialchars($row['Password']);
                $admin = $row['Admin'];
                echo '
                <form method="post" action="backend.php" onsubmit="return confirm(\'Sei sicuro di voler eliminare questo utente?\');">
                    <tr id="row-'.$id.'">
                        <td style="padding:8px; border:1px solid #ccc;">'.$id.'</td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delNome" value="'.$nome.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delCognome" value="'.$cognome.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delPsw" value="'.$password.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <select name="delAdmin" disabled style="width:90%;">
                                <option value="0" '.($admin==0?'selected':'').'>No</option>
                                <option value="1" '.($admin==1?'selected':'').'>Sì</option>
                            </select>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="hidden" name="delId" value="'.$id.'">
                            <button type="submit" name="submit_deluser" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px;">Seleziona</button>
                        </td>
                    </tr>
                </form>
                ';
            }
            mysqli_free_result($result);
        } else {
            echo '<tr><td colspan="6">Errore nella query: '.mysqli_error($conn).'</td></tr>';
        }
        mysqli_close($conn);
    } else {
        echo '<tr><td colspan="6">Connessione fallita: '.mysqli_connect_error().'</td></tr>';
    }
    // Chiudi la tabella e il form
    echo '
        </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'deluser-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    ';
}

//Funzione per aggiungere competenze
function addcomp() {
        // Script per fare insert into nel database
    if (isset($_POST['submit_addcomp'])) {
        $titolo = $_POST['addTitolo_comp'];
        $valcomp = $_POST['addVal_comp'];
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);

        if ($conn) {
            $stmt = mysqli_prepare($conn, "INSERT INTO competenze (Titolo_comp, Val_comp) VALUES (?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $titolo, $valcomp);
                $executed = mysqli_stmt_execute($stmt);

                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Competenza aggiunta con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return;
                } else {
                    echo '<p style="color:red;">Errore nell\'inserimento: ' . mysqli_stmt_error($stmt) . '</p>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
            }
            mysqli_close($conn);
        } else {
            echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
        }
    }

    echo '
    <div id="addcomp-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 600px;
        background: #b1e48aff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <form class="addcomp" action="backend.php" method="post" style="display: flex; flex-direction: column; gap: 12px;">
            <label for="addTitolo_comp">Titolo competenza:</label>
            <input type="text" name="addTitolo_comp" id="addTitolo_comp" required style="width: 60%;">

            <label for="addVal_comp">Valore competenza:</label>
            <input type="text" name="addVal_comp" id="addVal_comp" required style="width: 60%;">

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="submit" name="submit_addcomp" style="width: 120px; background-color: #28a745; color: #fff; border: none; border-radius: 4px; padding: 10px 0;">Aggiungi Competenza</button>
                <button type="button" class="closebtn" onclick="document.getElementById(\'addcomp-form\').remove();" style="width: 120px; background-color: #dc3545; color: #fff; border: none; border-radius: 4px; padding: 10px 0;">Chiudi</button>
            </div>
        </form>
    </div>';
}

//Funzione per modificare le competenze
function updcomp() {
    // Gestione submit per aggiornamento
    if (isset($_POST['submit_updcomp'])) {
        $id = $_POST['updId_comp'];
        $titolocomp = $_POST['updTitolo_comp'];
        $valcomp = $_POST['updVal_comp'];
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            $stmt = mysqli_prepare($conn, "UPDATE competenze SET Titolo_comp=?, Val_comp=? WHERE ID_competenza=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sii", $titolocomp, $valcomp, $id);
                $executed = mysqli_stmt_execute($stmt);
                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #818e36ff;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Competenza aggiornata con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return;
                } else {
                    echo '<p style="color:red;">Errore nell\'aggiornamento: ' . mysqli_stmt_error($stmt) . '</p>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
            }
            mysqli_close($conn);
        } else {
            echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
        }
    }

    echo '
    <div id="updcomp-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #d2df6bff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Modifica competenze</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID_competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Titolo competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Valore competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM competenze");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_competenza'];
                $Titolo_comp = htmlspecialchars($row['Titolo_comp']);
                $Val_comp = htmlspecialchars($row['Val_comp']);
                echo '
                <form method="post" action="backend.php">
                    <tr id="row-'.$id.'">
                        <td style="padding:8px; border:1px solid #ccc;">'.$id.'</td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="updTitolo_comp" value="'.$Titolo_comp.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="updVal_comp" value="'.$Val_comp.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="hidden" name="updId_comp" value="'.$id.'">
                            <button type="button" onclick="enableEditComp('.$id.')" style="background:#007bff; color:#fff; border:none; border-radius:4px; padding:6px 12px;">Seleziona</button>
                            <button type="submit" name="submit_updcomp" style="background:#28a745; color:#fff; border:none; border-radius:4px; padding:6px 12px;" disabled id="submitcomp-'.$id.'">Conferma</button>
                        </td>
                    </tr>
                </form>
                ';
            }
            mysqli_free_result($result);
        } else {
            echo '<tr><td colspan="4">Errore nella query: '.mysqli_error($conn).'</td></tr>';
        }
        mysqli_close($conn);
    } else {
        echo '<tr><td colspan="4">Connessione fallita: '.mysqli_connect_error().'</td></tr>';
    }

    echo '
        </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'updcomp-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    <script>
    function enableEditComp(id) {
        var row = document.getElementById("row-"+id);
        var inputs = row.querySelectorAll("input, select");
        for (var i=0; i<inputs.length; i++) {
            if (inputs[i].name !== "updId_comp") inputs[i].disabled = false;
        }
        document.getElementById("submitcomp-"+id).disabled = false;
    }
    </script>
    ';
}
// Funzione per cancellare le competenze
function delcomp() {
    // Gestione submit per cancellazione della competenza
    if (isset($_POST['submit_delcomp'])) {
        $id = $_POST['delId'];
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            // Corretto il nome del campo nella WHERE
            $stmt = mysqli_prepare($conn, "DELETE FROM competenze WHERE ID_competenza=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                $executed = mysqli_stmt_execute($stmt);
                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #d17359ff;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Competenza rimossa con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return;
                } else {
                    echo '<p style="color:red;">Errore nella rimozione: ' . mysqli_stmt_error($stmt) . '</p>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
            }
            mysqli_close($conn);
        } else {
            echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
        }
    }

    echo '
    <div id="delcomp-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #e6a46dff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Rimozione competenza</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID_competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Titolo competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Valore competenza</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM competenze");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_competenza'];
                $delTitolo_comp = htmlspecialchars($row['Titolo_comp']);
                $delVal_comp = htmlspecialchars($row['Val_comp']);
                echo '
                <form method="post" action="backend.php" onsubmit="return confirm(\'Sei sicuro di voler eliminare questa competenza?\');">
                    <tr id="row-'.$id.'">
                        <td style="padding:8px; border:1px solid #ccc;">'.$id.'</td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delTitolo_comp" value="'.$delTitolo_comp.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delVal_comp" value="'.$delVal_comp.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="hidden" name="delId" value="'.$id.'">
                            <button type="submit" name="submit_delcomp" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px;">Seleziona</button>
                        </td>
                    </tr>
                </form>
                ';
            }
            mysqli_free_result($result);
        } else {
            echo '<tr><td colspan="4">Errore nella query: '.mysqli_error($conn).'</td></tr>';
        }
        mysqli_close($conn);
    } else {
        echo '<tr><td colspan="4">Connessione fallita: '.mysqli_connect_error().'</td></tr>';
    }

    echo '
        </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'delcomp-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    ';
}

// Funzione per aggiungere un nuovo elemento portfolio
function addport() {
    $img_filename = null;

    // Se è stato inviato il form, procediamo con upload e inserimento
    if (isset($_POST['submit_addport'])) {
        $titolo_port = $_POST['addTitolo_port'] ?? '';
        $descr_port = $_POST['addDescr_port'] ?? '';

        // Calcola nome immagine solo una volta
        $id = nameImg_P();
        $img_filename = "p" . $id . ".png";
        $img_path = "img/" . $img_filename;

        // Caricamento immagine via FTP
        if (
            isset($_FILES['portfolio_image']) &&
            $_FILES['portfolio_image']['error'] === UPLOAD_ERR_OK &&
            mime_content_type($_FILES['portfolio_image']['tmp_name']) === 'image/png'
        ) {
            include("crd/crd.php");
            $ftp_server = $ftp_config['server'];
            $ftp_user = $ftp_config['user'];
            $ftp_pass = $ftp_config['pass'];
            $ftp_remote_dir = $ftp_config['remote_dir'];
            $local_file = $_FILES['portfolio_image']['tmp_name'];
            $remote_file = $ftp_remote_dir . $img_filename;

            $ftp_conn = ftp_connect($ftp_server, 21, 20);
            if ($ftp_conn && ftp_login($ftp_conn, $ftp_user, $ftp_pass)) {
                ftp_pasv($ftp_conn, true);
                if (!ftp_put($ftp_conn, $remote_file, $local_file, FTP_BINARY)) {
                    echo "<div style='color:red; text-align:center;'>Errore nel caricamento dell'immagine via FTP.</div>";
                    ftp_close($ftp_conn);
                    return;
                }
                ftp_close($ftp_conn);
            } else {
                echo "<div style='color:red; text-align:center;'>Connessione FTP fallita.</div>";
                return;
            }
        } else {
            echo "<div style='color:red; text-align:center;'>File non valido. Assicurati che sia un file PNG.</div>";
            return;
        }

        // Inserimento nel database
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            $stmt = mysqli_prepare($conn, "INSERT INTO portfolio (titolo_p, descr_p, img_p) VALUES (?, ?, ?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $titolo_port, $descr_port, $img_path);
                if (mysqli_stmt_execute($stmt)) {
                    echo "
                    <div id='success-message' style='
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    '>
                        Voce di portfolio inserita correttamente.
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById('success-message');
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ";
                } else {
                    echo "
                    <div id='error-message' style='
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #f8d7da;
                        color: #721c24;
                        border: 1px solid #f5c6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    '>
                        Errore nell'inserimento nel database.
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById('error-message');
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<div style='color:red; text-align:center;'>Errore nella preparazione della query.</div>";
            }
            mysqli_close($conn);
        } else {
            echo "<div style='color:red; text-align:center;'>Connessione al database fallita.</div>";
        }
    }

    // Mostra il form HTML
    echo '
    <div id="addport-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #a4f186ff;
        border-top: 1px solid #ccc;
        padding: 20px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <form class="addport" action="backend.php" method="post" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 12px;">
            <label for="addTitolo_port">Titolo:</label>
            <input type="text" name="addTitolo_port" id="addTitolo_port" required style="width: 60%;">

            <label for="addDescr_port">Descrizione:</label>
            <textarea name="addDescr_port" id="addDescr_port" required style="width: 90%; min-height: 80px;"></textarea>

            <label for="portfolio_image">Immagine (solo PNG):</label>
            <input type="file" name="portfolio_image" id="portfolio_image" accept="image/png" required style="width: 60%;">

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <button type="submit" name="submit_addport" style="width: 180px; background-color: #28a745; color: white; border: none; padding: 10px; border-radius: 5px;">Aggiungi voce di portfolio</button>
                <button type="button" class="closebtn" onclick="document.getElementById(\'addport-form\').remove();" style="width: 120px; background-color: #dc3545; color: white; border: none; padding: 10px; border-radius: 5px;">Chiudi</button>
            </div>
        </form>
    </div>';
}

// Funzione per aggiornare un elemento del portfolio
function updport() {
    include("crd/crd.php");
    // Gestione submit per aggiornamento
    if (isset($_POST['submit_updport'])) {
        $id = intval($_POST['updId_port']);
        $titolo_port = $_POST['updTitolo_port'] ?? '';
        $descr_port = $_POST['updDescr_port'] ?? '';

        // Recupera il nome file immagine esistente dal database
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        $img_path = '';
        $img_filename = '';
        if ($conn) {
            $res = mysqli_query($conn, "SELECT img_p FROM portfolio WHERE ID_po=" . $id);
            if ($res && $row = mysqli_fetch_assoc($res)) {
                $img_path = $row['img_p'];
                $img_filename = basename($img_path);
            }
            if ($res) mysqli_free_result($res);
            mysqli_close($conn);
        }

        $remote_file = $ftp_config['remote_dir'] . $img_filename;

        // Gestione upload nuova immagine PNG
        $upload_image = false;
        if (isset($_FILES['updImgPort']) && $_FILES['updImgPort']['error'] === UPLOAD_ERR_OK) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['updImgPort']['tmp_name']);
            finfo_close($finfo);

            if ($mime === 'image/png') {
                $upload_image = true;
            } else {
                echo "<div style='color:red; text-align:center;'>Il file non è un PNG valido.</div>";
                return;
            }
        }

        // Upload FTP se necessario
        if ($upload_image) {
            $ftp_conn = ftp_connect($ftp_config['server'], 21, 20);
            if ($ftp_conn && ftp_login($ftp_conn, $ftp_config['user'], $ftp_config['pass'])) {
                ftp_pasv($ftp_conn, true);

                // Elimina file remoto se esiste
                $existing_files = ftp_nlist($ftp_conn, $ftp_config['remote_dir']);
                if ($existing_files && in_array($remote_file, $existing_files)) {
                    ftp_delete($ftp_conn, $remote_file);
                }

                // Carica nuova immagine
                $local_file = $_FILES['updImgPort']['tmp_name'];
                if (!ftp_put($ftp_conn, $remote_file, $local_file, FTP_BINARY)) {
                    echo "<div style='color:red; text-align:center;'>Errore nel caricamento dell'immagine via FTP.</div>";
                    ftp_close($ftp_conn);
                    return;
                }
                ftp_close($ftp_conn);
            } else {
                echo "<div style='color:red; text-align:center;'>Connessione FTP fallita.</div>";
                return;
            }
        }

        // Aggiorna il database
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            $stmt = mysqli_prepare($conn, "UPDATE portfolio SET titolo_p=?, descr_p=?, img_p=? WHERE ID_po=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sssi", $titolo_port, $descr_port, $img_path, $id);
                $executed = mysqli_stmt_execute($stmt);
                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #155724;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Elemento portfolio aggiornato con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                } else {
                    echo "<p style='color:red;'>Errore nell'aggiornamento: " . mysqli_stmt_error($stmt) . "</p>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<p style='color:red;'>Errore nella query: " . mysqli_error($conn) . "</p>";
            }
            mysqli_close($conn);
        }
    }

    // FORM HTML
    echo '
    <div id="updport-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 1200px;
        background: #d2df6bff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Modifica portfolio</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID_portfolio</th>
                    <th style="padding:8px; border:1px solid #ccc;">Titolo portfolio</th>
                    <th style="padding:8px; border:1px solid #ccc;">Descrizione portfolio</th>
                    <th style="padding:8px; border:1px solid #ccc;">Modifica Immagine</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';

    // Stampa righe
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM portfolio");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_po'];
                $Titolo_p = htmlspecialchars($row['titolo_p']);
                $Descr_p = htmlspecialchars($row['descr_p']);
                echo "
                <form method=\"post\" action=\"backend.php\" enctype=\"multipart/form-data\">
                    <tr id=\"row-{$id}\">
                        <td style=\"padding:8px; border:1px solid #ccc;\">{$id}</td>
                        <td style=\"padding:8px; border:1px solid #ccc;\">
                            <input type=\"text\" name=\"updTitolo_port\" value=\"{$Titolo_p}\" style=\"width:90%;\" disabled>
                        </td>
                        <td style=\"padding:8px; border:1px solid #ccc;\">
                            <input type=\"text\" name=\"updDescr_port\" value=\"{$Descr_p}\" style=\"width:90%;\" disabled>
                        </td>
                        <td style=\"padding:8px; border:1px solid #ccc;\">
                            <input type=\"file\" name=\"updImgPort\" accept=\"image/png\" style=\"width:90%;\" disabled>
                        </td>
                        <td style=\"padding:8px; border:1px solid #ccc;\">
                            <input type=\"hidden\" name=\"updId_port\" value=\"{$id}\">
                            <button type=\"button\" onclick=\"enableEditPort({$id})\" style=\"background:#007bff; color:#fff; border:none; border-radius:4px; padding:6px 12px;\">Seleziona</button>
                            <button type=\"submit\" name=\"submit_updport\" style=\"background:#28a745; color:#fff; border:none; border-radius:4px; padding:6px 12px;\" disabled id=\"submitport-{$id}\">Conferma</button>
                        </td>
                    </tr>
                </form>";
            }
            mysqli_free_result($result);
        }
        mysqli_close($conn);
    }

    echo '
            </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'updport-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    <script>
    function enableEditPort(id) {
        var row = document.getElementById("row-"+id);
        var inputs = row.querySelectorAll("input, select");
        for (var i=0; i<inputs.length; i++) {
            if (inputs[i].name !== "updId_port") inputs[i].disabled = false;
        }
        document.getElementById("submitport-"+id).disabled = false;
    }
    </script>';
}


//Funzione per rimuovere un elemento del portfolio
function delport() {
    // Gestione submit per cancellazione
    if (isset($_POST['submit_delport'])) {
        $id = $_POST['delId'];
        include("crd/crd.php");
        $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
        if ($conn) {
            $stmt = mysqli_prepare($conn, "DELETE FROM portfolio WHERE ID_po=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "i", $id);
                $executed = mysqli_stmt_execute($stmt);
                if ($executed) {
                    echo '
                    <div id="success-message" style="
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background-color: #d4edda;
                        color: #d17359ff;
                        border: 1px solid #c3e6cb;
                        padding: 18px 32px;
                        border-radius: 8px;
                        font-weight: bold;
                        font-size: 1.2em;
                        z-index: 9999;
                        box-shadow: 0 2px 12px rgba(0,0,0,0.15);
                        text-align: center;
                    ">
                        Elemento del portfolio rimosso con successo!
                    </div>
                    <script>
                        setTimeout(function() {
                            var msg = document.getElementById("success-message");
                            if(msg) msg.remove();
                        }, 3000);
                    </script>
                    ';
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    return;
                } else {
                    echo '<p style="color:red;">Errore nella rimozione: ' . mysqli_stmt_error($stmt) . '</p>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<p style="color:red;">Errore nella preparazione della query: ' . mysqli_error($conn) . '</p>';
            }
            mysqli_close($conn);
        } else {
            echo '<p style="color:red;">Connessione fallita: ' . mysqli_connect_error() . '</p>';
        }
    }

    echo '
    <div id="delport-form" style="
        position: fixed;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100vw;
        max-width: 700px;
        background: #e6a46dff;
        border-top: 1px solid #ccc;
        padding: 20px 10px 20px 10px;
        z-index: 1000;
        border-radius: 12px 12px 0 0;
        font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        max-height: 90vh;
    ">
        <h3>Rimozione portfolio</h3>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#e3e3e3;">
                    <th style="padding:8px; border:1px solid #ccc;">ID</th>
                    <th style="padding:8px; border:1px solid #ccc;">Titolo</th>
                    <th style="padding:8px; border:1px solid #ccc;">Descrizione</th>
                    <th style="padding:8px; border:1px solid #ccc;">Azioni</th>
                </tr>
            </thead>
            <tbody>
    ';
    include("crd/crd.php");
    $conn = mysqli_connect($config['hostname'], $config['username'], $config['password'], $config['dbname']);
    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM portfolio");
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['ID_po'];
                $titolo_p = htmlspecialchars($row['titolo_p']);
                $descr_p = htmlspecialchars($row['descr_p']);
                echo '
                <form method="post" action="backend.php" onsubmit="return confirm(\'Sei sicuro di voler eliminare questo elemento del portfolio?\');">
                    <tr id="row-'.$id.'">
                        <td style="padding:8px; border:1px solid #ccc;">'.$id.'</td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delPort" value="'.$titolo_p.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="text" name="delDesc" value="'.$descr_p.'" style="width:90%;" disabled>
                        </td>
                        <td style="padding:8px; border:1px solid #ccc;">
                            <input type="hidden" name="delId" value="'.$id.'">
                            <button type="submit" name="submit_delport" style="background:#dc3545; color:#fff; border:none; border-radius:4px; padding:6px 12px;">Seleziona</button>
                        </td>
                    </tr>
                </form>
                ';
            }
            mysqli_free_result($result);
        } else {
            echo '<tr><td colspan="4">Errore nella query: '.mysqli_error($conn).'</td></tr>';
        }
        mysqli_close($conn);
    } else {
        echo '<tr><td colspan="4">Connessione fallita: '.mysqli_connect_error().'</td></tr>';
    }

    echo '
        </tbody>
        </table>
        <div style="margin-top:20px; text-align:right;">
            <button type="button" class="closebtn" onclick="document.getElementById(\'delport-form\').remove();" style="width:120px; background-color:#dc3545; color:#fff; border:none; border-radius:4px; padding:10px 0;">Chiudi</button>
        </div>
    </div>
    ';
}



?>

