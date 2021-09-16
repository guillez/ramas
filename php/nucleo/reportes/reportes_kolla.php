<?php
require_once 'reportes_base.php';

class reportes_kolla extends reportes_base {

    protected $filtro = null;
    protected $tipo_reporte;
    protected $path_txts;
    protected $nombre_archivo;
    protected $id_archivo = null;
    protected $externa = false;
    protected $tiene_conceptos = false;
    protected $sale_por_pantalla = false;

    public function __construct($filtro, $tipo, $pantalla=false)
    {
        $this->filtro = $filtro;
        $this->tipo_reporte = $tipo;
        $this->sale_por_pantalla = $pantalla;
        $this->path_txts = toba::proyecto()->get_path() . "/procesos/reportes/";
    }

    public function get_columnas_reporte_encuestado() {
        return $this->get_headers();
    }

    public function get_nombre_archivo() {
        return $this->nombre_archivo;
    }

    public function get_id_archivo() {
        return $this->id_archivo;
    }

    public function get_datos_habilitacion() {
        $res = toba::consulta_php('consultas_habilitaciones')->get_habilitacion($this->filtro['habilitacion']);
        $this->externa = ($res[0]['externa'] == 'S');
        $this->tiene_conceptos = toba::consulta_php('consultas_habilitaciones')->tiene_concepto($this->filtro['habilitacion']);
    }

    public function get_where_preguntas_reporte()
    {
        $where = array('TRUE');
        if ( $this->filtro['habilitacion'] && $this->filtro['habilitacion'] ) {
            $where[] = 'habilitacion = '. kolla_db::quote($this->filtro['habilitacion']);
        }
        if ( isset($this->filtro['encuesta']) && $this->filtro['encuesta'] ) {
            $where[] =  'encuesta = '. kolla_db::quote($this->filtro['encuesta']);
        }
        if ( isset($this->filtro['elemento']) && $this->filtro['elemento'] ) {
            $where[] = 'elemento = '. kolla_db::quote($this->filtro['elemento']);
        }
        $where = implode(' AND ', $where);
        return $where;
    }

    /*
    * Obtiene todas las respuestas a un formulario_habilitado
    * sirve para distintos tipos de reporte
    */
    public function obtener_respuestas_formulario_habilitado($select, $order_by=null)
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
        $filtro_preguntas = (isset($this->filtro['respondidas']) && $this->filtro['respondidas'] == 'R') ? " WHERE trim(respuesta_valor) != '' " : '';
        //si se pidieron los resultados de acuerdo a la respuesta dada a una determinada pregunta se debe filtrar los resultados
        //para que solo pertenezcan a ese conjunto de respuestas (o sea respondido_formulario)
        $enc_def = (isset($this->filtro['pregunta_filtro']) && ($this->filtro['pregunta_filtro'] != '')) ? $this->filtro['pregunta_filtro'] : 'null';
        $rta_codigo = (isset($this->filtro['respuesta']) && ($this->filtro['respuesta'] != '')) ? $this->filtro['respuesta'] : 'null';

        $sql = " SELECT
            $select 
            FROM
            resultados_habilitacion($h,$formulario_habilitado,$g, $c, $elto, $enc, $p, $terminadas, $desde, $hasta, $enc_def, $rta_codigo)
            resultados ( habilitacion integer, --1
			formulario_habilitado integer, --2 
			formulario_nombre text,  --3
			respondido_formulario integer, --4 
			ingreso integer, --5
			 fecha_inicio date, --3
			 terminado_formulario character(1), --7
			 fecha_terminado date, --8
			 respondido_encuesta integer, --9
			respondido_detalle integer, --10
			 moderada character(1), --11
			 es_libre text, --12
			 es_multiple text, --13
			 respuesta_codigo integer, --14
			 respuesta_valor character varying, --15
			encuesta_definicion integer, --16
			 encuesta integer, --17
			 orden_encuesta integer, --18
			 orden_bloque smallint, --19
			 bloque integer, --20
			 bloque_nombre character varying(255), --21			 
			orden_pregunta smallint, --22
			 pregunta integer, --23
			 pregunta_nombre character varying(4096), --24
			 componente character varying(35), --25
			 tabla_asociada character varying(100), --26
			concepto integer, 
			 encuesta_nombre character varying, 
			 elemento integer, 
			 elemento_nombre text, 
			 respondido_encuestado integer, 
			 encuestado integer, 			 
			usuario character varying(60), 
			 respondido_por character varying(60), 
			 ignorado char, 
			 concepto_nombre text , 
			 concepto_externo character varying(100) , 
			elemento_externo character varying(100) , 
			 pregunta_tabla_codigo character varying(50) , 
			 pregunta_tabla_descripcion character varying(50) , 
			 numero integer , 
			 
			respondido_por_encuestado integer , 
			 codigo_columna character varying )
            $filtro_preguntas
            $order_by  
        ";

