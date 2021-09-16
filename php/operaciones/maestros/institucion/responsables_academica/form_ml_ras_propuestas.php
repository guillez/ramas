<?php
/**
 * @todo cambiar por namespace
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_ml_formulario.php';

class form_ml_ras_propuestas extends bootstrap_ml_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Validacion general ----------------------------------	
		{$this->objeto_js}.evt__validar_datos = function()
		{
			 var filas = this.filas()
			 var estados = new Array();
			 for (id_fila in filas) {
			 	var cant = estados.push(this.ef('propuesta').ir_a_fila(filas[id_fila]).get_estado());
			 }

			 for (i=0; i<cant; i++) {
				for (j=i+1; j<cant; j++) {
 					if (estados[i] == estados[j]){
 						alert('¡Atención! Existen propuestas repetidas.');
 						return false;
 					}				
			 	}
			 }
			return true;			 

		}		
		";
	}
}

?>