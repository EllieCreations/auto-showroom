<?php
// car_details.php

// 1. Includi la connessione al database
require_once 'db.php';

// 2. Recupera l'ID dell'auto dal parametro GET
// Utilizza il parametro `id` passato tramite la query string, effettuando un cast a intero per evitare valori non validi.
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 3. Controllo sull'ID
// Se l'ID non è valido (0 o negativo), effettua un redirect a `inventario.php` con un messaggio di errore.
if ($car_id <= 0) {
    header('Location: inventario.php?error=invalid_id');
    exit;
}

// 4. Query per recuperare i dettagli dell'auto
// Questa query utilizza un JOIN tra diverse tabelle (brands, car_types, fuel_types, transmissions)
// per ottenere informazioni complete sull'auto, come marca, tipo, alimentazione e cambio.
$sqlCar = "
    SELECT 
        c.*, 
        b.name AS brand_name,
        ct.name AS car_type_name,
        f.name AS fuel_type_name,
        t.name AS transmission_name
    FROM cars c
    JOIN brands b ON c.brand_id = b.id
    JOIN car_types ct ON c.car_type_id = ct.id
    JOIN fuel_types f ON c.fuel_type_id = f.id
    JOIN transmissions t ON c.transmission_id = t.id
    WHERE c.id = :car_id
";

// Prepara la query per una maggiore sicurezza contro SQL Injection
$stmtCar = $conn->prepare($sqlCar);

// Associa il valore dell'ID auto al parametro `:car_id`
$stmtCar->bindValue(':car_id', $car_id, PDO::PARAM_INT);

// Esegui la query
$stmtCar->execute();

// Recupera il risultato come array associativo
$car = $stmtCar->fetch(PDO::FETCH_ASSOC);

// 5. Verifica se l'auto esiste
// Se l'auto non viene trovata (risultato vuoto), effettua un redirect a `inventario.php` con un messaggio di errore.
if (!$car) {
    error_log("Car not found with ID: $car_id", 3, '/path/to/error.log');

    header('Location: inventario.php?error=not_found');
    exit;
}

// 6. Query per recuperare le immagini dell'auto
// Questa query recupera tutte le immagini collegate all'ID dell'auto tramite la tabella `car_images`.
$sqlImages = "SELECT image_path FROM car_images WHERE car_id = :car_id";

// Prepara la query per la selezione delle immagini
$stmtImages = $conn->prepare($sqlImages);

// Associa il valore dell'ID auto al parametro `:car_id`
$stmtImages->bindValue(':car_id', $car_id, PDO::PARAM_INT);

// Esegui la query
$stmtImages->execute();

// Recupera i risultati come array associativo (ogni immagine è rappresentata come un array con il percorso dell'immagine)
$images = $stmtImages->fetchAll(PDO::FETCH_ASSOC);

// Ora `$car` contiene i dettagli dell'auto e `$images` contiene le immagini associate.
