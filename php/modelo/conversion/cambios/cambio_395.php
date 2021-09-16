<?php

require_once('cambio.php');

class cambio_395 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 395 : Unidad de gestión en datos importados';
    }
    
	function cambiar()
	{
        $sqls = array();
        $sqls[] = "ALTER TABLE mgi_titulo ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_mgi_titulo_sge_unidad_gestion ON  mgi_titulo (unidad_gestion)";
        $sqls[] = "ALTER TABLE mgi_titulo 
                    ADD CONSTRAINT fk_mgi_titulo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";
        
        $sqls[] = "ALTER TABLE int_guarani_titulos ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_int_guarani_titulos_sge_unidad_gestion ON  int_guarani_titulos (unidad_gestion)";
        $sqls[] = "ALTER TABLE int_guarani_titulos
					ADD CONSTRAINT fk_int_guarani_titulos_sge_unidad_gestion FOREIGN KEY (unidad_gestion)
					REFERENCES sge_unidad_gestion (unidad_gestion)";        
        
        $sqls[] = "ALTER TABLE mgi_propuesta ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_mgi_propuesta_sge_unidad_gestion ON  mgi_propuesta (unidad_gestion)";
        $sqls[] = "ALTER TABLE mgi_propuesta 
                    ADD CONSTRAINT fk_mgi_propuesta_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";

        $sqls[] = "ALTER TABLE int_guarani_carrera ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_int_guarani_carrera_sge_unidad_gestion ON  int_guarani_carrera (unidad_gestion)";
        $sqls[] = "ALTER TABLE int_guarani_carrera 
                    ADD CONSTRAINT fk_int_guarani_carrera_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";
        
        $sqls[] = "ALTER TABLE mgi_responsable_academica ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_mgi_responsable_academica_sge_unidad_gestion ON  mgi_responsable_academica (unidad_gestion)";
        $sqls[] = "ALTER TABLE mgi_responsable_academica 
                    ADD CONSTRAINT fk_mgi_responsable_academica_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";

        $sqls[] = "ALTER TABLE int_guarani_ra ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_int_guarani_ra_sge_unidad_gestion ON  int_guarani_ra (unidad_gestion)";
        $sqls[] = "ALTER TABLE int_guarani_ra 
                    ADD CONSTRAINT fk_int_guarani_ra_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";
        
        $sqls[] = "ALTER TABLE int_guarani_persona ADD COLUMN unidad_gestion Varchar (100)";
        $sqls[] = "CREATE INDEX ifk_int_guarani_persona_sge_unidad_gestion ON  int_guarani_persona (unidad_gestion)";
        $sqls[] = "ALTER TABLE int_guarani_persona 
                    ADD CONSTRAINT fk_int_guarani_persona_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion)";        
        
        $this->ejecutar($sqls);
	}

} 
