<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/routes.php';
require_once __DIR__ . '/../src/model/twig.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if (!isset($_SESSION['user']) && $page !== 'connexion-inscription') {
    header('Location: ?page=connexion-inscription');
    exit;
}

$data = [];


switch ($page) {
    case 'home':
        $data = require_once __DIR__ . '/../src/controller/accueil.php';
        $template = 'accueil.twig';
        break;
    case 'recherche':
        $data = require_once __DIR__ . '/../src/controller/recherche.php';
        $template = 'recherche.twig';
        break;
    case 'restaurant':
        $data = require_once __DIR__ . '/../src/controller/restaurant.php';
        $template = 'restaurant.twig';
        break;
    case 'contact':
        $data = require_once __DIR__ . '/../src/controller/contact.php';
        $template = 'contact.twig';
        break;
    case 'connexion-inscription':
        $data = require_once __DIR__ . '/../src/controller/connexion-inscription.php';
        $template = 'connexion-inscription.twig';
        break;
    case 'deconnexion':
        session_destroy();
        header('Location: ?page=connexion-inscription');
        exit;
    default:
        $data = require_once __DIR__ . '/../src/controller/accueil.php';
        $template = 'accueil.twig';
        break;
}

// Ensure $data is an array
if (!is_array($data)) {
    $data = [];
}

if (isset($_SESSION['error_message'])) {
    $data['error_message'] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

echo $twig->render($template, $data);
?>