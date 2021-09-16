<?php

/**
 * @todo Cambiar por namespace
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_ml_formulario.php';

class formulario_ml extends bootstrap_ml_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		$mensaje_filas_duplicadas = $this->get_mensaje('filas_duplicadas');
		
		echo "
		
		//---- Validacion general ----------------------------------
		
		/**
		 * 	Valida que no exista la misma fila repetida 2 veces (encuesta + tipo_elemento).
		 */
		{$this->objeto_js}.evt__validar_datos = function()
		{
			var filas = this.filas();
			var filas_repetidas = new Array();
			
			for (id_fila in filas) {
				encuesta	  = this.ef('encuesta').ir_a_fila(filas[id_fila]).get_estado();
				tipo_elemento = this.ef('tipo_elemento').ir_a_fila(filas[id_fila]).get_estado();
				
				//Si no existe encuesta + tipo_elemento lo agrego a la pila, sino emito mensaje de error
				if (filas_repetidas.indexOf(encuesta + '_' + tipo_elemento) != '-1') {
					notificacion.agregar('$mensaje_filas_duplicadas');
					return false;
				} else {
					filas_repetidas.push(encuesta + '_' + tipo_elemento);
				}
			}
			
			return true;
		}
		
		";
	}
}
?>