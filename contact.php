<?php
// Utilizza PHPMailer per inviare email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return;
}

// Importa PHPMailer
require 'php_mail/PHPMailer.php';
require 'php_mail/SMTP.php';
require 'php_mail/Exception.php';

// Leggi i dati dal form
$nome = htmlspecialchars($_POST['nome']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$messaggio = htmlspecialchars($_POST['messaggio']);

$mail = new PHPMailer(true);

try {
    // Configurazione SMTP Aruba
    $mail->isSMTP();
    $mail->Host = 'smtps.aruba.it'; // Host SMTP di Aruba
    $mail->SMTPAuth = true;
    $mail->Username = 'sender@angeloiandolo.it'; // Inserisci la tua email Aruba
    $mail->Password = '7I19!42q;aR#'; // Inserisci la password della tua email Aruba
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; // Porta TLS

    // Mittente e destinatario
    $mail->setFrom('sender@angeloiandolo.it', 'Sito Web');
    $mail->addAddress('info@angeloiandolo.it', 'Angelo Iandolo');
    $mail->addReplyTo($email, $nome); // così puoi rispondere al mittente

    // Contenuto dell’email
    $mail->isHTML(true);
    $mail->Subject = 'Nuovo messaggio dal sito';
    $mail->Body    = "
        <h2>Hai ricevuto un nuovo messaggio dal sito</h2>
        <p><strong>Nome:</strong> {$nome}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Messaggio:</strong><br>{$messaggio}</p>
    ";
    $mail->AltBody = "Nome: $nome\nEmail: $email\nMessaggio:\n$messaggio";

    // Invia
    $mail->send();
    echo "<p style='color: green;'>Messaggio inviato con successo!</p>";
    echo "<p><a href='index.php#contatti'>Torna indietro</a></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Errore nell'invio del messaggio: {$mail->ErrorInfo}</p>";
    echo "<p><a href='index.php#contatti'>Torna indietro</a></p>";
}
?>
