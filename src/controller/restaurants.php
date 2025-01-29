<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrator') {
    header('Location: ?page=accueil');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'edit_restaurant') {
            $restaurantId = $_POST['restaurant_id'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $website = $_POST['website'];

            if (updateRestaurant($restaurantId, $name, $address, $phone, $website)) {
                $_SESSION['success_message'] = 'Restaurant mis à jour avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la mise à jour du restaurant.';
            }
        } elseif ($_POST['action'] == 'delete_restaurant') {
            $restaurantId = $_POST['restaurant_id'];

            if (deleteRestaurant($restaurantId)) {
                $_SESSION['success_message'] = 'Restaurant supprimé avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la suppression du restaurant.';
            }
        }

        header('Location: ?page=restaurants');
        exit;
    }
}

$restaurants = getAllRestaurants();

$data = [
    'restaurants' => $restaurants,
    'success_message' => $_SESSION['success_message'] ?? null,
    'error_message' => $_SESSION['error_message'] ?? null,
];

unset($_SESSION['success_message'], $_SESSION['error_message']);

return $data;
?>