        kolla::logger()->debug("Consulta para obtener resultados: ");
        kolla::logger()->var_dump($sql);
        return kolla_db::consultar($sql);
    }

    public function obtener_reporte_texto()
    {
        $datos_reporte = reportes_base::obtener_reporte_texto();

        // Se almacena el usuario que genera el reporte
        $datos = array('usuario'   => toba::usuario()->get_id());

        switch ( $this->tipo_reporte ) {
            case RESULTADOS_ENCUESTADO:
                $tipo = '_enc_';
                break;
            case RESULTADOS_PREGUNTA:
                $tipo = '_preg_';
                break;
            case RESULTADOS_CONTEO_RESPUESTAS:
                $tipo = '_conteo_';
                break;
        }

        //fecha, hora, tipo de reporte, id de habilitacion
        $nombre = date('Ymd-Hi').$tipo.'h'.$this->filtro['habilitacion'];

        $datos['reporte_tipo'] = $this->tipo_reporte;
        $datos['habilitacion'] = $this->filtro['habilitacion'];
        $datos['grupo'] = ($this->filtro['grupo'] != '') ? $this->filtro['grupo'] : null;
        $datos['concepto'] = ($this->filtro['concepto'] != '') ?$this->filtro['concepto'] : null;
        $datos['elemento'] = (isset($this->filtro['elemento']) && $this->filtro['elemento'] != '') ? $this->filtro['elemento'] : null;
        $datos['encuesta'] = (isset($this->filtro['encuesta']) && $this->filtro['encuesta'] != '') ? $this->filtro['encuesta'] : null;
        $datos['pregunta'] = (isset($this->filtro['pregunta']) && $this->filtro['pregunta'] != '') ? $this->filtro['pregunta'] : null;
        $datos['filtro_pregunta'] = (isset($this->filtro['pregunta_filtro']) && $this->filtro['pregunta_filtro'] != '') ? $this->filtro['pregunta_filtro'] : null;
        $datos['filtro_pregunta_opcion_respuesta'] = (isset($this->filtro['respuesta']) && $this->filtro['respuesta'] != '') ? $this->filtro['respuesta'] : null;
        $datos['terminadas'] = $this->filtro['terminadas'];
        $datos['codigos'] = $this->filtro['codigos'];
        $datos['multiples']    = 0;
        $datos['fecha_desde'] = (isset($this->filtro['fecha_desde']) &&$this->filtro['fecha_desde'] != '') ? $this->filtro['fecha_desde'] : null;
        $datos['fecha_hasta'] = (isset($this->filtro['fecha_hasta']) && $this->filtro['fecha_hasta'] != '') ? $this->filtro['fecha_hasta'] : null;

        $file = $this->path_txts.$nombre.'.txt';
        if ( !file_exists($file) ) {
            file_put_contents($file ,$datos_reporte);
        }

        $datos['archivo']      = $nombre;
        $sql = sql_array_a_insert('sge_reporte_exportado', $datos);
        $sql = substr($sql, 0, -1);
        $sql .= " RETURNING exportado_codigo;";

        $this->nombre_archivo = $nombre;

        if ( $res = kolla_db::consultar_fila($sql) ) {
            $this->id_archivo = $res['exportado_codigo'];
        }
    }

    public function continuar_reporte_texto ()
    {
        $datos_reporte = reportes_base::continuar_reporte_texto();

        $file = $this->path_txts.$this->nombre_archivo.'.txt';
        file_put_contents($file ,$datos_reporte, FILE_APPEND);
    }

}
?>
