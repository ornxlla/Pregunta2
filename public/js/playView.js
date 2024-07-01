function move() {
    var elem = document.getElementById("myBar");
    var countdown = document.getElementById("countdown");
    var width = 100; // Ancho inicial
    var duration = 30000; // Duración en milisegundos (30 segundos)
    var frameRate = 10; // Intervalo de actualización (ms)
    var frames = duration / frameRate;
    var step = width / frames;
    var i = 0;

    function frame() {
        if (i < frames) {
            i++;
            width -= step;
            elem.style.width = width + "%";
            var remainingTime = ((frames - i) * frameRate / 1000).toFixed(1);
            countdown.textContent = remainingTime + " segundos restantes";
        } else {
            clearInterval(id);
            alert("¡Tiempo agotado!");
            // Puedes agregar aquí cualquier otra acción que desees cuando el tiempo se agote
            let aux_url = window.location.href
            if(aux_url.includes("playDuelo")){
                window.location.replace("/Play/playDuelo");
            }else{
                window.location.replace("/Play/finalizar_partida");
            }
        }
    }

    var id = setInterval(frame, frameRate);
}

window.onload = function() {
    move();
};


function reportarPregunta(id_pregunta) {
    var motivo = prompt("Ingrese el motivo de reporte:");
    if (motivo !== null) {
        var data = {
            id_pregunta: id_pregunta,
            motivo: motivo
        };

        // Envío de la solicitud AJAX
        $.ajax({
            type: "POST",
            url: "/reportar-pregunta",
            data: data,
            success: function(response) {
                alert('Pregunta reportada exitosamente.');
            },
            error: function(xhr, status, error) {
                alert('Error al reportar la pregunta.');
                console.error(error);
            }
        });
    }
}