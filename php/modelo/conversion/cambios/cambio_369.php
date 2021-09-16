<?php

require_once('cambio.php');

class cambio_369 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 369: Administración de respuestas por dependencia.';
	}
    
	function cambiar()
	{
        $sql = "ALTER TABLE sge_respuesta ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_respuesta_sge_unidad_gestion ON  sge_respuesta (unidad_gestion); 
                
                ALTER TABLE sge_respuesta  
                    ADD CONSTRAINT fk_sge_respuesta_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                
                ";
        
        $this->ejecutar($sql);
	}

}
?>