<?php

require_once('cambio.php');

class cambio_368 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 3xx: Filtrado de preguntas y respuestas por unidad de gestión.';
	}
    
	function cambiar()
	{
        $sql = "ALTER TABLE sge_pregunta ADD COLUMN unidad_gestion Varchar;
            
                CREATE INDEX ifk_sge_pregunta_sge_unidad_gestion ON  sge_pregunta (unidad_gestion); 
                
                ALTER TABLE sge_pregunta 
                    ADD CONSTRAINT fk_sge_pregunta_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                ";
        
        $this->ejecutar($sql);
	}

}
?>
