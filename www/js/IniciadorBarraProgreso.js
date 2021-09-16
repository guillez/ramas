/**
 * Este módulo tiene como objetivo preparar el ambiente para el uso de la barra de progreso.
 * Inicializa:
 * -- Objeto EstadoPreguntas
 * -- Obtiene el color de la encuesta
 * -- Asigna los eventos para actualizar el progreso en la encuesta
 * -- Objeto BarraProgreso
 * -- Calcula el progreso actual
 * -- Configura el objeto MediatorDependientesBarraProgreso
 */

// Variables globales para el estado de las preguntas y la barra de progreso
var estado = new EstadoPreguntas();
var barra_progreso;

/**
 * Función para pasar un valor de color RGB a Hexadecimal.
 * @param rgb Color en formato RGB.
 * @returns {string} Cadena de caracteres con el color en formato hexadecimal.
 */
function rgb2hex(rgb) {
    var hexDigits = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"];
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
    }
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

$(document).ready(function() {
    // Hago este pequeño fix para que no se solapen los botones guardar/terminar con la barra de progreso
    $(".form-actions").css({"margin-bottom":"50px"});

    // Esta solución para obtener el color es de compromiso
    // Lo mismo para el id #barra-progreso, podría pasarlo por un script y luego accederlo
    let color = rgb2hex($('.btn-primary').css('backgroundColor'));
    barra_progreso = new BarraProgreso('#barra-progreso', color);

    // Asigno los eventos que van a disparar el control de avance en las respuestas
    var divs = $(".cuerpo-formulario .form-group");
    divs.change(function() {
        let elem = $( this );
        estado.calcularPreguntasRespondidasDesdeElemento(elem);
        barra_progreso.asignarProgreso(estado.calcularProgreso());
    });

    var divs = $(".cuerpo-formulario .form-group");
    divs.blur(function() {
        let elem = $( this );
        estado.calcularPreguntasRespondidasDesdeElemento(elem);
        barra_progreso.asignarProgreso(estado.calcularProgreso());
    });

    divs.focusout(function() {
        let elem = $( this );
        estado.calcularPreguntasRespondidasDesdeElemento(elem);
        barra_progreso.asignarProgreso(estado.calcularProgreso());
    });

    divs.keyup(function() {
        let elem = $( this );
        estado.calcularPreguntasRespondidasDesdeElemento(elem);
        barra_progreso.asignarProgreso(estado.calcularProgreso());
    });

    // Calculo las preguntas totales y hago el recuento para el caso
    // que se carga una encuesta con valores almacenados.
    estado.calcularPreguntasTotales();
    estado.calcularPreguntasRespondidas();

    // Actualizo la clase que hace de mediador entre la BarraProgreso/EstadoPreguntas y el manejo
    // de preguntas dependientes (helper_encuestas).
    mediator.setEstadoPreguntas(estado);
    mediator.setBarraProgreso(barra_progreso);
    mediator.setInicio(false);
    mediator.sincronizarPreguntasTotales();

    // Finalmente calculo el progreso con el valor real
    // de preguntas totales.
    barra_progreso.asignarProgreso(estado.calcularProgreso());
});