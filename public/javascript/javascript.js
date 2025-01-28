document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('map').setView([45.75, 4.85], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Vérification et affichage des restaurants
    var restaurants = JSON.parse(document.getElementById('restaurants-data').textContent);
    console.log(restaurants); // Vérification dans la console du navigateur

    restaurants.forEach(function(restaurant) {
        if (restaurant.latitude && restaurant.longitude) {
            L.marker([parseFloat(restaurant.latitude), parseFloat(restaurant.longitude)])
                .addTo(map)
                .bindPopup(restaurant.name);
        } else {
            console.error('Coordonnées manquantes pour:', restaurant);
        }
    });
});

function showReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = 'block';
    }
}

function hideReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form) {
        form.style.display = 'none';
    }
}