<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\src\Model;

class HomeController extends AbstractController
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        // Récupérer les restaurants depuis la base de données
        $restaurants = $this->model->getAllRestaurants();

        return $this->render('home.twig', [
            'restaurants' => $restaurants,
        ]);
    }
}

?>