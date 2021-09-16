<?php
require_once 'reportes_kolla.php';

class reportes_kolla_pregunta extends reportes_kolla {

    private $columnas_reporte_pregunta = array(
        'encuesta_nombre'   => 'Encuesta',
        'concepto_nombre'   => 'Concepto Evaluado',
        'elemento_nombre'   => 'Elemento evaluado',
        'elemento_externo'  => 'Código de origen del elemento',
        'bloque_nombre'     => 'Bloque',
        'pregunta'          => 'Código de Pregunta',
        'pregunta_nombre'   => 'Pregunta',
        'respuesta_valor'   => 'Valor de Respuesta',
        'usuario'           => 'Usuario',
        'respondido_por'    => 'Respondido por'
    );

    public function __construct($filtro, $pantalla=false)
    {
        parent::__construct($filtro, RESULTADOS_PREGUNTA, $pantalla);
    }

    public function generar_reporte()
    {
        //Obtener datos de habilitacion
        $this->get_datos_habilitacion();
        $this->generar_reporte_pregunta_habilitacion();
    }

    //--------------------------------------------
    //-- METODOS PARA REPORTES DE HABILITACION ---
    //--------------------------------------------

    public function generar_reporte_pregunta_habilitacion()
    {
        $this->agregar_columnas_array($this->columnas_reporte_pregunta);

        if ( $this->filtro['codigos'] )
        {
            $this->agregar_columna('respuesta_codigo', "Código de respuesta");
        }

        /*
        if ($this->tiene_conceptos)
        {
            $this->agregar_columna('concepto_nombre', 'Concepto Evaluado');
            $this->agregar_columna('elemento_nombre', 'Elemento evaluado');
            if ($this->externa && $this->filtro['codigos_externos']) {
                $this->agregar_columna('elemento_externo', 'Código del elemento');
            }
        }*/

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

        //invocar consulta
        $select = " concepto, orden_encuesta, elemento, orden_bloque, orden_pregunta, respondido_formulario, 
            encuesta_nombre, concepto_nombre, elemento_nombre, elemento_externo, bloque_nombre, pregunta,
            pregunta_nombre, respuesta_valor, respuesta_codigo, usuario, respondido_por ";
        $order_by = " ORDER BY  concepto, orden_encuesta, elemento, orden_bloque, orden_pregunta, respondido_formulario ";
        $datos = array();

        foreach ($forms_habs as $form_hab_id)
        {
            $this->filtro['formulario_habilitado'] = $form_hab_id['formulario_habilitado'];
            //Para el reporte por pregunta y respuesta obtenida se fuerza la selección "solo respondidas"
            //Esto se hace porque este reporte por naturaleza no muestra preguntas sin respuesta,
            //sin embargo cuando la respuesta tiene espacios vacíos cargados puede ocurrir que aparezcan las filas vacías en el reporte
            $this->filtro['respondidas'] = 'R';
            $resultados = $this->obtener_respuestas_formulario_habilitado($select, $order_by);

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