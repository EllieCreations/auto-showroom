<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoShowRoom di Simone</title>
    <!-- Collegamento al file CSS principale -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link rel="stylesheet" href="../css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Include l'header comune a tutte le pagine -->
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <!-- Include la sidebar contenente i filtri e la barra di ricerca -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- CONTENUTO PRINCIPALE -->
        <div class="main-content">
            <h2>Auto Disponibili</h2>
            <div class="cars-grid">
                <?php
                // Includi i file PHP per la gestione delle immagini e dei filtri
                include_once '../functions/get_car_images.php'; // Funzioni per recuperare le immagini delle auto
                include_once '../functions/filter_car.php';    // Filtra le auto in base ai parametri GET
                
                // Controlla se ci sono auto disponibili dopo i filtri
                if ($cars && count($cars) > 0) {
                    // Cicla attraverso l'elenco delle auto filtrate
                    foreach ($cars as $car) {
                        echo "<div class='car-card'>";

                        // Recupera l'immagine principale dell'auto
                        $images = getCarImages($conn, $car['id'], 1); // Limita a una sola immagine
                        $image_path = $images[0]['image_path'] ?? '../assets/no-image.png'; // Immagine di default
                
                        // Stampa il contenuto della card per ogni auto
                        echo "<img src='" . htmlspecialchars($image_path) . "' alt='Immagine Auto' class='car-image'>";
                        echo "<div class='car-info'>";
                        echo "<h3>" . htmlspecialchars($car['brand_name']) . " " . htmlspecialchars($car['car_type_name']) . "</h3>";
                        echo "<p>Prezzo: €" . number_format($car['price'], 2, ',', '.') . "</p>";
                        echo "<p>Anno: " . htmlspecialchars($car['year']) . "</p>";
                        echo "<p>Km: " . number_format($car['km'], 0, ',', '.') . " km</p>";
                        echo "</div>";
                        // Link ai dettagli dell'auto
                        echo "<a href=\"details.php?id=" . htmlspecialchars($car['id']) . "\" class=\"btn\">Dettagli</a>";
                        echo "</div>";
                    }
                } else {
                    // Messaggio se nessuna auto è stata trovata
                    echo "<p>Nessuna auto trovata.</p>";
                }
                ?>
            </div>

            <!-- Paginazione -->

            <?php
            // Gestione dei parametri GET
            $query_params = $_GET;
            unset($query_params['page']); // Rimuove il parametro 'page' per evitare conflitti
            
            // Costruisce l'URL base senza il parametro 'page'
            $base_url = strtok($_SERVER["REQUEST_URI"], '?'); // Rimuove i parametri GET dall'URL
            $base_query = http_build_query($query_params);
            $base_query = $base_query ? '&' . $base_query : ''; // Aggiungi il separatore se ci sono altri parametri
            
            // Controlla se ci sono più di 1 pagine
            if ($total_pages >= 1): ?>
                <!-- Mostra il numero di pagine -->
                <p>Pagina <?php echo $page; ?> di <?php echo $total_pages; ?></p>
                <div class="pagination">
                    <?php if ($total_cars > 0): ?>
                        <!-- Pulsante Precedente -->
                        <?php if ($page > 1): ?>
                            <a href="<?php echo htmlspecialchars($base_url . '?page=' . ($page - 1) . $base_query); ?>"
                                class="pagination-prev">Precedente</a>
                        <?php endif; ?>

                        <!-- Genera i link di navigazione per ogni pagina -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?php echo htmlspecialchars($base_url . '?page=' . $i . $base_query); ?>"
                                class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Pulsante Successivo -->
                        <?php if ($page < $total_pages): ?>
                            <a href="<?php echo htmlspecialchars($base_url . '?page=' . ($page + 1) . $base_query); ?>"
                                class="pagination-next">Successivo</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Caso in cui ci sia una sola pagina -->
                        <p>Pagina 1 di 1</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Include il footer comune a tutte le pagine -->
    <?php require '../includes/footer.php'; ?>
    <!-- Collegamento al file JS principale -->
    <script src="../js/script.js"></script>

</body>

</html>