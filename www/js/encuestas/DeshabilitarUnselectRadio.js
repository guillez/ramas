/**
 * Este m�dulo incorpor a la encuesta un script que evita que un radio seleccionado
 * sea clickeado nuevamente para que se anula su selecci�n (#18055).
 * El problema est� en que esta acci�n NO DISPARA un evento "change".
 * Por esto la soluci�n elgida es evitar que se pueda anular una selecci�n de la
 * opci�n de un radio.
 */
$(document).ready(function() {
    // Simplemente busco todas las opciones de componentes radio que tenga la encuesta
    // y en caso de que se quiera anular una selecci�n, descarto la acci�n.
    $(".ef_radio").click(function (e) {
        if (!$(this).is(':checked')) {
            e.preventDefault();
            return false;
        }
    });
});