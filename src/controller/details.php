<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

$restaurantId = $_GET['id'] ?? null;

if ($restaurantId) {
    $restaurant = getRestaurantById($restaurantId);
} else {
    $restaurant = null;
}

$data = [
    'restaurant' => $restaurant,
    'current_page' => 'details',
];

return $data;
?>