<?php
session_start();

// Genera un token CSRF se non esiste
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoShowRoom di Simone</title>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="contact-page">
            <h2 class="page-title">Contattaci</h2>
            <div class="contact-content">
                <!-- Modulo di Contatto -->
                <div class="contact-form-wrapper">
                    <form class="contact-form" action="../functions/process_contact.php" method="POST" id="contact-form">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="text" name="honeypot" style="display:none">

                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" id="name" name="name" placeholder="Il tuo nome" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="Il tuo indirizzo email" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Messaggio</label>
                            <textarea id="message" name="message" placeholder="Scrivi il tuo messaggio" rows="5" required></textarea>
                        </div>

                        <!-- reCAPTCHA v2 Invisible -->
                        <button class="g-recaptcha button form-button" 
                                data-sitekey="6LegbtIqAAAAAIkF-8mcvUhU5xo1VBkRMgKbJrDh" 
                                data-callback='onSubmit' 
                                data-action='submit'>Invia</button>
                    </form>
                </div>
                <!-- Informazioni di Contatto -->
                <div class="contact-info">
                    <h3>Informazioni di Contatto</h3>
                    <p><strong>Località:</strong> World</p>
                    <p><strong>Telefono:</strong> 555 - 555 - 555</p>
                    <p><strong>Email:</strong> -------@gmail.com</p>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        function onSubmit(token) {
            document.getElementById('contact-form').submit();
        }
    </script>

    <script src="../js/script.js"></script>
</body>

</html>