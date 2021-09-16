<?php
use ext_bootstrap\componentes\botones\bootstrap_evento_usuario;

/**
 * @todo agregar namespaces
 * 
 * @author ptoledo
 *
 */
class bootstrap_cuadro extends toba_ei_cuadro
{
	
	protected function cargar_lista_eventos()
	{
		parent::cargar_lista_eventos();
		
		foreach ($this->_info_eventos as $info_evento) {
			$e = new bootstrap_evento_usuario($info_evento, $this);
				
			$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
			$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;		//Lista de utilizados
			if( $e->es_implicito() ){
				toba::logger()->debug($this->get_txt() . " IMPLICITO: " . $e->get_id(), 'toba');
				$this->_evento_implicito = $e;
			}
		}
	}
	
	function instanciar_manejador_tipo_salida($tipo)
	{
		//Si existe seteo explicito de parte del usuario para el tipo de salida
		if (isset($this->_manejador_tipo_salida[$tipo])) {
			$clase =  $this->_manejador_tipo_salida[$tipo];
		} else {
			//Verifico que sea uno de los tipos estandar o disparo excepcion
			switch($tipo) {
				case 'html':
				case 'impresion_html':
				case 'pdf':
				case 'excel':
				case 'xml':
					$clase = 'ext_bootstrap\\componentes\\interfaz\\bootstrap_ei_cuadro_salida_' . $this->_tipo_salida;
					break;
				default:
					throw new toba_error_def('El tipo de salida solicitado carece de una clase que lo soporte');
			}
		}
		if (isset($clase)) {
			$this->_salida = new $clase($this);
		}
	}

    protected function mensaje_responsive_scroll() {
	    // Si el cuadro no tiene datos, no debería mostrar el mensaje
	    if ($this->datos != null) {
            $elemento = "<div class='mensaje-scroll-cuadro alert-info visible-xs' role='alert'>";
            $elemento .= "<strong>Deslice horizontalmente </strong>";
            $elemento .= "para ver todas las opciones.";
            $elemento .= "</div>";

            $script = "<script>";
            $script .= "$(document).ready(function() {";
            $script .= "$('#cuerpo_js_" . $this->get_id_form() . "').parent().before(\"" . $elemento . "\");";
            $script .= "});";
            $script .= "</script>";

            echo $script;
        }
    }
	
	protected function crear_corte(&$nodo, $es_ultimo)
	{
		static $id_corte_control = 0;
		$id_corte_control++;
		$id_unico = $this->_submit . '__cc_' .$id_corte_control;
		//Disparo las funciones de sumarizacion creadas por el usuario para este corte
		if(isset($this->_cortes_def[$nodo['corte']]['sum_usuario'])){
			foreach($this->_cortes_def[$nodo['corte']]['sum_usuario'] as $sum){
				$metodo = $this->_sum_usuario[$sum]['metodo'];
				$nodo['sum_usuario'][$sum] = $this->$metodo($nodo['filas']);
			}
		}
		$this->generar_cabecera_corte_control($nodo, $id_unico);
		echo "<div>";
		//Genero el corte
		$estilo = $this->get_estilo_inicio_colapsado($nodo);
		$this->generar_inicio_zona_colapsable($id_unico, $estilo);
	
		//Disparo la generacion recursiva de hijos
		if(isset($nodo['hijos'])){
			$this->generar_cc_inicio_nivel();
			$i = 0;
			foreach(array_keys($nodo['hijos']) as $corte){
				$hijo_es_ultimo = ($i == count($nodo['hijos']) -1);
				$this->crear_corte( $nodo['hijos'][$corte] , $hijo_es_ultimo);
				echo "</div>";
				$i++;
			}
			$this->generar_cc_fin_nivel();
		}else{
			//Disparo la construccion del ultimo nivel
			$temp = null;
			$this->generar_cuadro( $nodo['filas'], $temp, $nodo); //Se pasa el nodo para las salidas no-html
		}
		$this->generar_fin_zona_colapsable();
		$this->generar_pie_corte_control($nodo, $es_ultimo);
	}
	
