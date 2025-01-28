<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'connexion') {
        // Connexion
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = authenticateUser($login, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id']; // Assurez-vous que l'identifiant est stocké dans la session
            header('Location: ?page=accueil');
            exit;
        } else {
            $_SESSION['error_message'] = 'Email/Pseudo ou mot de passe incorrect.';
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'inscription') {
        // Inscription
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $pseudo = $_POST['pseudo'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = 'user';

        if ($password === $confirmPassword) {
            if (isEmailOrPseudoUsed($email, $pseudo)) {
                if (isEmailOrPseudoUsed($email, null)) {
                    $_SESSION['error_message'] = 'Cet email est déjà utilisé.';
                    $_SESSION['error_field'] = 'email';
                } elseif (isEmailOrPseudoUsed(null, $pseudo)) {
                    $_SESSION['error_message'] = 'Ce pseudo est déjà utilisé.';
                    $_SESSION['error_field'] = 'pseudo';
                }
            } else {
                $result = registerUser($nom, $prenom, $email, $pseudo, $password, $role);

                if ($result) {
                    $_SESSION['user'] = $result;
                    $_SESSION['user_id'] = $result['id'];
                    header('Location: ?page=accueil');
                    exit;
                } else {
                    $_SESSION['error_message'] = 'Erreur lors de l\'inscription.';
                }
            }
        } else {
            $_SESSION['error_message'] = 'Les mots de passe ne correspondent pas.';
            $_SESSION['error_field'] = 'password';
        }
    }
}

$data['error_message'] = $_SESSION['error_message'] ?? null;
$data['error_field'] = $_SESSION['error_field'] ?? null;
unset($_SESSION['error_message'], $_SESSION['error_field']);

return $data;
?>