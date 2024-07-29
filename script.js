// Liste des hôpitaux par ville avec leurs coordonnées GPS
const hospitals = {
    "Cotonou": [
        { name: "Hôpital de la Mère et de l'Enfant", lat: 6.3716, lng: 2.4256 },
        { name: "Centre National Hospitalier et Universitaire Hubert Koutoukou Maga", lat: 6.3647, lng: 2.4195 }
    ],
    "Porto-Novo": [
        { name: "Centre Hospitalier Départemental de l'Ouémé-Plateau", lat: 6.4976, lng: 2.6169 },
        { name: "Hôpital St Joseph de Porto-Novo", lat: 6.4979, lng: 2.6166 }
    ],
    "Calavi": [
        { name: "Hôpital de Zone d'Abomey-Calavi", lat: 6.477253074767697, lng: 2.342983684947536 },
        { name: "Clinique Centrale de Calavi", lat: 6.436827409254074, lng: 2.3491634945873496 },
        { name: "La Polyclinique Cooperative de Calavi", lat: 6.461049081798427, lng: 2.3556866269849346 },
        { name: "Centre de Santé Calavi Kpota", lat: 6.452861603698625, lng: 2.353626690438329 },
        { name: "Clinique Divine Miséricorde", lat: 6.441433027620622, lng: 2.34555860563079 },
        { name: "Centre Hospitalier International de Calavi", lat: 6.476400245992095, lng: 2.341267071158695 },
        { name: "Clinique HOREB", lat: 6.438874355908541, lng: 2.3390354732332055 },
        { name: "Hôpital de Zone Abomey-Calavi-So Ava", lat: 6.454567339220798, lng: 2.3460735897674416 },
        { name: "Centre Médical Bonne Santé", lat: 6.433245232757827, lng: 2.323929271891431 }
    ]
    // Ajouter d'autres villes et hôpitaux ici
};

// Fonction pour obtenir la position de l'utilisateur
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById("location").innerHTML = "La géolocalisation n'est pas supportée par ce navigateur.";
    }
}

// Fonction pour afficher la position et générer les liens Google Maps
function showPosition(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    // document.getElementById("location").innerHTML = `Latitude: ${userLat} <br> Longitude: ${userLng}`;

    let nearestHospitals = [];

    // Trouver les hôpitaux les plus proches en fonction des coordonnées de l'utilisateur
    for (let city in hospitals) {
        hospitals[city].forEach(hospital => {
            const distance = calculateDistance(userLat, userLng, hospital.lat, hospital.lng);
            nearestHospitals.push({ ...hospital, distance });
        });
    }

    // Trier les hôpitaux par distance
    nearestHospitals.sort((a, b) => a.distance - b.distance);

    // Afficher les liens Google Maps
    let hospitalsDiv = document.getElementById("hospitals");
    hospitalsDiv.innerHTML = '<h2>Hôpitaux les plus proches</h2>';
    nearestHospitals.forEach(hospital => {
        const link = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(hospital.name)}`;
        hospitalsDiv.innerHTML += `<p><a href="${link}" target="_blank">${hospital.name}</a> - Distance: ${hospital.distance.toFixed(2)} km</p>`;
    });
}

// Fonction pour calculer la distance entre deux points (coordonnées en degrés)
function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 6371; // Rayon de la Terre en kilomètres
    const dLat = deg2rad(lat2 - lat1);
    const dLng = deg2rad(lng2 - lng1);
    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
              Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
              Math.sin(dLng/2) * Math.sin(dLng/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

// Fonction pour convertir des degrés en radians
function deg2rad(deg) {
    return deg * (Math.PI/180);
}

// Fonction pour afficher les erreurs de géolocalisation
function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            document.getElementById("location").innerHTML = "L'utilisateur a refusé la demande de géolocalisation.";
            break;
        case error.POSITION_UNAVAILABLE:
            document.getElementById("location").innerHTML = "Les informations de localisation ne sont pas disponibles.";
            break;
        case error.TIMEOUT:
            document.getElementById("location").innerHTML = "La demande de localisation a expiré.";
            break;
        case error.UNKNOWN_ERROR:
            document.getElementById("location").innerHTML = "Une erreur inconnue est survenue.";
            break;
    }
}

// Appeler la fonction pour obtenir la position de l'utilisateur
getLocation();
