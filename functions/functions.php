<?php
// Se hai un file separato db.php con la variabile $conn, includilo:
require_once 'db.php';

/* ======================================================================
   FUNZIONI PER LISTE DI SUPPORTO (brands, car_types, fuel_types, transmissions)
   ====================================================================== */
function get_brands() {
    global $conn;
    $sql = "SELECT id, name FROM brands ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_car_types() {
    global $conn;
    $sql = "SELECT id, name FROM car_types ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_fuel_types() {
    global $conn;
    $sql = "SELECT id, name FROM fuel_types ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_transmissions() {
    global $conn;
    $sql = "SELECT id, name FROM transmissions ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* ======================================================================
   FUNZIONI CRUD PER LA TABELLA CARS
   ====================================================================== */

/**
 * Restituisce i dettagli di un’auto singola (array associativo).
 */
function get_car_by_id($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Inserisce una nuova auto nella tabella `cars` e restituisce:
 * - l'ID generato (int) in caso di successo
 * - oppure una stringa di errore.
 */
function add_car($brand_id, $car_type_id, $fuel_type_id, $transmission_id, $year, $price, $km, $seats, $power_kw, $car_condition) {
    global $conn;

    // Esempio di validazioni minime
    $brand_id        = (int)$brand_id;
    $car_type_id     = (int)$car_type_id;
    $fuel_type_id    = (int)$fuel_type_id;
    $transmission_id = (int)$transmission_id;
    $year            = (int)$year;
    $price           = (float)$price;
    $km              = (int)$km;
    $seats           = (int)$seats;
    $power_kw        = (int)$power_kw;

    // Query di inserimento
    $sql = "INSERT INTO cars 
        (brand_id, car_type_id, fuel_type_id, transmission_id, year, price, km, seats, power_kw, car_condition)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
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
        ]);
        // Se tutto ok, ritorniamo l'ID generato
        return (int)$conn->lastInsertId();

    } catch (PDOException $e) {
        error_log("Errore SQL in add_car: " . $e->getMessage());
        return "Errore nell'inserimento dei dati: " . $e->getMessage();
    }
}

/**
 * Aggiorna un’auto esistente in `cars`.
 * Restituisce true in caso di successo oppure una stringa di errore.
 */
function update_car($id, $brand_id, $car_type_id, $fuel_type_id, $transmission_id, $year, $price, $km, $seats, $power_kw, $car_condition) {
    global $conn;
    
    $id              = (int)$id;
    $brand_id        = (int)$brand_id;
    $car_type_id     = (int)$car_type_id;
    $fuel_type_id    = (int)$fuel_type_id;
    $transmission_id = (int)$transmission_id;
    $year            = (int)$year;
    $price           = (float)$price;
    $km              = (int)$km;
    $seats           = (int)$seats;
    $power_kw        = (int)$power_kw;

    $sql = "UPDATE cars
            SET brand_id = ?,
                car_type_id = ?,
                fuel_type_id = ?,
                transmission_id = ?,
                year = ?,
                price = ?,
                km = ?,
                seats = ?,
                power_kw = ?,
                car_condition = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            $brand_id,
            $car_type_id,
            $fuel_type_id,
            $transmission_id,
            $year,
            $price,
            $km,
            $seats,
            $power_kw,
            $car_condition,
            $id
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Errore SQL in update_car: " . $e->getMessage());
        return "Errore nell'aggiornamento dei dati: " . $e->getMessage();
    }
}

/**
 * Elimina un’auto (e le immagini correlate, se vuoi) dalla tabella `cars`.
 */
function delete_car($id) {
    global $conn;
    try {
        // Se vuoi eliminare anche le immagini collegate (file + righe di DB):
        // 1) Cancella i record da car_images
        $stmt = $conn->prepare("SELECT image_path FROM car_images WHERE car_id = ?");
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Elimina i file dal server
        foreach ($images as $img) {
            if ($img['image_path'] && file_exists($img['image_path'])) {
                unlink($img['image_path']);
            }
        }
        // Elimina record
        $stmtDel = $conn->prepare("DELETE FROM car_images WHERE car_id = ?");
        $stmtDel->execute([$id]);

        // 2) Cancella l’auto
        $stmtCar = $conn->prepare("DELETE FROM cars WHERE id = ?");
        $stmtCar->execute([$id]);

        return true;

    } catch (PDOException $e) {
        error_log("Errore nell'eliminazione dell'auto: " . $e->getMessage());
        return false;
    }
}

