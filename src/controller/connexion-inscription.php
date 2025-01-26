<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'connexion') {
        // Connexion
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = authenticateUser($email, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: ?page=home');
            exit;
        } else {
            $_SESSION['error_message'] = 'Email ou mot de passe incorrect.';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'inscription') {
        // Inscription
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = 'user'; // Vous pouvez ajuster le rôle par défaut si nécessaire

        if ($password === $confirmPassword) {
            $result = registerUser($nom, $prenom, $email, $password, $role);

            if ($result) {
                $_SESSION['user'] = $result;
                header('Location: ?page=home');
                exit;
            } else {
                $_SESSION['error_message'] = 'Erreur lors de l\'inscription.';
            }
        } else {
            $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
        }
    }
}

$data = [
    'routes' => $routes ?? [],
    'current_page' => 'connexion-inscription',
];

return $data;
?>