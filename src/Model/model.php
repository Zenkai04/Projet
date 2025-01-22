// Function to fetch restaurant data from Overpass API and populate the database
function populateRestaurantData($pdo) {
    // Overpass API endpoint to fetch restaurants (change to your region or coordinates)
    $overpassUrl = 'http://overpass-api.de/api/interpreter?data=[out:json];(node["amenity"="restaurant"];way["amenity"="restaurant"];relation["amenity"="restaurant"];);out body;';

    // Fetch the JSON data from Overpass API
    $response = file_get_contents($overpassUrl);
    if ($response === false) {
        die('Error fetching Overpass API data');
    }

    // Decode the JSON data
    $data = json_decode($response, true);

    if (!isset($data['elements']) || count($data['elements']) === 0) {
        echo "No restaurant data found.\n";
        return;
    }

    // Prepare SQL statement to insert data into your restaurants table
    $stmt = $pdo->prepare('
        INSERT INTO restaurants (name, address, phone, entree, idMenu)
        VALUES (:name, :address, :phone, :entree, :idMenu)
    ');

    // Loop through the data from OSM and insert each restaurant
    foreach ($data['elements'] as $element) {
        // Extract relevant fields from each element
        $name = isset($element['tags']['name']) ? $element['tags']['name'] : 'Unknown';
        $address = isset($element['tags']['addr:street']) ? $element['tags']['addr:street'] : 'Unknown';
        $phone = isset($element['tags']['contact:phone']) ? $element['tags']['contact:phone'] : 'Unknown';
        $entree = isset($element['tags']['cuisine']) ? $element['tags']['cuisine'] : 'Unknown';
        $idMenu = 1; // Example value, you can modify based on your own logic

        // Execute the insert query
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone,
            ':entree' => $entree,
            ':idMenu' => $idMenu,
        ]);
    }

    echo "Data has been inserted into the database.\n";
}
