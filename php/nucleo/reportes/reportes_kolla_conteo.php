<?php
require_once 'reportes_kolla.php';

class reportes_kolla_conteo extends reportes_kolla {


    private $columnas_reporte_respuestas = array(
        'encuesta_nombre'   => 'Encuesta',
        'concepto_nombre'   => 'Concepto Evaluado',
        'elemento_nombre'   => 'Elemento evaluado',
        'elemento_externo'  => 'Código de origen del elemento',
        'bloque_nombre'     => 'Bloque',
        'pregunta'          => 'Código de Pregunta',
        'pregunta_nombre'   => 'Pregunta',
        'respuesta_codigo'  => 'Código de Respuesta',
        'respuesta_valor'   => 'Valor de Respuesta',
        'cantidad_elegidas' => 'Cantidad'
    );

    private $definicion_tabla_respuestas_habilitacion = " resultados ( 
                pregunta integer                        --1
                , pregunta_nombre character varying(4096)--2
                , respuesta_codigo integer              --3
                , respuesta_valor character varying     --4
                , cantidad_elegidas bigint              --5
                , concepto_externo character varying    --6
                , concepto_nombre text                  --7
                , elemento_externo character varying    --8
                , elemento_nombre text                  --9
                , encuesta_nombre character varying     --10
                , bloque_nombre character varying       --11
                , habilitacion integer                  --12
                , orden_encuesta integer                --13
                , orden_bloque smallint                 --14
                , orden_pregunta smallint               --15
                , orden_respuesta character varying     --16
                ) ";

    public function __construct($filtro, $pantalla=false)
    {
        parent::__construct($filtro, RESULTADOS_CONTEO_RESPUESTAS, $pantalla);
    }

    public function generar_reporte()
    {
        //Obtener datos de habilitacion
        $this->get_datos_habilitacion();
        $this->generar_reporte_respuestas_habilitacion();
    }

    //--------------------------------------------
    //-- METODOS PARA REPORTES DE HABILITACION ---
    //--------------------------------------------


    public function obtener_informacion_respuestas_habilitacion ($select, $order_by=null)
    {
        $order_by = (isset($order_by)) ? $order_by : '';

        $h = $this->filtro['habilitacion'];
        $formulario_habilitado = ($this->filtro['formulario_habilitado'] != '') ? $this->filtro['formulario_habilitado'] : 'null';
        $g = ($this->filtro['grupo'] != '') ? $this->filtro['grupo'] : 'null';
        $c = ($this->filtro['concepto'] != '') ? $this->filtro['concepto'] : 'null';
        $elto = ($this->filtro['elemento'] != '') ? $this->filtro['elemento'] : 'null';
        $enc = ($this->filtro['encuesta'] != '') ? $this->filtro['encuesta'] : 'null';
        $p = ($this->filtro['pregunta'] != '') ? $this->filtro['pregunta'] : 'null';
        $terminadas = ($this->filtro['terminadas'] != 'S' && $this->filtro['terminadas'] != 'N') ? 'null' : "'".$this->filtro['terminadas']."'";
        $desde = (isset($this->filtro['desde']) && $this->filtro['desde'] != '') ? kolla_db::quote($this->filtro['desde']) : 'null';
        $hasta = (isset($this->filtro['hasta']) && $this->filtro['hasta'] != '') ? kolla_db::quote($this->filtro['hasta']) : 'null';
        $enc_def = (isset($this->filtro['pregunta_filtro']) && ($this->filtro['pregunta_filtro'] != '')) ? $this->filtro['pregunta_filtro'] : 'null';
        $rta_codigo = (isset($this->filtro['respuesta']) && ($this->filtro['respuesta'] != '')) ? $this->filtro['respuesta'] : 'null';
        $filtro_preguntas = (isset($this->filtro['respondidas']) && $this->filtro['respondidas'] == 'R') ? " WHERE trim(respuesta_valor) != '' " : '';

        $sql = "
            SELECT
                $select 
            FROM
                resultados_habilitacion_conteo_respuestas($h,$formulario_habilitado,$g, $c, $elto, $enc, $p, $terminadas, $desde, $hasta, $enc_def, $rta_codigo)
                $this->definicion_tabla_respuestas_habilitacion
            $filtro_preguntas
            $order_by
        ;";

        kolla::logger()->debug("Consulta para obtener resultados: ");
        kolla::logger()->var_dump($sql);
        return kolla_db::consultar($sql);
    }

    public function generar_reporte_respuestas_habilitacion()
    {
        //crear headers
        $this->agregar_columnas_array($this->columnas_reporte_respuestas);

        //quitar las columnas que no se necesitan
        if ( !$this->filtro['codigos'] ) {
            $this->quitar_columna('respuesta_codigo');
        }

        if (!$this->tiene_conceptos)
        {
            $this->quitar_columna('concepto_nombre');
            $this->quitar_columna('elemento_nombre');
        }

        if (!$this->externa || !$this->filtro['codigos_externos']) {
            $this->quitar_columna('elemento_externo');
        }

        //obtener los formularios habilitados incluidos para particionar la generación del reporte
        $forms_habs = $this->filtro['formularios_habilitados'];

        //condiciones de consulta
        $select = " * ";
        $order_by = "order by elemento_externo, orden_encuesta, orden_bloque, "
            . "orden_pregunta, orden_respuesta";

        $datos = array();

        foreach ($forms_habs as $form_hab_id) {
            $this->filtro['formulario_habilitado'] = $form_hab_id['formulario_habilitado'];
            //Para el reporte con conteo de respuestas se fuerza la selección "solo respondidas"
            //Esto se hace porque este reporte por naturaleza no muestra preguntas sin respuesta,
            //sin embargo cuando la respuesta tiene espacios vacíos cargados puede ocurrir que se cuente como una rta
            $this->filtro['respondidas'] = 'R';
            $resultados = $this->obtener_informacion_respuestas_habilitacion($select, $order_by);

            if (sizeof($resultados) > 0) {
                $this->set_data($resultados);
                if (is_null($this->id_archivo)) {
                    $this->obtener_reporte_texto();
                } else {
                    $this->continuar_reporte_texto();
                }

                if ($this->sale_por_pantalla) {
                    $datos = array_merge($datos, $resultados);
                }
            }
        }

        $this->set_data($datos);
    }

}
?>