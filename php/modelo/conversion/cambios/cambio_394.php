<?php

require_once('cambio.php');

class cambio_394 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 394 : Se dan de alta los procesos de la base que pueden haber quedado sin crear';
    }
    
	function cambiar()
	{
		$ddl = $this->path_proyecto . '/sql/ddl/';
		
		$procesos = array_merge(
            $this->get_sqls_de_directorio($ddl.'80_Procesos')
        );
		
		foreach ($procesos as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}


} 
