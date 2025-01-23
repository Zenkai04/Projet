<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        set_time_limit(300); // Augmenter la durée maximale d'exécution à 5 minutes

        $query = '[out:json];node["amenity"="restaurant"](45.70,4.80,45.80,4.90);out body;';
        $url = 'https://overpass-api.de/api/interpreter?data=' . urlencode($query);
        $response = $this->fetchUrlWithRetry($url);

        if ($response === false) {
            throw new \Exception("Erreur lors de la récupération des données de l'Overpass API");
        }

        $data = json_decode($response, true);
        $restaurants = [];

        if (isset($data['elements'])) {
            foreach ($data['elements'] as $element) {
                $address = $this->getAddressFromCoords($element['lat'], $element['lon'], $element['tags']);

                $restaurants[] = [
                    'name' => $element['tags']['name'] ?? 'Nom inconnu',
                    'lat' => $element['lat'],
                    'lon' => $element['lon'],
                    'address' => $address
                ];
            }
        }

        return $this->render('home/index.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }

    private function getAddressFromCoords(float $lat, float $lon, array $tags): string
    {
        // Si adresse déjà fournie par Overpass
        if (isset($tags['addr:street'])) {
            return ($tags['addr:housenumber'] ?? '') . ' ' .
                   ($tags['addr:street'] ?? '') . ', ' .
                   ($tags['addr:postcode'] ?? '') . ' ' .
                   ($tags['addr:city'] ?? 'Lyon');
        }

        // Sinon, utiliser l'API Nominatim
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";
        $response = $this->fetchUrlWithRetry($url);

        if ($response === false) {
            return 'Adresse inconnue';
        }

        $data = json_decode($response, true);

        if (isset($data['address'])) {
            $addressParts = $data['address'];
            return ($addressParts['house_number'] ?? '') . ' ' .
                   ($addressParts['road'] ?? '') . ', ' .
                   ($addressParts['postcode'] ?? '') . ' ' .
                   ($addressParts['city'] ?? 'Lyon');
        }

        return 'Adresse inconnue';
    }

    private function fetchUrlWithRetry(string $url, int $maxRetries = 3, int $delay = 1): ?string
    {
        $attempts = 0;
        while ($attempts < $maxRetries) {
            $response = @file_get_contents($url);
            if ($response !== false) {
                return $response;
            }
            $attempts++;
            sleep($delay);
        }
        return false;
    }
}

?>