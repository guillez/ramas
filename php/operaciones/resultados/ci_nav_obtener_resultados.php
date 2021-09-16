<?php
require_once ('informe_resumido.php');
require_once ('resultados_mensajes.php');

class ci_nav_obtener_resultados extends ci_navegacion_por_ug
{
    protected $s__reporte;
    protected $s__id_reporte;
    protected $s__filtro;
    protected $s__habilitaciones;
    protected $s__seleccion_habilitacion;
    protected $s__datos_habilitacion;
    protected $s__seleccion_grupo;
    protected $s__seleccion_concepto;
    protected $s__seleccion_elemento;
    protected $s__seleccion_encuesta;
    protected $s__preguntas_encuesta;
    protected $s__respuestas_pregunta_filtro;
    protected $s__filtros_config;
    protected $s__vis_config;
    protected $s__cantidad_columnas;


    function ini()
    {

    }

    //-------------------------------------------------------------------
    //--- Dependencias --------------------------------------------------
    //-------------------------------------------------------------------

    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__filtro__filtrar($datos)
    {
        $this->s__filtro = $datos;
    }

    function conf__filtro()
    {
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }

    function evt__filtro__cancelar()
    {
        unset($this->s__filtro);
    }

    //-----------------------------------------------------------------------------------
    //---- listado ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__listado(bootstrap_cuadro $cuadro)
    {
        $where = " TRUE ";

        $where .= (isset($this->s__filtro['archivadas']) && $this->s__filtro['archivadas'] != 'T') ? " AND archivada = ".quote($this->s__filtro['archivadas']) : "";
        $where .= (isset($this->s__filtro['destacadas']) && $this->s__filtro['destacadas'] != 'T') ? " AND destacada = ".quote($this->s__filtro['destacadas']) : "";
        $where .= isset($this->s__filtro['externa']) ? " AND externa = ".quote($this->s__filtro['externa']) : " AND externa = 'N'";
        $where .= isset($this->s__filtro['fecha_inicio']) ? " AND fecha_desde >= ".quote($this->s__filtro['fecha_inicio']) : "";
        $where .= isset($this->s__filtro['fecha_fin']) ? " AND fecha_hasta <= ".quote($this->s__filtro['fecha_fin']) : "";
        $where .= isset($this->s__filtro['descripcion']) ? " AND descripcion ilike ".quote("%".$this->s__filtro['descripcion']."%") : "";
        $where .= " AND unidad_gestion = ".quote($this->get_ug());

        $this->s__habilitaciones = toba::consulta_php('consultas_habilitaciones')->get_resumen_estado_habilitacion($where);

        $cuadro->set_datos($this->s__habilitaciones);
        $this->s__seleccion_habilitacion = null;
    }

    function evt__listado__detalle($seleccion)
    {
        $detalle = toba::consulta_php('consultas_habilitaciones')->get_habilitacion($seleccion['habilitacion']);
        $forms = toba::consulta_php('consultas_formularios')->get_formularios_habilitados_habilitacion($seleccion['habilitacion']);
        $detalle[0]['forms'] = $forms;

        $resumen = new informe_resumido();
        $tabla = $resumen->generar_resumen_completo($detalle[0]);

        toba::notificacion()->info($tabla->get_resumen());
    }

    function evt__listado__seleccion($seleccion)
    {
        $this->s__seleccion_habilitacion = $seleccion['habilitacion'];
        //conservar los datos de la habilitación elegida
        $key = array_search($seleccion['habilitacion'], array_column($this->s__habilitaciones, 'habilitacion'));
        $this->s__datos_habilitacion = $this->s__habilitaciones[$key];
        kolla::logger()->debug("Datos de la habilitación seleccionada: ");
        kolla::logger()->var_dump($this->s__datos_habilitacion);
        $this->set_pantalla('configuracion');
    }

