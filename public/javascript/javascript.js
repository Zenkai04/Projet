$(document).ready(function() {
    $('.nav-link').on('click', function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });

    var map = L.map('map').setView([45.75, 4.85], 13); // Coordinates for Lyon

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Utiliser la variable restaurants définie dans le script inline
    if (typeof restaurantList !== 'undefined') {
        var restaurantList = $('#restaurant-list');
        restaurantList.empty(); // Clear any existing list items

        restaurantList.forEach(function(restaurant) {
            if (restaurant !== null) {
                var marker = L.marker([restaurant.lat, restaurant.lon]).addTo(map);
                marker.bindPopup(restaurant.tags.name || "Restaurant");

                // Add restaurant to the list
                var listItem = $('<li></li>').text(restaurant.tags.name || "Restaurant");
                restaurantList.append(listItem);
            }
        });
    }
});