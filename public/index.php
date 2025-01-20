<?php
require_once '../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('../templates');
$twig = new \Twig\Environment($loader);

$page = $_GET['page'] ?? 'home';
$id = $_GET['id'] ?? null;

switch ($page) {
    case 'restaurants':
        echo $twig->render('restaurants.twig');
        break;
    case 'restaurant_details':
        // Vous devriez obtenir les détails du restaurant de votre base de données ou d'une source de données ici
        $restaurant = [
            'id' => $id,
            'address' => '123 Rue Example, Lyon',
            'description' => 'Un excellent restaurant.'
        ];
        echo $twig->render('restaurant_details.twig', ['restaurant' => $restaurant]);
        break;
    case 'menu':
        // Vous devriez obtenir le menu du restaurant de votre base de données ou d'une source de données ici
        $menu = [
            'dish1' => 'Salade',
            'dish2' => 'Steak',
            'dish3' => 'Dessert'
        ];
        echo $twig->render('menu.twig', ['menu' => $menu]);
        break;
    case 'home':
    case 'contact':
        echo $twig->render($page . '.twig');
        break;
    default:
        echo $twig->render('home.twig');
        break;
}