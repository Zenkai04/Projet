<?php
require_once __DIR__ . '/../model/twig.php';

$data = [
    'current_page' => 'accueil',
];

// Add user information to the data array
if (isset($_SESSION['user'])) {
    $data['user'] = $_SESSION['user'];
}

return $data;
?>