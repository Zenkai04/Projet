<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../src/model/twig.php';

$page = $_GET['page'] ?? 'accueil';

if (array_key_exists($page, $routes)) {
    echo $twig->render($routes[$page]);
} else {
    echo $twig->render('404.twig'); // Vous pouvez créer un template 404.twig pour les pages non trouvées
}
?>