<?php

use ext_bootstrap\componentes\botones\bootstrap_evento_usuario;
use ext_bootstrap\componentes\interfaz\bootstrap_form;

/**
 * Esta clase, es una copia de bootstrap_formulario, ya que por la herencia que maneja
 * Toba, no es posible extenderlo sin tener que tocar codigo del mismo. Por lo tanto,
 * Se hace un copy&paste. Esto lleva a mantener el mismo codigo en ambos lados.
 * 
 * @author Paulo Toledo  < ptoledo@siu.edu.ar >
 * @category Extension Toba
 * @version 1.0.0
 */
class bootstrap_ml_formulario extends \toba_ei_formulario_ml
{
	/**
	 * Se sobreescribte el método para incluir los JS que 
	 * cambian el comportamiento y utiliza JQuery para el acceso del DOM
	 * 
	 * {@inheritDoc}
	 * @see toba_ei_formulario_ml::get_consumo_javascript()
	 */
	function get_consumo_javascript()
	{
		$consumo = parent::get_consumo_javascript();
		$consumo[] = '../../'.toba_recurso::url_proyecto().'/bt-assets/js/bt_formulario';
		//$consumo[] = '../../kolla/bt-assets/js/bt_formulario';
        $consumo[] = '../../'.toba_recurso::url_proyecto(). '/bt-assets/js/bt_formulario_ml';
		//$consumo[] = '../../kolla/bt-assets/js/bt_formulario_ml';
	
		return $consumo;
	}
	
	protected function cargar_lista_eventos()
	{
		foreach ($this->_info_eventos as $info_evento) {
			$e = new bootstrap_evento_usuario($info_evento, $this);
			$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
			$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;	//Lista de utilizados
            
			if ( $e->es_implicito() ) {
				toba::logger()->debug($this->get_txt() . " IMPLICITO: " . $e->get_id(), 'toba');
				$this->_evento_implicito = $e;
			}
		}
	}
	
	/**
	 * Se sobreescribe el metodo para poder referencias a los componentes EFs
	 * redefinidos para el manejo de bootstrap
	 * 
	 * {@inheritDoc}
	 * @see toba_ei_formulario::crear_elementos_formulario()
	 */
	protected function crear_elementos_formulario()
	{
		$this->_lista_ef = array();
		for($a=0; $a<count($this->_info_formulario_ef); $a++)
		{
			//-[1]- Separa los efs segun su tipo en varias listas.
			$id_ef = $this->_info_formulario_ef[$a]['identificador'];
			$this->separar_listas_efs($id_ef, $this->_info_formulario_ef[$a]['elemento_formulario']);
	
			//Preparo el identificador del dato que maneja el EF.
			$dato = $this->clave_dato_multi_columna($this->_info_formulario_ef[$a]['columnas']);
	
			$parametros = $this->_info_formulario_ef[$a];
			if (isset($parametros['carga_sql']) && !isset($parametros['carga_fuente'])) {
				$parametros['carga_fuente']=$this->_info['fuente'];
			}
			$this->_parametros_carga_efs[$id_ef] = $parametros;
			//Nombre	del formulario.
			$clase_ef = 'ext_bootstrap\\componentes\\efs\\bootstrap_'.$this->_info_formulario_ef[$a]['elemento_formulario'];;
			$this->instanciar_ef($id_ef, $clase_ef, $a, $dato, $parametros);
		}
		//--- Se registran las cascadas porque la validacion de efs puede hacer uso de la relacion maestro-esclavo
		$this->_carga_opciones_ef = new toba_carga_opciones_ef($this, $this->_elemento_formulario, $this->_parametros_carga_efs);
		$this->_carga_opciones_ef->registrar_cascadas();
	}
	
	/**
	 * @see bootstrap_formulario->generar_html()
	 */
	function generar_html()
	{
		$this->_rango_tabs = toba_manejador_tabs::instancia()->reservar(1000); // Formulario ML
		//Genero la interface
		echo "\n\n<!-- ***************** Inicio EI FORMULARIO (	".	$this->_id[1] ." )	***********	-->\n\n";
		//Campo de sincroniacion con JS
		echo toba_form::hidden($this->_submit, '');
		echo toba_form::hidden($this->_submit.'_implicito', '');
	
		echo "<div class='panel panel-default'>";
		echo 	$this->get_html_barra_editor();
		$this->generar_html_barra_sup();
		$this->generar_formulario();
	
		echo "</div>\n";
		$this->_flag_out = true;
	}

