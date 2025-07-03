<?php
// Avvia la sessione per verificare se l'utente è autenticato
session_start();

// Se l'utente non è loggato, reindirizza alla pagina di login
if (!isset($_SESSION['user_id'])) {
    header('Location:./login.php');
    exit;
}

// Includi le funzioni per recuperare le auto dal database
require '../functions/functions.php';

// Includi le funzioni per recuperare le immagini associate alle auto
include '../functions/get_car_images.php';

// Recupera tutte le auto con i relativi dettagli (JOIN tra tabelle collegate)
$cars = get_all_cars_complete();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Auto</title>
    <!-- Collegamento al file CSS dell'area admin -->
    <link rel="stylesheet" href="../css/admin.css">
    <!-- Libreria per le icone animate -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
</head>
<body>

    <!-- HEADER della sezione amministrativa -->
    <header class="header">
        <h1><a href="../index.php">Sezione amministrativa</a></h1>
        <nav>
            <ul>
                <!-- Link per aggiungere una nuova auto -->
                <li><a href="./add_car.php">Aggiungi Auto</a></li>
            </ul>
        </nav>
    </header>

    <!-- CONTENUTO PRINCIPALE -->
    <main class="container">
        <h2>Gestione delle Auto</h2>
        <div class="table-wrapper">
            <table class="styled-table">
                <thead>
                    <tr>
                        <!-- Intestazioni della tabella -->
                        <th class="col-id">ID</th>
                        <th class="col-img">Immagine</th>
                        <th>Marca</th>
                        <th>Anno</th>
                        <th>Tipo</th>
                        <th>Alimentazione</th>
                        <th>Cambio</th>
                        <th>KM</th>
                        <th>Potenza (kW)</th>
                        <th>Posti</th>
                        <th>Condizione</th>
                        <th>Prezzo</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ciclo sulle auto recuperate dal database -->
                    <?php foreach ($cars as $car): ?>
                        <tr class="car-row" data-id="<?php echo htmlspecialchars($car['id']); ?>">
                            <!-- Colonna ID + bottone per toggle (accordion) -->
                            <td class="car-id-toggle" data-label="ID">
                                <?php echo htmlspecialchars($car['id']); ?>
                                <button class="toggle-btn">Espandi</button>
                            </td>

                            <!-- Colonna immagine -->
                            <td class="car-img" data-label="Immagine">
                                <?php
                                    // Recupera la prima immagine disponibile, oppure un segnaposto
                                    $images = getCarImages($conn, $car['id'], 1);
                                    $image_path = $images[0]['image_path'] ?? '../assets/no-image.png';
                                    echo "<img src='" . htmlspecialchars($image_path) . "' alt='Immagine Auto' class='car-image'>";
                                ?>
                            </td>

                            <!-- Colonne dati -->
                            <td data-label="Marca"><?php echo htmlspecialchars($car['brand_name']); ?></td>
                            <td data-label="Anno"><?php echo htmlspecialchars($car['year']); ?></td>
                            <td data-label="Tipo"><?php echo htmlspecialchars($car['car_type_name']); ?></td>
                            <td data-label="Alimentazione"><?php echo htmlspecialchars($car['fuel_type_name']); ?></td>
                            <td data-label="Cambio"><?php echo htmlspecialchars($car['transmission_name']); ?></td>
                            <td data-label="KM"><?php echo number_format($car['km'], 0, '', '.'); ?></td>
                            <td data-label="Potenza (kW)"><?php echo htmlspecialchars($car['power_kw']); ?></td>
                            <td data-label="Posti"><?php echo htmlspecialchars($car['seats']); ?></td>
                            <td data-label="Condizione"><?php echo htmlspecialchars($car['car_condition']); ?></td>
                            <td data-label="Prezzo">€<?php echo number_format($car['price'], 2, ',', '.'); ?></td>

                            <!-- Colonna con i pulsanti Azioni -->
                            <td data-label="Azioni">
                                <a href="./edit_car.php?id=<?php echo $car['id']; ?>" class="button edit">Modifica</a>
                                <a href="./delete_car.php?id=<?php echo $car['id']; ?>" class="button delete"
                                   onclick="return confirm('Sei sicuro di voler eliminare questa auto?');">
                                    Elimina
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Pulsante per uscire (logout) -->
    <a href="logout.php" class="button delete"
       style="margin: 20px auto; display:block; text-align:center; max-width:200px;">
        Logout
    </a>

    <!-- Footer della pagina -->
    <footer>
        <p>&copy; 2025 Autoshowroom. Tutti i diritti riservati.</p>
    </footer>

    <!-- Script JS per comportamento dinamico (accordion o altri toggle) -->
    <script src="../js/admin.js"></script>
</body>
</html>
