/**
 * Algunas variables globales que mantengo a lo largo del script.
 */
var sticky_check = true;
var predecessor;
var gap_value;
var button;

/**
 * Método con mensajes de debug por consola que informa los valores importantes al momento de
 * calcular si hay que seguir en modo sticky o pasar a fijo.
 * @param valor_div valor de posición del div que contiene al botón.
 * @param valor_pred valor del predecesor al div.
 * @param diff diferencia entre ambos valores.
 */
function mensaje_debug(valor_div, valor_pred, diff) {
    console.log("\n");
    console.log("Valor div  : " + valor_div);
    console.log("Valor pred : " + valor_pred);
    console.log("Valor diff : " + diff);
    console.log("Valor gap  : " + gap_value);
}

/**
 * Al cargar la página tengo que editar un estilo y por eficiencia almacenar referencia a ciertos elementos.
 */
$(document).ready(function() {
    // Saco a este div el overflow:hidden, caso contrario no funciona el "position:sticky"
    $('.wrapper').css('overflow', 'visible');

    // Almaceno el div que antecede al div que contiene al botón
    predecessor = $(".divider").prev();

    // Almaceno la dif. entre el div del botón y el elemento predecesor
    gap_value = $(".divider").offset().top - predecessor.offset().top;

    // Almaceno el botón
    button = $('.sticky-button');

    // Cheque inicial respecto a si el botón esta visible
    if (!button.isFullyInViewport()) {
        $('.divider').removeClass("divider").addClass("no-divider");
        //button.addClass("shadow-button");
        sticky_check = false;
    }
});

/**
 * Método para evaluar si un elemento se encuentra actualmente TOTALMENTE visible en pantalla.
 * @returns {boolean}
 */
$.fn.isFullyInViewport = function() {
    var elementTop = $(this).offset().top;
    var elementBottom = elementTop + $(this).outerHeight();

    var viewportTop = $(window).scrollTop();
    var viewportBottom = viewportTop + $(window).height();

    return elementTop >= viewportTop && elementBottom <= viewportBottom;
};

/**
 * Cada vez que se realice scroll o resize de pantalla evaluo en que estado hay que mostrar el botón
 * (fijo o sticky).
 */
$(window).on('resize scroll', function() {
    if (sticky_check) {
        if (!button.isFullyInViewport()) {
                $('.divider').removeClass("divider").addClass("no-divider");
                //button.addClass("shadow-button");
                sticky_check = false;
            }
    } else {
        let new_value = $(".no-divider").offset().top;
        let predecessor_value = predecessor.offset().top;
        let diff = new_value - predecessor_value;
        //mensaje_debug(new_value, predecessor_value, diff);

        if (diff == gap_value) {
            $('.no-divider').removeClass("no-divider").addClass("divider");
            //button.removeClass("shadow-button");
            sticky_check = true;
        }
    }
});