<?php
require_once 'reportes_kolla.php';

class reportes_kolla_encuestado extends reportes_kolla {

    //TABLAS DE RESULTADOS
    private $definicion_tabla_preguntas_con_rtas_habilitacion = "preguntas (
            codigo_columna character varying, --1
            habilitacion integer, --2
            encuesta integer, --3
            encuesta_orden integer, --4
            encuesta_definicion integer, --5
            bloque integer, --6
            bloque_orden smallint, --7
            pregunta integer, --8
            pregunta_orden smallint, --9
            pregunta_nombre text, --10
            componente_numero integer, --11
            componente character varying, --12
            opciones_multiples text, --13
            respuesta_codigo integer, --14
            valor_tabulado character varying, --15
            respuesta_orden integer, --16
            hay_elemento smallint, --17
            tabla_asociada text --28
            )";

    public function __construct($filtro, $pantalla=false)
    {
        parent::__construct($filtro, RESULTADOS_ENCUESTADO, $pantalla);
    }

    public function generar_reporte()
    {
        //Obtener datos de habilitacion
        $this->get_datos_habilitacion();
        $this->generar_reporte_encuestado();
    }

    //--------------------------------------------
    //-- METODOS PARA REPORTES DE HABILITACION ---
    //--------------------------------------------

    public function generar_reporte_encuestado()
    {
        // Crear headers
        $this->generar_columnas_reporte_encuestado_habilitacion($this->filtro);

        //obtener los formularios habilitados incluidos para particionar la generación del reporte
        $forms_habs = $this->filtro['formularios_habilitados'];
        $datos_acumulados_reporte = array();

        foreach ($forms_habs as $form_hab_id) {
            // Cargar las respuestas dadas a este formulario en el reporte
            $datos = array();
            $id_respuesta = -1;
            $encuesta = -1;
            $orden_encuesta = -1;
            $codigo_col_elemento = -1;

            $this->filtro['formulario_habilitado'] = $form_hab_id['formulario_habilitado'];
            $resultados = $this->obtener_respuestas_formulario_habilitado('*');

            foreach ($resultados as $rta) {

                if ($id_respuesta != $rta['respondido_formulario']) {//nuevo encabezado encontrado, se prepara una nueva linea
                    $id_respuesta = $rta['respondido_formulario'];
                    $datos[$id_respuesta][-1] = $rta['fecha_inicio'];
                    $datos[$id_respuesta][-2] = $rta['fecha_terminado'];
                    $datos[$id_respuesta]['usuario'] = $rta['usuario'];
                    //si corresponde, completar la columna respondida_por
                    if (isset($this->filtro['respondida_por']) && $this->filtro['respondida_por']) {
                        //si corresponde, completar la columna respondida_por
                        //if ($this->filtro['respondida_por']) {
                            $datos[$id_respuesta]['respondida_por'] = $rta['respondido_por'];
                        //}
                    }
                    //si corresponde, completar la columna terminada
                    if (isset($this->filtro['terminadas']) && ($this->filtro['terminadas']=='T')) {
                            $datos[$id_respuesta]['terminada'] = ($rta['terminado_formulario'] == 'S') ? 'Si' : 'No';
                    }
                    if ($rta['elemento'] != '') {
                        $datos[$id_respuesta]['concepto'] = $rta['concepto_nombre'];
                        if ($this->externa && $this->filtro['codigos_externos']) {
                            $datos[$id_respuesta]['concepto_codext'] = $rta['concepto_externo'];
                            if ($this->externa && $this->filtro['codigos_externos']) {
                                $datos[$id_respuesta]['concepto_codext'] = $rta['concepto_externo'];
                            }
                        }
                    }
                    $encuesta = -1;
                    $orden_encuesta = -1;
                }

                if (($rta['elemento'] != '') && ($rta['encuesta'] != $encuesta || $rta['orden_encuesta'] != $orden_encuesta)) {
                    $encuesta = $rta['encuesta'];
                    $orden_encuesta = $rta['orden_encuesta'];
                    $codigo_col_elemento = $rta['encuesta'] . '_' . $rta['orden_encuesta'] . '_elto';
                    $datos[$id_respuesta][$codigo_col_elemento] = $rta['elemento_nombre'];

                    if ($this->externa && $this->filtro['codigos_externos']) {
                        $codigo_col_elemento_ext = $rta['encuesta'] . '_' . $rta['orden_encuesta'] . '_eltoext';
                        $datos[$id_respuesta][$codigo_col_elemento_ext] = $rta['elemento_externo'];
                    }
                }

                //obtener codigo
                $codigo = $rta['codigo_columna'];

                //cargar el valor de respuesta
                $datos[$id_respuesta][$codigo] = $rta['respuesta_valor'];

                //si corresponde, completar la columna de código para cada pregunta
                if ($this->filtro['codigos']) {
                    $codigo .= '-cod';
                    $datos[$id_respuesta][$codigo] = $rta['respuesta_codigo'];
                }
            }

            if (count($datos) > 0) {
                $this->set_data($datos);
                if (is_null($this->id_archivo)) {
                    $this->obtener_reporte_texto();
                } else {
                    $this->continuar_reporte_texto();
                }
                if ($this->sale_por_pantalla) {
                    $datos_acumulados_reporte = array_merge($datos_acumulados_reporte, $datos);
                }
            }
        }
        $this->set_data($datos_acumulados_reporte);
    }

    public function generar_columnas_reporte_encuestado_habilitacion()
    {
        // COLUMNAS DE DATOS EXTRA
        // usuario -> columna precargada
        $this->agregar_columna('usuario', 'Usuario');
        //chequear si se desea agregar la columna de respondida por o no
        if ( isset($this->filtro['respondida_por']) && $this->filtro['respondida_por']) {
            $this->agregar_columna('respondida_por', 'Respondida por');
        }
        //si se pidieron todas las respuestas sin importar el grado de avance, agregamos una columna para informar el estado
        if ( isset($this->filtro['terminadas']) && ($this->filtro['terminadas'] == 'T')) {
            $this->agregar_columna('terminada', 'Finalizada');
        }

        //para completar el encabezado se debe obtener
        //las preguntas incluidas en el formulario
        $pregs_order_by = ' ORDER BY encuesta_orden, bloque_orden, pregunta_orden ';
        $preguntas=$this->obtener_preguntas_con_respuestas_habilitacion("*", $pregs_order_by);

        if (isset($preguntas) && isset($preguntas[0]) && ($preguntas[0]['hay_elemento'] == 1)) {
            $this->agregar_columna('concepto', 'Concepto evaluado');
            //si ademas es externa y se pide el código externo, agregar la columna necesaria
            $this->agregar_columna('concepto_codext', 'Código de origen del concepto');
        }

        $encuesta_actual = -1;
        $orden_actual = -1;
        //UNA COLUMNA POR CADA PREGUNTA DEL FORMULARIO
        foreach ($preguntas as $p) {
            //por cada instancia de encuesta agregar la columna que describe el elemento evaluado
            if ($p['hay_elemento'] == 1 && ($p['encuesta'] != $encuesta_actual
                    || $p['encuesta_orden'] != $orden_actual) ){
                $encuesta_actual = $p['encuesta'];
                $orden_actual = $p['encuesta_orden'];
                $codigo = $p['encuesta'].'_'.$p['encuesta_orden'].'_elto';
                $etiqueta = 'Elemento evaluado';
                $this->agregar_columna($codigo, $etiqueta);

                if ($this->externa && $this->filtro['codigos_externos']) {
                    $codigo = $p['encuesta'].'_'.$p['encuesta_orden'].'_eltoext';
                    $etiqueta = 'Código de origen del elemento';
                    $this->agregar_columna($codigo, $etiqueta);
                }

            }

            $etiqueta   = $p['pregunta_nombre'];
            $codigo     = $p['codigo_columna'];
            $this->agregar_columna($codigo, $etiqueta);

            //SI CORRESPONDE, AGREGAR COLUMNA DE CODIGO
            if ( $this->filtro['codigos'] ) {
                $codigo .= '-cod';
                $this->agregar_columna($codigo, "Código - ".$etiqueta);
            }
        }

        //COLUMNAS DE DATOS EXTRA
        $this->agregar_columna(-1, 'Fecha de inicio');
        $this->agregar_columna(-2, 'Fecha fin');
        //$hdrs = $this->get_headers();
    }


    public function obtener_preguntas_con_respuestas_habilitacion($select, $order_by=null)
    {
        $habilitacion = $this->filtro['habilitacion'];
        $formulario_habilitado = ($this->filtro['formulario_habilitado'] != '') ? $this->filtro['formulario_habilitado'] : 'null';
        $g = ($this->filtro['grupo'] != '') ? $this->filtro['grupo'] : 'null';
        $c = ($this->filtro['concepto'] != '') ? $this->filtro['concepto'] : 'null';
        $elto = ($this->filtro['elemento'] != '') ? $this->filtro['elemento'] : 'null';
        $enc = ($this->filtro['encuesta'] != '') ? $this->filtro['encuesta'] : 'null';
        $p = ($this->filtro['pregunta'] != '') ? $this->filtro['pregunta'] : 'null';
        $filtro_preguntas = (isset($this->filtro['respondidas']) && $this->filtro['respondidas'] == 'R') ? 'true' : 'false';
        $filtro_respuestas = (isset($this->filtro['opciones']) && $this->filtro['opciones'] == 'E') ? 'true' : 'false';

        $order_by = (isset($order_by)) ? $order_by : ' order by encuesta_orden';

        $sql = "SELECT $select 
                    FROM preguntas_con_respuestas_resultados_encuestado($habilitacion, $formulario_habilitado, $g, $c, $elto, $enc, $p, $filtro_preguntas, $filtro_respuestas) 
                $this->definicion_tabla_preguntas_con_rtas_habilitacion
                ".$order_by.";";

        kolla::logger()->debug("Consulta para armar encabezado: ");
        kolla::logger()->var_dump($sql);
        return consultar_fuente($sql);
    }

}
?>