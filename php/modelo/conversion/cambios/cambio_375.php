<?php

require_once('cambio.php');

class cambio_375 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 375: Corrección de respuestas duplicadas en reporte por preguntas.';
	}
    
	function cambiar()
	{
		$archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/50_respuestas_completas_formulario_habilitado.sql';
		$this->ejecutar_archivo($archivo);
	}

}
?>