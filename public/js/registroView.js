navigator.geolocation.getCurrentPosition(function(position) {
    document.getElementById('latitud').value = position.coords.latitude;
    document.getElementById('longitud').value = position.coords.longitude;
});

document.getElementById("password").addEventListener("focus", function() {
    document.getElementById("password-info").style.display = "block";
});

document.getElementById("password").addEventListener("blur", function() {
    document.getElementById("password-info").style.display = "none";
});

function validateForm() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm_password").value;
    var passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;

    if (!passwordPattern.test(password)) {
        alert("La contraseña debe tener al menos 6 caracteres, incluyendo al menos una letra y un número.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Las contraseñas no coinciden.");
        return false;
    }


    return true;
}

var map, geocoder, marker;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 },
        zoom: 2,
    });

    geocoder = new google.maps.Geocoder();

    map.addListener('click', function(e) {
        if (marker) {
            marker.setMap(null);
        }
        var latLng = e.latLng;
        document.getElementById('latitud').value = latLng.lat();
        document.getElementById('longitud').value = latLng.lng();
        geocodeLatLng(geocoder, latLng);
    });
}

function geocodeLatLng(geocoder, latlng) {
    geocoder.geocode({ location: latlng }, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                var components = results[0].address_components;
                var country = '';
                var city = '';
                for (var i = 0; i < components.length; i++) {
                    var types = components[i].types;
                    if (types.includes('country')) {
                        country = components[i].long_name;
                    }
                    if (types.includes('locality')) {
                        city = components[i].long_name;
                    }
                }
                document.getElementById('pais').value = country;
                document.getElementById('ciudad').value = city;
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
            } else {
                alert('No se encontraron resultados de geocodificación.');
            }
        } else {
            alert('Geocode fallido: ' + status);
        }
    });
}