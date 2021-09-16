<?php

require_once('cambio.php');


/**
 * Definición de una encuesta al nuevo esquema.
 *
 * Adaptaciones de Habilitaciones de formularios y respuestas.
 */
class cambio_256 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 256: Migración de Formularios y Encuestas desde 3.3.0';
	}

	function cambiar()
	{
		$mig = $this->path_proyecto . '/sql/cambios/3.4.0/';

		$migraciones = array($mig.'modelo_forms_y_encuestas/migracion_forms_3_3.sql');
		
		foreach ($migraciones as $archivo) {
			$this->ejecutar_archivo($archivo);
		}		
	}

}

?>