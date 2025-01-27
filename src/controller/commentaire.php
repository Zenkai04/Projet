<?php
require_once __DIR__ . '/../model/connect.php';
require_once __DIR__ . '/../model/twig.php';

$restaurantId = $_GET['restaurant_id'] ?? null;

if ($restaurantId) {
    // Prépare la requête pour sélectionner les commentaires du restaurant
    $stmt = $pdo->prepare('SELECT * FROM comments WHERE restaurant_id = ?');
    $stmt->execute([$restaurantId]);
    $comments = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM restaurants WHERE id = ?');
    $stmt->execute([$restaurantId]);
    $restaurant = $stmt->fetch();

    echo $twig->render('commentaire.twig', [
        'comments' => $comments,
        'restaurant' => $restaurant,
    ]);
} else {
    echo "Restaurant ID is required.";
}
?>