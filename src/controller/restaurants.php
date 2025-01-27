<?php
// filepath: /u:/Mes documents/Projet/src/controller/restaurants.php
<?php
require_once __DIR__ . '/../model/connect.php';
require_once __DIR__ . '/../model/twig.php';

// Prépare la requête pour sélectionner tous les restaurants
$stmt = $pdo->query('SELECT * FROM restaurants');
$restaurants = $stmt->fetchAll();

echo $twig->render('restaurants.twig', [
    'restaurants' => $restaurants,
]);
?>