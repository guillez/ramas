<?php

class kolla_migracion_4_1_0 extends kolla_migracion
{
    function negocio__748()
    {
        $sql = "INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES      (16, 'fecha_calculo_anios', 'Tipo fecha con cálculo de años', 'A');
                
                ALTER TABLE sge_pregunta ADD COLUMN oculta character(1) NOT NULL DEFAULT 'N'::bpchar;
            
                CREATE  TABLE sge_pregunta_cascada
                (
                    pregunta_disparadora Integer NOT NULL,
                    pregunta_receptora Integer NOT NULL
                );
                
                ALTER TABLE sge_pregunta_cascada ADD CONSTRAINT pk_sge_pregunta_cascada PRIMARY KEY (pregunta_disparadora);
                
                ALTER TABLE sge_pregunta_cascada OWNER TO postgres;
                GRANT ALL ON TABLE sge_pregunta_cascada TO postgres;
                
                CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_disparadora ON  sge_pregunta_cascada (pregunta_disparadora);

                ALTER TABLE sge_pregunta_cascada 
                    ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_disparadora FOREIGN KEY (pregunta_disparadora) 
                    REFERENCES sge_pregunta (pregunta);
                    
                CREATE INDEX ifk_sge_pregunta_sge_pregunta_cascada_receptora ON  sge_pregunta_cascada (pregunta_receptora);

                ALTER TABLE sge_pregunta_cascada 
                    ADD CONSTRAINT fk_sge_pregunta_sge_pregunta_cascada_receptora FOREIGN KEY (pregunta_receptora) 
                    REFERENCES sge_pregunta (pregunta);
               ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__749()
    {
        //Inserción del nuevo tipo y creación de la tabla para los Códigos Postales
        $sql = "INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES      (18, 'localidad_y_cp', 'Localidad y código postal', 'E');
                
                INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo)
                VALUES      (19, 'combo_dinamico', 'Lista con valores dinámicos', 'C');
                ";
        
        $this->get_db()->ejecutar($sql);
        $dir = $this->get_dir_juegos_de_datos();
        $dir_ddl =  $this->get_dir_ddl();
        
        $archivos = array(
            $dir_ddl.'/20_Tablas/mug_cod_postales.sql',
            $dir_ddl.'/10_Secuencias/mug_cod_postales_seq.sql',
            $dir_ddl.'/50_SetVals/mug_cod_postales_setval.sql',
            $dir.'/mug/60_mug_cod_postales_datos.sql',
        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    function negocio__751()
    {
        $sql = "INSERT INTO sge_componente_pregunta (numero, componente, descripcion, tipo) 
                VALUES (17, 'combo_autocompletado', 'Combo (autocompletable)', 'C');
               ";
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__763()
    {
        $sql = "UPDATE kolla.sge_componente_pregunta
                SET descripcion='Combo (listado)'
                WHERE numero=3;
               ";
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__719()
    {
        /*
         * Se incorporan en este método cambios de tablas y datos que se solicitaron en otros
         * tickets/issues pero están relacionados a estas encuestas: 17335 (784) y 17333(779)
         */
        
        $this->mover_preguntas_dependientes_existentes();
        $dir = $this->get_dir_juegos_de_datos();
        $dir_ddl =  $this->get_dir_ddl();
        
        $archivos = array(
            $dir_ddl.'/20_Tablas/mdi_pueblo_originario.sql',
            $dir_ddl.'/20_Tablas/mdi_carrera.sql',
            $dir_ddl.'/20_Tablas/mdi_primario.sql',
            $dir_ddl.'/20_Tablas/mdi_secundario.sql',
            $dir_ddl.'/20_Tablas/mdi_secundario_titulo.sql',
            
            $dir.'/mdi/mdi_pueblo_originario_datos.sql',
            $dir.'/mdi/mdi_secundario_titulo_datos.sql',
            $dir.'/mdi/mdi_primario_datos.sql',
            $dir.'/mdi/mdi_secundario_datos.sql',
            $dir.'/desgranamiento_universitario/10_formulario_preinscripcion.sql',
            $dir.'/desgranamiento_universitario/20_finalizacion_curso_de_ingreso.sql',
            $dir.'/desgranamiento_universitario/30_finalizacion_primer_cuatrimestre.sql',
        );
      
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }
        
        $this->actualizar_secuencias_preguntas_dependientes();
    }

    function mover_preguntas_dependientes_existentes()
    {
        $id_pregunta_dependencia   = $this->get_secuencia_pregunta_dependencia();
        $id_dependencia_definicion = $this->get_secuencia_dependencia_definicion();
        
        if ($id_dependencia_definicion < 1000 && $id_pregunta_dependencia < 1000) {
            $id_inicial = 1000;
        } else {
            $id_inicial = $id_dependencia_definicion > $id_pregunta_dependencia ? $id_dependencia_definicion + 1 : $id_pregunta_dependencia + 1;
        }
        
        $sql = "CREATE TEMP TABLE IF NOT EXISTS _sge_pregunta_dependencia_definicion (dependencia_definicion integer, pregunta_dependencia integer, bloque integer, pregunta integer, condicion character varying, valor character varying, accion character varying, encuesta_definicion integer);
                
                INSERT INTO _sge_pregunta_dependencia_definicion SELECT * FROM sge_pregunta_dependencia_definicion;
                UPDATE _sge_pregunta_dependencia_definicion SET dependencia_definicion = dependencia_definicion + $id_inicial, pregunta_dependencia = pregunta_dependencia + $id_inicial;
                DELETE FROM sge_pregunta_dependencia_definicion;

                CREATE TEMP TABLE IF NOT EXISTS _sge_pregunta_dependencia (pregunta_dependencia integer, encuesta_definicion integer);

                INSERT INTO _sge_pregunta_dependencia SELECT * FROM sge_pregunta_dependencia;
                UPDATE _sge_pregunta_dependencia SET pregunta_dependencia = pregunta_dependencia + $id_inicial;
                DELETE FROM sge_pregunta_dependencia;

                INSERT INTO sge_pregunta_dependencia SELECT * FROM _sge_pregunta_dependencia;
                INSERT INTO sge_pregunta_dependencia_definicion SELECT * FROM _sge_pregunta_dependencia_definicion;
                ";

        $this->get_db()->ejecutar($sql);
    }
    
    function get_secuencia_pregunta_dependencia()
	{
		$sql = "SELECT nextval('sge_pregunta_dependencia_seq'::regclass) AS seq";
		$res = $this->get_db()->consultar_fila($sql);
		return $res['seq'];
	}
    
    function get_secuencia_dependencia_definicion()
	{
		$sql = "SELECT nextval('sge_pregunta_dependencia_definicion_seq'::regclass) AS seq";
        $res = $this->get_db()->consultar_fila($sql);
		return $res['seq'];
	}
    
    function actualizar_secuencias_preguntas_dependientes()
    {
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'/50_SetVals/sge_pregunta_dependencia_setval.sql',
            $dir.'/50_SetVals/sge_pregunta_dependencia_definicion_setval.sql',
        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    function negocio__17537() 
    {
        $sql = "INSERT INTO sge_parametro_configuracion(seccion, parametro, valor)
                VALUES ('REPORTES', 'limite_opciones_respuesta_multiple', 10);
                ";
        $this->get_db()->ejecutar($sql);
        
        $dir = $this->get_dir_ddl();
        
        $archivos = array(
            $dir.'/80_Procesos/40_preguntas_formulario_habilitado.sql',
            $dir.'/80_Procesos/100_preguntas_habilitacion.sql',
        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
        
    function negocio__17609() 
    {
        $sql = "INSERT INTO sge_parametro_configuracion(seccion, parametro, valor)
                    VALUES ('RESPUESTAS', 'limite_opciones_respuesta_enlinea', 15);
                    ";
        $this->get_db()->ejecutar($sql);

        $sql = "INSERT INTO sge_parametro_configuracion(seccion, parametro, valor)
                    VALUES ('RESPUESTAS', 'limite_tamaño_opciones_respuesta_enlinea', 60);
                    ";
        $this->get_db()->ejecutar($sql);

        $sql = "INSERT INTO sge_parametro_configuracion(seccion, parametro, valor)
                    VALUES ('RESPUESTAS', 'limite_opciones_respuesta_impresas', 100);
                    ";
        $this->get_db()->ejecutar($sql);
    }
    
}