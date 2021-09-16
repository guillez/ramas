<?php
class ci_abm_evaluacion extends ci_navegacion
{
        protected $s__filtro;
        protected $s__evaluacion; 
        
	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__filtro_evaluaciones__cancelar()
	{
            unset($this->s__filtro);
	}

	function conf__filtro_evaluaciones($filtro)
	{
            if (isset($this->s__filtro)) {
                $filtro->set_datos($this->s__filtro);
            }		
	}
        
        function evt__filtro_evaluaciones__filtrar($datos)
        {
            $this->s__filtro = $datos;        
        }
        

        //-----------------------------------------------------------------------------------
        //-----------------------------------------------------------------------------------

        function get_habilitaciones_xvigencia_ug_filtro($ug=null, $vigente=null) 
        {
            $where = null;
            if (isset($ug) && ($ug != '')) {
                $where = " sge_habilitacion.unidad_gestion = '".$ug."'";
            }

            if (isset($vigente) && ($vigente != '')) {
                $where = isset($where) ? $where." AND " : '' ;
                $where .= ($vigente == 'S') ? " (sge_habilitacion.fecha_desde <= now() AND sge_habilitacion.fecha_hasta >= now()) " 
                                            : " (sge_habilitacion.fecha_desde > now() OR sge_habilitacion.fecha_hasta < now()) " ;
            };

            return toba::consulta_php('consultas_habilitaciones')->get_habilitaciones_combo($where);
        }
        
        function limpiar () {
            unset($this->s__evaluacion);
            $this->get_relacion_evaluacion()->resetear();
        }

	//-----------------------------------------------------------------------------------
	//---- cuadro_evaluaciones ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_evaluaciones(bootstrap_cuadro $cuadro)
	{
            if (isset($this->s__filtro)) {
                return toba::consulta_php('consultas_evaluacion')->get_evaluaciones_habilitacion($this->s__filtro['habilitacion']);
            }
	}

	function evt__cuadro_evaluaciones__seleccion($seleccion)
	{
            $this->s__evaluacion = $seleccion['evaluacion'];
            $rel = $this->get_relacion_evaluacion();
            $rel->cargar($seleccion);
            $this->set_pantalla('edicion');
	}
        
	function evt__cuadro_evaluaciones__visualizar($seleccion)
	{
            $this->s__evaluacion = $seleccion['evaluacion'];
            $rel = $this->get_relacion_evaluacion();
            $rel->cargar($seleccion);
            $this->set_pantalla('visualizacion');
	}        
        
        function get_evaluacion() 
        {
            $fila = $this->get_tabla_evaluacion()->get_filas();
            if (isset($fila[0])) {
                return $fila[0]['evaluacion'];
            } else {
                return $this->s__evaluacion;
            }
        }
        
        function get_habilitacion() 
        {
            return isset($this->s__filtro) ? $this->s__filtro['habilitacion'] : null;
        }

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
            $this->set_pantalla('edicion');
	}
        
	function evt__volver()
	{
            $this->set_pantalla('seleccion');
            $this->limpiar();
	}        

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__seleccion(toba_ei_pantalla $pantalla)
	{
            if (!isset($this->s__filtro)) {
                $pantalla->eliminar_evento('agregar');
            }
	}

	function conf__edicion(toba_ei_pantalla $pantalla)
	{
            $pantalla->eliminar_evento('agregar');
	}

    //-----------------------------------------------------------------------------------
    //---- Auxiliares -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
      
    function get_relacion_evaluacion() 
    {
        return $this->dep('datos');
    }

    function get_tabla_evaluacion() 
    {
        return $this->dep('datos')->tabla('sge_evaluacion');
    }

    function get_tabla_puntaje_aplicacion() 
    {
        return $this->dep('datos')->tabla('sge_puntaje_aplicacion');
    }        

	//-----------------------------------------------------------------------------------
	//---- detalle ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__detalle(toba_ei_cuadro $cuadro)
	{
            $detalle = toba::consulta_php('consultas_evaluacion')->get_evaluacion_detalle($this->s__evaluacion);
            $cuadro->set_datos($detalle);
	}

}
?>