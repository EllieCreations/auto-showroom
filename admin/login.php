<?php
include_once '../functions/message.php';
if (isset($_GET['logout_message'])) {
    echo displayMessage('green', 'Logout effettuato con successo');
}

session_start();
require_once '../functions/db.php'; // Connessione al database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Query per verificare le credenziali
    $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['welcome_message'] = "Benvenuto, {$username}!";
        header('Location: ../admin/admin.php');
        exit;
    } else {
        $error = "Credenziali non valide";
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Login Amministratore</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>

<body>
    <div class="form-wrapper">
        <h2 class="form-title">Accesso Amministrativo</h2>
        <a href="../index.php">
            <lord-icon src="https://cdn.lordicon.com/jeuxydnh.json" trigger="hover"
                colors="primary:#121331,secondary:#70b8ff" style="width:50px;height:50px">
            </lord-icon>
        </a>

        <?php if (isset($error))
            echo "<p style='color: red;'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" autocomplete="on" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="form-button">Accedi</button>
        </form>
    </div>
</body>

</html>