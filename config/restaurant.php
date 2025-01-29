<?php

$host = '127.0.0.1'; // Votre hôte de base de données
$db = 'restaurants_lyon'; // Le nom de votre base de données
$user = 'user'; // Utilisateur MySQL
$pass = 'mdpGS'; // Mot de passe MySQL
$charset = 'utf8mb4'; // Jeu de caractères

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function fetch_restaurants() {
    $query = '[out:json];node["amenity"="restaurant"](45.70,4.80,45.80,4.90);out body;';
    $url = 'https://overpass-api.de/api/interpreter?data=' . urlencode($query);
    $context = stream_context_create([
        'http' => [
            'header' => 'User-Agent: MyUserAgent/1.0'
        ]
    ]);
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        throw new Exception("Erreur lors de la récupération des données de l'Overpass API");
    }

    return json_decode($response, true)['elements'];
}

function get_address($lat, $lon) {
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";
    $context = stream_context_create([
        'http' => [
            'header' => 'User-Agent: MyUserAgent/1.0'
        ]
    ]);
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        return 'Adresse inconnue';
    }

    $data = json_decode($response, true);
    if (isset($data['address'])) {
        $address = $data['address'];
        $houseNumber = $address['house_number'] ?? '';
        $road = $address['road'] ?? '';
        $postcode = $address['postcode'] ?? '';
        $city = $address['city'] ?? 'Lyon';
        
        // Formater l'adresse
        $formattedAddress = trim("{$houseNumber} {$road}");
        return "{$formattedAddress}, {$postcode} {$city}";
    }

    return 'Adresse inconnue';
}

function store_restaurant($pdo, $name, $address, $lat, $lon, $phone, $entree) {
    $stmt = $pdo->prepare("INSERT INTO restaurants (name, address, latitude, longitude, phone, entree) VALUES (?, ?, ?, ?, ?, ?)
                           ON DUPLICATE KEY UPDATE name = VALUES(name), address = VALUES(address), phone = VALUES(phone), entree = VALUES(entree)");
    $stmt->execute([$name, $address, $lat, $lon, $phone, $entree]);
}

set_time_limit(3600);

$restaurants = fetch_restaurants();
foreach ($restaurants as $restaurant) {
    $name = $restaurant['tags']['name'] ?? 'Nom inconnu';
    $lat = $restaurant['lat'];
    $lon = $restaurant['lon'];
    $address = get_address($lat, $lon);
    $phone = $restaurant['tags']['contact:phone'] ?? 'Non fourni'; // exemple pour téléphone
    $entree = $restaurant['tags']['cuisine'] ?? 'Non spécifiée'; // exemple pour entrée

    store_restaurant($pdo, $name, $address, $lat, $lon, $phone, $entree, $idMenu);

    sleep(1); // Ajouter une temporisation entre les requêtes pour éviter d'être bloqué par l'API
}

echo "Données des restaurants insérées avec succès.";

?>