<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Model\Model;

class DetailsController
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader);
    }

    public function show($id)
    {
        $model = new Model();
        $restaurant = $model->getRestaurantById($id);

        echo $this->twig->render('Details.twig', ['restaurant' => $restaurant]);
    }
}