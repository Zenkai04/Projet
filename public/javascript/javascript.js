$(document).ready(function() {
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

    var map = L.map('map').setView([45.75, 4.85], 13); // Coordonnées pour Lyon

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

  
    

    // Vérifie si la variable restaurants est définie et contient des données
    if (typeof restaurants !== 'undefined' && restaurants.length > 0) {
        var restaurantList = $('#restaurant-list');
        restaurantList.empty(); // Vider la liste existante

    restaurants.forEach(function(restaurant) {
            if (restaurant && restaurant.lat && restaurant.lon) {
                // Ajouter le marqueur sur la carte
                var marker = L.marker([restaurant.lat, restaurant.lon]).addTo(map);
                marker.bindPopup(`<b>${restaurant.tags.name || "Restaurant"}</b><br>${restaurant.tags['addr:street'] || 'Adresse inconnue'}`);

                // Ajouter le restaurant à la liste HTML
                var listItem = $('<li></li>').text(restaurant.tags.name || "Restaurant inconnu");
                restaurantList.append(listItem);
            } else {
                console.warn("Données de restaurant incomplètes :", restaurant);
            }
        });

        // Ajouter les restaurants sur la carte
    restaurants.forEach(function(restaurant) {
        var marker = L.marker([restaurant.lat, restaurant.lon]).addTo(map);
        marker.bindPopup(`<b>${restaurant.tags.name}</b><br>${restaurant.tags['addr:street']}`);
        
        // Ajouter à la liste HTML
        var listItem = document.createElement('li');
        listItem.innerHTML = `${restaurant.tags.name} - ${restaurant.tags['addr:street']}`;
        document.getElementById('restaurant-list').appendChild(listItem);
    });
    } else {
        console.error('Aucun restaurant disponible ou variable "restaurants" non définie.');
    }
});
