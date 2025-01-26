<?php
require_once('connect.php');

function getRestaurants() {
    global $pdo;
    try {
        $req = $pdo->query('SELECT name, latitude, longitude FROM restaurants');
        $restaurants = $req->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($restaurants)) {
            die('Aucun restaurant trouvé dans la base de données.');
        }

        return $restaurants;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

?>