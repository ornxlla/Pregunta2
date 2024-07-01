var map, geocoder, marker;

function initMap() {
    // Obtener las coordenadas desde el HTML
    var latitud = parseFloat(document.getElementById('latitud').textContent);
    var longitud = parseFloat(document.getElementById('longitud').textContent);

    // Inicializar el mapa en las coordenadas especificadas
    var latlng = { lat: latitud, lng: longitud };

    map = new google.maps.Map(document.getElementById('map'), {
        center: latlng,
        zoom: 12, // Ajusta el nivel de zoom según sea necesario
    });

    geocoder = new google.maps.Geocoder();

    // Colocar el marcador en las coordenadas especificadas
    marker = new google.maps.Marker({
        position: latlng,
        map: map
    });

    // Geocodificar la latitud y longitud para obtener país y ciudad
    geocodeLatLng(geocoder, latlng);

    map.addListener('click', function(e) {
        if (marker) {
            marker.setMap(null);
        }
        var latLng = e.latLng;
        document.getElementById('latitud').textContent = latLng.lat();
        document.getElementById('longitud').textContent = latLng.lng();
        geocodeLatLng(geocoder, latLng);

        // Enviar las coordenadas al backend para guardar
        fetch('/guardar_coordenadas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                latitud: latLng.lat(),
                longitud: latLng.lng()
            })
        }).then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Coordenadas guardadas exitosamente.');
                } else {
                    console.error('Error al guardar las coordenadas.');
                }
            });
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
                document.getElementById('pais').textContent = country;
                document.getElementById('ciudad').textContent = city;
            } else {
                alert('No se encontraron resultados de geocodificación.');
            }
        } else {
            alert('Geocode fallido: ' + status);
        }
        console.log(country);
    });
}

// Inicializar el mapa cuando el script de Google Maps esté cargado
window.initMap = initMap;