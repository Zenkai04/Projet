<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false, // Vous pouvez spécifier un chemin de cache si nécessaire
]);
?>