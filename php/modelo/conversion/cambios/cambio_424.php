<?php

require_once('cambio.php');

class cambio_424 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 424: Fecha fin en encuestas no terminadas';
    }
    
    function cambiar()
    {
        $procesos = $this->path_proyecto . '/sql/ddl/80_Procesos/';
		//se corrigieron dos consultas para reportes
		$procesos[] = $procesos.'50_respuestas_completas_formulario_habilitado.sql';
        $procesos[] = $procesos.'110_respuestas_completas_habilitacion.sql';
        $procesos[] = $procesos.'120_respuestas_completas_habilitacion_conteo.sql';
        
		foreach ($procesos as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
    }

} 