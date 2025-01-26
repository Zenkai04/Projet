<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

$restaurants = getRestaurants();

$data = [
    'routes' => $routes ?? [],
    'restaurants' => $restaurants,
    'current_page' => 'recherche',
];

return $data;
?>