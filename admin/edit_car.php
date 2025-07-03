<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit;
}

require_once '../functions/functions.php'; // Adatta il percorso

// Recupera l'ID da GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("ID auto non valido.");
}

// Recupera i dati dell’auto
$car = get_car_by_id($id);
if (!$car) {
    die("Auto non trovata.");
}

// Recupera le immagini esistenti
$stmt = $conn->prepare("SELECT id, image_path FROM car_images WHERE car_id = ?");
$stmt->execute([$id]);
$existing_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Liste per i <select>
$brands        = get_brands();
$car_types     = get_car_types();
$fuel_types    = get_fuel_types();
$transmissions = get_transmissions();

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Aggiorna i dati dell’auto
    $brand_id        = $_POST['brand_id']        ?? '';
    $car_type_id     = $_POST['car_type_id']     ?? '';
    $fuel_type_id    = $_POST['fuel_type_id']    ?? '';
    $transmission_id = $_POST['transmission_id'] ?? '';
    $year            = $_POST['year']           ?? '';
    $price           = $_POST['price']          ?? '';
    $km              = $_POST['km']             ?? '';
    $seats           = $_POST['seats']          ?? '';
    $power_kw        = $_POST['power_kw']       ?? '';
    $car_condition   = $_POST['car_condition']   ?? 'usato';

    $update_result = update_car(
        $id,
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

    if ($update_result !== true) {
        $error = $update_result; // stringa con errore
    } else {
        // 2) Eliminazione immagini selezionate
        if (!empty($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $img_id) {
                // Recupera info immagine
                $stmtImg = $conn->prepare("SELECT image_path FROM car_images WHERE id = ? AND car_id = ?");
                $stmtImg->execute([$img_id, $id]);
                $img = $stmtImg->fetch(PDO::FETCH_ASSOC);
                
                if ($img) {
                    // Elimina file dal server
                    if (file_exists($img['image_path'])) {
                        unlink($img['image_path']);
                    }
                    // Elimina record DB
                    $stmtDel = $conn->prepare("DELETE FROM car_images WHERE id = ?");
                    $stmtDel->execute([$img_id]);
                }
            }
        }

        // 3) Aggiunta nuove immagini
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $upload_result = handle_multiple_uploads($_FILES['images'], 15);
            if (!empty($upload_result['errors'])) {
                $error .= implode("<br>", $upload_result['errors']);
            } else {
                insert_car_images($id, $upload_result['paths']);
            }
        }

        if (empty($error)) {
            $success = "Auto aggiornata con successo!";

            // Ricarica i dati e le immagini per riflettere i cambiamenti
            $car = get_car_by_id($id);
            $stmt = $conn->prepare("SELECT id, image_path FROM car_images WHERE car_id = ?");
            $stmt->execute([$id]);
            $existing_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Modifica Auto</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header class="header">
        <h1><a href="./admin.php">Gestione Auto</a></h1>
        <nav>
            <ul>
                <li><a href="./admin.php">Gestione Auto</a></li>
                <li><a href="./add_car.php">Aggiungi Auto</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-wrapper">
        <h2 class="form-title">Modifica Auto (ID=<?php echo $car['id']; ?>)</h2>
        
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_car.php?id=<?php echo $car['id']; ?>" enctype="multipart/form-data">
            
            <!-- Marca -->
            <div class="form-group">
                <label for="brand_id">Marca</label>
                <select id="brand_id" name="brand_id" required>
                    <option value="">-- Seleziona Marca --</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?php echo $b['id']; ?>"
                            <?php if ($b['id'] == $car['brand_id']) echo 'selected'; ?>>
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
                        <option value="<?php echo $ct['id']; ?>"
                            <?php if ($ct['id'] == $car['car_type_id']) echo 'selected'; ?>>
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
                        <option value="<?php echo $ft['id']; ?>"
                            <?php if ($ft['id'] == $car['fuel_type_id']) echo 'selected'; ?>>
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
                        <option value="<?php echo $t['id']; ?>"
                            <?php if ($t['id'] == $car['transmission_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($t['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Anno -->
            <div class="form-group">
                <label for="year">Anno</label>
                <input type="number" id="year" name="year" 
                       min="1900" max="2100" required
                       value="<?php echo htmlspecialchars($car['year']); ?>">
            </div>

            <!-- Prezzo -->
            <div class="form-group">
                <label for="price">Prezzo (€)</label>
                <input type="number" id="price" name="price" step="0.01" required
                       value="<?php echo htmlspecialchars($car['price']); ?>">
            </div>

            <!-- KM -->
            <div class="form-group">
                <label for="km">KM</label>
                <input type="number" id="km" name="km" required
                       value="<?php echo htmlspecialchars($car['km']); ?>">
            </div>

            <!-- Posti -->
            <div class="form-group">
                <label for="seats">Posti</label>
                <input type="number" id="seats" name="seats" required
                       value="<?php echo htmlspecialchars($car['seats']); ?>">
            </div>

            <!-- Potenza (kW) -->
            <div class="form-group">
                <label for="power_kw">Potenza (kW)</label>
                <input type="number" id="power_kw" name="power_kw" required
                       value="<?php echo htmlspecialchars($car['power_kw']); ?>">
            </div>

            <!-- Condizione -->
            <div class="form-group">
                <label for="car_condition">Condizione</label>
                <select id="car_condition" name="car_condition" required>
                    <option value="nuovo" <?php if ($car['car_condition'] === 'nuovo') echo 'selected'; ?>>
                        Nuovo
                    </option>
                    <option value="usato" <?php if ($car['car_condition'] === 'usato') echo 'selected'; ?>>
                        Usato
                    </option>
                </select>
            </div>

            <hr>

            <!-- Immagini esistenti -->
            <h3>Immagini attuali</h3>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <?php foreach ($existing_images as $img): ?>
                    <div style="border:1px solid #ccc; padding:10px;">
                        <img src="<?php echo htmlspecialchars($img['image_path']); ?>"
                             alt="Immagine" style="max-width:100px;">
                        <br>
                        <!-- Checkbox per eliminarla -->
                        <label>
                            <input type="checkbox" name="delete_images[]" value="<?php echo $img['id']; ?>">
                            Elimina
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

            <hr>

            <!-- Nuove immagini -->
            <h3>Aggiungi nuove immagini (opzionale)</h3>
            <p>Puoi caricarne fino a 15 alla volta</p>
            <div class="form-group">
                <input type="file" name="images[]" multiple>
            </div>

            <button type="submit" class="form-button">Salva Modifiche</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2025 Autoshowroom. Tutti i diritti riservati.</p>
    </footer>
</body>
</html>
