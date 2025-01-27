<?php
require_once('connect.php');

// Fonction qui retourne les informations des restaurants
function getRestaurants($searchQuery = '') {
    global $pdo;
    try {
        if ($searchQuery) {
            $req = $pdo->prepare('SELECT id, name, latitude, longitude FROM restaurants WHERE name LIKE :searchQuery');
            $req->execute(['searchQuery' => '%' . $searchQuery . '%']);
        } else {
            $req = $pdo->query('SELECT id, name, latitude, longitude FROM restaurants');
        }
        $restaurants = $req->fetchAll(PDO::FETCH_ASSOC);

        return $restaurants;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne les informations d'un restaurant par son ID
function getRestaurantById($restaurantId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT * FROM restaurants WHERE id = :id');
        $req->execute(['id' => $restaurantId]);
        $restaurant = $req->fetch(PDO::FETCH_ASSOC);

        return $restaurant;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne la note moyenne d'un restaurant
function getAverageRating($restaurantId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT AVG(rate) AS average FROM rate WHERE idRestaurant = :restaurantId');
        $req->execute(['restaurantId' => $restaurantId]);
        $average = $req->fetch(PDO::FETCH_ASSOC);

        return $average['average'];
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui enregistre un utilisateur
function registerUser($nom, $prenom, $email, $pseudo, $password, $role) {
    global $pdo;
    try {
        $req = $pdo->prepare('INSERT INTO users (nom, prenom, email, pseudo, password, role) VALUES (:nom, :prenom, :email, :pseudo, :password, :role)');
        $req->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'pseudo' => $pseudo,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        // Retourner toutes les informations utiles de l'utilisateur
        return [
            'id' => $pdo->lastInsertId(),
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'pseudo' => $pseudo,
            'role' => $role
        ];
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui authentifie un utilisateur
function authenticateUser($email, $password) {
    global $pdo;
    $req = $pdo->prepare('SELECT id, nom, prenom, email, password, role FROM users WHERE email = :email');
    $req->execute(['email' => $email]);
    $user = $req->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']); // Supprimer le mot de passe pour la sécurité
        return $user;
    }

    return false;
}



// Fonction qui donne une note à un restaurant
function rateRestaurant($restaurantId, $userId, $rating) {
    global $pdo;
    try {
        $req = $pdo->prepare('INSERT INTO rate (idRestaurant, idUser, rate) VALUES (:restaurantId, :userId, :rating)');
        $req->execute([
            'restaurantId' => $restaurantId,
            'userId' => $userId,
            'rating' => $rating,
        ]);

        return true;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}
?>