	function generar_html_barra_sup($titulo=null, $control_titulo_vacio=false, $estilo="")
    {
		$colapsado_coherente = (! $this->hay_botones() || ($this->hay_botones() && !$this->botonera_arriba()));
		$tiene_titulo = trim($this->_info["titulo"])!="" || trim($titulo) != '';
	
		if ( $tiene_titulo || ( $this->_info['colapsable'] && isset($this->objeto_js) && $colapsado_coherente ) ) {
			echo "	<div class='panel-heading'>";
			parent::generar_html_barra_sup(null, true,"ei-form-barra-sup");
			echo "	</div>\n";
		}
	}
	
	/**
	 * @see bootstrap_formulario->generar_html_descripcion()
	 */
	protected function generar_html_descripcion($mensaje, $tipo=null)
	{
		if (! isset($tipo) || $tipo == 'info') {
			$imagen = '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>';
			$clase = 'text-info';
		} elseif ($tipo== 'warning') {
			$imagen = '<span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>';
			$clase = 'text-warning';
		} elseif ($tipo == 'error') {
			$imagen = '<span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>';
			$clase = 'text-danger';
		}
		$descripcion = \toba_parser_ayuda::parsear($mensaje);
		echo "<p class='no-margin $clase' role='alert'>$imagen $descripcion</p>";
	}
	
	protected function generar_formulario()
	{
		//--- Si no se cargaron datos, se cargan ahora
		if (!isset($this->_datos)) {
			$this->carga_inicial();
		}
		$style = '';
		$colapsado = (isset($this->_colapsado) && $this->_colapsado) ? "display:none;" : "";
		if ($this->_info_formulario["scroll"]) {
			$alto_maximo = isset($this->_info_formulario["alto"]) ? $this->_info_formulario["alto"] : "auto";
			if ($alto_maximo != 'auto') {
				$style .= "overflow: auto; height: $alto_maximo;";
			}
		}
		echo "<div class='form-horizontal' style='$colapsado $style' id='cuerpo_{$this->objeto_js}'>"; //Comienza el formulario
		echo toba_form::hidden("{$this->objeto_js}_listafilas",'');
		echo toba_form::hidden("{$this->objeto_js}__parametros", '');
		
		$this->generar_layout();
	
		$hay_colapsado = false;
		
		if ($hay_colapsado) {
			$img = toba_recurso::imagen_skin('expandir_vert.gif', false);
			$colapsado = "style='cursor: pointer; cursor: hand;' onclick=\"{$this->objeto_js}.cambiar_expansion();\" title='Mostrar / Ocultar'";
			echo "<div class='ei-form-fila ei-form-expansion'>";
			echo "<img id='{$this->objeto_js}_cambiar_expansion' src='$img' $colapsado>";
			echo "</div>";
		}
		echo "</div>\n"; // Fin de formulario
	}
	
	protected function generar_layout($ancho='auto')
	{
		//-- Botonera excel y pdf
		$this->generar_botonera_exportacion();
		//Botonera de agregar y ordenar
		$this->generar_botonera_manejo_filas();
		echo "<div class='table-resposive'>";
		echo "<table class='table table-condensed'>";
		$this->generar_formulario_encabezado();
		$this->generar_formulario_cuerpo();
		$this->generar_formulario_pie();
		echo "</table>";
		echo "\n</div>";
		if ($this->botonera_abajo()) {
			$this->generar_botones();
		}
	}
	
	function generar_botones($clase = '', $extra='')
	{
		$agregar_abajo = ($this->_info_formulario['filas_agregar'] && $this->_modo_agregar[0]);
		if ($this->hay_botones() || $agregar_abajo) {
			echo "<div class='col-md-12 divider  $clase'>";
			$agregar = $this->_info_formulario['filas_agregar'];
			$ordenar = $this->_info_formulario['filas_ordenar'];
			if ($agregar_abajo && $this->_mostrar_agregar ) {
				
				$texto = "<span class='glyphicon glyphicon-plus'></span>";
				if ($this->_modo_agregar[1] != '') {
					$texto .= ' '.$this->_modo_agregar[1];
				}
				echo bootstrap_form::button_html("{$this->objeto_js}_agregar", $texto, "onclick='{$this->objeto_js}.crear_fila();'", 	$this->_rango_tabs[0]++, '+', 'Crea una nueva fila');
			}
			$this->generar_botones_eventos();
			echo "</div>";
		}
	}
	
