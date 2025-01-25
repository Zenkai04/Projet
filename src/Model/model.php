namespace App\Model;

use PDO;

class Model
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getRestaurantById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM restaurants WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllRestaurants(): array
    {
        $stmt = $this->pdo->query('SELECT name, address, latitude AS lat, longitude AS lon FROM restaurants');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAddressFromCoords(float $lat, float $lon, array $tags): string
    {
        if (isset($tags['addr:street'])) {
            return ($tags['addr:housenumber'] ?? '') . ' ' .
                   ($tags['addr:street'] ?? '') . ', ' .
                   ($tags['addr:postcode'] ?? '') . ' ' .
                   ($tags['addr:city'] ?? 'Lyon');
        }

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lon}&addressdetails=1";
        $response = $this->fetchUrlWithRetry($url);

        if ($response === null) {
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
        return null;
    }
}
