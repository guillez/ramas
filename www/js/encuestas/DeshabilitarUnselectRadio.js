/**
 * Este módulo incorpor a la encuesta un script que evita que un radio seleccionado
 * sea clickeado nuevamente para que se anula su selección (#18055).
 * El problema está en que esta acción NO DISPARA un evento "change".
 * Por esto la solución elgida es evitar que se pueda anular una selección de la
 * opción de un radio.
 */
$(document).ready(function() {
    // Simplemente busco todas las opciones de componentes radio que tenga la encuesta
    // y en caso de que se quiera anular una selección, descarto la acción.
    $(".ef_radio").click(function (e) {
        if (!$(this).is(':checked')) {
            e.preventDefault();
            return false;
        }
    });
});