	/**
	 * @todo ver de cambiar los iconos de exportación y chequear el responsive
	 * @see toba_ei_formulario_ml::generar_botonera_exportacion()
	 */
	protected function generar_botonera_exportacion()
	{
		if (! isset($this->_info_formulario['exportar_pdf'])) {
			$this->_info_formulario['exportar_pdf'] = 0;
		}
		if (! isset($this->_info_formulario['exportar_xls'])) {
			$this->_info_formulario['exportar_xls'] = 0;
		}

		if (($this->_info_formulario['exportar_pdf'] || $this->_info_formulario['exportar_xls'])) {
			echo "<div class='btn-group' role='group'>";
			if ($this->_info_formulario['exportar_pdf'] == 1) {
				$img = toba_recurso::imagen_toba('extension_pdf.png', true);
				echo "<a class='btn btn-default' href='javascript: {$this->objeto_js}.exportar_pdf()' title='Exporta el listado a formato PDF'>$img</a>";
			}
			if ($this->_info_formulario['exportar_xls'] == 1) {
				$img = toba_recurso::imagen_toba('exp_xls.gif', true);
				echo "<a class='btn btn-default' href='javascript: {$this->objeto_js}.exportar_excel()' title='Exporta el listado a formato Excel (.xls)'>$img</a>";
			}
			echo "</div>\n";
		}
	}
	
	/**
	 * @todo Revisar los titles de los botones porque se rompe todo
	 * {@inheritDoc}
	 * @see toba_ei_formulario_ml::generar_botonera_manejo_filas()
	 */
	protected function generar_botonera_manejo_filas()
	{
		$agregar = $this->_info_formulario['filas_agregar'] && (!$this->_modo_agregar[0] || !$this->_borrar_en_linea);
		$ordenar = $this->_info_formulario['filas_ordenar'];
		if ($agregar || ($ordenar && !$this->_ordenar_en_linea)) {
			echo "<div class='btn-group col-md-12' role='group'>"; // Inicio de botonera
			if ($agregar) {
				if ($this->_mostrar_agregar) {
					if (! $this->_modo_agregar[0]) {
						$img= '<span class="glyphicon glyphicon-plus"></span>';
						if ($this->_modo_agregar[1] != '') {
							$img .= $this->_modo_agregar[1];
						} 
						echo bootstrap_form::button_html("{$this->objeto_js}_agregar", $img,
						"onclick='{$this->objeto_js}.crear_fila();'", $this->_rango_tabs[0]++, '+', 'Crea una nueva fila');
					}
				}
				if (! $this->_borrar_en_linea) {
					$img= '<span class="glyphicon glyphicon-minus"></span>';
					echo bootstrap_form::button_html("{$this->objeto_js}_eliminar", $img,
					"onclick='{$this->objeto_js}.eliminar_seleccionada();' disabled", $this->_rango_tabs[0]++, '-', 'Elimina la fila seleccionada');
				}
			}
				
			if ($this->_info_formulario['filas_agregar'] ) {		//Si se pueden agregar o quitar filas, el deshacer debe estar
				//$html = toba_recurso::imagen_toba('nucleo/deshacer.gif', true)."<span id='{$this->objeto_js}_deshacer_cant'  style='font-size: 8px;'></span>";
				$html = "<span class='glyphicon glyphicon-refresh' id='{$this->objeto_js}_deshacer_cant'></span>";
				echo bootstrap_form::button_html("{$this->objeto_js}_deshacer", $html,
				" onclick='{$this->objeto_js}.deshacer();' disabled", $this->_rango_tabs[0]++, 'z', 'Deshace la última eliminación');
				
			}
				
			if ($ordenar && !$this->_ordenar_en_linea) {
				$arriba = '<span class="glyphicon glyphicon-arrow-up"></span>';;
				$abajo = '<span class="glyphicon glyphicon-arrow-down"></span>';;
				echo bootstrap_form::button_html("{$this->objeto_js}_subir", $arriba,
				"onclick='{$this->objeto_js}.subir_seleccionada();' disabled ", $this->_rango_tabs[0]++, '<', 'Sube una posición la fila seleccionada');
				echo bootstrap_form::button_html("{$this->objeto_js}_bajar", $abajo,
				"onclick='{$this->objeto_js}.bajar_seleccionada();' disabled ", $this->_rango_tabs[0]++, '>', 'Baja una posición la fila seleccionada');
			}
			echo "</div>\n"; // Fin de botonera
		}
	}
	
