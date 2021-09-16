<?php

use ext_bootstrap\componentes\interfaz\bootstrap_ei_cuadro_salida_html;
require_once toba::proyecto()->get_path_php().'/extension_toba/bootstrap/componentes/interfaz/bootstrap_cuadro.php';

class cuadro_seleccion_multiple extends bootstrap_cuadro
{
	protected $con_evt_todos_ninguno = true;
	
	function inicializar($parametros=array())
	{
		parent::inicializar($parametros);
		
		//Si existe el evento 'seleccion_multiple' y efectivamente es múltiple, se alinea a izquierda de las columnas y se genera la utilidad "Todos/Ninguno".
		if ($this->existe_evento('seleccion_multiple') && $this->evento('seleccion_multiple')->es_seleccion_multiple()) {
			$this->evento('seleccion_multiple')->set_alineacion_pre_columnas(true);
			if ($this->con_evt_todos_ninguno) {
				$this->set_manejador_salida('html', 'cuadro_seleccion_multiple_salida_html');
			}
		}
	}

	function set_sin_evt_todos_ninguno()
	{
		$this->con_evt_todos_ninguno = false;
	}
	
}

class cuadro_seleccion_multiple_salida_html extends bootstrap_ei_cuadro_salida_html
{
	function html_cabecera()
	{
		parent::html_cabecera();
		$objeto_js = $this->_cuadro->get_id_objeto_js();
		
		echo "
			<div class= 'ef-multi-sel-todos'>
				<a href=\"javascript:$objeto_js.seleccionar_todos('seleccion_multiple')\">Todos</a> / 
				<a href=\"javascript:$objeto_js.deseleccionar_todos('seleccion_multiple')\">Ninguno</a>
			</div>
		";
	}
	
}
?>