<?php

class ci_resultados_por_preguntas extends ci_navegacion_por_ug_reportes
{
    protected $s__filtro;
    protected $s__reporte = null;
    protected $s__id_reporte;
    protected $s__externa = false;
    
    //-------------------------------------------------------------------
    //--- Dependencias
    //-------------------------------------------------------------------

    //-- FILTRO --

    function evt__filtro__filtrar($filtro)
    {
        $resultado = toba::consulta_php('consultas_habilitaciones')->get_habilitacion($filtro['habilitacion']);

        $this->s__externa = ($resultado[0]['externa'] == 'S');

        if (isset($filtro['fecha_desde'])) {
            $ffdesde = mktime(0, 0, 0, substr($filtro['fecha_desde'], 5, 2), substr($filtro['fecha_desde'], 8, 2), substr($filtro['fecha_desde'], 0, 4));
        }
        if (isset($filtro['fecha_hasta'])) {
            $ffhasta = mktime(0, 0, 0, substr($filtro['fecha_hasta'], 5, 2), substr($filtro['fecha_hasta'], 8, 2), substr($filtro['fecha_hasta'], 0, 4));
        }
        $hfdesde = mktime(0, 0, 0, substr($resultado[0]['fecha_desde'], 5, 2), substr($resultado[0]['fecha_desde'], 8, 2), substr($resultado[0]['fecha_desde'], 0, 4));
        $hfhasta = mktime(0, 0, 0, substr($resultado[0]['fecha_hasta'], 5, 2), substr($resultado[0]['fecha_hasta'], 8, 2), substr($resultado[0]['fecha_hasta'], 0, 4));

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
        $this->s__filtro = $filtro;
		
        if ($filtrar) {
            $this->s__filtro = $filtro;
            
            $this->s__reporte = new reportes_kolla($this->s__filtro, REPORTE_PREGUNTA);
            $this->s__reporte->generar_reporte();
        } else {
            unset($this->s__filtro);
            toba::notificacion()->agregar('Verifique que las fechas estén dentro del rango de la habilitación.', 'info');
        }
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
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
	
    function conf__cuadro(toba_ei_cuadro $cuadro)
    {
        if ( isset($this->s__filtro) && !$this->s__filtro['codigos'] ) {
            $cuadro->eliminar_columnas(array('respuesta_codigo'));
        }

        if ( isset($this->s__reporte) ) {
            $datos = $this->s__reporte->get_data();
            
            if (isset($datos[0])) {
                if ($datos[0]['concepto_nombre'] == '') $cuadro->eliminar_columnas (array('concepto_nombre'));
                if ($datos[0]['elemento_nombre'] == '') $cuadro->eliminar_columnas (array('elemento_nombre'));
            }

            if (!$this->s__externa) {
                $cuadro->eliminar_columnas(array('elemento_externo'));
            }
            
            if ( !empty($datos) ) {
                $cuadro->set_datos($datos);
                $this->s__id_reporte = $this->s__reporte->get_id_archivo();
                $nombre_archivo = $this->s__reporte->get_nombre_archivo();
                toba::memoria()->set_dato('nombre_archivo', $nombre_archivo);
                toba::notificacion()->agregar('El reporte se exportó a txt, para obtener más tarde el archivo conserve el siguiente código: '.$this->s__id_reporte, 'info');                
            }
        }
    }
    
}
?>