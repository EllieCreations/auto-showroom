<?php
include '../functions/db.php';

// Recupera le opzioni dinamiche dalle tabelle di riferimento
$brands = $conn->query("SELECT id, name FROM brands ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$car_types = $conn->query("SELECT id, name FROM car_types ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$fuel_types = $conn->query("SELECT id, name FROM fuel_types ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$transmissions = $conn->query("SELECT id, name FROM transmissions ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<aside class="sidebar">
    <!-- Barra di Ricerca Sempre Visibile -->
    <?php include 'searchbar.php'; ?>

    <!-- Menu a Tendina per Ordinamento -->
    <div class="sort-menu">
        <label for="sort_order">Ordina per:</label>
        <select id="sort_order" name="sort_order">
            <option value="">Predefinito</option>
            <option value="price_asc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'price_asc') ? 'selected' : '' ?>>Prezzo Crescente</option>
            <option value="price_desc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'price_desc') ? 'selected' : '' ?>>Prezzo Decrescente</option>
            <option value="year_asc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'year_asc') ? 'selected' : '' ?>>Anno Crescente</option>
            <option value="year_desc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'year_desc') ? 'selected' : '' ?>>Anno Decrescente</option>
            <option value="km_asc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'km_asc') ? 'selected' : '' ?>>Km Crescente</option>
            <option value="km_desc" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] === 'km_desc') ? 'selected' : '' ?>>Km Decrescente</option>
        </select>
    </div>

    <!-- Filtro delle Auto -->
    <div class="filter-header">
        <h3>Filtra le Auto</h3>
        <button class="toggle-filters" aria-expanded="false" aria-controls="filter-form" aria-label="Toggle Filtro Auto">
            ☰
        </button>
    </div>
    <form id="filter-form" method="GET" action="">
        <!-- Marca -->
        <div class="filter-group">
            <label for="brand">Marca:</label>
            <select id="brand" name="brand_id">
                <option value="">Tutte</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand['id']) ?>" <?= (isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Carrozzeria -->
        <div class="filter-group">
            <label for="car_type">Carrozzeria:</label>
            <select id="car_type" name="car_type_id">
                <option value="">Tutte</option>
                <?php foreach ($car_types as $car_type): ?>
                    <option value="<?= htmlspecialchars($car_type['id']) ?>" <?= (isset($_GET['car_type_id']) && $_GET['car_type_id'] == $car_type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($car_type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Alimentazione -->
        <div class="filter-group">
            <label for="fuel_type">Alimentazione:</label>
            <select id="fuel_type" name="fuel_type_id">
                <option value="">Tutte</option>
                <?php foreach ($fuel_types as $fuel_type): ?>
                    <option value="<?= htmlspecialchars($fuel_type['id']) ?>" <?= (isset($_GET['fuel_type_id']) && $_GET['fuel_type_id'] == $fuel_type['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($fuel_type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo di Cambio -->
        <div class="filter-group">
            <label for="transmission">Tipo di Cambio:</label>
            <select id="transmission" name="transmission_id">
                <option value="">Tutti</option>
                <?php foreach ($transmissions as $transmission): ?>
                    <option value="<?= htmlspecialchars($transmission['id']) ?>" <?= (isset($_GET['transmission_id']) && $_GET['transmission_id'] == $transmission['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($transmission['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Anno -->
        <div class="filter-group">
            <label for="year">Anno:</label>
            <input type="number" id="year" name="year" min="1980" max="<?= date('Y') ?>" value="<?= htmlspecialchars($_GET['year'] ?? '') ?>" placeholder="Es. 2020">
        </div>

        <!-- Prezzo -->
        <div class="filter-group">
            <label for="price_min">Prezzo Minimo (€):</label>
            <input type="number" id="price_min" name="price_min" step="0.01" value="<?= htmlspecialchars($_GET['price_min'] ?? '') ?>" placeholder="Es. 5000">
        </div>
        <div class="filter-group">
            <label for="price_max">Prezzo Massimo (€):</label>
            <input type="number" id="price_max" name="price_max" step="0.01" value="<?= htmlspecialchars($_GET['price_max'] ?? '') ?>" placeholder="Es. 50000">
        </div>

        <!-- Km Percorsi -->
        <div class="filter-group">
            <label for="km_max">Km Percorsi (Max):</label>
            <input type="number" id="km_max" name="km_max" value="<?= htmlspecialchars($_GET['km_max'] ?? '') ?>" placeholder="Es. 100000">
        </div>

        <!-- Posti -->
        <div class="filter-group">
            <label for="seats">Posti:</label>
            <input type="number" id="seats" name="seats" min="2" max="9" value="<?= htmlspecialchars($_GET['seats'] ?? '') ?>" placeholder="Es. 5">
        </div>

        <!-- Potenza -->
        <div class="filter-group">
            <label for="power_min">Potenza Minima (kW):</label>
            <input type="number" id="power_min" name="power_min" min="20" value="<?= htmlspecialchars($_GET['power_min'] ?? '') ?>" placeholder="Es. 50">
        </div>
        <div class="filter-group">
            <label for="power_max">Potenza Massima (kW):</label>
            <input type="number" id="power_max" name="power_max" max="500" value="<?= htmlspecialchars($_GET['power_max'] ?? '') ?>" placeholder="Es. 200">
        </div>

        <!-- Condizione -->
        <div class="filter-group">
            <label for="car_condition">Condizione:</label>
            <select id="car_condition" name="car_condition">
                <option value="">Tutte</option>
                <option value="nuovo" <?= (isset($_GET['car_condition']) && $_GET['car_condition'] === 'nuovo') ? 'selected' : '' ?>>Nuovo</option>
                <option value="usato" <?= (isset($_GET['car_condition']) && $_GET['car_condition'] === 'usato') ? 'selected' : '' ?>>Usato</option>
            </select>
        </div>

        <!-- Pulsanti -->
        <button type="submit">Applica Filtri</button>
        <a href="?" class="reset-filters">Reset Filtri</a>
    </form>
</aside>
