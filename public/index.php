<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/model/twig.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

if (!isset($_SESSION['user']) && $page !== 'connexion-inscription') {
    header('Location: ?page=connexion-inscription');
    exit;
}

$data = [];

if (isset($_SESSION['user'])) {
    $data['user'] = $_SESSION['user'];
}

switch ($page) {
    case 'recherche':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/recherche.php');
        $template = 'recherche.twig';
        break;
    case 'restaurant':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/restaurant.php');
        $template = 'restaurant.twig';
        break;
    case 'contact':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/contact.php');
        $template = 'contact.twig';
        break;
    case 'connexion-inscription':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/connexion-inscription.php');
        $template = 'connexion-inscription.twig';
        break;
    case 'details':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/details.php');
        $template = 'details.twig';
        break;
    case 'commentaire':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/commentaire.php');
        $template = 'commentaire.twig';
        break;
    case 'reserver':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/reserver.php');
        $template = 'reserver.twig';
        break;
    case 'user':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/user.php');
        $template = 'user.twig';
        break;
    case 'restaurants':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/restaurants.php');
        $template = 'restaurants.twig';
        break;
    case 'userAdmin':
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/userAdmin.php');
        $template = 'userAdmin.twig';
        break;
    case 'deconnexion':
        session_destroy();
        header('Location: ?page=connexion-inscription');
        exit;
    default:
        $data = array_merge($data, require_once __DIR__ . '/../src/controller/accueil.php');
        $template = 'accueil.twig';
        break;
}

if (!is_array($data)) {
    $data = [];
}

if (isset($_SESSION['error_message'])) {
    $data['error_message'] = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

echo $twig->render($template, $data);
?>