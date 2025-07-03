<?php
session_start(); // Avvia la sessione per verificare l'accesso

// Controlla se l'utente è autenticato, altrimenti reindirizza al login
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

require_once '../functions/functions.php';  // Include le funzioni per la gestione auto

// Recupera i dati necessari per i menu a discesa del form
$brands = get_brands();
$car_types = get_car_types();
$fuel_types = get_fuel_types();
$transmissions = get_transmissions();

$success = '';
$error = '';

// Se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1) Recupera i dati inviati dal form
    $brand_id = $_POST['brand_id'] ?? '';
    $car_type_id = $_POST['car_type_id'] ?? '';
    $fuel_type_id = $_POST['fuel_type_id'] ?? '';
    $transmission_id = $_POST['transmission_id'] ?? '';
    $year = $_POST['year'] ?? '';
    $price = $_POST['price'] ?? '';
    $km = $_POST['km'] ?? '';
    $seats = $_POST['seats'] ?? '';
    $power_kw = $_POST['power_kw'] ?? '';
    $car_condition = $_POST['car_condition'] ?? 'usato';

    // 2) Tenta di aggiungere l'auto nel DB, la funzione restituisce l'id oppure una stringa di errore
    $result = add_car(
        $brand_id,
        $car_type_id,
        $fuel_type_id,
        $transmission_id,
        $year,
        $price,
        $km,
        $seats,
        $power_kw,
        $car_condition
    );

    if (is_int($result)) {
        // Auto inserita correttamente
        $car_id = $result;

        // 3) Gestione upload immagini multiple
        if (isset($_FILES['images'])) {
            $upload_result = handle_multiple_uploads($_FILES['images'], 15); // max 15 immagini

            if (!empty($upload_result['errors'])) {
                $error = implode("<br>", $upload_result['errors']); // errori durante l’upload
            } else {
                // Salva i path delle immagini nel DB
                insert_car_images($car_id, $upload_result['paths']);
                $success = "Auto inserita con successo, con " . count($upload_result['paths']) . " immagini!";
            }
        } else {
            $success = "Auto inserita con successo, senza immagini!";
        }

    } else {
        // Errore nella funzione add_car()
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Aggiungi Auto</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const overlay = document.getElementById('loading-overlay');

        if (form && overlay) {
            form.addEventListener('submit', function () {
                overlay.style.display = 'block'; // Mostra overlay di caricamento
            });
        }

        // Blocca anche i pulsanti
        form.addEventListener('submit', function (e) {
            const buttons = form.querySelectorAll('button');
            buttons.forEach(btn => btn.disabled = true);
            overlay.style.display = 'block';
        });
    });
</script>


<body>

    <!-- Overlay e spinner -->
    <div id="loading-overlay">
        <div class="spinner"></div>
        <p>Caricamento in corso, attendere...</p>
    </div>

    <!-- Header -->
    <header class="header">
        <h1><a href="../index.php">Sezione amministrativa</a></h1>
        <nav>
            <ul>
                <li><a href="./admin.php">Gestione Auto</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <h1>Aggiungi Auto</h1>

        <!-- Messaggi -->
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="form-wrapper">
            <!-- Enctype obbligatorio per upload file -->
            <form method="POST" action="add_car.php" enctype="multipart/form-data">

                <!-- Marca -->
                <div class="form-group">
                    <label for="brand_id">Marca</label>
                    <select id="brand_id" name="brand_id" required>
                        <option value="">-- Seleziona Marca --</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?php echo $b['id']; ?>">
                                <?php echo htmlspecialchars($b['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo carrozzeria -->
                <div class="form-group">
                    <label for="car_type_id">Tipo di carrozzeria</label>
                    <select id="car_type_id" name="car_type_id" required>
                        <option value="">-- Seleziona Tipo --</option>
                        <?php foreach ($car_types as $ct): ?>
                            <option value="<?php echo $ct['id']; ?>">
                                <?php echo htmlspecialchars($ct['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Alimentazione -->
                <div class="form-group">
                    <label for="fuel_type_id">Alimentazione</label>
                    <select id="fuel_type_id" name="fuel_type_id" required>
                        <option value="">-- Seleziona Alimentazione --</option>
                        <?php foreach ($fuel_types as $ft): ?>
                            <option value="<?php echo $ft['id']; ?>">
                                <?php echo htmlspecialchars($ft['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Cambio -->
                <div class="form-group">
                    <label for="transmission_id">Cambio</label>
                    <select id="transmission_id" name="transmission_id" required>
                        <option value="">-- Seleziona Cambio --</option>
                        <?php foreach ($transmissions as $t): ?>
                            <option value="<?php echo $t['id']; ?>">
                                <?php echo htmlspecialchars($t['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Anno -->
                <div class="form-group">
                    <label for="year">Anno</label>
                    <input type="number" id="year" name="year" min="1900" max="2100" required>
                </div>

                <!-- Prezzo -->
                <div class="form-group">
                    <label for="price">Prezzo (€)</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>

                <!-- KM -->
                <div class="form-group">
                    <label for="km">KM</label>
                    <input type="number" id="km" name="km" required>
                </div>

                <!-- Posti -->
                <div class="form-group">
                    <label for="seats">Posti</label>
                    <input type="number" id="seats" name="seats" required>
                </div>

                <!-- Potenza (kW) -->
                <div class="form-group">
                    <label for="power_kw">Potenza (kW)</label>
                    <input type="number" id="power_kw" name="power_kw" required>
                </div>

                <!-- Condizione -->
                <div class="form-group">
                    <label for="car_condition">Condizione</label>
                    <select id="car_condition" name="car_condition" required>
                        <option value="nuovo">Nuovo</option>
                        <option value="usato" selected>Usato</option>
                    </select>
                </div>

                <!-- Immagini multiple -->
                <div class="form-group">
                    <label for="images">Immagini (1-15)</label>
                    <input type="file" id="images" name="images[]" multiple>
                    <small>Puoi caricare fino a 15 immagini</small>
                </div>

                <button type="submit" class="form-button">Salva</button>
            </form>
        </div>
    </div>
</body>

</html>