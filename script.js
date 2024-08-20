 function showDoctors(hopitalId) {
            window.location.href = `voirdoc.php?hopitalId=${hopitalId}`;
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                document.getElementById("location").innerHTML = "La géolocalisation n'est pas supportée par ce navigateur.";
            }
        }

        function showPosition(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            let nearestHospitals = [];

            hospitals.forEach(hospital => {
                const distance = calculateDistance(userLat, userLng, hospital.latitude, hospital.longitude);
                nearestHospitals.push({ 
                    ...hospital, 
                    distance 
                });
            });

            nearestHospitals.sort((a, b) => a.distance - b.distance);
            displayHospitals(nearestHospitals);
        }

        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Rayon de la Terre en kilomètres
            const dLat = deg2rad(lat2 - lat1);
            const dLng = deg2rad(lat2 - lng1);
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }

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

        getLocation();