<?php

require_once('cambio.php');

class cambio_330 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 330: Cambios en el modelo';
	}

	function cambiar()
	{
        $dir = $this->path_proyecto . '/sql/';
		
		$cambios = array (
            $dir.'cambios/3.4.1/int_ingenieria_relevamiento_mods.sql',
			$dir.'cambios/3.4.1/drop_fks.sql'
		);
        
        $sqls = array_merge (
                $cambios,
                $this->get_sqls_de_directorio($dir.'ddl/60_FK')
        );
        
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}

}

?>