<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUser = $_SESSION['user_id'];
    $idRestaurant = $_POST['idRestaurant'];
    $nbPersonnes = $_POST['nbPersonnes'];
    $dateHeure = $_POST['dateHeure'];

    if (isReservationExists($idUser, $idRestaurant, $dateHeure)) {
        $_SESSION['error_message'] = 'Une réservation à cet endroit à cette date et à cet horaire a déjà été effectuée.';
        header('Location: ?page=accueil&error_message=' . urlencode($_SESSION['error_message']));
        exit;
    }

    if (reserveTable($idUser, $idRestaurant, $nbPersonnes, $dateHeure)) {
        $_SESSION['success_message'] = 'Réservation effectuée avec succès.';
        header('Location: ?page=accueil&success_message=' . urlencode($_SESSION['success_message']));
        exit;
    } else {
        $_SESSION['error_message'] = 'Erreur lors de la réservation.';
        header('Location: ?page=accueil&error_message=' . urlencode($_SESSION['error_message']));
        exit;
    }
}

$restaurantId = $_GET['id'] ?? null;
$restaurant = getRestaurantById($restaurantId);

$data = [
    'restaurant' => $restaurant,
    'success_message' => $_SESSION['success_message'] ?? null,
    'error_message' => $_SESSION['error_message'] ?? null,
];

unset($_SESSION['success_message'], $_SESSION['error_message']);

return $data;
?>