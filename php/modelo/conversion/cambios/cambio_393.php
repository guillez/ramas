<?php

require_once('cambio.php');

class cambio_393 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 393 : Identificacion externa para tipos de elementos';
    }
    
	function cambiar()
	{
        //LA COLUMNA tipo_elemento_externo puede existir en algunas pocas instalaciones de 3.5.0
        $esquema = 'kolla';
        
        $existe = $this->existe_columna($esquema, 'sge_tipo_elemento', 'tipo_elemento_externo');
        if (!$existe) {
            $sql = "ALTER TABLE sge_tipo_elemento 
                    ADD COLUMN tipo_elemento_externo Varchar (100);";    
            $this->ejecutar($sql);
        }		
        
        //LA COLUMNA unidad_gestion puede existir en algunas pocas instalaciones de 3.5.0
        $existe = $this->existe_columna($esquema, 'sge_tipo_elemento', 'unidad_gestion');
        if (!$existe) {
            $sql = "ALTER TABLE sge_tipo_elemento 
                    ADD COLUMN  unidad_gestion Varchar";
            $this->ejecutar($sql);
            
            $sql = "CREATE INDEX ifk_sge_tipo_elemento_sge_unidad_gestion 
                        ON  sge_tipo_elemento (unidad_gestion)";
            $this->ejecutar($sql);

            $sql = "ALTER TABLE sge_tipo_elemento 
                ADD CONSTRAINT fk_sge_tipo_elemento_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                REFERENCES sge_unidad_gestion (unidad_gestion)";
            
            $this->ejecutar($sql);
        }
        
        //COLUMNA sistema
        $existe = $this->existe_columna($esquema, 'sge_tipo_elemento', 'sistema');
        if (!$existe) {
	        $sql = "ALTER TABLE sge_tipo_elemento 
	                ADD COLUMN sistema Integer ;";
	        $this->ejecutar($sql, get_class() . " - ejecutar() ".$sql);            
	
	        $sql = "CREATE INDEX ifk_sge_tipo_elemento_sge_sistema_externo 
	                    ON  sge_tipo_elemento (sistema);";
	        $this->ejecutar($sql, get_class() . " - ejecutar() ".$sql);
	
	        $sql = "ALTER TABLE sge_tipo_elemento 
	                ADD CONSTRAINT fk_sge_tipo_elemento_sge_sistema_externo FOREIGN KEY (sistema) 
	                REFERENCES sge_sistema_externo (sistema);";
	        $this->ejecutar($sql, get_class() . " - ejecutar() ".$sql);
        }
	}

} 
