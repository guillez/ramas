<?php

class kolla_migracion_3_7_0 extends kolla_migracion
{
    function negocio__525()
    {
        $sql = "ALTER TABLE mgn_mail DROP COLUMN IF EXISTS formulario_habilitado;
                
                DROP TABLE IF EXISTS mgn_mail_formulario_habilitado;
                CREATE TABLE mgn_mail_formulario_habilitado
                (
                    mail Integer NOT NULL,
                    formulario_habilitado Integer NOT NULL,
                    encuestado Integer NOT NULL
                );

                ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT pk_mgn_mail_formulario_habilitado PRIMARY KEY (mail,formulario_habilitado,encuestado);

                ALTER TABLE mgn_mail_formulario_habilitado OWNER TO postgres;
                GRANT ALL ON TABLE mgn_mail_formulario_habilitado TO postgres;
                
                CREATE INDEX ifk_mgn_mail_formulario_habilitado_mgn_mail ON  mgn_mail_formulario_habilitado (mail);
                ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_mgn_mail FOREIGN KEY (mail) REFERENCES mgn_mail (mail);
                
                CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_encuestado ON  mgn_mail_formulario_habilitado (encuestado);
                ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_encuestado FOREIGN KEY (encuestado) REFERENCES sge_encuestado (encuestado);
    
                CREATE INDEX ifk_mgn_mail_formulario_habilitado_sge_formulario_habilitado ON  mgn_mail_formulario_habilitado (formulario_habilitado);
                ALTER TABLE mgn_mail_formulario_habilitado ADD CONSTRAINT fk_mgn_mail_formulario_habilitado_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) REFERENCES sge_formulario_habilitado (formulario_habilitado);
            ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__495()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/40_preguntas_formulario_habilitado.sql',
            $dir.'80_Procesos/50_respuestas_completas_formulario_habilitado.sql',
            $dir.'80_Procesos/100_preguntas_habilitacion.sql',
            $dir.'80_Procesos/140_respuestas_completas_formulario_habilitado_conteo.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }    
    
    function negocio__534()
    {
        $sql = "ALTER TABLE int_persona         ADD COLUMN grupo integer;
                ALTER TABLE int_guarani_persona ADD COLUMN grupo integer;
                ";
        
        $this->get_db()->ejecutar($sql);
    }

    function negocio__536()
    {
        $sql = "CREATE INDEX ifk_sge_concepto_sge_sistema_externo ON  sge_concepto (sistema);
                ALTER TABLE sge_concepto
                    ADD CONSTRAINT fk_sge_concepto_sge_sistema_externo FOREIGN KEY (sistema)
                    REFERENCES sge_sistema_externo (sistema);
                
                CREATE INDEX ifk_sge_elemento_sge_sistema_externo ON  sge_elemento (sistema);
                ALTER TABLE sge_elemento 
                    ADD CONSTRAINT fk_sge_elemento_sge_sistema_externo FOREIGN KEY (sistema) 
                    REFERENCES sge_sistema_externo (sistema);
                ";
        
        $this->get_db()->ejecutar($sql);
    }

    function negocio__516()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/170_ws_resultados_de_encuesta_resumen.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }    
    
    function negocio__517()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/150_ws_resultados_de_encuesta_detalle.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }        
    
    function negocio__547()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/160_ws_encuesta_definicion.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    /*
     * Se modifica el tipo de la columna descripcion_resumida de la tabla de
     * preguntas. No es necesario setear el timing de chequeo de constraints
     * para la transacción actual debido a que la validación de constraints
     * se setea al final de cada sentencia de manera global de la migración.
     */
    function negocio__553()
    {
        $sql = "ALTER TABLE  sge_pregunta
                ALTER COLUMN descripcion_resumida TYPE varchar(30);
                ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__561()
    {
    	$this->negocio__517();
    }
    
    function negocio__563()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'80_Procesos/180_mover_encuesta_a_unidad_gestion.sql',
            $dir.'80_Procesos/190_copiar_encuesta_a_unidad_gestion.sql',
        );
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    function negocio__568()
    {
    	$dir = $this->get_dir_ddl();
    	
    	$archivos = array(
    			$dir.'80_Procesos/140_respuestas_completas_formulario_habilitado_conteo.sql',
    	);
    	
    	foreach ($archivos as $archivo) {
    		$this->get_db()->ejecutar_archivo($archivo);
    	}
    }
    
    function negocio__575()
    {
        $sql = "UPDATE int_guarani_persona
                    SET titulo_codigo=-1
                  WHERE titulo_codigo IS NULL;";
        $this->get_db()->ejecutar($sql);        
        
        $sql = "ALTER TABLE int_guarani_persona
                DROP CONSTRAINT pk_int_guarani_persona;";
        $this->get_db()->ejecutar($sql);        
        
        $sql = "ALTER TABLE int_guarani_persona
                ADD CONSTRAINT pk_int_guarani_persona PRIMARY KEY(fecha_proceso, usuario, titulo_codigo);";
        $this->get_db()->ejecutar($sql);
    }
}