<?php

require_once('cambio.php');

class cambio_397 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 397 : Nueva columna formulario_habilitado_externo para sge_formulario_habilitado';
    }
    
	function cambiar()
	{
        $sql = "ALTER TABLE kolla.sge_formulario_habilitado
                ADD COLUMN  formulario_habilitado_externo character varying(100);";
        
        $this->ejecutar($sql);
	}

}