<?php
session_start();

// Controlla se l'utente è loggato (opzionale per sicurezza)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once '../functions/db.php'; // Connessione al database

try {
    $stmt = $conn->query("SELECT id, username FROM admin_users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errore nella query: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Lista Utenti Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <div class="form-wrapper">
        <h2 class="form-title">Utenti Amministratori</h2>
        <ul>
            <?php foreach ($users as $user): ?>
                <li><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?> –
                    <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
