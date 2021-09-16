/**
 * Esta clase es la encargada de mantener y calcular la cantidad de preguntas totales
 *  y respondidas.
 */
class EstadoPreguntas {

    constructor(solamente_obligatorias = false) {
        this._solamente_obligatorias = solamente_obligatorias;
        this._preguntas_totales = 0;
        this._preguntas_respondidas = 0;
    }

    getSolamenteObligatorias() {
        return this._solamente_obligatorias;
    }

    /**
     * Se incrementa el número de preguntas totales. Este método esta pensado para
     * utilizarse cuando se habilita/muestra una pregunta.
     */
    incrementarPreguntasTotales() {
        this._preguntas_totales++;
        console.log("Preguntas totales: " + this._preguntas_totales);
    }

    /**
     * Se incrementa el número de preguntas totales. Este método esta pensado para
     * utilizarse cuando se deshabilita/oculta una pregunta.
     */
    decrementarPreguntasTotales() {
        this._preguntas_totales--;
        console.log("Preguntas totales: " + this._preguntas_totales);
    }

    /**
     * Método para calcular inicialmente las preguntas totales.
     * Este método se usa al principio para obtener el total de preguntas.
     * Luego debe actualizarse este total con la cantidad de preguntas
     * deshabilitadas/ocultas (esto es porque el script que oculta/deshabilita
     * preguntas al cargar una encuesta notifica al MediatorDependientesBarraProgreso
     * para que actualice el total de preguntas).
     */
    calcularPreguntasTotales() {
      let totales = $(".cuerpo-formulario .form-group").length;
      let calculo_edad_dinamico = $(".dinamico_fecha_edad").length;
      let combo_localidad = $(".dinamico_localidad").length;

      console.log("Total antes de la resta: " + totales);
      console.log("Editable numero edad: " + calculo_edad_dinamico);
      console.log("Combo localidad: " + combo_localidad);

      this._preguntas_totales = totales;
      console.log("Preguntas totales: " + totales);
    }

    calcularPreguntasInputText() {
        let cantidad = 0;
        // El not readonly era para evitar los inputs deshabilitados por otras preguntas (por ej. calculo edad).
        // El ".has-error" es importante contemplarlo, porque sino me suma una respuesta que tenga contenido erroneo
        let inputs = $(".form-group:not(.has-error) > div > :text:not([readonly]):not(.pertenece_localidad)");

        if (this._solamente_obligatorias) {
            inputs = inputs.filter('[required]');
        }

        // Contabilizo únicamente los que no sean vacíos
        inputs.each(function() {
            if ($.trim($(this).val()) != '') {
                $(this).addClass("procesado");
                cantidad++;
                if ($(this).hasClass("dinamico_fecha_edad")) {
                    cantidad++;
                }
            }
        });
        
        return cantidad;
    }

