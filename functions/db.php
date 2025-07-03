<?php
$host = 'localhost';
$dbname = 'autoshowroom';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // In produzione, logga l'errore invece di mostrarlo
    error_log("Errore di connessione al database: " . $e->getMessage());
    die("Errore nella connessione al database.");
}
?>
