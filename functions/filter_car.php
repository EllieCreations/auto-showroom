<?php
// filter_car.php

include_once 'db.php';
include_once 'get_car_images.php'; // Includi solo una volta per evitare errori

// Ottieni i parametri di filtro dalla query string
$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$sort_order = isset($_GET['sort_order']) ? trim($_GET['sort_order']) : '';

$brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';
$car_type_id = isset($_GET['car_type_id']) ? trim($_GET['car_type_id']) : '';
$fuel_type_id = isset($_GET['fuel_type_id']) ? trim($_GET['fuel_type_id']) : '';
$transmission_id = isset($_GET['transmission_id']) ? trim($_GET['transmission_id']) : '';
$year = isset($_GET['year']) ? trim($_GET['year']) : '';
$price_min = (isset($_GET['price_min']) && is_numeric($_GET['price_min']) && $_GET['price_min'] !== '') ? (float)$_GET['price_min'] : 0;
$price_max = (isset($_GET['price_max']) && is_numeric($_GET['price_max']) && $_GET['price_max'] !== '') ? (float)$_GET['price_max'] : PHP_INT_MAX;
$km_max = (isset($_GET['km_max']) && is_numeric($_GET['km_max']) && $_GET['km_max'] !== '') ? (int)$_GET['km_max'] : PHP_INT_MAX;
$seats = isset($_GET['seats']) ? trim($_GET['seats']) : '';
$power_min = (isset($_GET['power_min']) && is_numeric($_GET['power_min']) && $_GET['power_min'] !== '') ? (int)$_GET['power_min'] : 0;
$power_max = (isset($_GET['power_max']) && is_numeric($_GET['power_max']) && $_GET['power_max'] !== '') ? (int)$_GET['power_max'] : PHP_INT_MAX;
$car_condition = isset($_GET['car_condition']) ? trim($_GET['car_condition']) : '';

// Paginazione
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;

// Costruzione della query principale con i filtri applicati
$base_query = "
    FROM cars
    JOIN brands ON cars.brand_id = brands.id
    JOIN car_types ON cars.car_type_id = car_types.id
    JOIN fuel_types ON cars.fuel_type_id = fuel_types.id
    JOIN transmissions ON cars.transmission_id = transmissions.id
    WHERE 1=1
";

$params = [];

// Aggiungi condizioni dinamiche basate sui filtri
if ($brand_id !== '') {
    $base_query .= " AND cars.brand_id = :brand_id";
    $params[':brand_id'] = $brand_id;
}

if ($car_type_id !== '') {
    $base_query .= " AND cars.car_type_id = :car_type_id";
    $params[':car_type_id'] = $car_type_id;
}

if ($fuel_type_id !== '') {
    $base_query .= " AND cars.fuel_type_id = :fuel_type_id";
    $params[':fuel_type_id'] = $fuel_type_id;
}

if ($transmission_id !== '') {
    $base_query .= " AND cars.transmission_id = :transmission_id";
    $params[':transmission_id'] = $transmission_id;
}

if ($year !== '') {
    $base_query .= " AND cars.year = :year";
    $params[':year'] = $year;
}

if ($price_min > 0 || $price_max < PHP_INT_MAX) {
    $base_query .= " AND cars.price BETWEEN :price_min AND :price_max";
    $params[':price_min'] = $price_min;
    $params[':price_max'] = $price_max;
}

if ($km_max < PHP_INT_MAX) {
    $base_query .= " AND cars.km <= :km_max";
    $params[':km_max'] = $km_max;
}

if ($seats !== '') {
    $base_query .= " AND cars.seats = :seats";
    $params[':seats'] = $seats;
}

if ($power_min > 0 || $power_max < PHP_INT_MAX) {
    $base_query .= " AND cars.power_kw BETWEEN :power_min AND :power_max";
    $params[':power_min'] = $power_min;
    $params[':power_max'] = $power_max;
}

if ($car_condition !== '') {
    $base_query .= " AND cars.car_condition = :car_condition";
    $params[':car_condition'] = $car_condition;
}

// Gestione della ricerca testuale
if ($search_query !== '') {
    $base_query .= " AND (brands.name LIKE :search_query OR car_types.name LIKE :search_query OR fuel_types.name LIKE :search_query OR transmissions.name LIKE :search_query)";
    $params[':search_query'] = '%' . $search_query . '%';
}

// Gestione dell'ordinamento
$order_by = "cars.id DESC"; // Default

switch ($sort_order) {
    case 'price_asc':
        $order_by = "cars.price ASC";
        break;
    case 'price_desc':
        $order_by = "cars.price DESC";
        break;
    case 'year_asc':
        $order_by = "cars.year ASC";
        break;
    case 'year_desc':
        $order_by = "cars.year DESC";
        break;
    case 'km_asc':
        $order_by = "cars.km ASC";
        break;
    case 'km_desc':
        $order_by = "cars.km DESC";
        break;
    default:
        $order_by = "cars.id DESC";
        break;
}

// Query per contare il numero totale di auto filtrate
$count_query = "SELECT COUNT(*) as total " . $base_query;
$count_stmt = $conn->prepare($count_query);

// Bind dei parametri per la query di conteggio
foreach ($params as $key => $value) {
    if (is_int($value)) {
        $count_stmt->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $count_stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
}

$count_stmt->execute();
$count_result = $count_stmt->fetch(PDO::FETCH_ASSOC);
$total_cars = $count_result['total'] ?? 0;

// Calcola il numero totale di pagine
$total_pages = ($total_cars > 0) ? ceil($total_cars / $limit) : 1;

// Assicurati che la pagina corrente sia valida
if ($page > $total_pages) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
} elseif ($page < 1) {
    $page = 1;
    $offset = 0;
}

// Query principale per recuperare i dettagli delle auto con la paginazione
$main_query = "
    SELECT 
        cars.id, cars.year, cars.price, cars.km, cars.seats, cars.power_kw, cars.car_condition, 
        brands.name AS brand_name, car_types.name AS car_type_name, fuel_types.name AS fuel_type_name, 
        transmissions.name AS transmission_name
    " . $base_query . "
    ORDER BY " . $order_by . "
    LIMIT :limit OFFSET :offset
";

$main_stmt = $conn->prepare($main_query);

// Bind dei parametri per la query principale
foreach ($params as $key => $value) {
    if (is_int($value)) {
        $main_stmt->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $main_stmt->bindValue($key, $value, PDO::PARAM_STR);
    }
}

// Bind dei parametri di limit e offset
$main_stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$main_stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

// Esegui la query principale
$main_stmt->execute();
$cars = $main_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
