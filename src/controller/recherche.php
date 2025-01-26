<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

$restaurants = getRestaurants();

// Vérification des données récupérées (peut être retirée après débogage)
if (!$restaurants) {
    die('Aucun restaurant trouvé.');
}

$data = [
    'routes' => $routes,
    'restaurants' => $restaurants,
    'current_page' => 'recherche',
];

return $data;


?>