<?php
// process_contact.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../src/PHPMailer.php';
require '../src/Exception.php';
require '../src/SMTP.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Protezione CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Richiesta non autorizzata.");
    }

    // Protezione Honeypot
    if (!empty($_POST['honeypot'])) {
        die("Spam rilevato.");
    }

    // Rate limiting
    if (!isset($_SESSION['form_submit_count'])) {
        $_SESSION['form_submit_count'] = 0;
    }
    $_SESSION['form_submit_count']++;
    if ($_SESSION['form_submit_count'] > 5) {
        die("Hai superato il limite di invii.");
    }

    // Verifica reCAPTCHA v2 Invisible
    $recaptcha_secret = '6LegbtIqAAAAABBZoj17EqKYKy8roaxnKrh3zz2S';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $response = file_get_contents(""); //link google recaptcha
    $response_keys = json_decode($response, true);

    if (!$response_keys['success']) {
        die("Verifica reCAPTCHA fallita. Riprova.");
    }

    $nome = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $messaggio = htmlspecialchars($_POST['message']);

    if (empty($nome) || empty($email) || empty($messaggio)) {
        die("Errore: tutti i campi sono obbligatori.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Errore: formato email non valido.");
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = '';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('', ''); // Mittente ufficiale del dominio
        $mail->addReplyTo($email, $nome);  // L'utente riceverà la risposta a questa email
        $mail->addAddress(''); // Email di destinazione


        $mail->Subject = "Nuovo messaggio dal sito web";
        $mail->Body = "Hai ricevuto un nuovo messaggio dal modulo di contatto:\n\nNome: $nome\nEmail: $email\nMessaggio:\n$messaggio";
        $mail->AltBody = "Nome: $nome - Email: $email - Messaggio: $messaggio";

        $mail->send();
        echo "<script>alert('Messaggio inviato con successo! Ti risponderemo al più presto.'); window.location.href='../pages/contatti.php';</script>";
    } catch (Exception $e) {
        echo "Errore nell'invio dell'email: {$mail->ErrorInfo}";
    }
} else {
    echo "Accesso non autorizzato.";
}
?>