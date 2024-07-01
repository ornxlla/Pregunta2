let respuestaCount = 4;  // Inicialmente hay 4 respuestas

function addRespuesta() {
    const respuestasDiv = document.getElementById('respuestas');

    const label = document.createElement('label');
    label.setAttribute('for', `respuesta_${respuestaCount}`);
    label.textContent = `Respuesta ${respuestaCount + 1}:`;

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'respuestas[]';
    input.id = `respuesta_${respuestaCount}`;
    input.required = true;

    const radio = document.createElement('input');
    radio.type = 'radio';
    radio.name = 'respuesta_correcta';
    radio.value = respuestaCount;
    radio.required = true;

    const correctLabel = document.createTextNode(' Correcta');

    respuestasDiv.appendChild(label);
    respuestasDiv.appendChild(input);
    respuestasDiv.appendChild(radio);
    respuestasDiv.appendChild(correctLabel);
    respuestasDiv.appendChild(document.createElement('br'));

    respuestaCount++;
}