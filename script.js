// Liste des hôpitaux par ville avec leurs coordonnées GPS
const hospitals = [
    { name: "Hôpital Saint et Sauf", lat: 6.37747, lng: 2.38244 },
    { name: "Hôpital de Cotonou", lat: 6.37954, lng: 2.37963 },
    { name: "Hôpital de Parakou", lat: 9.33474, lng: 2.6345 },
    { name: "Hôpital de calavi", lat: 6.43604, lng: 2.33921 },
    { name: "DON DE DIEU", lat: 6.51185, lng: 2.36291 },
    // Ajouter d'autres villes et hôpitaux ici
];

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
        const link = `https://www.google.com/maps/search/?api=1&query=${hospital.lat},${hospital.lng}`;        
        hospitalsDiv.innerHTML += `<p><a href="${link}" target="_blank">${hospital.name}</a> - Distance: ${hospital.distance.toFixed(2)} km</p>`;    
    });}
    

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