	function generar_html_barra_sup($titulo=null, $control_titulo_vacio=false, $estilo="")
	{
	    // Esta línea la agrego para el #18007
	    $this->mensaje_responsive_scroll();
	    // ***********************************
		if ($this->_mostrar_barra_superior) {
				
			$botonera_en_item = false;
			if (isset($this->_info_ci['botonera_barra_item']) && $this->_info_ci['botonera_barra_item']) {
				$botonera_en_item = true;
			}
			$botonera_sup = $this->hay_botones() && isset($this->_posicion_botonera) && ($this->_posicion_botonera == "arriba" ||
					$this->_posicion_botonera == "ambos") && ! $botonera_en_item;
					$tiene_titulo = trim($this->_info["titulo"])!="" || trim($titulo) != '';
					$fuerza_titulo = (isset($this->_info_cuadro) && $this->_info_cuadro['siempre_con_titulo'] == '1');
					if ($botonera_sup || !$control_titulo_vacio || $tiene_titulo || $fuerza_titulo) {
						if (!isset($titulo)) {
							$titulo = $this->_info["titulo"];
						}
						if ($botonera_sup) {
						 if (!$tiene_titulo) {
						 	$estilo = "ei-barra-sup-sin-tit $estilo";
						 } else {
						 	$estilo = "ei-barra-sup $estilo";
						 }
						}
						if (!$botonera_sup && $tiene_titulo) {
							$estilo = 'ei-barra-sup ' . $estilo. ' ei-barra-sup-sin-botonera';
						}
						//ei_barra_inicio("ei-barra-sup $estilo");
	
						//---Barra de colapsado
						$colapsado = "";
						// Se colapsa cuando no hay botones o cuando hay pero no esta la botonera arriba
						$colapsado_coherente = (! $this->hay_botones() || ($this->hay_botones() && !$this->botonera_arriba()));
						if ($this->_info['colapsable'] && isset($this->objeto_js) && $colapsado_coherente) {
							$colapsado = "style='cursor: pointer; cursor: hand;' onclick=\"{$this->objeto_js}.cambiar_colapsado();\" title='Mostrar / Ocultar'";
						}
						
						if ( ! ($colapsado == "" && isset($titulo) && trim($titulo) == "") ){
							echo "<div class='panel-heading' $colapsado>\n";
						
							//--> Botonera
							if ($botonera_sup) {
								$this->generar_botones();
							}
							//--- Descripcion Tooltip
							if(trim($this->_info["descripcion"])!="" &&  $this->_modo_descripcion_tooltip){
								echo '<span class="ei-barra-sup-desc">';
								$desc = toba_parser_ayuda::parsear($this->_info["descripcion"]);
								echo toba_recurso::imagen_toba("descripcion.gif",true,null,null, $desc);
								echo '</span>';
							}
		
							//---Barra de colapsado
							if ($this->_info['colapsable'] && isset($this->objeto_js) && $colapsado_coherente) {
								$img_min = toba_recurso::imagen_toba('nucleo/sentido_asc_sel.gif', false);
								echo "<img class='ei-barra-colapsar' id='colapsar_boton_{$this->objeto_js}' src='$img_min'>";
							}
		
							//---Titulo
							echo $titulo;
							echo "</div>";
						}
						//echo ei_barra_fin();
					}
						
					//--- Descripcion con barra. Muestra una barra en lugar de un tooltip
					if(trim($this->_info["descripcion"])!="" &&  !$this->_modo_descripcion_tooltip){
						$tipo = isset($this->_info['descripcion_tipo']) ? $this->_info['descripcion_tipo'] : null;
						$this->generar_html_descripcion($this->_info['descripcion'], $tipo);
					}
					echo "<div id='{$this->_submit}_notificacion'>";
					foreach ($this->_notificaciones as $notificacion){
						$this->generar_html_descripcion($notificacion['mensaje'], $notificacion['tipo']);
					}
					echo "</div>";
					$this->_notificaciones = array();
		}
	
	}
	
	protected function generar_html_descripcion($mensaje, $tipo=null)
	{
		if (! isset($tipo) || $tipo == 'info') {
			$imagen = "<span class='glyphicon glyphicon-info-sign'></span>";
			$clase = 'info';
		} elseif ($tipo== 'warning') {
			$imagen = "<span class='glyphicon glyphicon-warning-sign''></span>";
			$clase = 'warning';
		} elseif ($tipo == 'error') {
			$imagen = "<span class='glyphicon glyphicon-remove-sign></span>";
			$clase = 'danger';
		}
		$descripcion = toba_parser_ayuda::parsear($mensaje);
		echo "<div class='alert alert-$clase' role='alert'>$imagen $descripcion </div>";
	}
	
	/*****************************************************/
	/** 				EVENTOS							 */
	/*****************************************************/
	function get_invocacion_evento_fila($evento, $fila, $clave_fila, $salida_como_vinculo = false, $param_extra = array())
	{
		$invoc_evt = '';
		$id = $evento->get_id();
		if( ! $evento->esta_anulado() ) { //Si el evento viene desactivado de la conf, no lo utilizo
			//1: Posiciono al evento en la fila
			$evento->set_parametros($clave_fila);
			if($evento->posee_accion_vincular()) {
				$parametros = $param_extra;
				$parametros[apex_ei_evento] = $id;
				$parametros['fila'] = $fila;
				$evento->vinculo(true)->set_parametros($parametros);
			}
			//2: Ventana de modificacion del evento por fila
			//- a - ¿Existe una callback de modificacion en el CONTROLADOR?
			$callback_modificacion_eventos_contenedor = 'conf_evt__' . $this->_parametros['id'] . '__' . $id;
			if (method_exists($this->controlador, $callback_modificacion_eventos_contenedor)) {
				$this->controlador->$callback_modificacion_eventos_contenedor($evento, $fila);
			} else {
				//- b - ¿Existe una callback de modificacion una subclase?
				$callback_modificacion_eventos = 'conf_evt__' . $id;
				if (method_exists($this, $callback_modificacion_eventos)) {
					$this->$callback_modificacion_eventos($evento, $fila);
				}
			}
			//3: Genero el boton o el js para el link
			if( ! $evento->esta_anulado() ) {
				if ($salida_como_vinculo) {								//Si es un vinculo lo que se envia
					$evento->set_en_botonera(false);
					$evento->set_nivel_de_fila(false);
					$evento->ocultar();
					$invoc_evt = $evento->get_invocacion_js($this->objeto_js, $this->_id);
				} else if ($evento->posee_accionar_diferido()) {		//Si es un evento que no dispara submit inmediatamente (solo para el cuadro por ahora)
					$invoc_evt = $evento->get_html_evento_diferido($id .$this->_submit, $fila, $this->objeto_js, $this->_id);
				} else {																				//Cualquier otro evento, inclusive los de multiple seleccion.
					$invoc_evt = $evento->get_html($this->_submit.$fila, $this->objeto_js, $this->_id);
				}
			} else {
				$evento->restituir();	//Lo activo para la proxima fila
			}
		}
		return $invoc_evt;
	}

	
	

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__archivar = function()
		{
		}
		";
	}

}
?>