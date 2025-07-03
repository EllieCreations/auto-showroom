<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I veicoli di Simone</title>
    <!-- Collegamenti ai file -->
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>

<body>
    <!-- header -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- Sezione principale del contenuto -->
    <main>
        <!-- HERO SECTION -->
        <section id="hero">
            <div class="hero-content">
                <p>Abbiamo quello che cerchi.</p>
                <h1>Trova la macchina perfetta per te!</h1>
                <!-- Barra di Ricerca Sempre Visibile -->
                <?php include 'includes/searchbar.php'; ?>
            </div>
        </section>

        <section id="main-content">
            <!-- CAROSELLO MARCHE 
                <div class="logo-grid">
                    <div class="logo-card">
                        <img src="assets/images/toyota.png" alt="Toyota">
                        <p>Toyota</p>
                    </div>
                    <div class="logo-card">
                        <img src="assets/images/Ford.svg" alt="Ford">
                        <p>Ford</p>
                    </div>
                    <div class="logo-card">
                        <img src="assets/images/honda.png" alt="Honda">
                        <p>Honda</p>
                    </div>
                    <div class="logo-card">
                        <img src="assets/images/merchedes.png" alt="Mercedes">
                        <p>Mercedes</p>
                    </div>
                    <div class="logo-card">
                        <img src="assets/images/bmw.png" alt="BMW">
                        <p>BMW</p>
                    </div>
                </div>-->
            <!-- CARD PRINCIPALI -->
            <div class="main-cards">
                <div class="main-card">
                    <h2>Vuoi comprare un'auto?</h2>
                    <div class="card-content">
                        <p>Scopri il nostro inventario di auto nuove e usate.</p>
                        <img src="assets/images/buyIcon.svg" alt="buyIcon">
                    </div>
                    <a href="./inventario.php" class="btn">Vai all'inventario</a>

                </div>
                <div class="main-card">
                    <h2>Vuoi vendere un'auto?</h2>
                    <div class="card-content">
                        <p>Contattaci per mettere in vendita la tua auto facilmente.</p>
                        <img src="assets/images/sellicon.svg" alt="sellIcon">

                    </div>
                    <a href="./contatti.php" class="btn">Vai ai contatti</a>
                </div>
            </div>
            <!-- PERCHè SCEGLIERCI -->
            <div>
                <h2>Perchè sceglierci?</h2>
                <div class="reasons-grid">
                    <div class="reason-card">
                        <lord-icon src="https://cdn.lordicon.com/vttzorhw.json" trigger="hover"
                            colors="primary:#121331,secondary:#70b8ff" style="width:150px;height:150px">
                        </lord-icon>
                        <h3>Qualità</h3>
                        <p>Auto controllate e garantite.</p>
                    </div>
                    <div class="reason-card">
                        <lord-icon src="https://cdn.lordicon.com/dkobpcrm.json" trigger="hover" state="hover-rotate"
                            colors="primary:#121331,secondary:#70b8ff" style="width:150px;height:150px">
                        </lord-icon>
                        <h3>Prezzi convenienti</h3>
                        <p>Auto nuove e usate a prezzi competitivi.</p>
                    </div>
                    <div class="reason-card">
                        <lord-icon src="https://cdn.lordicon.com/sjoccsdj.json" trigger="hover"
                            colors="primary:#121331,secondary:#70b8ff" style="width:150px;height:150px">
                        </lord-icon>
                        <h3>Assistenza</h3>
                        <p>Assistenza post-vendita e garanzia.</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer del sito -->

    <?php include 'includes/footer.php'; ?>

    <!-- Collegamenti ai file JavaScript -->
    <script src="js/home.js"></script>
</body>

</html>