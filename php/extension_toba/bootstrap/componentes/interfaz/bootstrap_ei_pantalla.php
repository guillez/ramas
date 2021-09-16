<?php

 namespace ext_bootstrap\componentes\interfaz;
 
use ext_bootstrap\componentes\botones\bootstrap_tab;
use ext_bootstrap\componentes\botones\bootstrap_evento_usuario;
	
	
		
 class bootstrap_ei_pantalla extends \toba_ei_pantalla{
 	
 	function cargar_lista_eventos_override()
 	{
 		foreach ($this->_info_eventos as $info_evento) {
 			$e = new bootstrap_evento_usuario($info_evento, $this);
 			$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
 			$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;		//Lista de utilizados
 			if( $e->es_implicito() ){
 				\toba::logger()->debug($this->get_txt() . " IMPLICITO: " . $e->get_id(), 'toba');
 				$this->_evento_implicito = $e;
 			}
 		}
 	}
 
 	protected function cargar_lista_eventos()
 	{
 		//--- Filtra los eventos definidos por el usuario segun la asignacion a pantallas
 		$this->cargar_lista_eventos_override();
 	
 		if (isset($this->_evento_implicito)) {
 			//Si el evento implicito no esta en esta pantalla, no usarlo
 			$id = $this->_evento_implicito->get_id();
 			if (! isset($this->_eventos_usuario_utilizados[$id])) {
 				unset($this->_evento_implicito);
 			}
 		}
 	
 		//Como los eventos de pantalla vienen indexados por identificador (al igual que los utilizados por el usuario) podemos usar eso a nuestro favor
 		// en lugar de hacer el tipico ciclo, asi obtenemos los eventos usados por el usuario en una linea.
 		$this->_eventos_usuario_utilizados = array_intersect_key($this->_eventos_usuario_utilizados, $this->_eventos_pantalla);
 	
 		//-- Agrega los eventos internos relacionados con la navegacion tabs
 		switch($this->_info_ci['tipo_navegacion']) {
 			case self::NAVEGACION_TAB_HORIZONTAL:
 			case self::NAVEGACION_TAB_VERTICAL:
 				foreach ($this->_lista_tabs as $id => $tab) {
 					$this->registrar_evento_cambio_tab($id);
 				}
 				break;
 			case self::NAVEGACION_WIZARD:
 				list($anterior, $siguiente) = array_elem_limitrofes(array_keys($this->get_lista_tabs()),
 				$this->_info_pantalla['identificador']);
 				if ($anterior !== false) {
 					$e = new bootstrap_evento_usuario();
 					$e->set_id('cambiar_tab__anterior');
 					$e->set_etiqueta('< &Anterior');
 					$e->set_estilo_css('pull-left');
 					$e->set_maneja_datos(false);
 					$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
 					$nuevo[$e->get_id()] = $e;
 					$this->_eventos_usuario_utilizados = array_merge($nuevo, $this->_eventos_usuario_utilizados);
 					//$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;	//Lista de utilizados
 				}
 				if ($siguiente !== false) {
 					$e = new bootstrap_evento_usuario();
 					$e->set_id('cambiar_tab__siguiente');
 					$e->set_etiqueta('&Siguiente >');
 					$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
 					$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;	//Lista de utilizados
 				}
 				break;
 		}
 	}
 	
 	
 	protected function cargar_lista_tabs()
 	{
 		$this->_lista_tabs = array();
 		for($a = 0; $a<count($this->_info_ci_me_pantalla);$a++)
 		{
 			$id = $this->_info_ci_me_pantalla[$a]["identificador"];
 			$datos['identificador'] = $id;
 			$datos['etiqueta'] = $this->_info_ci_me_pantalla[$a]["etiqueta"];
 			$datos['ayuda'] = $this->_info_ci_me_pantalla[$a]["tip"];
 			$datos['imagen'] = $this->_info_ci_me_pantalla[$a]["imagen"];
 			$datos['imagen_recurso_origen'] = $this->_info_ci_me_pantalla[$a]["imagen_recurso_origen"];
 			$this->_lista_tabs[$id] = new bootstrap_tab($datos);
 		}
 	}
 	
 	/**
 	 * @todo analizar si es necesario la etiqueta del wizard, total ya esta en el tab
 	 * {@inheritDoc}
 	 * @see toba_ei_pantalla::generar_html_contenido()
 	 */
 	protected function generar_html_contenido()
 	{
 		//--- Descripcion de la PANTALLA
 		$es_wizard = $this->_info_ci['tipo_navegacion'] == 'wizard';
 		if ($this->_info_pantalla['descripcion'] !="" || $es_wizard) {
 			$tipo = isset($this->_info_pantalla['descripcion_tipo']) ? $this->_info_pantalla['descripcion_tipo'] : null;
 			if ($es_wizard) {
 				if ($this->_info_pantalla['descripcion'] != "") {
 					$this->generar_html_descripcion($this->_info_pantalla['descripcion'], $tipo);
 				}
 				foreach ($this->_notificaciones as $notificacion){
 					$this->generar_html_descripcion($notificacion['mensaje'], $notificacion['tipo']);
 				}
 			} else {
 				$this->generar_html_descripcion($this->_info_pantalla['descripcion'], $tipo);
 				foreach ($this->_notificaciones as $notificacion){
 					$this->generar_html_descripcion($notificacion['mensaje'], $notificacion['tipo']);
 				}
 			}
 		}
 		$this->generar_layout();
 		echo "<div id='{$this->objeto_js}_pie'></div>";
 	}
 	
 	protected function generar_html_descripcion($mensaje, $tipo=null)
 	{
 		if (! isset($tipo) || $tipo == 'info') {
 			$imagen = '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>';
 			$clase = 'alert-info';
 		} elseif ($tipo== 'warning') {
 			$imagen = '<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>';
 			$clase = 'alert-warning';
 		} elseif ($tipo == 'error') {
 			$imagen = '<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>';
 			$clase = 'alert-danger';
 		}
 		$descripcion = \toba_parser_ayuda::parsear($mensaje);
 		echo "<div class='alert $clase' role='alert'>$imagen $descripcion</div>";
 		
 	}
 	
 	function generar_html(){
 		
 		echo "<!--Comienza la generación del contenido -->";
 		
 		echo "<div id='{$this->objeto_js}_cont'>";

 		/**@todo ver que tarea realiza, por ahora no se ve ningun cambio sacandola */
//  		echo $this->controlador->get_html_barra_editor();
 		
 		$class_extra = '';
 		if ($this->_info_ci['tipo_navegacion'] == self::NAVEGACION_TAB_HORIZONTAL) {
 			$class_extra = 'ci-barra-sup-tabs';
 		}
 		$this->generar_html_barra_sup(null,true,"ci-barra-sup $class_extra");
 		$colapsado = (isset($this->_colapsado) && $this->_colapsado) ? "style='display:none'" : "";
 		echo "<div $colapsado id='cuerpo_{$this->objeto_js}'>\n";
 		
 		//-->Listener de eventos
 		if ( (count($this->_eventos) > 0) || (count($this->_eventos_usuario_utilizados) > 0) ) {
 			echo bootstrap_form::hidden($this->_submit, '');
 			echo bootstrap_form::hidden($this->_submit."__param", '');
 		}
 		
 		//--> Cuerpo del CI
 		$this->generar_html_cuerpo();
 		
 		//--> Botonera
 		if($this->botonera_abajo()) {
 			$this->generar_botones('');
 		}
 		if ( $this->_utilizar_impresion_html ) {
 			$this->generar_utilidades_impresion_html();
 		}
 		echo "<!--Fin la generación del contenido -->";
		echo "</div>";// cierre div panel  	
 	}
 	
 	function generar_botones($clase = '', $extra='')
 	{
 		//----------- Generacion
 		if ($this->hay_botones()) {
 			echo "<div class='col-md-12 divider $clase'>";
 			echo $extra;
 			$this->generar_botones_eventos();
 			echo "</div>";
 		} elseif ($extra != '') {
 			echo $extra;
 		}
 	}
 	protected function generar_html_cuerpo()
 	{
 		switch($this->_info_ci['tipo_navegacion'])
 		{
 			case self::NAVEGACION_TAB_HORIZONTAL:
 				echo "<div class='nav-tabs-custom'>"; // inicio de tabs
 				$this->generar_tabs_horizontales();
 				echo "	<div class='tab-content'>"; // Inicio de contenido
 				$this->generar_html_contenido();
 				echo "	</div>"; // Fin de contenido
 				echo "</div>"; // Fin de tabs
 				
 				break;
 			case self::NAVEGACION_TAB_VERTICAL: 									//*** TABs verticales
 				echo "<div class='nav-tabs-custom nav-stacked '>";
 				$this->generar_tabs_verticales();
 				echo "	<div class='tab-content col-md-10'>";
 				$this->generar_html_contenido();
 				echo "</div>\n";
 				echo "</div>\n";
 				break;
 			case self::NAVEGACION_WIZARD: 									//*** Wizard (secuencia estricta hacia adelante)
 				echo "<div class='nav-tabs-custom nav-stacked '>";
 				if ($this->_info_ci['con_toc']) {
 					$this->generar_toc_wizard();
 				}
 				echo "	<div class='tab-content col-md-10'>";
 							$this->generar_html_contenido();
 				echo "	</div>"; // Fin de contenido
 				echo "</div>"; // Fin de tabs
 				break;
 			default:										//*** Sin mecanismo de navegacion
 				//echo "<div class='ci-simple-cont'>";
 				
 				$this->generar_html_contenido();
 				//echo "</div>";
 		}
 	}
 	
 	protected function generar_toc_wizard()
 	{
 		echo "<ul class='nav nav-tabs nav-stacked col-md-2'>";
 		$pasada = true;
 		foreach ($this->_lista_tabs as $id => $pantalla) {
 			
 			if ($pasada)
 				$clase = 'ci-wiz-toc-pant-pasada';
 			else
 				$clase = 'ci-wiz-toc-pant-futuro';
 					if ($id == $this->_id_en_controlador) {
 						$clase = 'active';
 						$pasada = false;
 					}
 					echo "<li class='$clase'> <a>";
 					echo $pantalla->get_etiqueta();
 					echo "</a></li>";
 		}
 		echo "</ul>";
 	}
 	
 	function generar_tabs_horizontales()
 	{
 		echo "<ul class='nav nav-tabs'>\n";
 		foreach( $this->_lista_tabs as $id => $tab ) {
 			$editor = '';
 			if (\toba_editor::modo_prueba()) {
 				$editor = \toba_editor::get_vinculo_pantalla($this->_id, $this->_info['clase_editor_item'], $id)."\n";
 			}
 			echo $tab->get_html('H', $this->_submit, $this->objeto_js, ($this->_id_en_controlador == $id), $editor );
 		}
 		echo "</ul>";
 	}
 	protected function generar_tabs_verticales()
 	{
 		echo "<ul class='nav nav-tabs nav-stacked col-md-2  no-padding'>";
 		foreach( $this->_lista_tabs as $id => $tab ) {
 			$editor = '';
 			if (\toba_editor::modo_prueba()) {
 				$editor = \toba_editor::get_vinculo_pantalla($this->_id, $this->_info['clase_editor_item'], $id)."\n";
 			}
 			echo $tab->get_html('V', $this->_submit, $this->objeto_js, ($this->_id_en_controlador == $id), $editor);
 		}
 		echo "</ul>";
 	}
 	
 	function generar_html_barra_sup($titulo=null, $control_titulo_vacio=false, $estilo="")
 	{
 		if ($this->_mostrar_barra_superior && $titulo != '') {
 				
 			echo "<h3>$titulo</h3>";
 		}
 	
 	}
 	

 	
 }