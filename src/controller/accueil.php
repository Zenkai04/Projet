<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$topRatedRestaurants = getTopRatedRestaurants();

$data = [
    'topRatedRestaurants' => $topRatedRestaurants,
    'user' => $_SESSION['user'] ?? null,
    'success_message' => $_SESSION['success_message'] ?? null,
    'error_message' => $_SESSION['error_message'] ?? null,
];

unset($_SESSION['success_message'], $_SESSION['error_message']);

return $data;
?>