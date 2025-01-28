<?php
require_once __DIR__ . '/../model/connect.php';
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$restaurantId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'] ?? null;

$data = [
    'current_page' => 'commentaire',
    'user' => $_SESSION['user'] ?? null,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && $restaurantId && $userId) {
    $content = $_POST['content'];
    $parentId = $_POST['parent_id'] ?? null;
    addComment($restaurantId, $userId, $content, $parentId);
}

if ($restaurantId) {
    $comments = getCommentsByRestaurantId($restaurantId);
    $restaurant = getRestaurantById($restaurantId);

    $data['comments'] = $comments;
    $data['restaurant'] = $restaurant;
} else {
    $data['error_message'] = "Restaurant ID is required.";
}

return $data;
?>