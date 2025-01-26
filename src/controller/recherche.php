<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

$searchQuery = $_GET['search'] ?? '';
$restaurants = getRestaurants($searchQuery);

$data = [
    'routes' => $routes ?? [],
    'restaurants' => $restaurants,
    'current_page' => 'recherche',
    'search_query' => $searchQuery,
    'no_results' => empty($restaurants),
];

return $data;
?>