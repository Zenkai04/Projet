<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader);
    }

    public function index()
    {
        // URL de l'Overpass API
        $url = 'https://overpass-api.de/api/interpreter';

        // Requête Overpass (en JSON)
        $query = '[out:json];area["name"="Lyon"]->.searchArea;node["amenity"="restaurant"](area.searchArea);out body;';

        // Paramètres pour la requête
        $options = [
            'http' => [
                'method'  => 'GET',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query(['data' => $query])
            ]
        ];

        // Créer un contexte de flux
        $context = stream_context_create($options);

        // Récupérer la réponse JSON
        $response = file_get_contents($url, false, $context);

        // Décoder la réponse JSON en tableau PHP
        $data = json_decode($response, true);

        // Passer les données à la vue Twig
        echo $this->twig->render('home.twig', ['restaurants' => $data['elements']]);
    }
}