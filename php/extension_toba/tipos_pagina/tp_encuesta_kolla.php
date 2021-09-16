<?php

class tp_encuesta_kolla extends kolla_tp_basico
{
	protected function plantillas_css()
	{
		parent::plantillas_css();
		if (isset($this->menu)) {
			$estilo = $this->menu->plantilla_css();
			if ($estilo != '') {
				echo toba_recurso::link_css($estilo, 'screen', false);
			}
		}

		$item = toba::memoria()->get_item_solicitado_original();
		if ($item[1] == 40000134)
		{//se llega por la visualizacion de encuestas,
			//en este caso el estilo no est� en la base sino que viene como parametro
			//$estilo_id = toba::memoria()->get_parametro('estilo');
			//$estilo = toba::consulta_php('consultas_encuestas')->get_estilos('ee.estilo='.$estilo_id);
		} else {
			$id = toba::memoria()->get_parametro('habilitacion');
		}

		echo "<link href='css/bootstrap.min.css' rel='stylesheet' media='screen'>
			  <link href='css/encuesta-kolla.css' rel='stylesheet' media='screen'>";
	}
	//Le piso el metodo a toba que incluye sus css
	protected function estilos_css(){
		//parent::estilos_css();
	}

	protected function cabecera_html()
	{
		echo "<!DOCTYPE html>";
		echo "<HTML>\n";
		echo "<HEAD>\n";
		echo "<title>".$this->titulo_pagina()."</title>\n";
		$this->encoding();
		$this->plantillas_css();
		$this->estilos_css();
		toba_js::cargar_consumos_basicos();
		echo "</HEAD>\n";
	}

}
?>
