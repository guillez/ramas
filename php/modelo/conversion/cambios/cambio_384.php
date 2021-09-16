<?php

require_once('cambio.php');

class cambio_384 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 384: Corregir referencias a columnas en función para reportes  .';
	}
    
	function cambiar()
	{
        $dir = $this->path_proyecto . '/sql/ddl/80_Procesos/';
        
        $sqls = array (
            $dir.'40_preguntas_formulario_habilitado.sql',
            $dir.'50_respuestas_completas_formulario_habilitado.sql'            
		);

		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}