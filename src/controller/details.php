<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$restaurantId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating']) && $restaurantId && $userId) {
    $rating = (int)$_POST['rating'];    
    rateRestaurant($restaurantId, $userId, $rating);
}

if ($restaurantId) {
    $restaurant = getRestaurantById($restaurantId);
    $averageRating = getAverageRating($restaurantId);
} else {
    $restaurant = null;
    $averageRating = null;
}

$data = [
    'restaurant' => $restaurant,
    'averageRating' => $averageRating,
    'current_page' => 'details',
];

return $data;
?>