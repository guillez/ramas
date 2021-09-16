<?php

class datos_encuesta
{
    public $encuesta;
    public $sufijo_tabla_asociada;

	function __construct($encuesta = null, $nombre_tabla_asociada = null)
	{
        $this->encuesta = kolla_db::quote($encuesta);
        $this->sufijo_tabla_asociada = $nombre_tabla_asociada;
	}
    
    public function crear_esquema()
    {
        $creacion  = 'DROP SCHEMA IF EXISTS kolla_temporal CASCADE'.$this->end_statement().$this->line_break();
        $creacion .= 'CREATE SCHEMA kolla_temporal AUTHORIZATION postgres'.$this->end_statement().$this->line_break();
        $creacion .= $this->setear_esquema('kolla_temporal').$this->line_break();
        
        return $creacion;
    }
    
    public function eliminar_esquema()
    {
        return 'DROP SCHEMA kolla_temporal CASCADE'.$this->end_statement();
    }
    
    public function setear_esquema($esquema)
    {
        return "SET search_path TO $esquema".$this->end_statement().$this->line_break();
    }
    
    public function crear_tabla_sge_componente_pregunta()
    {
        $sql = "CREATE TABLE sge_componente_pregunta
                (
                    numero integer NOT NULL DEFAULT nextval(('sge_componente_pregunta_seq'::text)::regclass),
                    componente character varying(35) NOT NULL,
                    descripcion character varying(255),
                    tipo character(1) NOT NULL
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_componente_pregunta OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_componente_pregunta TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_pregunta()
    {
        $sql = "CREATE TABLE sge_pregunta
                (
                    pregunta integer NOT NULL DEFAULT nextval(('sge_pregunta_seq'::text)::regclass),
                    nombre character varying(4096) NOT NULL,
                    componente_numero integer NOT NULL,
                    tabla_asociada character varying(100),
                    tabla_asociada_codigo character varying(50),
                    tabla_asociada_descripcion character varying(50),
                    tabla_asociada_orden_campo character varying(50),
                    tabla_asociada_orden_tipo character(4),
                    unidad_gestion character varying,
                    descripcion_resumida character varying(30) NOT NULL,
                    ayuda character varying,
                    oculta character varying(1) NOT NULL DEFAULT 'N'::character varying,
                    visualizacion_horizontal character(1) NOT NULL DEFAULT 'N'::bpchar
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_pregunta OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_pregunta TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_respuesta()
    {
        $sql = "CREATE TABLE sge_respuesta
                (
                    respuesta integer NOT NULL DEFAULT nextval(('sge_respuesta_seq'::text)::regclass),
                    valor_tabulado character varying(255) NOT NULL,
                    unidad_gestion character varying
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_respuesta OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_respuesta TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_pregunta_respuesta()
    {
        $sql = "CREATE TABLE sge_pregunta_respuesta
                (
                    respuesta integer NOT NULL,
                    pregunta integer NOT NULL,
                    orden smallint NOT NULL
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_pregunta_respuesta OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_pregunta_respuesta TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_bloque()
    {
        $sql = "CREATE TABLE sge_bloque
                (
                    bloque integer NOT NULL DEFAULT nextval(('sge_bloque_seq'::text)::regclass),
                    nombre character varying(255) NOT NULL,
                    descripcion character varying(255),
                    orden smallint NOT NULL
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_bloque OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_bloque TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_encuesta_atributo()
    {
        $sql = "CREATE TABLE sge_encuesta_atributo
                (
                    encuesta integer NOT NULL DEFAULT nextval(('sge_encuesta_atributo_seq'::text)::regclass),
                    nombre character varying NOT NULL,
                    descripcion character varying,
                    texto_preliminar text,
                    implementada character(1) NOT NULL,
                    estado character(1) NOT NULL,
                    unidad_gestion character varying
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_encuesta_atributo OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_encuesta_atributo TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_encuesta_definicion()
    {
        $sql = "CREATE TABLE sge_encuesta_definicion
                (
                    encuesta_definicion integer NOT NULL DEFAULT nextval(('sge_encuesta_definicion_seq'::text)::regclass),
                    encuesta integer NOT NULL,
                    bloque integer NOT NULL,
                    pregunta integer NOT NULL,
                    orden smallint NOT NULL,
                    obligatoria character(1) NOT NULL DEFAULT 'N'::bpchar
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_encuesta_definicion OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_encuesta_definicion TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_pregunta_dependencia()
    {
        $sql = "CREATE TABLE sge_pregunta_dependencia
                (
                    pregunta_dependencia integer NOT NULL DEFAULT nextval(('sge_pregunta_dependencia_seq'::text)::regclass),
                    encuesta_definicion integer NOT NULL
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_pregunta_dependencia OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_pregunta_dependencia TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_pregunta_dependencia_definicion()
    {
        $sql = "CREATE TABLE sge_pregunta_dependencia_definicion
                (
                    dependencia_definicion integer NOT NULL DEFAULT nextval(('sge_pregunta_dependencia_definicion_seq'::text)::regclass),
                    pregunta_dependencia integer NOT NULL,
                    bloque integer NOT NULL,
                    pregunta integer,
                    condicion character varying NOT NULL,
                    valor character varying,
                    accion character varying NOT NULL,
                    encuesta_definicion integer
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_pregunta_dependencia_definicion OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_pregunta_dependencia_definicion TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_tabla_sge_pregunta_cascada()
    {
        $sql = "CREATE TABLE sge_pregunta_cascada
                (
                    pregunta_disparadora integer NOT NULL,
                    pregunta_receptora integer NOT NULL
                )
                WITH
                (
                    OIDS=FALSE
                )".$this->end_statement()."
                ALTER TABLE sge_pregunta_cascada OWNER TO postgres".$this->end_statement()."
                GRANT ALL ON TABLE sge_pregunta_cascada TO postgres".$this->end_statement();
        
        return $sql.$this->line_break();
    }
    
    public function crear_estructura_tablas_asociadas()
    {
        $creaciones = $this->setear_esquema('kolla');
        
        //Se obtienen aquellas preguntas que tienen tablas asociadas
        $sql = "SELECT	sge_pregunta.tabla_asociada,
                        sge_pregunta.tabla_asociada_codigo,
                        sge_pregunta.tabla_asociada_descripcion,
                        sge_pregunta.tabla_asociada_orden_campo,
                        sge_pregunta.tabla_asociada_orden_tipo
                FROM	sge_pregunta
                WHERE	sge_pregunta.tabla_asociada LIKE 'ta_%'
                AND     sge_pregunta.pregunta IN (
                        SELECT	sge_encuesta_definicion.pregunta
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$preguntas = kolla_db::consultar($sql);
        
        foreach ($preguntas as $pregunta) {
            
            //Creación de la tabla asociada
            $sql_definicion = " SELECT  column_name,
                                        column_default,
                                        data_type,
                                        is_nullable
                                FROM    Information_Schema.Columns
                                WHERE   TABLE_NAME = '".$pregunta['tabla_asociada']."'
                                ";
            
            $definicion_tabla = kolla_db::consultar($sql_definicion);
            $creaciones .= " CREATE TABLE IF NOT EXISTS ".$pregunta['tabla_asociada'].'_'.$this->sufijo_tabla_asociada." (";
            $columnas = '';
            
            foreach ($definicion_tabla as $definicion) {
                $is_nullable = $definicion['is_nullable'] == 'YES' ? '' : ' NOT NULL';
                $columnas .= $definicion['column_name'].' '.$definicion['data_type'].$is_nullable.','.$this->line_break();
            }
            
            $creaciones .= substr(rtrim($columnas), 0, -1)."
                        )
                        WITH
                        (
                            OIDS=FALSE
                        )".$this->end_statement()."
                        ALTER TABLE ".$pregunta['tabla_asociada'].'_'.$this->sufijo_tabla_asociada." OWNER TO postgres".$this->end_statement()."
                        GRANT ALL ON TABLE ".$pregunta['tabla_asociada'].'_'.$this->sufijo_tabla_asociada." TO postgres".$this->end_statement().$this->line_break();
            
            //Inserción de datos en la tabla asociada
            $sql = "SELECT  *
                    FROM    ".$pregunta['tabla_asociada'];

            $datos_tabla = kolla_db::consultar($sql);
            $inserciones = '';
                
            foreach ($datos_tabla as $registro) {
                $insert = kolla_sql::sql_array_a_insert_if_not_exists($pregunta['tabla_asociada'].'_'.$this->sufijo_tabla_asociada, $registro);
                $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
            }
            
            $creaciones .= $inserciones;
        }
        
        return $creaciones;
    }
    
    public function insertar_datos_sge_componente_pregunta()
    {
        $sql = "SELECT  sge_componente_pregunta.numero,
                        sge_componente_pregunta.componente,
                        sge_componente_pregunta.descripcion,
                        sge_componente_pregunta.tipo
                FROM    sge_componente_pregunta
				";
				
		$componentes_preguntas = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($componentes_preguntas as $componente_pregunta) {
            $insert = sql_array_a_insert('sge_componente_pregunta', $componente_pregunta);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_pregunta()
    {
        $sql = "SELECT	sge_pregunta.pregunta,
                        sge_pregunta.nombre,
                        sge_pregunta.componente_numero,
                        sge_pregunta.tabla_asociada,
                        sge_pregunta.tabla_asociada_codigo,
                        sge_pregunta.tabla_asociada_descripcion,
                        sge_pregunta.tabla_asociada_orden_campo,
                        sge_pregunta.tabla_asociada_orden_tipo,
                        sge_pregunta.descripcion_resumida,
                        sge_pregunta.ayuda,
                        sge_pregunta.oculta,
                        sge_pregunta.visualizacion_horizontal
                FROM	sge_pregunta
                WHERE	sge_pregunta.pregunta IN (
                        SELECT	sge_encuesta_definicion.pregunta
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
        
		$preguntas = kolla_db::consultar($sql);
        $inserciones = '';
        
        foreach ($preguntas as $pregunta) {
            if (strcmp(substr($pregunta['tabla_asociada'], 0, 3), 'ta_') === 0) {
                $pregunta['tabla_asociada'] = $pregunta['tabla_asociada'].'_'.$this->sufijo_tabla_asociada;
            }
            
            $insert = sql_array_a_insert('sge_pregunta', $pregunta);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_respuesta()
    {
        $sql = "SELECT	sge_respuesta.valor_tabulado,
                        sge_respuesta.respuesta
                FROM	sge_respuesta
                WHERE	sge_respuesta.respuesta IN (
                        SELECT	sge_pregunta_respuesta.respuesta
                        FROM	sge_pregunta_respuesta,
                                sge_encuesta_definicion
                        WHERE	sge_pregunta_respuesta.pregunta = sge_encuesta_definicion.pregunta
                        AND     sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$respuestas = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($respuestas as $respuesta) {
            $insert = sql_array_a_insert('sge_respuesta', $respuesta);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_pregunta_respuesta()
    {
        $sql = "SELECT  sge_pregunta_respuesta.pregunta,
                        sge_pregunta_respuesta.respuesta,
                        sge_pregunta_respuesta.orden
                FROM    sge_pregunta_respuesta
                WHERE   sge_pregunta_respuesta.pregunta IN (
                        SELECT	sge_encuesta_definicion.pregunta
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$preguntas_respuestas = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($preguntas_respuestas as $pregunta_respuesta) {
            $insert = sql_array_a_insert('sge_pregunta_respuesta', $pregunta_respuesta);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_bloque()
    {
        $sql = "SELECT	sge_bloque.nombre,
                        sge_bloque.descripcion,
                        sge_bloque.orden,
                        sge_bloque.bloque
                FROM	sge_bloque
                WHERE	sge_bloque.bloque IN (
                        SELECT	sge_encuesta_definicion.bloque
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$bloques = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($bloques as $bloque) {
            $insert = sql_array_a_insert('sge_bloque', $bloque);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_encuesta_atributo()
    {
        $sql = "SELECT	sge_encuesta_atributo.nombre,
                        sge_encuesta_atributo.descripcion,
                        sge_encuesta_atributo.texto_preliminar,
                        sge_encuesta_atributo.implementada,
                        sge_encuesta_atributo.estado,
                        sge_encuesta_atributo.encuesta
                FROM	sge_encuesta_atributo
                WHERE	sge_encuesta_atributo.encuesta = ".$this->encuesta;
				
		$encuestas = kolla_db::consultar($sql);
        $inserciones = '';
        
        foreach ($encuestas as $encuesta) {
            $insert = sql_array_a_insert('sge_encuesta_atributo', $encuesta);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_encuesta_definicion()
    {
        $sql = "SELECT	sge_encuesta_definicion.encuesta_definicion,
                        sge_encuesta_definicion.encuesta,
                        sge_encuesta_definicion.bloque,
                        sge_encuesta_definicion.pregunta,
                        sge_encuesta_definicion.orden,
                        sge_encuesta_definicion.obligatoria
                FROM	sge_encuesta_definicion
                WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta;
				
		$encuestas_def = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($encuestas_def as $encuesta_def) {
            $insert = sql_array_a_insert('sge_encuesta_definicion', $encuesta_def);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_pregunta_dependencia()
    {
        $sql = "SELECT	sge_pregunta_dependencia.pregunta_dependencia,
                        sge_pregunta_dependencia.encuesta_definicion
                FROM	sge_pregunta_dependencia
                WHERE	sge_pregunta_dependencia.encuesta_definicion IN (
                        SELECT	sge_encuesta_definicion.encuesta_definicion
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$preguntas_dependencias = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($preguntas_dependencias as $pregunta_dependencia) {
            $insert = sql_array_a_insert('sge_pregunta_dependencia', $pregunta_dependencia);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_pregunta_dependencia_definicion()
    {
        $sql = "SELECT	sge_pregunta_dependencia_definicion.dependencia_definicion,
                        sge_pregunta_dependencia_definicion.pregunta_dependencia,
                        sge_pregunta_dependencia_definicion.bloque,
                        sge_pregunta_dependencia_definicion.pregunta,
                        sge_pregunta_dependencia_definicion.condicion,
                        sge_pregunta_dependencia_definicion.valor,
                        sge_pregunta_dependencia_definicion.accion,
                        sge_pregunta_dependencia_definicion.encuesta_definicion
                FROM	sge_pregunta_dependencia_definicion
                            JOIN sge_pregunta_dependencia ON (sge_pregunta_dependencia_definicion.pregunta_dependencia = sge_pregunta_dependencia.pregunta_dependencia)
                WHERE	sge_pregunta_dependencia.encuesta_definicion IN (
                        SELECT	sge_encuesta_definicion.encuesta_definicion
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
                        ";
				
		$preguntas_dependencias_def = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($preguntas_dependencias_def as $pregunta_dependencia_def) {
            $insert = sql_array_a_insert('sge_pregunta_dependencia_definicion', $pregunta_dependencia_def);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    public function insertar_datos_sge_pregunta_cascada()
    {
        $sql = "SELECT  sge_pregunta_cascada.pregunta_disparadora,
                        sge_pregunta_cascada.pregunta_receptora
                FROM    sge_pregunta_cascada
                WHERE   sge_pregunta_cascada.pregunta_disparadora IN (
                        SELECT	sge_encuesta_definicion.pregunta
                        FROM	sge_encuesta_definicion
                        WHERE	sge_encuesta_definicion.encuesta = ".$this->encuesta.")
				";
				
		$preguntas_cascadas = kolla_db::consultar($sql);
        $inserciones = '';
                
        foreach ($preguntas_cascadas as $pregunta_cascada) {
            $insert = sql_array_a_insert('sge_pregunta_cascada', $pregunta_cascada);
            $inserciones .= substr($insert, 0, -1).$this->end_statement().$this->line_break();
        }
        
        return $inserciones;
    }
    
    private function line_break()
    {
        return "\n";
    }
    
    private function end_statement()
    {
        return '<END_STATEMENT>';
    }
    
}