$(document).ready(function() {

jQuery.extend(jQuery.validator.messages, {
        required: "Por favor, complete el campo.",
        remote: "Por favor, corrija este campo.",
        email: "Por favor, ingrese una direcci�n de email v�lida.",
        url: "Por favor, ingrese una URL v�lida.",
        date: "Por favor, ingrese una fecha v�lida.",
        dateISO: "Por favor, ingrese una fecha v�lida (ISO).",
        number: "Por favor, ingrese un n�mero v�lido",
        digits: "Por favor, ingrese solo d�gitos.",
        creditcard: "Por favor, ingrese un n�mero de tarjeta de cr�dito v�lido.",
        equalTo: "Por favor, ingrese nuevamente el mismo valor.",
        accept: "Por favor, ingrese un valor con extensi�n v�lida.",
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
        "Por favor, ingrese un n�mero de tel�fono v�lido"
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
                    //mensaje = "<label>Se ha detectado 1 error, por favor corr�jalo antes de terminar.</label>";
                    mensaje = "<label>Existe una pregunta que necesita ser revisada, por favor corr�jala antes de terminar.</label>";
                } else {
                    //mensaje = "<label>Se han detectado " + cantidad_invalidos + " errores, por favor corr�jalos antes de terminar.</label>";
                    mensaje = "<label>Existen preguntas que necesitan ser revisadas, por favor corr�jalas antes de terminar.</label>";
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
            