    //-----------------------------------------------------------------------------------
    //---- filtro_config ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro_config(bootstrap_formulario $form)
    {

        if (isset($this->s__filtros_config)) {
            $form->set_datos($this->s__filtros_config);
        }

        //obtener los datos de la habilitación, si es externa deshabilitar:
        // códigos de origen
        // respondido por

        if ($this->s__datos_habilitacion['externa'] == 'S')
        {
            if ($form->existe_ef('respondida_por'))
                $form->desactivar_efs(array('respondida_por'));
        } else {
            $conceptos = toba::consulta_php('consultas_formularios')->get_conceptos_por_habilitacion($this->s__seleccion_habilitacion);
            if ($form->existe_ef('codigos_externos') && count($conceptos) == 0) {
                $form->desactivar_efs(array('codigos_externos'));
            }
        }

        $mensaje_fijo = "Si desea acotar los resultados en base a la respuesta dada a una pregunta lo puede indicar con el siguiente filtro.";
        $form->set_datos(array('habilitacion' => $this->s__seleccion_habilitacion,
                                'texto_ayuda' => $mensaje_fijo
                        ));
    }


    function filtro_config_concepto_cargar($grupo)
    {
        $conceptos = toba::consulta_php('consultas_formularios')->get_conceptos_por_habilitacion($this->s__seleccion_habilitacion, $grupo);
        $this->s__seleccion_grupo = $grupo;
        return $conceptos;
    }

    function filtro_config_elemento_cargar($concepto)
    {
        $elementos = toba::consulta_php('consultas_formularios')->get_elementos_por_concepto($concepto);
        $this->s__seleccion_concepto = $concepto;
        return $elementos;
    }

    function filtro_config_encuesta_cargar($elemento=null)
    {
        $encuestas = $this->get_encuestas_habilitacion($elemento);
        return $encuestas;
    }

