<?php
require_once('connect.php');

// Fonction qui retourne les informations des restaurants
function getRestaurants($searchQuery = '') {
    global $pdo;
    try {
        if ($searchQuery) {
            $req = $pdo->prepare('SELECT name, latitude, longitude FROM restaurants WHERE name LIKE :searchQuery');
            $req->execute(['searchQuery' => '%' . $searchQuery . '%']);
        } else {
            $req = $pdo->query('SELECT name, latitude, longitude FROM restaurants');
        }
        $restaurants = $req->fetchAll(PDO::FETCH_ASSOC);

        return $restaurants;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui enregistre un utilisateur
function registerUser($nom, $prenom, $email, $password, $role) {
    global $pdo;
    try {
        $req = $pdo->prepare('INSERT INTO users (nom, prenom, email, password, role) VALUES (:nom, :prenom, :email, :password, :role)');
        $req->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        return [
            'id' => $pdo->lastInsertId(),
            'email' => $email,
        ];
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui authentifie un utilisateur
function authenticateUser($email, $password) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT id, email, password FROM users WHERE email = :email');
        $req->execute(['email' => $email]);
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'email' => $user['email'],
            ];
        }

        return false;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne les informations d'un restaurant
function getRestaurantById($restaurantId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT name, cuisine, latitude, longitude FROM restaurants WHERE id = :id');
        $req->execute(['id' => $restaurantId]);
        $restaurant = $req->fetch(PDO::FETCH_ASSOC);

        return $restaurant;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}
?>