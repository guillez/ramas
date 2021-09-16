<?php

class datos {

    public $definicion_tabla_resultados = "resultados (
                    habilitacion integer,
                    formulario_habilitado integer,
                    formulario_nombre text,
                    respondido_formulario integer, 
                    ingreso integer, 
                    fecha_inicio date, 
                    terminado_formulario character(1), 
                    fecha_terminado date, 
                    respondido_encuesta integer, 
                    respondido_detalle integer, 
                    moderada character(1), 
                    respuesta_codigo integer, 
                    respuesta_valor character varying, 
                    encuesta_definicion integer,
                    encuesta integer, 
                    orden_encuesta integer, 
                    orden_bloque smallint, 
                    bloque integer, 
                    bloque_nombre character varying(255), 
                    orden_pregunta smallint, 
                    pregunta integer, 
                    pregunta_nombre character varying(4096), 
                    componente character varying(35),
                    tabla_asociada character varying(50), 
                    concepto integer, 
                    encuesta_nombre character varying, 
                    elemento integer, 
                    elemento_nombre text, 
                    respondido_encuestado integer,
                    encuestado integer,
                    usuario character varying(60),
                    respondido_por character varying(60),
                    ignorado char,
                    concepto_nombre text,
                    concepto_externo character varying(100),
                    elemento_externo character varying(100),
                    pregunta_tabla_codigo character varying(50),
                    pregunta_tabla_descripcion character varying(50),
                    numero integer,
                    respondido_por_encuestado integer,
                    codigo_columna character varying)";
    
    public $definicion_tabla_preguntas = "preguntas (
            habilitacion integer,
            formulario_habilitado integer,
            nombre text,
            formulario_habilitado_detalle integer,
            encuesta integer,
            elemento integer,
            encuesta_orden integer,
            encuesta_definicion integer,
            bloque integer,
            bloque_orden smallint,
            pregunta integer,
            pregunta_orden smallint,
            pregunta_nombre text, 
            componente_numero integer,
            componente character varying,
            opciones_multiples text,
            respuesta_codigo integer,
            valor_tabulado character varying,
            respuesta_orden smallint)";    
    
    public $columnas_reporte_pregunta = array(
            'encuesta_nombre' => 'Encuesta',
            'elemento_nombre' => 'Elemento evaluado',
            'bloque_nombre' => 'Bloque',
            'pregunta' => 'Código de Pregunta',
            'pregunta_nombre' => 'Pregunta',
            //'respuesta_codigo' => 'Código de Respuesta',
            'respuesta_valor' => 'Valor de Respuesta',
            'usuario' => 'Usuario'
   );
    
    /*
    public function get_definicion_tabla_resultados()
    {
        return $this->definicion_tabla_resultados;
    }
    
    public function get_definicion_tabla_preguntas()
    {
        return $this->definicion_tabla_preguntas;
    }
    
    public function get_array_columnas_reporte_pregunta()
    {
        return $this->columnas_reporte_pregunta;
    }
    
    
    public $definicion_tabla_resultados = "resultados (
            habilitacion integer,
            formulario_habilitado integer,
            formulario_nombre text,
            respondido_formulario integer, 
            ingreso integer, 
            fecha_inicio date, 
            terminado_formulario character(1), 
            fecha_terminado date, 
            respondido_encuesta integer, 
            respondido_detalle integer, 
            moderada character(1), 
            respuesta_codigo integer, 
            respuesta_valor character varying, 
            encuesta_definicion integer,
            encuesta integer, 
            orden_encuesta integer, 
            orden_bloque smallint, 
            bloque integer, 
            bloque_nombre character varying(255), 
            orden_pregunta smallint, 
            pregunta integer, 
            pregunta_nombre character varying(4096),
            componente character varying(35),
            tabla_asociada character varying(50), 
            concepto integer, 
            encuesta_nombre character varying, 
            elemento integer, 
            elemento_nombre text, 
            respondido_encuestado integer,
            encuestado integer,
            usuario character varying(60),
            ignorado char)";
    
    public $definicion_tabla_preguntas = "preguntas (
            habilitacion integer,
            formulario_habilitado  integer,
            nombre  text,
            formulario_habilitado_detalle  integer,
            encuesta  integer,
            elemento  integer,
            encuesta_orden  integer,
            bloque  integer,
            bloque_orden  smallint,
            pregunta  integer,
            pregunta_orden  smallint,
            pregunta_nombre text, 
            componente_numero  integer,
            componente  character varying,
            opciones_multiples  text,
            respuesta_codigo integer,
            valor_tabulado  character varying,
            respuesta_orden  smallint)"; 
*/    
}
?>