    private function get_encuestas_habilitacion($elemento=null)
    {
        $where = '';
        $where .=  (isset($elemento)) ? " AND se.elemento = ".$elemento : '';
        $where .=  (isset($this->s__seleccion_concepto)) ? " AND sc.concepto = ".$this->s__seleccion_concepto : '';
        $where .=  (isset($this->s__seleccion_grupo)) ? " AND sgh.grupo = ".$this->s__seleccion_grupo : '';

        $sql = "select distinct
                	sea.encuesta, sea.nombre
                from sge_encuesta_atributo sea 
                    inner join sge_formulario_habilitado_detalle sfhd on (sea.encuesta = sfhd.encuesta)
                    inner join sge_formulario_habilitado sfh on (sfh.formulario_habilitado = sfhd.formulario_habilitado)
                    inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfh.formulario_habilitado)
                    left join sge_concepto sc on (sc.concepto = sfh.concepto and sc.unidad_gestion = sea.unidad_gestion)
                    left join sge_elemento se on (se.elemento = sfhd.elemento)
                where habilitacion = ".$this->s__seleccion_habilitacion.$where;
        return kolla_db::consultar($sql);
    }

    //Este es el método que permite la carga del combo de preguntas para filtrar los resultados
    // a una pregunta sola dentro de una encuesta determinada
    function filtro_config_pregunta_cargar($encuesta)
    {
        $preguntas = toba::consulta_php('consultas_encuestas')->get_datos_preguntas_para_filtro_resultados($encuesta);
        $this->s__seleccion_encuesta = $encuesta;
        return $preguntas;
    }

    //Este es el método que permite la carga del combo de preguntas para filtrar
    // condicionando a una respuesta determinada a una pregunta elegida
    function filtro_config_pregunta_condicion_cargar($encuesta=null)
    {
        $preguntas = [];
        $lista_encuestas = isset($encuesta) ? [['encuesta' => $encuesta]] : $this->get_encuestas_habilitacion(null);

        foreach ($lista_encuestas as $encuesta) {
            $mas_preguntas = toba::consulta_php('consultas_encuestas')->get_datos_preguntas_para_filtro_resultados($encuesta['encuesta']);
            $preguntas += $mas_preguntas;
        }

        $this->s__preguntas_encuesta = $preguntas;
        return $this->s__preguntas_encuesta;
    }


    function filtro_config_respuesta_cargar($enc_def)
    {
        if (isset($enc_def)) {
            $key = array_search($enc_def, array_column($this->s__preguntas_encuesta, 'encuesta_definicion'));
            $datos_pregunta = $this->s__preguntas_encuesta[$key];

            if ($datos_pregunta['tabla_asociada'] == '') {
                $respuestas = toba::consulta_php('consultas_encuestas')->get_opciones_respuesta($datos_pregunta['pregunta']);
            } else {
                $respuestas = toba::consulta_php('consultas_encuestas')->get_opciones_respuesta_tabla_asociada($datos_pregunta['tabla_asociada'],
                                                                                                                    $datos_pregunta['tabla_asociada_codigo'],
                                                                                                                    $datos_pregunta['tabla_asociada_descripcion']);
            }
            $this->s__respuestas_pregunta_filtro = $respuestas;
            return $respuestas;
        }
    }

    function evt__filtro_config__filtrar($datos)
    {
        $this->s__filtros_config = $datos;
        kolla::logger()->debug("Estado de los filtros cargados: ");
        kolla::logger()->var_dump($this->s__filtros_config);
        $this->set_pantalla('definicion');
    }

    function evt__filtro_config__cancelar()
    {
        $this->s__filtros_config = array();
    }

    // carga dinámicamente el combo de encuestas cuando se modifica el elemento seleccionado en el combo del formulario
    function ajax__buscar_encuestas($elemento, toba_ajax_respuesta $respuesta)
    {
        if ($elemento == '' || $elemento == 'nopar') {
            $elemento = null;
        }

        $valores_guardar = array();
        $encuestas = $this->filtro_config_encuesta_cargar($elemento);

        $estructura = (sizeof($encuestas) > 1) ? array(['nopar','-- Incluir solo una encuesta --']) : array();

        foreach ($encuestas as $enc) {
            $estructura[] = array($enc['encuesta'], $enc['nombre']);
            $valores_guardar[] = $enc['encuesta'];
        }

        //Guardo claves en sesion para que no falle al volver al server
        $this->dep('filtro_config')->ef('encuesta')->guardar_dato_sesion($valores_guardar, true);
        $respuesta->set($estructura);
    }

    // carga dinámicamente el combo de preguntas cuando se modifica la encuesta seleccionada en el combo del formulario
    function ajax__cambia_encuesta($encuesta, toba_ajax_respuesta $respuesta)
    {
        if ($encuesta == '' || $encuesta == 'nopar') {
            $encuesta = null;
        }

        $valores_guardar = array();
        $datos_preguntas = $this->filtro_config_pregunta_condicion_cargar($encuesta);
        $nuevas_preguntas = array();

        if (count($datos_preguntas) > 0) {
            $nuevas_preguntas = array(['nopar', '-- Filtrar por las respuestas a una pregunta --']);

            foreach ($datos_preguntas as $pregunta) {
                $nuevas_preguntas[] = array($pregunta['encuesta_definicion'], $pregunta['pregunta_nombre']);
                $valores_guardar[] = $pregunta['encuesta_definicion'];
            }

            //Guardo claves en sesion para que no falle al volver al server
            $this->dep('filtro_config')->ef('pregunta_filtro')->guardar_dato_sesion($valores_guardar, true);
        }
        $respuesta->set($nuevas_preguntas);
    }

    //-----------------------------------------------------------------------------------
    //---- form_definicion --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__form_definicion(bootstrap_formulario $form)
    {
        //$mensaje = $this->formatear_info_filtros();
        $mensaje = resultados_mensajes::formatear_info_filtros($this->s__datos_habilitacion, $this->s__filtros_config, $this->s__respuestas_pregunta_filtro);
        $form->agregar_notificacion("Los resultados a generar responden a los siguientes filtros: </br>".$mensaje);

        if ($this->s__datos_habilitacion['externa'] == 'N' && $form->existe_ef('datos_externos')) {
            $form->desactivar_efs('datos_externos');
        }

        $this->s__vis_config['respuestas_recibidas'] = $this->respuestas_recibidas_cant();
        //$this->calcular_columnas();
        $this->s__cantidad_columnas = 0;

        return $this->s__vis_config;
    }

    function respuestas_recibidas_cant ()
    {
        $h = $this->s__datos_habilitacion['habilitacion'];
        $formulario_habilitado = (isset($this->s__filtros_config['formulario_habilitado']) && $this->s__filtros_config['formulario_habilitado'] != '') ? $this->s__filtros_config['formulario_habilitado'] : 'null';
        $g = ($this->s__filtros_config['grupo'] != '') ? $this->s__filtros_config['grupo'] : 'null';
        $c = ($this->s__filtros_config['concepto'] != '') ? $this->s__filtros_config['concepto'] : 'null';
        $elto = ($this->s__filtros_config['elemento'] != '') ? $this->s__filtros_config['elemento'] : 'null';
        $enc = ($this->s__filtros_config['encuesta'] != '') ? $this->s__filtros_config['encuesta'] : 'null';
        $enc_def_pregunta = ($this->s__filtros_config['pregunta'] != '') ? $this->s__filtros_config['pregunta'] : 'null';
        $terminadas = ($this->s__filtros_config['terminadas'] != 'S' && $this->s__filtros_config['terminadas'] != 'N') ? 'null' : "'".$this->s__filtros_config['terminadas']."'";
        $desde = (isset($this->s__filtros_config['desde']) && $this->s__filtros_config['desde'] != '') ? kolla_db::quote($this->s__filtros_config['desde']) : 'null';
        $hasta = (isset($this->s__filtros_config['hasta']) && $this->s__filtros_config['hasta'] != '') ? kolla_db::quote($this->s__filtros_config['hasta']) : 'null';

        $enc_def = (isset($this->s__filtros_config['pregunta_filtro']) && ($this->s__filtros_config['pregunta_filtro'] != '')) ? $this->s__filtros_config['pregunta_filtro'] : 'null';
        $rta_codigo = (isset($this->s__filtros_config['respuesta']) && ($this->s__filtros_config['respuesta'] != '')) ? $this->s__filtros_config['respuesta'] : 'null';

        $sql = "
            SELECT * 
            FROM estimar_cantidad_resultados ($h,$formulario_habilitado,$g, $c, $elto, $enc, $enc_def_pregunta, $terminadas, $desde, $hasta, $enc_def, $rta_codigo)
            resultados (respuestas_recibidas bigint)
        ";
        $res = kolla_db::consultar_fila($sql);
        return $res['respuestas_recibidas'];
    }
