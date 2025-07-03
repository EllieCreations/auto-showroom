<?php
function getCarImages($conn, $car_id, $limit = null) {
    // Query per recuperare le immagini associate all'auto
    $query = "SELECT image_path FROM car_images WHERE car_id = :car_id";
    if ($limit) {
        $query .= " LIMIT :limit";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':car_id', $car_id, PDO::PARAM_INT);
    if ($limit) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
