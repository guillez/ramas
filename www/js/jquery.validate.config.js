$(document).ready(function() {

jQuery.extend(jQuery.validator.messages, {
        required: "Por favor, complete el campo.",
        remote: "Por favor, corrija este campo.",
        email: "Por favor, ingrese una dirección de email válida.",
        url: "Por favor, ingrese una URL válida.",
        date: "Por favor, ingrese una fecha válida.",
        dateISO: "Por favor, ingrese una fecha válida (ISO).",
        number: "Por favor, ingrese un número válido",
        digits: "Por favor, ingrese solo dígitos.",
        creditcard: "Por favor, ingrese un número de tarjeta de crédito válido.",
        equalTo: "Por favor, ingrese nuevamente el mismo valor.",
        accept: "Por favor, ingrese un valor con extensión válida.",
        maxlength: jQuery.validator.format("Por favor, ingrese {0} o menos caracteres."),
        minlength: jQuery.validator.format("Por favor, ingrese al menos {0} caracteres."),
        rangelength: jQuery.validator.format("Por favor, ingrese un texto de entre {0} y {1} caracteres."),
        range: jQuery.validator.format("Por favor, ingrese un valor entre {0} y {1}."),
        max: jQuery.validator.format("Por favor, ingrese un valor menor o igual que {0}."),
        min: jQuery.validator.format("Por favor, ingrese un valor mayor o igual que {0}.")
    });

jQuery.validator.addMethod(
        "mi_fecha", function (value, element) {
                var bits = value.match( /([0-9]+)/gi ), str;
                if ( ! bits ) return this.optional(element) || false;
                str = bits[ 1 ] + "/" + bits[ 0 ] + "/" + bits[ 2 ];
                if (!bits[2] || bits[2].length != 4) return false;
                return this.optional(element) || !/Invalid|NaN/.test(new Date( str ));
        },
        "Por favor, ingrese una fecha con formato dd/mm/aaaa"
);

jQuery.validator.addClassRules({
        val_fecha : { mi_fecha : true }
});

// PROBANDO EL VALIDADOR DE TELEFONO
jQuery.validator.addMethod(
        "mi_telefono", function (value, element) {
            let bits = value.match( /^[\+]?[0-9]{2,4}[\s]([0-9]{2,5}|\([0-9]{2,5}\))[\s]([0-9][\s,-]?){4,10}$/gi );
            if ( ! bits ) return this.optional(element) || false;

            return ( bits == value );
        },
        "Por favor, ingrese un número de teléfono válido"
);

jQuery.validator.addClassRules({
        val_telefono : { mi_telefono : true }
});
// *****************************

$('#formulario').validate({
        errorClass: "text-danger",
        errorPlacement: function(error, element) {
            error.appendTo(element.closest('.controls').children().last());
        },
        highlight: function(label) {
            $(label).closest('.form-group').addClass('has-error');
            $(label).closest('.form-group').removeClass('success');
        },
        success: function(label) {
            $(label).closest('.form-group').removeClass('has-error');
            $(label).closest('.form-group').addClass('success');
        },
        ignore: '',
        showErrors: function(errorMap, errorList) {
            var mensaje = '';
            var cantidad_invalidos = this.numberOfInvalids();
            
            if (cantidad_invalidos > 0) {
                if (cantidad_invalidos == 1) {
                    //mensaje = "<label>Se ha detectado 1 error, por favor corríjalo antes de terminar.</label>";
                    mensaje = "<label>Existe una pregunta que necesita ser revisada, por favor corríjala antes de terminar.</label>";
                } else {
                    //mensaje = "<label>Se han detectado " + cantidad_invalidos + " errores, por favor corríjalos antes de terminar.</label>";
                    mensaje = "<label>Existen preguntas que necesitan ser revisadas, por favor corríjalas antes de terminar.</label>";
                }
                $(".resumen-errores").show();
                $(".resumen-errores").html(mensaje);
                        } else {
                $(".resumen-errores").hide();
            }

            this.defaultShowErrors();
        }
    });
});
            
