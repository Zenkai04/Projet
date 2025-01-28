<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$restaurantId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;
$errorMessage = null;
$successMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['rating']) && $restaurantId && $userId) {
        $rating = (int)$_POST['rating'];

        if (hasUserRatedRestaurant($restaurantId, $userId)) {
            if (isset($_POST['update_rating'])) {
                updateRestaurantRating($restaurantId, $userId, $rating);
            } else {
                $errorMessage = "Vous avez déjà noté ce restaurant.";
            }
        } else {
            rateRestaurant($restaurantId, $userId, $rating);
        }
    }

    if (isset($_POST['content']) && $restaurantId && $userId) {
        $content = $_POST['content'];
        $parentId = $_POST['parent_id'] ?? null;
        addComment($restaurantId, $userId, $content, $parentId);
        $successMessage = "Merci pour votre commentaire.";
    }
}

if ($restaurantId) {
    $restaurant = getRestaurantById($restaurantId);
    $rate = getUserRating($restaurantId, $userId);
    $averageRating = getAverageRating($restaurantId);
    $comments = getCommentsByRestaurantId($restaurantId);
} else {
    $restaurant = null;
    $rate = null;
    $averageRating = null;
    $comments = [];
}

$data = [
    'restaurant' => $restaurant,
    'rate' => $rate,
    'averageRating' => $averageRating,
    'comments' => $comments,
    'current_page' => 'details',
    'errorMessage' => $errorMessage,
    'successMessage' => $successMessage,
];

return $data;
?>