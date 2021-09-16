<?php

class kolla_migracion_4_4_0 extends kolla_migracion
{
    function negocio__24881()
    {
        $sql_sre = "ALTER TABLE sge_reporte_exportado ADD grupo integer;
                    ALTER TABLE sge_reporte_exportado ADD concepto integer;
                    ALTER TABLE sge_reporte_exportado ADD habilitacion integer;
                    ALTER TABLE sge_reporte_exportado ADD pregunta integer;
                    ALTER TABLE sge_reporte_exportado ADD terminadas char(1);                    
                    ALTER TABLE sge_reporte_exportado ADD filtro_pregunta integer;
                    ALTER TABLE sge_reporte_exportado ADD filtro_pregunta_opcion_respuesta integer;
                    ALTER TABLE sge_reporte_exportado ALTER COLUMN inconclusas DROP NOT NULL;
                    ALTER TABLE sge_reporte_exportado ALTER COLUMN formulario_habilitado DROP NOT NULL;
                ";
        $this->get_db()->ejecutar($sql_sre);

        $sql_srt = "INSERT INTO sge_reporte_tipo(reporte_tipo, nombre, descripcion) 
                        VALUES (10,	'Resultados por encuestado', 'Resultados visualizados por encuestado que respondió');
                    INSERT INTO sge_reporte_tipo(reporte_tipo, nombre, descripcion) 
                        VALUES (11,	'Resultados por pregunta', 'Resultados visualizados por pregunta respondida');
                    INSERT INTO sge_reporte_tipo(reporte_tipo, nombre, descripcion) 
                        VALUES (12,	'Resultados con conteo de respuestas', 'Resultados visualizados por pregunta respondida con conteo de número de veces que se eligió cada respuesta.');";
        $this->get_db()->ejecutar($sql_srt);

        $dir = $this->get_dir_ddl();
        $archivos = array($dir.'80_Procesos/210_preguntas_con_respuestas_resultados_encuestado.sql',
                            $dir.'80_Procesos/220_resultados_habilitacion.sql',
                            $dir.'80_Procesos/230_resultados_habilitacion_conteo_respuestas.sql',
                            $dir.'80_Procesos/240_estimar_cantidad_resultados.sql',
                            $dir.'90_Vistas/10_resumen_estado_habilitacion.sql',
                            $dir.'90_Vistas/20_obtener_respondidos.sql'
                        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);
        }

        $sql_drops = "DROP FUNCTION preguntas_formulario_habilitado(int4);
                    DROP FUNCTION preguntas_habilitacion(int4);
                    DROP FUNCTION respuestas_completas_habilitacion(int4);
                    DROP FUNCTION respuestas_completas_habilitacion_conteo(int4,date,date);
                    DROP FUNCTION respuestas_completas_formulario_habilitado_conteo(int4,date,date);
                ";
        $this->get_db()->ejecutar($sql_drops);

    }

    function negocio__17974()
    {
        $this->crear_tabla_externa();
        $this->migrar_tablas_externas();
        $this->verificar_tablas_internas();
    }

    function crear_tabla_externa()
    {
        $sql = "CREATE SEQUENCE sge_tabla_externa_seq START 1;
        
                CREATE TABLE sge_tabla_externa
                (
                    tabla_externa integer NOT NULL DEFAULT nextval('sge_tabla_externa_seq'::text),
                    unidad_gestion character varying NOT NULL,
                    tabla_externa_nombre character varying NOT NULL,
                    CONSTRAINT pk_sge_tabla_externa PRIMARY KEY (tabla_externa),
                    CONSTRAINT fk_sge_tabla_externa_sge_unidad_gestion FOREIGN KEY (unidad_gestion)
                        REFERENCES sge_unidad_gestion (unidad_gestion) MATCH SIMPLE
                        ON UPDATE NO ACTION ON DELETE NO ACTION DEFERRABLE INITIALLY IMMEDIATE
                );
                
                ALTER TABLE sge_tabla_externa OWNER TO postgres;
                GRANT ALL ON TABLE sge_tabla_externa TO postgres;
                ";

        $this->get_db()->ejecutar($sql);
    }

    function migrar_tablas_externas()
    {
        $sql = "SELECT      sge_pregunta.tabla_asociada,
                            sge_pregunta.unidad_gestion
				FROM        sge_pregunta
                WHERE       sge_pregunta.tabla_asociada <> ''
                AND         sge_pregunta.tabla_asociada NOT LIKE 'ta_%'
                AND         sge_pregunta.tabla_asociada NOT LIKE 'sge_%'
                GROUP BY    sge_pregunta.tabla_asociada,
                            sge_pregunta.unidad_gestion";

        $tablas_externas = $this->get_db()->consultar($sql);

        foreach ($tablas_externas as $tabla) {
            $tabla_externa_nombre = $tabla['tabla_asociada'];
            $unidad_gestion = $tabla['unidad_gestion'];
            $sql = "INSERT INTO sge_tabla_externa (unidad_gestion, tabla_externa_nombre) VALUES ('$unidad_gestion', '$tabla_externa_nombre')";
            $this->get_db()->ejecutar($sql);
        }
    }

    /*
     * Si la instalación anterior contiene tablas internas de Kolla definidas
     * como respuestas de preguntas cerradas entonces se debe emitir un warning
     * por cada una de ellas.
     */
    function verificar_tablas_internas()
    {
        $sql = "SELECT  sge_pregunta.pregunta,
                        sge_pregunta.nombre,
                        sge_pregunta.tabla_asociada
				FROM    sge_pregunta
                WHERE   sge_pregunta.tabla_asociada LIKE 'sge_%'";

        $tablas_internas = $this->get_db()->consultar($sql);

        foreach ($tablas_internas as $tabla) {
            $mensaje = 'Aviso: No se generó la relación de la tabla "'.$tabla['tabla_asociada'].'" con la pregunta con ID '.$tabla['pregunta'].' y nombre "'.$tabla['nombre'].'"';
            $this->interface->mensaje($mensaje);
        }
    }
    
    function negocio__30657()
    {
        $dir = $this->get_dir_ddl();
        
        $archivo = $dir.'/80_Procesos/250_importar_encuesta_a_unidad_gestion.sql';

        $this->get_db()->ejecutar_archivo($archivo);
    }
    
    function negocio__30839()
    {
        $sql = "ALTER TABLE  sge_pregunta
                ALTER COLUMN tabla_asociada TYPE varchar(100);
                ";
        
        $this->get_db()->ejecutar($sql);
    }
    
    function negocio__30777()
    {
        $dir = $this->get_dir_ddl();
        
        $archivo = $dir.'/80_Procesos/190_copiar_encuesta_a_unidad_gestion.sql';

        $this->get_db()->ejecutar_archivo($archivo);
    }
    
    function negocio__32570()
    {
        $sql = "UPDATE  sge_pregunta
                SET     nombre = '¿A qué distancia en kilómetros vivís de la universidad?'
                WHERE   pregunta = 710;
                ";
        
        $this->get_db()->ejecutar($sql);
    }

}