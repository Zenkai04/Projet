$(document).ready(function() {
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

    var map = L.map('map').setView([45.75, 4.85], 13); // Coordonnées pour Lyon

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Fonction pour récupérer les restaurants depuis Overpass API
    function fetchRestaurants() {
        var overpassUrl = 'https://overpass-api.de/api/interpreter?data=' +
            encodeURIComponent(`
                [out:json];
                node["amenity"="restaurant"](45.70,4.80,45.80,4.90);
                out body;
            `);

        $.getJSON(overpassUrl, function(data) {
            if (data.elements && data.elements.length > 0) {
                var restaurantList = $('#restaurant-list');
                restaurantList.empty();

                data.elements.forEach(function(restaurant) {
                    if (restaurant && restaurant.lat && restaurant.lon) {
                        var marker = L.marker([restaurant.lat, restaurant.lon]).addTo(map);
                        marker.bindPopup(`<b>${restaurant.tags.name || "Restaurant"}</b><br>${restaurant.tags['addr:street'] || 'Adresse inconnue'}`);

                        var listItem = $('<li></li>').text(restaurant.tags.name || "Restaurant inconnu");
                        restaurantList.append(listItem);
                    } else {
                        console.warn("Données de restaurant incomplètes :", restaurant);
                    }
                });
            } else {
                console.error('Aucun restaurant trouvé dans les données Overpass API.');
            }
        }).fail(function() {
            console.error('Erreur lors de la récupération des données depuis Overpass API.');
        });
    }

    // Appel de la fonction pour récupérer les restaurants
    fetchRestaurants();
});