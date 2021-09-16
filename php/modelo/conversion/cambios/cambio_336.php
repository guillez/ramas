<?php

require_once('cambio.php');

class cambio_336 extends cambio
{
	function get_descripcion()
	{
		return "Cambio 336: creación de tabla sge_respondido_por";
	}
    
	function cambiar()
	{       
        // Renombramos el schema para que quede apuntando al nuevo migrado
        $sql = "
            CREATE SEQUENCE sge_respondido_por_seq START 1;
            
            CREATE  TABLE sge_respondido_por
            (
                respondido_por INTEGER NOT NULL DEFAULT nextval('sge_respondido_por_seq'::text) ,
                respondido_formulario Integer NOT NULL,
                encuestado Integer NOT NULL
            );

            ALTER TABLE sge_respondido_por ADD CONSTRAINT pk_sge_respondido_por PRIMARY KEY (respondido_por);
            
            CREATE INDEX ifk_sge_respondido_por_sge_respondido_formulario ON  sge_respondido_por (respondido_formulario);
            CREATE INDEX ifk_sge_respondido_por_sge_encuestado ON  sge_respondido_por (encuestado);

            ALTER TABLE sge_respondido_por
                ADD CONSTRAINT fk_sge_respondido_por_sge_respondido_formulario FOREIGN KEY (respondido_formulario) 
                REFERENCES sge_respondido_formulario (respondido_formulario);

            ALTER TABLE sge_respondido_por
                ADD CONSTRAINT fk_sge_respondido_por_sge_encuestado FOREIGN KEY (encuestado) 
                REFERENCES sge_encuestado (encuestado);
        ";
        
        $this->ejecutar($sql);
        
        $archivo = $this->path_proyecto . '/sql/ddl/80_Procesos/50_respuestas_completas_formulario_habilitado.sql';
        
        $this->ejecutar_archivo($archivo);
	}
}

?>