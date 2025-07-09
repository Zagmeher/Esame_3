<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Importa PHPMailer
require 'PHP_MAIL/PHPMailer.php';
require 'PHP_MAIL/SMTP.php';
require 'PHP_MAIL/Exception.php';

// Leggi i dati dal form
$nome = htmlspecialchars($_POST['nome']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$messaggio = htmlspecialchars($_POST['messaggio']);

$mail = new PHPMailer(true);

try {
    // Configurazione SMTP Aruba
    $mail->isSMTP();
    $mail->Host       = 'smtp.aruba.it';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sender@angeloiandolo.it'; // la tua email tecnica
    $mail->Password   = '7I19!42q;aR#';         // password casella sender
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

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
