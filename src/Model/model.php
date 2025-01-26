<?php
require_once('connect.php');

// Fonction qui récupère les restaurants
function getRestaurants() {
    global $pdo;
    $req = $pdo->query('SELECT name, latitude, longitude FROM restaurants');
    return $req->fetchAll(PDO::FETCH_ASSOC);
}
?>