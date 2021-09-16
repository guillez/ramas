<?php

class ci_resumen_de_encuestas extends ci_navegacion_por_ug_reportes
{
	protected $s__filtro;
	public $s__form_datos;
	public $s__encuesta;
	
	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $form)
	{
		if (isset($this->s__filtro)) {
			return $this->s__filtro;
		}
	}

	function evt__filtro__filtrar($filtro)
	{
		$resultado = toba::consulta_php('consultas_habilitaciones')->get_habilitacion($filtro['habilitacion']);
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

		if ($filtrar) {
			$this->s__filtro = $filtro;
		} else {
			unset($this->s__filtro);
			toba::notificacion()->agregar('Verifique que las fechas estén dentro del rango de la habilitación.', 'info');
		}        
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
		unset($this->s__form_datos);
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		$form->set_solo_lectura();
		if (isset($this->s__filtro)) {
			$resultados = toba::consulta_php('consultas_reportes')->resumen_encuesta($this->s__filtro);
			if (!empty($resultados)) {
				$this->s__form_datos = true;
				$form->set_datos($resultados);
				$form->set_solo_lectura();    
			} 
		} 
	}

    //-----------------------------------------------------------------------------------
	//---- cuadro resultados ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__resultados(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
            $resultados = toba::consulta_php('consultas_reportes')->resumen_encuesta($this->s__filtro);
            $cuadro->set_datos($resultados);
		} 
	}        
}
?>