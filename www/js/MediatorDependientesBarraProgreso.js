/**
 *  Esta clase se encarga de gestionar la comunicación para las preguntas dependientes.
 *  Cuando "helper_encuestas" habilita/deshabilita y/o muestra/oculta una pregunta,
 *  llama a los métodos actualizar de esta clase. Luego esta clase es la encargada de notificar
 *  a las clases EstadoPreguntas y BarraProgreso para que actualicen su estado. Esto en el caso
 *  de que se posea una barra de progreso.
 *  Notar que la clase cuenta con un flag "_inicio". Este es necesario porque ni bien se carga una
 *  encuesta, se deshabilitan/ocultan los elementos necesarios cuando todavía no se realizó el cálculo
 *  de preguntas totales por parte de EstadoPreguntas. Por ello es importante setearlo en falso al
 *  momento de comenzar a responder (se hace en el IniciadorBarraProgreso, que prepara el ambiente
 *  para la barra de progreso).
 */
class MediatorDependientesBarraProgreso {

    constructor() {
        this._inicio = true;
        this._cantidad_elementos_deshabilitados = 0;
        this._elementos_deshabilitados = new Set();
        this._estado_preguntas = null;
        this._barra_progreso = null;
    }

    setInicio(boolean) {
        this._inicio = boolean;
    }

    setEstadoPreguntas(estado_preguntas) {
        this._estado_preguntas = estado_preguntas;
    }

    setBarraProgreso(barra_progreso) {
        this._barra_progreso = barra_progreso;
    }

    /**
     * Una vez que se saben cuantas preguntas deshabilitadas hay, se puede
     * sincorinizar el total de preguntas actuales en EstadoPreguntas.
     * Nota de diseño: no es elegante usar el "for", pero por una cuestión
     * de diseño no quise forzar que la clase posea un "set", ya que los
     * incrementos/decrementos de preguntas totales son secuenciales al
     * momento de los métodos actualizar.
     */
    sincronizarPreguntasTotales() {
        if (this._estado_preguntas != null) {
            for(let i = 0; i < this._cantidad_elementos_deshabilitados; i++) {
                this._estado_preguntas.decrementarPreguntasTotales();
            }
        }
    }

    actualizoEstadoProgresoDeshabilitar(id) {
        let elem = $(id).parents('.form-group');
        this._estado_preguntas.decrementarPreguntasTotales();
        this._estado_preguntas.calcularPreguntasRespondidasDesdeElemento(elem);
        this._barra_progreso.asignarProgreso(this._estado_preguntas.calcularProgreso());
    }

    actualizoEstadoProgresoHabilitar(id) {
        this._estado_preguntas.incrementarPreguntasTotales();
        this._barra_progreso.asignarProgreso(this._estado_preguntas.calcularProgreso());
    }

    actualizarAlDeshabilitarElemento(id) {
        if (!this._elementos_deshabilitados.has(id)) {
            this._cantidad_elementos_deshabilitados++;
            this._elementos_deshabilitados.add(id);

            if ((this._estado_preguntas != null) & (this._barra_progreso != null)) {
                this.actualizoEstadoProgresoDeshabilitar(id);
            }
        }
        console.log("[Mediator] Elementos DESHABILITADOS: " + this._cantidad_elementos_deshabilitados);
    }

    actualizarAlHabilitarElemento(id) {
        if (!this._inicio) {
            if (this._elementos_deshabilitados.has(id)) {
                this._cantidad_elementos_deshabilitados--;
                this._elementos_deshabilitados.delete(id);

                if ((this._estado_preguntas != null) & (this._barra_progreso != null)) {
                    this.actualizoEstadoProgresoHabilitar(id);
                }
            }
            console.log("[Mediator] Elementos DESHABILITADOS: " + this._cantidad_elementos_deshabilitados);
        }
    }
}