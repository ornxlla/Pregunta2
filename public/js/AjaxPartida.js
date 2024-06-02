function formatDateTime(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function iniciarTemporizador() {

    var startTime = new Date();
    var formattedStartTime = formatDateTime(startTime);

    $.ajax({
        url: "/play/guardarTiempoDeArranque",
        type: "POST",
        dataType: "json",
        data: {
            startTime: formattedStartTime
        },
        success: function (data) {
            console.log("Tiempo de juego guardado");
        },
    });

    var temporizador = document.getElementById("temporizador");
    var tiempo = parseInt(localStorage.getItem("tiempo")) || 10;

    temporizador.textContent = tiempo;

    var intervalo = setInterval(function () {
        tiempo--;
        localStorage.setItem("tiempo", tiempo);
        temporizador.textContent = tiempo;

        if (tiempo === 0) {
            clearInterval(intervalo);

            document.getElementById("formularioPlay").submit();
        }
    }, 1000);
}
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById('formularioPlay');

    form.addEventListener('submit', function(e) {

        if (form.classList.contains('submitted')) {
            e.preventDefault();
            return;
        }

        form.classList.add('submitted');

        localStorage.setItem("tiempo", 10);
    });
});


