<?php
/**
 * @todo cambiar por namespace de alguna manera
 */
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_ml_formulario.php';


class ml_titulos extends bootstrap_ml_formulario
{
	function extender_objeto_js()
	{
		parent::extender_objeto_js();
		$mensaje_control_anio = $this->get_mensaje('control_anio_titulo');
		$mensaje_control_repetidos = $this->get_mensaje('control_repetidos', array('Títulos'));
		
		echo "
		
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__fecha__procesar = function(es_inicial)
		{
			var fila = this.ef('fecha').get_fila_actual();
			
			if (!es_inicial && !this.ef('anio').ir_a_fila(fila).tiene_estado()) {
				var fecha_completa = this.ef('fecha').valor();
				var anio = fecha_completa.substr(6);
				this.ef('anio').ir_a_fila(fila).set_estado(anio);
			}
		}
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__validar_datos = function()
		{
			var filas = this.filas();
			var estados = new Array();
			
			for (id_fila in filas) {
				var cant = estados.push(this.ef('titulo').ir_a_fila(filas[id_fila]).get_estado());
			}
		
			for (i = 0; i < cant; i++) {
				for (j = i + 1; j < cant; j++) {
 					if (estados[i] == estados[j]) {
 						alert('$mensaje_control_repetidos');
 						return false;
 					}
				}
			}
			
			return true;
		}
		
		{$this->objeto_js}.evt__anio__validar = function(fila)
		{
			var fecha = this.ef('fecha').ir_a_fila(fila).valor();
			var anio = this.ef('anio').ir_a_fila(fila).valor();
			
			if (anio < fecha.substr(6)) {
				this.ef('anio').ir_a_fila(fila).set_error('$mensaje_control_anio');
				return false;
			}
			
			return true;
		}
		
		";
	}

}
?>