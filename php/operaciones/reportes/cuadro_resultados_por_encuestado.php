<?php
class cuadro_resultados_por_encuestado extends toba_ei_cuadro
{
	protected $encuesta;
	
	function ini()
	{
		$this->encuesta = $this->controlador()->s__encuesta;
	}	
}

?>