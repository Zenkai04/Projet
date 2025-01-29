<?php
require_once __DIR__ . '/../model/twig.php';
require_once __DIR__ . '/../model/model.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header('Location: ?page=accueil');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'edit_reservation') {
            $reservationId = $_POST['reservation_id'];
            $nbPersonnes = $_POST['nbPersonnes'];
            $dateHeure = $_POST['dateHeure'];

            if (updateReservation($reservationId, $nbPersonnes, $dateHeure)) {
                $_SESSION['success_message'] = 'Réservation mise à jour avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de la mise à jour de la réservation.';
            }
        } elseif ($_POST['action'] == 'delete_reservation') {
            $reservationId = $_POST['reservation_id'];

            if (deleteReservation($reservationId)) {
                $_SESSION['success_message'] = 'Réservation annulée avec succès.';
            } else {
                $_SESSION['error_message'] = 'Erreur lors de l\'annulation de la réservation.';
            }
        } else {
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $pseudo = $_POST['pseudo'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            $result = updateUserDetails($userId, $nom, $prenom, $email, $pseudo, $currentPassword, $newPassword);

            if (isset($result['error'])) {
                $_SESSION['error_message'] = $result['error'];
            } else {
                $_SESSION['success_message'] = $result['success'];
            }
        }

        header('Location: ?page=user');
        exit;
    }
}

$userDetails = getUserDetailsById($userId);

$data = [
    'user' => $userDetails,
    'success_message' => $_SESSION['success_message'] ?? null,
    'error_message' => $_SESSION['error_message'] ?? null,
];

unset($_SESSION['success_message'], $_SESSION['error_message']);

return $data;
?>