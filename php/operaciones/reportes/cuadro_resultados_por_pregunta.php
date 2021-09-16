<?php
class cuadro_resultados_por_pregunta extends toba_ei_cuadro
{
	protected $encuesta;
	
	function ini()
	{
		$this->encuesta = $this->controlador()->s__encuesta;
	}
	
	//-----------------------------------------------------------------------------------
	//---- Extiendo html_cabecera para incorporar un nuevo boton(y funcion) de exportacion
	//-----------------------------------------------------------------------------------
	
	protected function html_cabecera()
	{
		parent::html_cabecera();
		//extension
		$img = toba_recurso::imagen_toba('nota.gif', true);
		$opciones['servicio'] = 'exportar_txt'; 
		$url = toba::vinculador()->get_url(null, null, array(), $opciones); 
        echo "<a href='$url' title='Exporta el listado a formato de texto(.txt)'>$img</a>";
	}

	
	//-----------------------------------------------------------------------------------
	//---- Extiendo vista_excel para setear el nombre del archivo excel
	//-----------------------------------------------------------------------------------
	
	function vista_excel(toba_vista_excel $salida) 
	{
		if (isset($this->encuesta)) {
			$nombre = 'encuesta_'.$this->encuesta.'_res_por_pregunta.xls';
		} else {
		 	$nombre = 'encuesta_res_por_pregunta.xls';
		}
		
		$salida->set_nombre_archivo($nombre);
		parent::vista_excel($salida);
	}

}
?>