	protected function generar_formulario_encabezado()
	{
		//¿Algún EF tiene etiqueta?
		$alguno_tiene_etiqueta = false;
		foreach ($this->_lista_ef_post as $ef) {
			if ($this->_elemento_formulario[$ef]->get_etiqueta() != '') {
				$alguno_tiene_etiqueta = true;
				break;
			}
		}
		if ($alguno_tiene_etiqueta) {
			echo "<thead id='cabecera_{$this->objeto_js}' >\n";
			//------ TITULOS -----
			echo "<tr>\n";
			$primera = true;
			if ($this->_info_formulario['filas_numerar']) {
				// Cambio la siguiente línea para el ticket #17309
			    //echo "<th>#</th>\n";
                echo "<th class='col-md-1'>#</th>\n";
			}
			foreach ($this->_lista_ef_post	as	$ef) {
				$id_form = $this->_elemento_formulario[$ef]->get_id_form_orig();
				$extra = '';
				if ($primera) {
					$extra = '';
				}
				echo "<th $extra id='nodo_$id_form' class='ei-ml-columna'>\n";
				if ($this->_elemento_formulario[$ef]->get_toggle()) {
					$this->_hay_toggle = true;
					$id_form_toggle = 'toggle_'.$id_form;
					echo "<input id='$id_form_toggle' type='checkbox' class='ef-checkbox' onclick='{$this->objeto_js}.toggle_checkbox(\"$ef\")' />";
				}
				$this->generar_etiqueta_columna($ef);
				echo "</th>\n";
				$primera = false;
			}
			if ($this->_info_formulario['filas_ordenar'] && $this->_ordenar_en_linea) {
				echo "<th class='ei-ml-columna'>&nbsp;\n";
				echo "</th>\n";
			}
			//-- Eventos sobre fila
			if ($this->cant_eventos_sobre_fila() > 0) {
				echo "<th class='ei-ml-columna ei-ml-columna-extra'>&nbsp;\n";
				foreach ($this->get_eventos_sobre_fila() as $evento) {
					if (toba_editor::modo_prueba()) {
						echo toba_editor::get_vinculo_evento($this->_id, $this->_info['clase_editor_item'], $evento->get_id())."\n";
					}
				}
				echo "</th>\n";
			}
			if ($this->_info_formulario['filas_agregar'] && $this->_borrar_en_linea) {
				echo "<th class='ei-ml-columna'>&nbsp;\n";
				echo "</th>\n";
			}
			echo "</tr>\n";
			echo "</thead>\n";
		}
	}

	protected function generar_formulario_cuerpo()
	{
		echo "<tbody>";
		if ($this->_registro_nuevo !== false) {
			$template = (is_array($this->_registro_nuevo)) ? $this->_registro_nuevo : array();
			$this->agregar_registro($template);
		}
		//------ FILAS ------
		$this->_filas_enviadas = array();
		if (!isset($this->_ordenes)) {
			$this->_ordenes = array();
		}
		//Se recorre una fila más para insertar una nueva fila 'modelo' para agregar en js
		if ( $this->_info_formulario['filas_agregar'] && $this->_info_formulario['filas_agregar_online']) {
			$this->_datos["__fila__"] = array();
			$this->_ordenes[] = "__fila__";
		}
		$a = 0;
		foreach ($this->_ordenes as $fila) {
			$dato = $this->_datos[$fila];
			//Si la fila es el template ocultarla
			if ($fila !== "__fila__") {
				$estilo_fila = '';
				$this->_filas_enviadas[] = $fila;
				$nombre_metodo = 'conf__'. $this->_id_en_controlador. '_estilo_fila';
				if (method_exists($this->controlador(), $nombre_metodo)) {
					$estilo_fila = "class = '{$this->controlador()->$nombre_metodo($dato)}' ";
				}
			} else {
				$estilo_fila = "style='display:none;'";
			}
			//Determinar el estilo de la fila
			if (isset($this->_clave_seleccionada) && $fila == $this->_clave_seleccionada) {
				$this->estilo_celda_actual = "warning";
			} else {
				$this->estilo_celda_actual = "";
			}
			$this->cargar_registro_a_ef($fila, $dato);
			//--- Se cargan las opciones de los efs de esta fila
			$this->_carga_opciones_ef->cargar();
			//--- Ventana para poder configurar una fila especifica
			$callback_configurar_fila_contenedor = 'conf_fila__' . $this->_parametros['id'];
			if (method_exists($this->controlador, $callback_configurar_fila_contenedor)) {
				$this->controlador->$callback_configurar_fila_contenedor($fila);
			}
			//-- Inicio html de la fila
			echo "<tr class='{$this->estilo_celda_actual}' $estilo_fila id='{$this->objeto_js}_fila$fila' onclick='{$this->objeto_js}.seleccionar($fila)'>";
			if ($this->_info_formulario['filas_numerar']) {
				echo "<td><span id='{$this->objeto_js}_numerofila$fila'>".($a + 1)."</span></td>\n";
			}
			//--Layout de las filas
			$this->generar_layout_fila($fila);
			//--Numeración de las filas
			if ($this->_info_formulario['filas_ordenar'] && $this->_ordenar_en_linea) {
				$arriba = '<span class="glyphicon glyphicon-arrow-up"></span>';;
				$abajo = '<span class="glyphicon glyphicon-arrow-down"></span>';;
				echo "<td >\n";
				echo "<a href='javascript: {$this->objeto_js}.subir_seleccionada();' id='{$this->objeto_js}_subir$fila' style='visibility:hidden' title='Subir la fila'>".
						$arriba."</a>";
						echo "<a href='javascript: {$this->objeto_js}.bajar_seleccionada();' id='{$this->objeto_js}_bajar$fila' style='visibility:hidden' title='Bajar la fila'>".
								$abajo."</a>";
				echo "</td>\n";
			}
			//--Creo los EVENTOS de la FILA
			$this->generar_eventos_fila($fila);
	
			//-- Borrar a nivel de fila
			if ($this->_info_formulario['filas_agregar'] && $this->_borrar_en_linea) {
				echo "<td>";
				echo toba_form::button_html("{$this->objeto_js}_eliminar$fila", toba_recurso::imagen_toba('borrar.gif', true),
				"onclick='{$this->objeto_js}.seleccionar($fila);{$this->objeto_js}.eliminar_seleccionada();'",
				$this->_rango_tabs[0]++, null, 'Elimina la fila');
				echo "</td>\n";
			}
				
			echo "</tr>\n";
			$a++;
		}
		echo "</tbody>\n";
	}
	