    calcularPreguntasInputTextDesdeElemento(elem) {
        let cantidad = 0;
        // El not readonly era para evitar los inputs deshabilitados por otras preguntas (por ej. calculo edad).
        // El ".has-error" no lo saco mas porque ahora necesito verificarlo
        let inputs = elem.find("div > :text:not([readonly]):not(.pertenece_localidad)");

        if (this._solamente_obligatorias) {
            inputs = inputs.filter('[required]');
        }

        inputs.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                    if ($(this).hasClass("dinamico_fecha_edad")) {
                        cantidad--;
                    }
                }
            } else {
                if ( ($(this).hasClass("procesado")) && ($.trim($(this).val()) == '') ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                    if ($(this).hasClass("dinamico_fecha_edad")) {
                        cantidad--;
                    }
                } else {
                    if ( (!$(this).hasClass("procesado")) && ($.trim($(this).val()) != '') ) {
                        $(this).addClass("procesado");
                        cantidad++;
                        if ($(this).hasClass("dinamico_fecha_edad")) {
                            cantidad++;
                        }
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasTextArea() {
        let cantidad = 0;
        let textarea = $(".form-group:not(.has-error) > div > textarea");

        if (this._solamente_obligatorias) {
            textarea = textarea.filter('[required]');
        }

        // Contabilizo únicamente los que no sean vacíos
        textarea.each(function() {
            if ($.trim($(this).val()) != '') {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasTextAreaDesdeElemento(elem) {
        let cantidad = 0;
        let textarea = elem.find("div > textarea");

        if (this._solamente_obligatorias) {
            textarea = textarea.filter('[required]');
        }

        textarea.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ( ($(this).hasClass("procesado")) && ($.trim($(this).val()) == '') ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ( (!$(this).hasClass("procesado")) && ($.trim($(this).val()) != '') ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasCheckbox() {
        let cantidad = 0;
        let checkbox = $(".form-group:not(.has-error) > div:has(div > :checkbox)");

        if (this._solamente_obligatorias) {
            checkbox = checkbox.filter('div > :checkbox[required]');
        }

        checkbox.each(function() {
            if ($(this).has("div > :checked").length > 0) {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasCheckboxDesdeElemento(elem) {
        let cantidad = 0;
        let checkbox = elem.find("div:has(div > :checkbox)");

        if (this._solamente_obligatorias) {
            checkbox = checkbox.filter("div > :checkbox[required]");
        }
        
        checkbox.each(function() {
            if (elem.hasClass("has-error")) {
                if ($(this).hasClass("procesado")) {
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ($(this).hasClass("controls") && $(this).hasClass("col-sm-12") && $(this).hasClass("procesado") && ($(this).has("div > :checked").length == 0)) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ($(this).hasClass("controls") && $(this).hasClass("col-sm-12") && !$(this).hasClass("procesado") && ($(this).has("div > :checked").length > 0)) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasRadio() {
        let cantidad = 0;
        let radio = $(".form-group:not(.has-error) > div:has(div > label > :radio)");

        if (this._solamente_obligatorias) {
            radio = radio.filter('div > label > :radio[required]');
        }

        radio.each(function() {
            if ($(this).has("div > label > :checked").length > 0) {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasRadioDesdeElemento(elem) {
        let cantidad = 0;
        let radio = elem.find("div:has(div > label > :radio)");

        if (this._solamente_obligatorias) {
            radio = radio.filter('div > label > :radio[required]');
        }

        radio.each(function() {
            if (elem.hasClass("has-error")) {
                if ($(this).hasClass("procesado")) {
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ($(this).hasClass("controls") && $(this).hasClass("col-sm-12") && $(this).hasClass("procesado") && ($(this).has("div > label > :checked").length == 0) ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    
                    if ($(this).hasClass("controls") && $(this).hasClass("col-sm-12") && !$(this).hasClass("procesado") && ($(this).has("div > label > :checked").length > 0) ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasListas() {
        let cantidad = 0;
        let listas = $(".form-group:not(.has-error) > div:has(.ef_multi_seleccion_lista)");

        if (this._solamente_obligatorias) {
            listas = listas.filter(':has([required])');
        }

        listas.each(function() {
            if ($(this).has("select > option:selected").length > 0) {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasListasDesdeElemento(elem) {
        let cantidad = 0;
        let listas = elem.find("div:has(.ef_multi_seleccion_lista)");

        if (this._solamente_obligatorias) {
            listas = listas.filter(':has([required])');
        }

        listas.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ( ($(this).hasClass("procesado")) && ($(this).has("select > option:selected").length == 0) ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ( (!$(this).hasClass("procesado")) && ($(this).has("select > option:selected").length > 0) ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasComboListado() {
        let cantidad = 0;
        let combo_listado = $(".form-group:not(.has-error) > div:has(select.ef_combo:not(.dinamico_localidad))");

        if (this._solamente_obligatorias) {
            combo_listado = combo_listado.filter('select.ef_combo[required]:not(.dinamico_localidad)');
        }

        combo_listado.each(function() {
            let value = $(this).find("select > option:selected").attr('value');
            if ((value != '') && (value != null)) {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasComboListadoDesdeElemento(elem) {
        let cantidad = 0;
        let combo_listado = elem.find("div:has(select.ef_combo:not(.dinamico_localidad))");

        if (this._solamente_obligatorias) {
            combo_listado = combo_listado.filter('select.ef_combo[required]:not(.dinamico_localidad)');
        }

        combo_listado.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                let value = $(this).find("select > option:selected").attr('value');
                if ( ($(this).hasClass("procesado")) && ((value == '') || (value == null)) ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ( (!$(this).hasClass("procesado")) && (value != '') && (value != null) ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasComboAuto() {
        let cantidad = 0;

        // Selecciono los combos auto NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente el control del REQUIRED en este caso solo se hace al momento del submit
        let combo_auto_not_required = $(".form-group:not(.has-error) > div:has(select.select_search)");

        combo_auto_not_required.each(function() {
            if ($(this).find("select > option:selected").attr('value') != '') {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasComboAutoDesdeElemento(elem) {
        let cantidad = 0;

        // Selecciono los combos auto NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente el control del REQUIRED en este caso solo se hace al momento del submit
        let combo_auto_not_required = elem.find("div:has(select.select_search)");

        combo_auto_not_required.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ( ($(this).hasClass("procesado")) && ($(this).find("select > option:selected").attr('value') == '') ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ( (!$(this).hasClass("procesado")) && ($(this).find("select > option:selected").attr('value') != '') ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }

    calcularPreguntasLocalidades() {
        let cantidad = 0;
        // Selecciono las localidades NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente no tienen validación alguna de REQUIRED
        let localidades = $(".form-group > div > div:has(input.localidad)");

        localidades.each(function() {
            if ($(this).find(":text").val() != '') {
                $(this).addClass("procesado");
                cantidad++;
            }
        });

        return cantidad;
    }

    calcularPreguntasLocalidadesDesdeElem(elem) {
        let cantidad = 0;
        // Selecciono las localidades NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente no tienen validación alguna de REQUIRED
        let localidades = elem.find("div > div:has(input.localidad)");

        localidades.each(function() {
            if ( elem.hasClass("has-error") ){
                if ( $(this).hasClass("procesado") ){
                    $(this).removeClass("procesado");
                    cantidad--;
                }
            } else {
                if ( ($(this).hasClass("procesado")) && ($(this).find(":text").val() == '') ) {
                    $(this).removeClass("procesado");
                    cantidad--;
                } else {
                    if ( (!$(this).hasClass("procesado")) && ($(this).find(":text").val() != '') ) {
                        $(this).addClass("procesado");
                        cantidad++;
                    }
                }
            }
        });

        return cantidad;
    }
    
    calcularPreguntasLocalidadesYCP() {
        let cantidad = 0;
        // Selecciono las localidades y CP NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente no tienen validación alguna de REQUIRED
        let localidades = $(".form-group > div > div:has(input.localidad_y_cp)");

        localidades.each(function() {
            if ($(this).find(":text").val() != '') {
                $(this).addClass("procesado");
                cantidad += 2;
            }
        });

        return cantidad;
    }
    
    calcularPreguntasLocalidadesYCPDesdeElem(elem) {
        let cantidad = 0;
        // Selecciono las localidades y CP NOT REQUIRED y REQUIRED
        // Esto es porque aparentemente no tienen validación alguna de REQUIRED
        let localidades = elem.find("div > div:has(input.localidad_y_cp)");
        
        localidades.each(function() {
            if (elem.hasClass("has-error")){
                if ($(this).hasClass("procesado")) {
                    $(this).removeClass("procesado");
                    cantidad -= 2;
                }
            } else {
                if (($(this).hasClass("procesado")) && ($(this).find(":text").val() == '')) {
                    $(this).removeClass("procesado");
                    cantidad -= 2;
                } else {
                    if ((!$(this).hasClass("procesado")) && ($(this).find(":text").val() != '')) {
                        $(this).addClass("procesado");
                        cantidad += 2;
                    }
                }
            }
        });

        return cantidad;
    }

    /**
     * Método que calcula la cantidad de preguntas respondidas.
     * Cuidado que este método es "pesado" ya que recorre todo el
     * DOM para verificar las preguntas respondidas. Debería utilizarse
     * únicamente al inicio.
     */
    calcularPreguntasRespondidas() {
        let respondidas = 0;

        respondidas += this.calcularPreguntasInputText();
        respondidas += this.calcularPreguntasTextArea();
        respondidas += this.calcularPreguntasCheckbox();
        respondidas += this.calcularPreguntasRadio();
        respondidas += this.calcularPreguntasListas();
        respondidas += this.calcularPreguntasComboListado();
        respondidas += this.calcularPreguntasComboAuto();
        respondidas += this.calcularPreguntasLocalidades();
        respondidas += this.calcularPreguntasLocalidadesYCP();

        this._preguntas_respondidas = respondidas;
        console.log("Preguntas respondidas: " + this._preguntas_respondidas);
    }

    /**
     * Método que actualiza la cantidad de respuestas a partir de un elemento.
     * Recorre todos los tipos de preguntas y actualiza, tanto para aumentar
     * como para disminuir, la cantidad de preguntas respondidas.
     * @param elem Elemento del DOM a partir del cual se desea verificar las
     * preguntas respondidas.
     */
    calcularPreguntasRespondidasDesdeElemento(elem) {
        let respondidas = this._preguntas_respondidas;

        respondidas += this.calcularPreguntasInputTextDesdeElemento(elem);
        respondidas += this.calcularPreguntasTextAreaDesdeElemento(elem);
        respondidas += this.calcularPreguntasCheckboxDesdeElemento(elem);
        respondidas += this.calcularPreguntasRadioDesdeElemento(elem);
        respondidas += this.calcularPreguntasListasDesdeElemento(elem);
        respondidas += this.calcularPreguntasComboListadoDesdeElemento(elem);
        respondidas += this.calcularPreguntasComboAutoDesdeElemento(elem);
        respondidas += this.calcularPreguntasLocalidadesDesdeElem(elem);
        respondidas += this.calcularPreguntasLocalidadesYCPDesdeElem(elem);

        this._preguntas_respondidas = respondidas;
        console.log("Preguntas respondidas: " + this._preguntas_respondidas);
    }

    /**
     * Método que calcula el porcentaje de preguntas respondidas.
     * @returns {number} Porcentaje correspondiente a las preguntas respondidas sobre
     * el total de preguntas.
     */
    calcularProgreso() {
        let progreso = this._preguntas_respondidas * 100.0 / this._preguntas_totales;

        return progreso;
    }
}