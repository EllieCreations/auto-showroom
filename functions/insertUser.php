<?php
require 'db.php';

// Attiva il debug per visualizzare gli errori
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Gestione dell'invio del form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']) ?? '';
    $password = trim($_POST['password']) ?? '';

    if (empty($username) || empty($password)) {
        $error = "Tutti i campi sono obbligatori.";
    } else {
        // Hash della password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Controlla se lo username esiste già
        $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Nome utente già esistente. Scegli un altro.";
        } else {
            // Inserisce il nuovo utente
            $stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $password_hash])) {
                $success = "Utente aggiunto con successo!";
            } else {
                $error = "Errore nell'inserimento dei dati.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Utente</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Aggiungi Utente</h1>

        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
            <a href="admin.php" class="button">Torna al pannello di gestione</a>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="insertUser.php" method="post" class="form">
            <div class="form-group">
                <label for="username">Nome utente:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="role">Ruolo:</label>
                <select id="role" name="role">
                    <option value="user">Utente</option>
                    <option value="admin">Amministratore</option>
                </select>
            </div>

            <button type="submit" class="button">Aggiungi Utente</button>
        </form>
    </div>
</body>
</html>
