<?php
require_once '../functions/db.php'; // Connessione al database

$username = 'admin';  // <-- Cambia con il tuo username
$password_plain = 'admin'; // <-- Cambia con la tua password

$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashed_password]);

echo "Utente admin creato con successo.";
