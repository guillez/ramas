<?php
class resultados_filtro_formulario extends bootstrap_formulario
{

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------


		{$this->objeto_js}.evt__elemento__procesar = function(es_inicial)
		{
            if (! es_inicial) {
                var elem = this.ef('elemento').get_estado();
                this.controlador.ajax('buscar_encuestas', elem, this, this.cargar_encuesta);
            }
		    return false;
		}

        {$this->objeto_js}.cargar_encuesta = function(datos)
		{
		    this.ef('encuesta').set_opciones_rs(datos);
		}

		{$this->objeto_js}.evt__encuesta__procesar = function(es_inicial)
		{
            
                var enc = this.ef('encuesta').get_estado();
                this.controlador.ajax('cambia_encuesta', enc, this, this.cargar_pregunta_filtro);
            
		    return false;
		}

		{$this->objeto_js}.cargar_pregunta_filtro = function(datos)
		{
            this.ef('pregunta_filtro').set_opciones_rs(datos);
			if (datos.length < 1) {
			     this.ef('respuesta').set_obligatorio(false);
			}
		}
		";
	}

}
?>