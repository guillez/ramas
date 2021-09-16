<?php

class ci_grupos_encuestados extends ci_navegacion_por_ug
{
    protected $s__filtro_grupos;
    
    function relacion()
	{
		return $this->dependencia('datos');
	}
    
    function resetear()
    {
        unset($this->s__filtro_grupos);
    }
    
	//-----------------------------------------------------------------------------------
	//---- PANTALLA SELECCION -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	//---- cuadro_grupos ----------------------------------------------------------------
	
	function conf__cuadro_grupos(toba_ei_cuadro $cuadro)
	{
        $this->set_ug();
        $filtro_ug = "sge_grupo_definicion.unidad_gestion = ".kolla_db::quote($this->s__ug);
        $filtro    = $this->dep('filtro_grupos')->get_sql_where();
        $filtro    = $filtro ? "$filtro AND $filtro_ug " : $filtro_ug;
        
        return toba::consulta_php('consultas_usuarios')->get_grupos_encuestados($filtro);
	}

	function evt__cuadro_grupos__seleccion($seleccion)
	{
        $this->relacion()->cargar($seleccion);
		$this->set_pantalla('pant_edicion');
	}

	//---- filtro_grupos ----------------------------------------------------------------
	
	function conf__filtro_grupos(toba_ei_filtro $filtro)
	{
        if (isset($this->s__filtro_grupos)) {
			return $this->s__filtro_grupos;
		}
	}

	function evt__filtro_grupos__filtrar($datos)
	{
        $this->s__filtro_grupos = $datos;
	}

	function evt__filtro_grupos__cancelar()
	{
        unset($this->s__filtro_grupos);
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
        $this->set_pantalla('pant_edicion');
	}
    
    function conf__edicion()
	{
	}
	
}
?>