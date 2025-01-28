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

// Fonction qui retourne les informations d'un restaurant par son ID, y compris le prix
function getRestaurantById($restaurantId) {
    global $pdo;
    try {
        $req = $pdo->prepare('
            SELECT r.*, p.prix 
            FROM restaurants r
            LEFT JOIN prix p ON r.id = p.idRestaurant
            WHERE r.id = :id
        ');
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

// Fonction qui vérifie si un utilisateur a déjà noté un restaurant
function hasUserRatedRestaurant($restaurantId, $userId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT COUNT(*) FROM rate WHERE idRestaurant = :restaurantId AND idUser = :userId');
        $req->execute([
            'restaurantId' => $restaurantId,
            'userId' => $userId,
        ]);
        $count = $req->fetchColumn();

        return $count > 0;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui met à jour la note d'un restaurant
function updateRestaurantRating($restaurantId, $userId, $rating) {
    global $pdo;
    try {
        $req = $pdo->prepare('UPDATE rate SET rate = :rating WHERE idRestaurant = :restaurantId AND idUser = :userId');
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

// Fonction qui retourne la note s'un utilisateur pour un restaurant
function getUserRating($restaurantId, $userId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT rate FROM rate WHERE idRestaurant = :restaurantId AND idUser = :userId');
        $req->execute([
            'restaurantId' => $restaurantId,
            'userId' => $userId,
        ]);
        $rating = $req->fetchColumn();

        return $rating;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui ajoute un commentaire ou une réponse à un commentaire
function addComment($restaurantId, $userId, $content, $parentId = null) {
    global $pdo;
    try {
        $reponse = $parentId ? 1 : 0;
        $req = $pdo->prepare('INSERT INTO comments (idRestaurant, idUser, content, idComment, reponse) VALUES (:restaurantId, :userId, :content, :parentId, :reponse)');
        $req->execute([
            'restaurantId' => $restaurantId,
            'userId' => $userId,
            'content' => $content,
            'parentId' => $parentId,
            'reponse' => $reponse,
        ]);

        return true;
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui récupère les commentaires d'un restaurant avec les réponses imbriquées
function getCommentsByRestaurantId($restaurantId) {
    global $pdo;
    try {
        $req = $pdo->prepare('SELECT c.*, u.pseudo AS author FROM comments c JOIN users u ON c.idUser = u.id WHERE c.idRestaurant = :restaurantId ORDER BY c.date DESC');
        $req->execute(['restaurantId' => $restaurantId]);
        $comments = $req->fetchAll(PDO::FETCH_ASSOC);

        // Organiser les commentaires et leurs réponses
        $commentsById = [];
        foreach ($comments as $comment) {
            $commentsById[$comment['id']] = $comment;
            $commentsById[$comment['id']]['responses'] = [];
        }

        foreach ($comments as $comment) {
            if ($comment['idComment']) {
                $commentsById[$comment['idComment']]['responses'][] = $comment;
            }
        }

        // Fonction récursive pour organiser les réponses imbriquées
        function nestComments($comments, $parentId = null) {
            $nested = [];
            foreach ($comments as $comment) {
                if ($comment['idComment'] == $parentId) {
                    $comment['responses'] = nestComments($comments, $comment['id']);
                    $nested[] = $comment;
                }
            }
            return $nested;
        }

        return nestComments($comments);
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne les restaurants filtrés par cuisine, prix, notation et recherche par nom
function getFilteredRestaurants($cuisine = null, $prix = null, $rating = null, $search = null) {
    global $pdo;
    try {
        $query = '
            SELECT r.*, p.prix, AVG(rt.rate) as average_rating
            FROM restaurants r
            LEFT JOIN prix p ON r.id = p.idRestaurant
            LEFT JOIN rate rt ON r.id = rt.idRestaurant
            WHERE 1=1
        ';

        $params = [];

        if ($cuisine) {
            $query .= ' AND r.cuisine = :cuisine';
            $params['cuisine'] = $cuisine;
        }

        if ($prix) {
            $query .= ' AND p.prix = :prix';
            $params['prix'] = $prix;
        }

        if ($search) {
            $query .= ' AND r.name LIKE :search';
            $params['search'] = '%' . $search . '%';
        }

        $query .= ' GROUP BY r.id';

        if ($rating) {
            $query .= ' HAVING average_rating >= :rating';
            $params['rating'] = $rating;
        }

        $req = $pdo->prepare($query);
        $req->execute($params);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne toutes les cuisines disponibles
function getAllCuisines() {
    global $pdo;
    try {
        $req = $pdo->query('SELECT DISTINCT cuisine FROM restaurants WHERE cuisine IS NOT NULL');
        return $req->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}

// Fonction qui retourne toutes les options de prix disponibles
function getAllPrixOptions() {
    global $pdo;
    try {
        $req = $pdo->query('SELECT DISTINCT prix FROM prix WHERE prix IS NOT NULL');
        return $req->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        die('Erreur SQL : ' . $e->getMessage());
    }
}
?>