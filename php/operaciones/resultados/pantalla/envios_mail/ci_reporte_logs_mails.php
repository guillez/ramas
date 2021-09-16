<?php

class ci_reporte_logs_mails extends ci_navegacion_por_ug
{
	protected $s__filtro;
	
	//---- formulario -------------------------------------------------------------------
    
    function conf__formulario()
    {
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }
    
	function evt__formulario__filtrar($datos)
	{
		if (isset($datos)) {
			$this->s__filtro = $datos;
		}
	}
	
	function evt__formulario__cancelar()
	{
		unset($this->s__filtro);
	}

	//---- cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
        $filtro = 'h.unidad_gestion = '.kolla_db::quote($this->s__ug);
        
		if (isset($this->s__filtro)) {
            $filtro .= isset($this->s__filtro['habilitacion']) ? ' AND h.habilitacion ='.$this->s__filtro['habilitacion'] : ' AND TRUE';
            $datos   = toba::consulta_php('consultas_mgn')->get_reporte_envios_con_ug($filtro);
            $cuadro->set_datos($datos);
        }
	}
    
    function get_habilitaciones()
    {
        $this->set_ug();
        return toba::consulta_php('consultas_habilitaciones')->get_habilitaciones_combo_resultados_envio_emails($this->s__ug);
    }

}
?>