/* ======================================================================
   Ridimensiona e salva l'immagine a destinazione
   ====================================================================== */
/**
 * @param string $source_path Percorso del file originale (tmp)
 * @param string $dest_path   Percorso di destinazione finale
 * @param int    $max_width   Larghezza massima in pixel
 * @param int    $max_height  Altezza massima in pixel
 * @param int    $quality     Qualità JPEG (0-100)
 * @return bool  true se ok, false se errore
 */
function resizeImage($source_path, $dest_path, $max_width, $max_height, $quality = 85) {
    // Ottieni info sull'immagine
    list($width, $height, $type) = getimagesize($source_path);

    // Se non si riesce a ottenere, ritorna false
    if (!$width || !$height) {
        return false;
    }

    // Calcola il rapporto di ridimensionamento
    $ratio_orig = $width / $height;
    $new_width  = $width;
    $new_height = $height;

    // Limita in base a $max_width, $max_height
    if ($new_width > $max_width) {
        $new_width  = $max_width;
        $new_height = $new_width / $ratio_orig;
    }
    if ($new_height > $max_height) {
        $new_height = $max_height;
        $new_width  = $new_height * $ratio_orig;
    }

    // Crea risorsa di immagine PHP dall'originale
    switch ($type) {
        case IMAGETYPE_JPEG:
            $src = imagecreatefromjpeg($source_path);
            break;
        case IMAGETYPE_PNG:
            $src = imagecreatefrompng($source_path);
            break;
        case IMAGETYPE_GIF:
            $src = imagecreatefromgif($source_path);
            break;
        default:
            // Tipo non supportato
            return false;
    }

    // Crea la nuova immagine "vuota" ridimensionata
    $dst = imagecreatetruecolor((int)$new_width, (int)$new_height);

    // Preserva trasparenza per PNG/GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    // Copia e riscalata l'immagine
    imagecopyresampled($dst, $src, 0, 0, 0, 0, (int)$new_width, (int)$new_height, $width, $height);

    // Salva l'immagine nel percorso di destinazione
    $success = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $success = imagejpeg($dst, $dest_path, $quality);
            break;
        case IMAGETYPE_PNG:
            // Convertiamo la "qualità" in un concetto di compressione PNG (0-9)
            // Più alto = più compressione = file più piccolo
            // mappiamo 85 di jpeg a ~3/4 di compressione png
            $png_compression = 9 - round(($quality / 100) * 9);
            $success = imagepng($dst, $dest_path, $png_compression);
            break;
        case IMAGETYPE_GIF:
            $success = imagegif($dst, $dest_path);
            break;
    }

    // Libera memoria
    imagedestroy($src);
    imagedestroy($dst);

    return $success;
}

/* ======================================================================
   FUNZIONI PER LE IMMAGINI (car_images)
   ====================================================================== */

/**
 * Carica più immagini (controlli MIME, dimensione, ecc.).
 * Restituisce:
 * [
 *   'paths'  => array di percorsi salvati
 *   'errors' => array di messaggi di errore
 * ]
 * @param array $files       L'array $_FILES["qualcosa"]
 * @param int   $max_images  Numero massimo di file accettati
 * @param int   $max_width   Larghezza max (pixel) per il resize
 * @param int   $max_height  Altezza max (pixel) per il resize
 * @param int   $quality     Qualità finale (JPEG, 0-100)
 * @return array
 *         [
 *            'paths'  => array con i percorsi finali (solo se caricamento+resize ok),
 *            'errors' => array con i messaggi di errore
 *         ]
 */