/*
    function calcular_columnas()
    {
        $h = $this->s__datos_habilitacion['habilitacion'];
        $formulario_habilitado = (isset($this->s__filtros_config['formulario_habilitado']) && $this->s__filtros_config['formulario_habilitado'] != '') ? $this->s__filtros_config['formulario_habilitado'] : 'null';
        $g = ($this->s__filtros_config['grupo'] != '') ? $this->s__filtros_config['grupo'] : 'null';
        $c = ($this->s__filtros_config['concepto'] != '') ? $this->s__filtros_config['concepto'] : 'null';
        $elto = ($this->s__filtros_config['elemento'] != '') ? $this->s__filtros_config['elemento'] : 'null';
        $enc = ($this->s__filtros_config['encuesta'] != '') ? $this->s__filtros_config['encuesta'] : 'null';
        $enc_def = ($this->s__filtros_config['pregunta'] != '') ? $this->s__filtros_config['pregunta'] : 'null';

        $filtro_preguntas = (isset($this->s__filtros_config['pregunta_filtro']) && ($this->s__filtros_config['pregunta_filtro'] != '')) ? 'true' : 'false';
        $filtro_respuestas = (isset($this->s__filtros_config['respuesta']) && ($this->s__filtros_config['respuesta'] != '')) ? 'true' : 'false';

        //$sql = "SELECT count(codigo_columna) as cantidad
        //            FROM columnas_del_reporte(".$this->s__datos_habilitacion['habilitacion'].",$filtro_preguntas, $filtro_respuestas)
        //                resultado (codigo_columna character varying)";

        //AVERIGUAR ENC_DEF¿¿
        $sql = "SELECT count(codigo_columna) as cantidad
                    FROM preguntas_con_respuestas_resultados_encuestado($h, $formulario_habilitado, $g, $c, $elto, $enc, $enc_def, $filtro_preguntas, $filtro_respuestas)
                $this->definicion_tabla_preguntas_con_rtas_habilitacion
                ".$order_by.";";

        $res = kolla_db::consultar_fila($sql);
        $this->s__cantidad_columnas = $res['cantidad'];
    }
*/

    function form_definicion_columnas_cargar($tipo)
    {
        $codigos = (isset($this->s__filtros_config['codigos']) && $this->s__filtros_config['codigos']) ? 1 : 0;
        $cant = ($this->s__filtros_config['terminadas'] == 'T') ? 1 : 0;
        switch ($tipo) {
            case "E":
                $cant += 3 + $this->s__cantidad_columnas; //(usuario + fecha inicio + fecha fin + columnas de preguntas y respuestas)
                break;
            case 'R':
                $cant += 9 + $codigos; //(encuesta + concepto + elemento + elemento externo + bloque + pregunta + respuesta + usuario + respondido por ) + codigo de pregunta
                break;
            case 'C':
                $cant += 6 + $codigos; //(encuesta + bloque + codigos + concepto + elemento + elemento externo
                break;
        }
        return $cant;
    }

    function form_definicion_filas_cargar($tipo)
    {
        $codigos = (isset($this->s__filtros_config['codigos']) && $this->s__filtros_config['codigos']) ? 1 : 0;
        $filas = $this->s__vis_config['respuestas_recibidas'];
        switch ($tipo) {
            case "E":
                $cant = $filas;
                break;
            case 'R':
                $cant = $filas * $this->s__cantidad_columnas;
                break;
            case 'C':
                $cant = 'falta calcular';
                break;
        }
        return $cant;
    }

    function evt__form_definicion__descargar($datos)
    {
        $this->s__vis_config = $datos;

        kolla::logger()->debug("Visualización pedida: ");
        kolla::logger()->var_dump($this->s__vis_config);

        $this->s__filtros_config['habilitacion'] = $this->s__seleccion_habilitacion;
        $this->s__filtros_config['desde'] = '';
        $this->s__filtros_config['hasta'] = '';
        if (isset($this->s__filtros_config['fecha_desde_respondido'])) {
            $this->s__filtros_config['desde'] = substr($this->s__filtros_config['fecha_desde_respondido'],5,2)."/"
                .substr($this->s__filtros_config['fecha_desde_respondido'],8,2)."/"
                .substr($this->s__filtros_config['fecha_desde_respondido'],0,4);
        }
        if (isset($this->s__filtros_config['fecha_hasta_respondido'])) {
            $this->s__filtros_config['hasta'] = substr($this->s__filtros_config['fecha_hasta_respondido'],5,2)."/"
                .substr($this->s__filtros_config['fecha_hasta_respondido'],8,2)."/"
                .substr($this->s__filtros_config['fecha_hasta_respondido'],0,4);
        }
        //se calculan los formularios habilitados incluidos
        $this->s__filtros_config['formularios_habilitados'] = $this->determinar_formularios_habilitados();

        switch ($this->s__vis_config['tipo']) {
            case "E":
                $this->s__reporte = new reportes_kolla_encuestado($this->s__filtros_config, $this->s__vis_config['pantalla']);
                break;
            case "R":
                $this->s__reporte = new reportes_kolla_pregunta($this->s__filtros_config, $this->s__vis_config['pantalla']);
                break;
            case "C":
                $this->s__reporte = new reportes_kolla_conteo($this->s__filtros_config, $this->s__vis_config['pantalla']);
                break;
        }

        $this->s__reporte->generar_reporte();
        $this->s__id_reporte = $this->s__reporte->get_id_archivo();

        if (!is_null($this->s__id_reporte)) {
            $nombre_archivo = $this->s__reporte->get_nombre_archivo();
            toba::memoria()->set_dato('nombre_archivo', $nombre_archivo);
            toba::notificacion()->agregar('El reporte se exportó a un archivo de texto. Puede obtenerlo luego en <b>Recuperación > Reportes</b> utilizando el código <b>'. $this->s__id_reporte . '</b>.', 'info');

            // Genero el link para que pueda descargar el reporte
            $link = toba::vinculador()->get_url('kolla', 44000001, [], array('celda_memoria'=>'descarga_directa', 'menu' => false));
            toba::notificacion()->agregar("<b><a href=$link  target='_blank'>Puede descargarlo de manera directa desde aquí.</a></b>", "info");
        } else {
            toba::notificacion()->agregar('No se exportaron resultados.', 'info');
        }
    }

    function evt__form_definicion__volver()
    {
        $this->set_pantalla('configuracion');
    }

    function determinar_formularios_habilitados ()
    {
        $where = " ";
        $where .= ($this->s__filtros_config['grupo'] != '') ? " AND sgh.grupo = " . $this->s__filtros_config['grupo'] : "";
        $where .= ($this->s__filtros_config['concepto'] != '') ? " AND sfh.concepto = " . $this->s__filtros_config['concepto'] : "";
        $where .= ($this->s__filtros_config['elemento'] != '') ? " AND sfhd.elemento = " . $this->s__filtros_config['elemento'] : "";
        $where .= ($this->s__filtros_config['encuesta'] != '') ? " AND sfhd.encuesta = " . $this->s__filtros_config['encuesta'] : "";


        $sql = "SELECT DISTINCT sfh.formulario_habilitado
                FROM sge_formulario_habilitado sfh
                    INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado)
                    INNER JOIN sge_grupo_habilitado sgh ON (sgh.formulario_habilitado = sfh.formulario_habilitado)
                WHERE sfh.habilitacion = ".$this->s__filtros_config['habilitacion']."
                        ".$where.";";
        $forms_habs = kolla_db::consultar($sql);

        if (count($forms_habs) == 1) {
            $this->s__filtros_config['formulario_habilitado'] = $forms_habs[0]['formulario_habilitado'];
        }
        return $forms_habs;
    }

    function cargar_opciones_datos_externos($tipo)
    {
        $opciones = array();
        $opciones[0]['clave'] = 'N';
        $opciones[0]['descripcion'] = 'No';
        /*
        if ($tipo != 'E') {
            $opciones[1]['clave'] = 'S';
            $opciones[1]['descripcion'] = 'Si - Mostrar datos del sistema de origen';
        }
        */
        return $opciones;
    }

    function cargar_mostrar_en_pantalla ($tipo)
    {
        $opciones = array();

        if ($tipo == 'E' && $this->s__vis_config['respuestas_recibidas'] > 100 && false) {
            unset($opciones);
            $opciones[0]['clave'] = 0;
            $opciones[0]['descripcion'] = 'No';
        } else {
            $opciones[0]['clave'] = 1;
            $opciones[0]['descripcion'] = 'Si';
            $opciones[1]['clave'] = 0;
            $opciones[1]['descripcion'] = 'No';
        }
        return $opciones;
    }

    //-----------------------------------------------------------------------------------
    //---- cuadro_dinamico --------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__cuadro_dinamico(toba_ei_cuadro $cuadro)
    {
        if (isset($this->s__reporte) && $this->s__vis_config['pantalla']) {
            $headers = $this->s__reporte->get_columnas_reporte_encuestado();
            //se quita la columna agregada por obilgacion
            $cuadro->eliminar_columnas(array('columna'));

            foreach ($headers as $clave => $titulo) {
                $col = array('clave' => $clave, 'titulo' => $titulo);
                $cuadro->agregar_columnas(array($col));
            }

            $datos = $this->s__reporte->get_data();

            if (!empty($datos)) {
                $cuadro->set_datos($datos);
                $cuadro->set_titulo("Id para descarga posterior del reporte: ".$this->s__id_reporte);

                // Esto es por el bug del ticket #18420, aparentemente lo soluciona...
                $this->s__reporte = null;
            }
        }
    }

}
?>