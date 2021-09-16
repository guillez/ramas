<?php

require_once('cambio.php');

class cambio_364 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 364: Incorporación de unidades de gestión para manejo de perfiles de datos.';
	}
    
	function cambiar()
	{
        $sql = "CREATE TABLE sge_unidad_gestion
                (
                    unidad_gestion Varchar NOT NULL,
                    nombre Varchar NOT NULL
                );
                
                ALTER TABLE sge_unidad_gestion ADD CONSTRAINT pk_sge_unidad_gestion PRIMARY KEY (unidad_gestion);
             
                ALTER TABLE sge_unidad_gestion OWNER TO postgres;
                GRANT ALL ON TABLE sge_unidad_gestion TO postgres;
                
                ALTER TABLE sge_encuesta_atributo ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_encuesta_atributo_sge_unidad_gestion ON  sge_encuesta_atributo (unidad_gestion);
                
                ALTER TABLE sge_encuesta_atributo
                    ADD CONSTRAINT fk_sge_encuesta_atributo_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES sge_unidad_gestion (unidad_gestion);
                ";
        
        $this->ejecutar($sql);
	}

}
?>