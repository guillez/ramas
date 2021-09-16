<?php

require_once('cambio.php');

class cambio_323 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 323: Tabla para logs de formularios habilitados';
	}

	function cambiar()
	{
		//parent::ejecutar($sql, get_class() . " - ejecutar()");
        $dir = $this->path_proyecto . '/sql/ddl/';
		
		$sqls = array (
			$dir.'20_Tablas/sge_log_formulario_definicion_habilitacion.sql',
            $dir.'70_Permisos/grant_sge_log_formulario_definicion_habilitacion.sql'
		);
        
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}

?>