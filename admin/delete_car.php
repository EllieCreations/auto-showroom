delete_car.php
<?php
// Includiamo il file delle funzioni per poter richiamare le funzioni definite
require '../functions/functions.php';

//verifica se la sessione è attiva sennà login
session_start();

if (!isset($_SESSION['user_id'])) {
    // Se la sessione non è attiva, reindirizza al login
    header('Location: ./login.php');
    exit;
}

// Verifica che ci sia un ID nell'URL
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Chiama la funzione per eliminare l'auto
    $success = delete_car($id);

    // Se l'eliminazione è riuscita, reindirizza alla pagina di gestione
    if ($success) {
        header("Location: ./admin.php");
        exit;
    } else {
        echo "Errore nell'eliminazione dell'auto.";
    }
}
?>