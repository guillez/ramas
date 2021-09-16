<?php

require_once('cambio.php');

/**
 * Cambio para definir los usuarios/encuestados y grupos.
 */

class cambio_334 extends cambio
{
	function get_descripcion()
	{
		return "Cambio 334: Inserción de datos iniciales del sistema";
	}

	function cambiar()
	{
        /*
        ----------------------------------------------------------------
        -- Se insertan datos base del sistema que quedan pendientes
        ----------------------------------------------------------------
       */
        $dir = $this->path_proyecto . '/sql/datos/';
        
        $sqls = array (
            $dir.'base/arau_instituciones_datos.sql',
            $dir.'base/arau_responsables_academicas_datos.sql',
            $dir.'base/arau_titulos_datos.sql',
            $dir.'juegos_de_datos/mug/10_mug_continentes_datos.sql',
            $dir.'juegos_de_datos/mug/20_mug_paises_datos.sql',
            $dir.'juegos_de_datos/mug/30_mug_provincias_datos.sql',
            $dir.'juegos_de_datos/mug/40_mug_dptos_partidos_datos.sql',
            $dir.'juegos_de_datos/mug/50_mug_localidades_datos.sql',
			$dir.'base/sge_componente_pregunta_datos.sql',
            $dir.'base/sge_documento_tipo_datos.sql',
            $dir.'base/sge_encuesta_estilo_datos.sql',
            $dir.'base/sge_reporte_tipo_datos.sql'
		);
        
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}
?>