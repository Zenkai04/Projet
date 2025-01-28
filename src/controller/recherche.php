<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cuisine = $_GET['cuisine'] ?? null;
$prix = $_GET['prix'] ?? null;
$rating = $_GET['rating'] ?? null;
$search = $_GET['search'] ?? null;

$restaurants = getFilteredRestaurants($cuisine, $prix, $rating, $search);
$cuisines = getAllCuisines();
$prixOptions = getAllPrixOptions();
$ratings = [1, 2, 3, 4, 5];

$data = [
    'restaurants' => $restaurants,
    'cuisines' => $cuisines,
    'prixOptions' => $prixOptions,
    'ratings' => $ratings,
    'current_page' => 'recherche',
];

return $data;
?>