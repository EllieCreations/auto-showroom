<?php
// Avvia la sessione
session_start();

// Distrugge la sessione
session_unset(); // Rimuove tutte le variabili di sessione
session_destroy(); // Distrugge la sessione stessa

// Reindirizza alla pagina di login o homepage
header('Location: login.php?logout_message=1');
exit;

?>
