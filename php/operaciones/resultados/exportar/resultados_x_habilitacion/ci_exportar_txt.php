<?php

require_once('contrib/lib/toba_manejador_procesos.php');

class ci_exportar_txt extends ci_navegacion_por_ug_reportes
{
    protected $s__filtro;
    protected $s__formulario;
    protected $s__reporte_tipo;
    protected $s__id_reporte;
    protected $s__nombre_archivo_resultados;
    private $path_scripts;	
    private $path_logs;
    private $path_txts;
    
    function ini()
    {
        $this->path_scripts = toba::proyecto()->get_path() . "/procesos/scripts/";
        $this->path_logs = toba::proyecto()->get_path() . "/procesos/logs";
        $this->path_txts = toba::proyecto()->get_path() . "/procesos/reportes/";
    }

    //-------------------------------------------------------------------
    //--- Dependencias --------------------------------------------------
    //-------------------------------------------------------------------

    //---- Filtro -------------------------------------------------------

    function cargar_filtro($filtro)
    {
        $this->s__reporte_tipo = $filtro['reporte_tipo'];
        $resultado = toba::consulta_php('consultas_encuestas')->get_datos_habilitacion($filtro['habilitacion']);

        if (isset($filtro['fecha_desde'])) {
            $ffdesde = mktime(0,0,0,substr($filtro['fecha_desde'],5,2),substr($filtro['fecha_desde'],8,2),substr($filtro['fecha_desde'],0,4));
        }
        if (isset($filtro['fecha_hasta'])) {
            $ffhasta = mktime(0,0,0,substr($filtro['fecha_hasta'],5,2),substr($filtro['fecha_hasta'],8,2),substr($filtro['fecha_hasta'],0,4));
        }

        $hfdesde = mktime(0,0,0,substr($resultado[0]['fecha_desde'],5,2),substr($resultado[0]['fecha_desde'],8,2),substr($resultado[0]['fecha_desde'],0,4));
        $hfhasta = mktime(0,0,0,substr($resultado[0]['fecha_hasta'],5,2),substr($resultado[0]['fecha_hasta'],8,2),substr($resultado[0]['fecha_hasta'],0,4));
        $filtrar = false;

        if (isset($ffdesde) && isset($ffhasta)) {
            $filtrar = (($hfdesde <= $ffdesde) && ($hfhasta >= $ffhasta) && ($ffdesde <= $ffhasta));
        } else { 
            if (isset($ffdesde)) {
                $filtrar = (($hfdesde <= $ffdesde) && ($ffdesde <= $hfhasta));
            } else {
                if (isset($ffhasta)) {
                    $filtrar = (($hfhasta >= $ffhasta) && ($ffhasta >= $hfdesde));
                } else {
                    $filtrar = true;
                }
            }
        }

        if ($filtrar) {
            $this->s__filtro = $filtro;
            return true;
        } else {
            $this->s__filtro = $filtro;
            toba::notificacion()->agregar('Verifique las fechas que estén dentro del rango de la habilitación.', 'info');
            return false;
        }
    }

    function conf__filtro()
    {
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }
    
    //-----------------------------------------------------------------------------------
    //---- PANTALLA DE SELECCION---------------------------------------------------------
    //-----------------------------------------------------------------------------------	

    //---- filtro exportar datos --------------------------------------------------------

    function evt__filtro__exportar($filtro)
    {
        //Cargar los datos de filtro obtenidos del formulario

        if ($this->cargar_filtro($filtro)) {
            //Averiguar el tipo de reporte solicitado para determinar el item que responde el pedido
            switch ($this->s__reporte_tipo) {
                    case 4:
                        $tipo_reporte = REPORTE_PREGUNTA_HABILITACION;
                        break;
                    case 5:
                        $tipo_reporte = REPORTE_RESPUESTAS_HABILITACION;
                        break;                
                    case 6:
                        $tipo_reporte = REPORTE_ENCUESTADO_HABILITACION;
                        break;                
            }

            $this->s__reporte = new reportes_kolla($this->s__filtro, $tipo_reporte);
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
    }

    //-----------------------------------------------------------------------------------
    //---- PANTALLA DE MENSAJES ---------------------------------------------------------	
    //-----------------------------------------------------------------------------------

    //---- codigo -----------------------------------------------------------------------

    function conf__codigo(toba_ei_formulario $form)
    {
        $mensaje = 'Se ha iniciado el proceso de exportación, para obtener el archivo de resultados conserve el siguiente código:';
        $this->pantalla('ticket')->set_descripcion($mensaje);
        $datos = array('id_reporte' => $this->s__id_reporte);
        $form->set_datos($datos);
    }

    function evt__codigo__recuperar($datos)
    {
        $id_reporte = $datos['id_reporte'];
        toba::vinculador()->navegar_a(null, '40000128', array('id_reporte' => $id_reporte), null, null);
    }
    
    //-----------------------------------------------------------------------------------
    //---- Mensaje de selección en combo Habilitaciones ---------------------------------
    //-----------------------------------------------------------------------------------
    
    function get_mensaje_seleccione()
    {
        return '-- Seleccione --';
    }

}
?>