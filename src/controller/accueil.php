<?php
require_once __DIR__ . '/../model/twig.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$data = [
    'current_page' => 'accueil',
    'success_message' => $_GET['success_message'] ?? null,
    'error_message' => $_GET['error_message'] ?? null,
];

// Add user information to the data array
if (isset($_SESSION['user'])) {
    $data['user'] = $_SESSION['user'];
}

return $data;
?>