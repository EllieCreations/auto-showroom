<?php
// details.php

// 1. Include il file car_details.php
// Questo file si occupa di recuperare i dettagli dell'auto e le immagini correlate dal database.
// La variabile `$car` conterrà i dettagli dell'auto e `$images` conterrà tutte le immagini associate.
require_once '../functions/car_details.php';
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Dettagli Auto</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Collegamento al CSS di Lightbox2 -->
    <link rel="stylesheet" href="../lightbox2/dist/css/lightbox.css">

    <!-- Collegamento a loricon-->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <!-- Collegamento alla libreria Font Awesome per le icone -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Collegamento al file CSS principale -->
    <link rel="stylesheet" href="../css/styles.css">
    <!-- Collegamento al file CSS della galleria di immagini -->
    <link rel="stylesheet" href="../css/gallery.css">
</head>

<body>
    <!-- Include l'header comune a tutte le pagine -->
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <!-- Sidebar: Dettagli dell'auto -->
        <aside class="sidebar">
            <div class="dettagli">
                <?php if ($car): ?>
                    <!-- Mostra le informazioni dettagliate dell'auto -->
                    <h3>Informazioni</h3>
                    <ul class="car-info-list">
                        <li><strong>Marca:</strong> <?= htmlspecialchars($car['brand_name']) ?></li>
                        <li><strong>Carrozzeria:</strong> <?= htmlspecialchars($car['car_type_name']) ?></li>
                        <li><strong>Alimentazione:</strong> <?= htmlspecialchars($car['fuel_type_name']) ?></li>
                        <li><strong>Tipo di Cambio:</strong> <?= htmlspecialchars($car['transmission_name']) ?></li>
                        <li><strong>Anno:</strong> <?= htmlspecialchars($car['year']) ?></li>
                        <li><strong>Prezzo:</strong> €<?= number_format($car['price'], 2, ',', '.') ?></li>
                        <li><strong>Km:</strong> <?= number_format($car['km'], 0, ',', '.') ?> km</li>
                        <li><strong>Posti:</strong> <?= htmlspecialchars($car['seats']) ?></li>
                        <li><strong>Potenza:</strong> <?= htmlspecialchars($car['power_kw']) ?> kW</li>
                        <li><strong>Condizione:</strong> <?= htmlspecialchars($car['car_condition']) ?></li>
                    </ul>
                <?php else: ?>
                    <!-- Messaggio se l'auto non è trovata -->
                    <p>Auto non trovata.</p>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Contenuto Principale: Galleria Immagini -->
        <div class="main-content">
            <h2>Galleria Immagini</h2>

            <!-- Contenitore dell'anteprima principale -->
            <div class="gallery-preview">
                <!-- Immagine principale -->
                <a href="<?= htmlspecialchars($images[0]['image_path'] ?? '') ?>" data-lightbox="autoShowroom"
                    data-title="Immagine dell'auto <?= htmlspecialchars($car['brand_name'] ?? '') ?>">
                    <img id="main-image" src="<?= htmlspecialchars($images[0]['image_path'] ?? '') ?>"
                        alt="Immagine principale dell'auto <?= htmlspecialchars($car['brand_name'] ?? '') ?>">
                </a>
            </div>

            <!-- Contenitore delle miniature -->
            <div class="gallery-thumbnails">
                <?php foreach ($images as $index => $img): ?>
                    <a href="<?= htmlspecialchars($img['image_path']) ?>" data-lightbox="autoShowroom"
                        data-title="Immagine dell'auto <?= htmlspecialchars($car['brand_name'] ?? '') ?>">
                        <img class="thumbnail" src="<?= htmlspecialchars($img['image_path']) ?>"
                            alt="Miniatura <?= htmlspecialchars($car['brand_name'] ?? '') ?>"
                            data-fullsize="<?= htmlspecialchars($img['image_path']) ?>" onclick="changeMainImage(this)">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>



    </div>

    </div>

    <!-- Include il footer comune a tutte le pagine -->
    <?php include '../includes/footer.php'; ?>


    <!-- Collegamento al file JavaScript principale -->
    <script src="../js/script.js"></script>

    <!-- Collegamento alla libreria Lightbox2 per gestire la galleria immagini -->
    <script src="../lightbox2/dist/js/lightbox-plus-jquery.js"></script>
    <script>
        lightbox.option({
            'alwaysShowNavOnTouchDevices': true, // Mostra sempre i pulsanti di navigazione su dispositivi touch
            'fitImagesInViewport': true, // Adatta le immagini alla dimensione del viewport
        });
    </script>
</body>

</html>