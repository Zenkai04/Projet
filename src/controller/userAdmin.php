<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'administrator') {
    header('Location: ?page=accueil');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'edit_user') {
            $userId = $_POST['user_id'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pseudo = $_POST['pseudo'];
            $role = $_POST['role'];

            if (updateUser($userId, $nom, $prenom, $email, $pseudo, $role)) {
                $_SESSION['success_message'] = 'Utilisateur mis à jour avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la mise à jour de l\'utilisateur.';
            }
        } elseif ($_POST['action'] == 'delete_user') {
            $userId = $_POST['user_id'];

            if (deleteUser($userId)) {
                $_SESSION['success_message'] = 'Utilisateur supprimé avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la suppression de l\'utilisateur.';
            }
        }

        header('Location: ?page=userAdmin');
        exit;
    }
}

$users = getAllUsers();

$data = [
    'users' => $users,
    'success_message' => $_SESSION['success_message'] ?? null,
    'error_message' => $_SESSION['error_message'] ?? null,
];

unset($_SESSION['success_message'], $_SESSION['error_message']);

return $data;
?>