	protected function generar_layout_fila($clave_fila)
	{
		foreach ($this->_lista_ef_post as $ef) {
			//--- Multiplexacion de filas
			$this->_elemento_formulario[$ef]->ir_a_fila($clave_fila);
			$id_form = $this->_elemento_formulario[$ef]->get_id_form();
			echo "<td id='cont_$id_form'>\n";
			echo "<div id='nodo_$id_form'>\n";
			$this->generar_input_ef($ef);
			echo "</div>";
			echo "</td>\n";
		}
	}
	
	protected function generar_eventos_fila($fila)
	{
		echo "<td class='text-right'>\n";
		foreach ($this->get_eventos_sobre_fila() as $id => $evento) {
			
			echo $this->get_invocacion_evento_fila($evento, $fila, $fila, false);
			
		}
		echo "</td>\n";
	}
	
	protected function get_html_ef($ef, $ancho_etiqueta=null, $con_etiqueta=true)
	{
		$salida = '';
		if (! in_array($ef, $this->_lista_ef_post)) {
			//Si el ef no se encuentra en la lista posibles, es probable que se alla quitado con una restriccion o una desactivacion manual
			return;
		}
		$clase = 'form-group';
		$estilo_nodo = "";
		$id_ef = $this->_elemento_formulario[$ef]->get_id_form();
	
		if (! $this->_elemento_formulario[$ef]->esta_expandido()) {
			$clase .= ' ei-form-fila-oculta';
			$estilo_nodo = "display:none";
		}
		if (isset($this->_info_formulario['resaltar_efs_con_estado']) && $this->_info_formulario['resaltar_efs_con_estado'] && $this->_elemento_formulario[$ef]->seleccionado()) {
			$clase .= ' ei-form-fila-filtrada';
        }
        $es_fieldset = ($this->_elemento_formulario[$ef] instanceof toba_ef_fieldset);
        if (! $es_fieldset) {							//Si es fieldset no puedo sacar el <div> porque el navegador cierra visualmente inmediatamente el ef.
            $salida .= "<div class='$clase' style='$estilo_nodo' id='nodo_$id_ef'>\n";
        }
        if ($this->_elemento_formulario[$ef]->tiene_etiqueta() && $con_etiqueta) {
            $salida .= $this->get_etiqueta_ef($ef, $ancho_etiqueta);

            $salida .= "<div id='cont_$id_ef' class='col-sm-5'>\n";
            $salida .= $this->get_input_ef($ef);
            $salida .= "</div>";
            if (isset($this->_info_formulario['expandir_descripcion']) && $this->_info_formulario['expandir_descripcion']) {
                $salida .= '<span class="ei-form-fila-desc">'.$this->_elemento_formulario[$ef]->get_descripcion().'</span>';
            }

        } else {
            $salida .= $this->get_input_ef($ef);
        }
        if (! $es_fieldset) {
            $salida .= "</div>\n";
        }
        return $salida;
	}
	
}