function handle_multiple_uploads($files, $max_images = 15, $max_width = 1920, $max_height = 1080, $quality = 85) {
    $result = [
        'paths'  => [],
        'errors' => []
    ];

    // Se l’utente non ha selezionato alcun file
    if (!isset($files['name']) || empty($files['name'][0])) {
        // Non è un errore a meno che non vuoi forzare almeno 1 immagine
        return $result;
    }

    // Controlla quante immagini in upload
    $num_files = count($files['name']);
    if ($num_files > $max_images) {
        $result['errors'][] = "Puoi caricare al massimo $max_images immagini.";
        return $result;
    }

    // Tipi consentiti (poi usiamo resizeImage() per uniformare/comprimere)
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    // Se vuoi un limite "hard" su dimensione (in bytes)
    $max_size = 7 * 1024 * 1024; // 7 MB

    // Crea cartella di destinazione (se non esiste)
    $upload_dir = "../assets/uploads/";
    @mkdir($upload_dir, 0777, true);

    // Crea cartella temporanea (per caricare e poi ridimensionare)
    $tmp_dir = "../assets/tmp/";
    @mkdir($tmp_dir, 0777, true);

    // Cicla su ogni file
    for ($i = 0; $i < $num_files; $i++) {
        $file_name  = $files['name'][$i];
        $file_error = $files['error'][$i];
        $tmp_name   = $files['tmp_name'][$i];
        $file_size  = $files['size'][$i];
        // Determina MIME effettivo
        $file_type = @mime_content_type($tmp_name);

        // Verifica errori di upload
        if ($file_error !== UPLOAD_ERR_OK) {
            $result['errors'][] = "Errore nell'upload del file: $file_name (codice $file_error)";
            continue;
        }
        if (!in_array($file_type, $allowed_types)) {
            $result['errors'][] = "Tipo di file non supportato: $file_name";
            continue;
        }
        if ($file_size > $max_size) {
            $result['errors'][] = "File troppo grande (max 7MB): $file_name";
            continue;
        }

        // Salva file in una cartella temporanea (con nome univoco)
        $temp_unique = uniqid('upload_', true);
        $temp_path   = $tmp_dir . $temp_unique;
        if (!move_uploaded_file($tmp_name, $temp_path)) {
            $result['errors'][] = "Impossibile spostare il file temporaneo: $file_name";
            continue;
        }

        // Genera un nome univoco per il file finale
        $ext         = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = uniqid('img_', true) . '.' . $ext;
        $final_path  = $upload_dir . $unique_name;

        // Esegui il resize e salva nell'upload_dir
        $resized_ok = resizeImage($temp_path, $final_path, $max_width, $max_height, $quality);
        // Elimina il file temporaneo
        @unlink($temp_path);

        if ($resized_ok) {
            $result['paths'][] = $final_path;
        } else {
            $result['errors'][] = "Errore nel ridimensionamento del file: $file_name";
            // Se ha creato un file parziale, lo rimuoviamo
            @unlink($final_path);
        }
    }

    return $result;
}

/**
 * Inserisce in `car_images` i percorsi delle immagini (array di $paths).
 */
function insert_car_images($car_id, array $paths) {
    global $conn;
    $sql = "INSERT INTO car_images (car_id, image_path) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($paths as $path) {
        try {
            $stmt->execute([$car_id, $path]);
        } catch (PDOException $e) {
            error_log("Errore inserimento car_images: " . $e->getMessage());
        }
    }
}

function get_all_cars_complete() {
    global $conn;

    $sql = "
        SELECT 
            c.id,
            c.brand_id,
            b.name        AS brand_name,
            c.car_type_id,
            ct.name       AS car_type_name,
            c.fuel_type_id,
            f.name        AS fuel_type_name,
            c.transmission_id,
            t.name        AS transmission_name,
            c.year,
            c.price,
            c.km,
            c.seats,
            c.power_kw,
            c.car_condition
        FROM cars c
        JOIN brands        b  ON c.brand_id        = b.id
        JOIN car_types     ct ON c.car_type_id     = ct.id
        JOIN fuel_types    f  ON c.fuel_type_id    = f.id
        JOIN transmissions t  ON c.transmission_id = t.id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>