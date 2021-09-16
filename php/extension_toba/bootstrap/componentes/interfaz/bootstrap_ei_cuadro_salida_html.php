<?php
namespace ext_bootstrap\componentes\interfaz;

class bootstrap_ei_cuadro_salida_html extends \toba_ei_cuadro_salida_html{

	protected $profundidad_table;
	protected $id_table;

	function html_cuadro(&$filas, $totales=0, $nodo=null)
	{
		//Determino el ancho del cuadro dependiendo de los cortes de control
		$nivel = $nodo['profundidad'];
		if( $nivel == 0 ) // si es uno solo, no hay anidamiento no necesito agregar nada
			$this->profundidad_table = '';
		if ( $nivel != 0 )
			$this->profundidad_table = " col-md-".(13 -$nivel)." col-md-offset-".($nivel-1);

		parent::html_cuadro($filas,$totales,$nodo);
	}

	/**
	 * @ignore
	 */
	function html_inicio()
	{
		$this->_cuadro->resetear_claves_enviadas();
		$id_js = $this->_cuadro->get_id_objeto_js();
		$total_col = $this->_cuadro->get_cantidad_columnas_total();
		$muestra_titulo_cc = $this->_cuadro->debe_mostrar_titulos_columnas_cc();
		$cuadro_colapsa = $this->_cuadro->es_cuadro_colapsado();

		$this->html_generar_campos_hidden();
		//-- Scroll y tabla Base
		$this->generar_tabla_base();
		echo $this->get_html_barra_editor();

		$this->generar_html_barra_sup(null, true,"ei-cuadro-barra-sup");


		$colapsado = ($cuadro_colapsa) ? "style='display:none'" : "";

		echo "<div $colapsado id='cuerpo_$id_js'>"; //-- INICIO zona COLAPSABLE del cuadro completo

		//------- Cabecera -----------------
		echo "<div class='panel-body custom'>";
		$this->html_cabecera();
		echo "</div>";

		// Si el layout es cortes/tabular se genera una sola tabla, que empieza aca
		if( $this->_cuadro->tabla_datos_es_general() ){
			$this->html_cuadro_inicio();
		}
		//-- Se puede por api cambiar a que los titulos de las columnas se muestren antes que los cortes
		if ($muestra_titulo_cc) {
			$this->html_cuadro_cabecera_columnas();
		}
	}

	function html_fin()
	{
		$acumulador = $this->_cuadro->get_acumulador_general();
		$info_cuadro = $this->_cuadro->get_informacion_basica_cuadro();
		if (isset($acumulador)) {
			$this->html_cuadro_totales_columnas($acumulador);
		}
		//$this->html_acumulador_usuario();
		if( $this->_cuadro->tabla_datos_es_general() ){
			$this->html_cuadro_fin();
		}

		// Pie
		echo"<div> \n";
		$this->html_pie();
		echo "</div>\n";

		if ($info_cuadro["paginar"]) {
			echo"<div>";
			$this->html_barra_paginacion();
			echo "</div>";
		}


		//Botonera
		if ($this->_cuadro->hay_botones() && $this->_cuadro->botonera_abajo()) {
			$this->generar_botones();
		}
		echo "</div>\n";//-- FIN zona COLAPSABLE

		echo "</div> \n"; // FIN del panel



		//Aca tengo que meter el javascript y el html del cosote para ordenar
		if ($info_cuadro["ordenar"]) {
			$this->html_selector_ordenamiento();
		}
	}

	protected function html_cabecera()
	{
		echo "<div class='pull-left'>";
		$info_cuadro = $this->_cuadro->get_informacion_basica_cuadro();
		$objeto_js = $this->_cuadro->get_id_objeto_js();

		if (isset($info_cuadro) && $info_cuadro['exportar_pdf'] == 1) {
			$img = \toba_recurso::imagen_toba('extension_pdf.png', true);
			echo "<a href='javascript: $objeto_js.exportar_pdf()' title='Exporta el listado a formato PDF'>$img</a>";
		}
		if (isset($info_cuadro) && $info_cuadro['exportar_xls'] == 1) {
			//Si hay vista xls entonces se muestra el link común y para exportar a plano
			if ($this->_cuadro->permite_exportacion_excel_plano()) {
				$img_plano = \toba_recurso::imagen_toba('exp_xls_plano.gif', true);
				echo "<a href='javascript: $objeto_js.exportar_excel_sin_cortes()' title='Exporta el listado a formato Excel sin cortes (.xls)'>$img_plano</a>";
			}
			$img = \toba_recurso::imagen_toba('exp_xls.gif', true);
			echo "<a href='javascript: $objeto_js.exportar_excel()' title='Exporta el listado a formato Excel (.xls)'>$img</a>";
		}
		if ($info_cuadro["ordenar"]) {
			//$img = \toba_recurso::imagen_toba('ordenar.gif', true);
			$glyphicon = "<span class='glyphicon glyphicon-sort' style='color:#000000'></span>";
			$filas = \toba_js::arreglo($this->_cuadro->get_filas_disponibles_selector());
			echo "<a href=\"javascript: $objeto_js.mostrar_selector($filas);\" title='Permite ordenar por múltiples columnas'>$glyphicon</a>";
		}
		if(trim($info_cuadro["subtitulo"])<>""){
			echo $info_cuadro["subtitulo"];
		}
		echo "</div>";
		echo "<div class='pull-right'>";
		$this->html_barra_total_registros();
		echo "</div>";

	}

	protected function generar_tabla_base()
	{
		$info_cuadro = $this->_cuadro->get_informacion_basica_cuadro();
		echo "<div class='panel panel-default' >";
	}

	protected function html_cuadro_cabecera_columnas()
	{
		//¿Alguna columna tiene título?
		$alguna_tiene_titulo = false;
		$columnas = $this->_cuadro->get_columnas();
		foreach(array_keys($columnas) as $clave) {
			if (trim($columnas[$clave]["titulo"]) != '') {
				$alguna_tiene_titulo = true;
				break;
			}
		}
		if ($alguna_tiene_titulo) {
			/*
			 * Verifico si el grupo tiene columnas visibles, sino no lo muestro,
			 * al mismo tiempo intersecto las columnas del grupo con las visibles para que no se expanda de mas el colspan.
			 */
			$hay_grupo_visible = false;
			$columnas_act_id = array_keys($columnas);
			$columnas_agrupadas = $this->_cuadro->get_columnas_agrupadas();
			foreach($columnas_agrupadas as $klave =>  $grupo) {
				foreach ($columnas_act_id as $a) {
					$hay_grupo_visible = ($hay_grupo_visible || in_array($a, $grupo));
				}
				$columnas_agrupadas[$klave] = array_intersect($grupo, $columnas_act_id);
			}

			$rowspan = ! $hay_grupo_visible ? '' : "rowspan='2'";
			$html_columnas_agrupadas = '';
			$grupo_actual = null;
			echo "<thead> \n";
			echo "<tr> \n";
			$this->html_cuadro_cabecera_columna_evento($rowspan, true);
			foreach (array_keys($columnas) as $a) {
				$html_columna = '';
				//El alto de la columna, si esta agrupada es uno sino es el general
				$rowspan_col = isset($columnas[$a]['grupo']) ? "" : $rowspan;

				if(isset($columnas[$a]["ancho"])){
					$ancho = " width='". $columnas[$a]["ancho"] . "'";
				}else{
					$ancho = "";
				}
				$estilo_columna = $columnas[$a]["estilo_titulo"];
				if(!$estilo_columna){
					//$estilo_columna = 'ei-cuadro-col-tit';
                    $estilo_columna = '';
				}
				$html_columna .= "<th class='$estilo_columna'> \n";
                //$html_columna .= "<th> \n";
				$html_columna .= $this->html_cuadro_cabecera_columna(    $columnas[$a]["titulo"],
						$columnas[$a]["clave"],
						$a );
				$html_columna .= "</th> \n";

				if (! isset($columnas[$a]['grupo']) || $columnas[$a]['grupo'] == '') {
					//Si no es una columna agrupada,saca directamente su html
					echo $html_columna;
					$grupo_actual = null;
				} else {
					//Guarda el html de la columna para sacarlo una fila mas abajo
					$html_columnas_agrupadas .= $html_columna;
					//Si es la primera columna de la agrupación saca un unico <td> del ancho de la agrupacion
					if (! isset($grupo_actual) || $grupo_actual != $columnas[$a]['grupo']) {
						$grupo_actual = $columnas[$a]['grupo'];
						$cant_col = count(array_unique($columnas_agrupadas[$grupo_actual]));		//Cuando se fija manualmente el grupo y se re procesa la definicion trae la misma columna + de una vez
						echo "<th class='ei-cuadro-col-tit ei-cuadro-col-tit-grupo' colspan='$cant_col'>$grupo_actual</th>";
					}
				}
			}
			//-- Eventos sobre fila
			$this->html_cuadro_cabecera_columna_evento($rowspan, false);
			echo "</tr> \n";
			echo "</thead> \n";
			//-- Columnas Agrupadas
			if ($html_columnas_agrupadas != '') {
				echo "<tr>\n";
				echo $html_columnas_agrupadas;
				echo "</tr>\n";
			}

		}
	}

	protected function html_cuadro_cabecera_columna($titulo,$columna,$indice)
	{
		$salida = '';
		$eventos = $this->_cuadro->get_eventos();
		$columnas = $this->_cuadro->get_columnas();
		$objeto_js = $this->_cuadro->get_id_objeto_js();

		//--- ¿Es ordenable?
		if (isset($eventos['ordenar']) && $columnas[$indice]["no_ordenar"] != 1 ){
				$sentido = [ ['asc', 'Ordenar ascendente','glyphicon-triangle-top'], ['des', 'Ordenar descendente','glyphicon-triangle-bottom'] ];

				$salida .= "";
				foreach($sentido as $sen){
					$clase_css="";
					if ($this->_cuadro->es_sentido_ordenamiento_seleccionado($columna, $sen[0])) {
						$clase_css = "text-red";//orden ACTIVO
					}

					//Comunicación del evento
					$parametros = [ 'orden_sentido'=>$sen[0], 'orden_columna'=>$columna ];
					$evento_js = \toba_js::evento('ordenar', $eventos['ordenar'], $parametros);
					$js = "$objeto_js.set_evento($evento_js);";
					$salida .= "<a href='#' onclick=\"$js\"><span class='glyphicon $sen[2] $clase_css'  data-toggle='tooltip' data-placement='top' title='$sen[1]'></span></a>";
					//$src = \toba_recurso::imagen_toba("nucleo/sentido_". $sen[0] . $sel . ".gif");
					//$salida .= \toba_recurso::imagen($src, null, null, $sen[1], '', "onclick=\"$js\"", 'cursor: pointer; cursor:hand;');
				}
				$salida .= "";
		}
		//--- Nombre de la columna
		if (trim($columna) != '' || trim($columnas[$indice]["vinculo_indice"])!="") {
			$salida .= " $titulo";
		}
		//---Editor de la columna
		if ( \toba_editor::modo_prueba()) {
			$item_editor = "1000253";
			$param_editor = array( apex_hilo_qs_zona => implode(apex_qs_separador,$this->_cuadro->get_id()),
					'columna' => $columna );
			$salida .= \toba_editor::get_vinculo_subcomponente($item_editor, $param_editor);
		}
		return $salida;
	}


	protected function html_cuadro_inicio()
	{


		echo "<div id='{$this->id_table}' class='table-responsive {$this->profundidad_table}'> \n";
		echo "	<table class='table table-condensed table-hover'> \n";
	}

	protected function html_cuadro_fin()
	{
		echo "	</table> \n";
		echo "</div> \n"; //Cierra el table responsive

	}

	protected function html_barra_paginacion()
	{
		$objeto_js = $this->_cuadro->get_id_objeto_js();
		$total_registros = $this->_cuadro->get_total_registros();
		$tamanio_pagina = $this->_cuadro->get_tamanio_pagina();
		$pagina_actual = $this->_cuadro->get_pagina_actual();
		$cantidad_paginas = $this->_cuadro->get_cantidad_paginas();
		$parametros = $this->_cuadro->get_nombres_parametros();
		$eventos = $this->_cuadro->get_eventos();

		echo "<nav aria-label='pagination'> \n";
		echo "<ul class='pager'> \n";
		if( isset($total_registros) && !($tamanio_pagina >= $total_registros) ) {
			//Calculo los posibles saltos
			//Primero y Anterior
			if($pagina_actual == 1) {
				$anterior = '<li class="disabled"><a href="#"><span aria-hidden="true">&larr;</span> Anterior</a></li> ';
			} else {
				$evento_js = \toba_js::evento('cambiar_pagina', $eventos["cambiar_pagina"], $pagina_actual - 1);
				$js = "$objeto_js.set_evento($evento_js);";
				$anterior = "<li ><a href='#' onclick=\"$js\"><span aria-hidden='true'>&larr;</span> Anterior</a></li>";
			}

			if( $pagina_actual == $cantidad_paginas ) {
				$siguiente = "<li class='disabled'><a href='#'>Siguiente <span aria-hidden='true'>&rarr;</span></a></li> \n";
			} else {
				$evento_js = \toba_js::evento('cambiar_pagina', $eventos["cambiar_pagina"], $pagina_actual + 1);
				$js = "$objeto_js.set_evento($evento_js);";
				$siguiente = "<li ><a href='#' onclick=\"$js\"> Siguiente <span aria-hidden='true'>&rarr;</span></a></li>";


			}

			echo "$anterior Página \n";
			$js = "$objeto_js.ir_a_pagina(this.value);";
			$tamanio = ceil(log10($total_registros));

			echo bootstrap_form::text($parametros['paginado'], $pagina_actual, false, '', $tamanio, 'form-control input-pager', "onchange=\"$js\"");

			echo "</strong> de <strong>{$cantidad_paginas}</strong> $siguiente \n";
		}
		echo "</ul> \n";
		echo "</nav> \n";
	}


	function html_mensaje_cuadro_vacio($texto)
	{
		$this->_cuadro->resetear_claves_enviadas();
		$info_cuadro = $this->_cuadro->get_informacion_basica_cuadro();
		$colapsado = $this->_cuadro->es_cuadro_colapsado();
		$objeto_js = $this->_cuadro->get_id_objeto_js();

		$this->html_generar_campos_hidden();
		$ancho = isset($info_cuadro["ancho"]) ? $info_cuadro["ancho"] : "";
		//-- Tabla BASE
		$ancho = convertir_a_medida_tabla($ancho);

		$this->generar_tabla_base(); //inicio el panel

		echo $this->get_html_barra_editor();
		$this->generar_html_barra_sup(null, true,"ei-cuadro-barra-sup");


		echo "<div class='ei-cuadro-scroll ei-cuadro-cuerpo' $colapsado' id='cuerpo_$objeto_js'>\n";
		echo "<div class='panel-body text-danger text-center'>". ei_mensaje($texto)."</div>";
		if ($this->_cuadro->hay_botones() && $this->_cuadro->botonera_abajo()) {
			$this->generar_botones();
		}
		echo '</div>';
		echo "</div>"; //Cierre del panel
	}

	function generar_botones()
	{
		$this->_cuadro->generar_botones('row divider');
	}


	/*******************************************************************/
	/**                     FILA DE CUADRO                            **/
	/*******************************************************************/

	function generar_layout_fila($columnas, $datos, $id_fila,  $clave_fila, $evt_multiples, $objeto_js, $estilo_fila, $formateo)
	{
		$estilo_seleccion = $this->get_estilo_seleccion($clave_fila);

		//Javascript de seleccion multiple
		$js = $this->get_invocacion_js_eventos_multiples($evt_multiples, $id_fila, $objeto_js);

		echo "<tr>\n"; // Abro tag para la fila

		//---> Creo los EVENTOS de la FILA  previos a las columnas<---
		$this->html_cuadro_celda_evento($id_fila, $clave_fila, true);
		foreach (array_keys($columnas) as $a) {
			//*** 1) Recupero el VALOR
			$valor = "";
			if(isset($columnas[$a]["clave"])) {
				if(isset($datos[$id_fila][$columnas[$a]["clave"]])) {
					$valor_real = $datos[$id_fila][$columnas[$a]["clave"]];
					//-- Hace el saneamiento para evitar inyección XSS
					if (!isset($columnas[$a]['permitir_html']) || $columnas[$a]['permitir_html'] == 0) {
						$valor_real = texto_plano($valor_real);
					}
				}else{
					$valor_real = null;
					//ATENCION!! hay una columna que no esta disponible!
				}
				//Hay que formatear?
				if(isset($columnas[$a]["formateo"])) {
					$funcion = "formato_" . $columnas[$a]["formateo"];
					//Formateo el valor
					$valor = $formateo->$funcion($valor_real);
				} else {
					$valor = $valor_real;
				}
			}

			//*** 2) La celda posee un vinculo??
			if ($columnas[$a]['usar_vinculo'] )  {
				$valor = $this->get_html_cuadro_celda_vinculo($columnas, $a, $id_fila, $clave_fila, $valor);
			}

			//*** 3) Genero el HTML
			$ancho = "";
			if(isset($columnas[$a]["ancho"])) {
				$ancho = " width='". $columnas[$a]["ancho"] . "'";
			}

			//Emito el valor de la celda
			echo "<td $js>\n";
			if (trim($valor) !== '') {
				echo $valor;
			} else {
				echo '&nbsp;';
			}
			echo "</td>\n";
			//Termino la CELDA
		}
		//---> Creo los EVENTOS de la FILA <---
		$this->html_cuadro_celda_evento($id_fila, $clave_fila, false);
		echo "</tr>\n";
	}


	/**
	 * @todo Validar la funcionalidad porque se cambio el orden completo de la creación.
	 * La idea es que todos los botons de una fila queden encerrados en una sola celda
	 *
	 * @see toba_ei_cuadro_salida_html::html_cuadro_celda_evento()
	 */
	protected function html_cuadro_celda_evento($id_fila, $clave_fila, $pre_columnas)
	{
		//Si es primera columna los input quedan centrados, caso contrario a derecha
		$clase_evento = "align=".( !$pre_columnas? "'right'":"'center'"  );
		if (count($this->_cuadro->get_eventos_sobre_fila()) > 0){
			$minimo_uno = false;

			foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
				$minimo_uno = $minimo_uno || !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());
			}

			if($minimo_uno){
				echo "<td $clase_evento'>\n";
			}

			foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
				$grafico_evento = !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());		//Decido si se debe graficar el boton en este lugar (logica explicada en html_cuadro_cabecera_columna_evento)
				if ($grafico_evento) {
					$parametros = $this->get_parametros_interaccion($id_fila, $clave_fila);
					$clase_alineamiento = ($evento->es_seleccion_multiple())?  'col-cen-s1' : '';	//coloco centrados los checkbox si es multiple

					if ($evento->posee_accion_respuesta_popup()) {
						$descripcion_popup = \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila));
						echo  \toba_form::hidden($this->_cuadro->get_id_form(). $id_fila .'_descripcion', \toba_js::sanear_string($this->_cuadro->get_descripcion_resp_popup($id_fila)));	//Podemos hacer esto porque no vuelve nada!
					}
					echo $this->get_invocacion_evento_fila($evento, $id_fila, $clave_fila, false, $parametros);	//ESto hay que ver como lo modifico para que de bien

				}
			}
			if($minimo_uno){
				echo "</td>\n";
			}
			//Se agrega la clave a la lista de enviadas
			$this->_cuadro->agregar_clave_enviada($clave_fila);
		}
	}

	protected function html_cuadro_cabecera_columna_evento($rowspan, $pre_columnas)
	{
		//-- Eventos sobre fila
		if($this->_cuadro->cant_eventos_sobre_fila() > 0) {
			$minimo_uno = false;
			foreach ($this->_cuadro->get_eventos_sobre_fila() as $id => $evento) {
				$minimo_uno = $minimo_uno || !($pre_columnas xor $evento->tiene_alineacion_pre_columnas());
			}
			if($minimo_uno){
				echo "<th class='ancho-minimo-columna-cuadro-75'>\n";
			}
			foreach ($this->_cuadro->get_eventos_sobre_fila() as $evento) {
				$etiqueta = '&nbsp;';
				if ($evento->es_seleccion_multiple()) {
					$etiqueta = $evento->get_etiqueta();
				}

				/**
				 * Condiciones gobernantes:
				 *  Evento con alineacion a Izquierda
				 *  Se estan graficando eventos pre-columnas de datos
				 *
				 *
				 * El evento se grafica unicamente cuando se dan ambas condiciones o
				 * cuando no se cumple ninguna de las dos, logicamente  eso seria:
				 * ((A || !B) && (!A || B)) lo cual es igual a un XOR negado.
				 */
				if ( !($pre_columnas xor $evento->tiene_alineacion_pre_columnas())) {
					if (\toba_editor::modo_prueba()) {
						$info_comp = $this->_cuadro->get_informacion_basica_componente();
						echo \toba_editor::get_vinculo_evento($this->_cuadro->get_id(), $info_comp['clase_editor_item'], $evento->get_id())."\n";
					}

				}
			}
			if($minimo_uno){
				echo "</th>\n";
			}
		}
	}
	//-------------------------------------------------------------------------------
	//-- Generacion de los CORTES de CONTROL
	//-------------------------------------------------------------------------------

	function html_inicio_zona_colapsable($id_unico, $estilo)
	{
		echo "";
	}

	function html_fin_zona_colapsable()
	{
		echo "";
	}


	/**
	 Genera la CABECERA del corte de control
	 */
	function html_cabecera_corte_control(&$nodo, $id_unico = null)
	{
		//Dedusco el metodo que tengo que utilizar para generar el contenido
		$this->id_table = $id_unico;
		$metodo = 'html_cabecera_cc_contenido';
		$metodo_redeclarado = $metodo . '__' . $nodo['corte'];
		if(method_exists($this, $metodo_redeclarado)){
			$metodo = $metodo_redeclarado;
		}
		$nivel_css = $this->get_nivel_css($nodo['profundidad']);

		$class = " col-md-offset-".($nodo['profundidad']-1)." col-md-".(13 - $nodo['profundidad']);

		if($this->_cuadro->get_cortes_modo() == apex_cuadro_cc_tabular) {
			$objeto_js = $this->_cuadro->get_id_objeto_js();
			$total_columnas = $this->_cuadro->get_cantidad_columnas_total();

			$js = "onclick=\"$objeto_js.colapsar_corte('$id_unico');\"";

			echo "<div class='$class ei-cuadro-cc-colapsable' $js >";
			echo "	<div class='alert corte-$nivel_css'>";
			$this->$metodo($nodo);

			if ($this->_cuadro->debe_colapsar_cortes())
				echo "<span class='pull-right glyphicon glyphicon-sort' ><span>";

			echo "	</div>\n";
			echo "</div>";
		}else{
			echo "<li class='$class'>\n";
			$this->$metodo($nodo);
		}
	}

	/**
	 Genera el CONTENIDO de la cabecera del corte de control
	 Muestra las columnas seleccionadas como descripcion del corte separadas por comas
	 */
	protected function html_cabecera_cc_contenido(&$nodo)
	{
		$indice = $this->_cuadro->get_indice_cortes();
		$descripcion = $indice[$nodo['corte']]['descripcion'];
		$valor = implode(", ",$nodo['descripcion']);
		if (trim($descripcion) != '') {
			echo $descripcion . ': <strong>' . $valor . '</strong>';
		} else {
			echo '<strong>' . $valor . '</strong>';
		}
	}

	/**
	 * Genera el PIE del corte de control
	 * Estaria bueno que esto consuma primitivas para:
	 * 	- no pisarse con el contenido anidado.
	 * 	- reutilizar en la regeneracion completa.
	 * @ignore
	 */
	function html_pie_corte_control(&$nodo, $es_ultimo)
	{
		if($this->_cuadro->get_cortes_modo() == apex_cuadro_cc_tabular){				//MODO TABULAR
			$indice = $this->_cuadro->get_indice_cortes();
			$total_columnas = $this->_cuadro->get_cantidad_columnas_total();


			if( ! $this->_cuadro->tabla_datos_es_general() ) {
				echo "<table class='tabla-0 ei-cuadro-cc-resumen' width='100%'>";
			}

			//-----  Cabecera del PIE --------
			$this->html_cabecera_pie($indice, $nodo, $total_columnas);
			$nivel_css = $this->get_nivel_css($nodo['profundidad']);
			$css_pie = 'ei-cuadro-cc-pie-nivel-' . $nivel_css;
			//----- Totales de columna -------
			if (isset($nodo['acumulador'])) {
				$titulos = false;
				if($indice[$nodo['corte']]['pie_mostrar_titulos']) {
					$titulos = true;
				}
				$this->html_cuadro_totales_columnas($nodo['acumulador'],
						'ei-cuadro-cc-sum-nivel-'.$nivel_css,
						$titulos,
						$css_pie);
			}
			//------ Sumarizacion AD-HOC del usuario --------
			$this->html_sumarizacion_usuario($nodo, $total_columnas);
			//----- Contar Filas
			if($indice[$nodo['corte']]['pie_contar_filas']) {
				echo "<tr><td  class='$css_pie' colspan='$total_columnas'>\n";
				echo $this->etiqueta_cantidad_filas($nodo['profundidad']) . count($nodo['filas']);
				echo "</td></tr>\n";
			}
			//----- Contenido del usuario al final del PIE
			$this->html_pie_pie($nodo, $total_columnas, $es_ultimo);
			if( ! $this->_cuadro->tabla_datos_es_general() ) {
				echo "</table>";
			}
		}else{																//MODO ANIDADO
			echo "</li>\n";
		}
	}

	/**
	 * @ignore
	 * @param <type> $nodo
	 * @param <type> $total_columnas
	 */
	function html_cabecera_pie($indice, $nodo, $total_columnas)
	{
		$nivel_css = $this->get_nivel_css($nodo['profundidad']);
		$css_pie = 'ei-cuadro-cc-pie-nivel-' . $nivel_css;
		$css_pie_cab = 'ei-cuadro-cc-pie-cab-nivel-'.$nivel_css;
		if($indice[$nodo['corte']]['pie_mostrar_titular']) {
			$metodo_redeclarado = 'html_pie_cc_cabecera__' . $nodo['corte'];
			if(method_exists($this, $metodo_redeclarado)){
				$descripcion = $this->$metodo_redeclarado($nodo);
			}else{
				$descripcion = $this->html_cabecera_pie_cc_contenido($nodo);
			}
			echo "<tr><td class='$css_pie' colspan='$total_columnas'>\n";
			echo "<div class='$css_pie_cab'>$descripcion<div>";
			echo "</td></tr>\n";
		}
	}

	/**
	 * @ignore
	 * @param <type> $nodo
	 * @param <type> $total_columnas
	 */
	function html_pie_pie($nodo, $total_columnas, $es_ultimo)
	{
		$nivel_css = $this->get_nivel_css($nodo['profundidad']);
		$css_pie = 'ei-cuadro-cc-pie-nivel-' . $nivel_css;
		$metodo = 'html_pie_cc_contenido__' . $nodo['corte'];
		if(method_exists($this, $metodo)){
			echo "<tr><td  class='$css_pie' colspan='$total_columnas'>\n";
			$this->$metodo($nodo, $es_ultimo);
			echo "</td></tr>\n";
		}
	}

	/**
	 * @ignore
	 * @param <type> $nodo
	 */
	function html_sumarizacion_usuario($nodo, $total_columnas)
	{
		if(isset($nodo['sum_usuario'])) {
			$datos = array();
			$acumulador_usuario = $this->_cuadro->get_acumulador_usuario();
			$nivel_css = $this->get_nivel_css($nodo['profundidad']);
			$css = 'ei-cuadro-cc-sum-nivel-'.$nivel_css;
			$css_pie = 'ei-cuadro-cc-pie-nivel-' . $nivel_css;
			foreach($nodo['sum_usuario'] as $id => $valor) {
				$desc = $acumulador_usuario[$id]['descripcion'];
				$datos[$desc] = $valor;
			}
			echo "<tr><td  class='$css_pie' colspan='$total_columnas'>\n";
			$this->html_cuadro_sumarizacion($datos,null,300,$css);
			echo "</td></tr>\n";
		}
	}

	/**
	 * Retorna el CONTENIDO de la cabecera del PIE del corte de control
	 * Muestra las columnas seleccionadas como descripcion del corte separadas por comas
	 * @return string
	 * @ignore
	 */
	protected function html_cabecera_pie_cc_contenido(&$nodo)
	{
		$indice = $this->_cuadro->get_indice_cortes();
		$descripcion = $indice[$nodo['corte']]['descripcion'];
		$valor = implode(", ",$nodo['descripcion']);
		if (trim($descripcion) != '') {
			return 'Resumen ' . $descripcion . ': <strong>' . $valor . '</strong>';
		} else {
			return 'Resumen <strong>' . $valor . '</strong>';
		}
	}

	/******************************************************************************/
    /******************************************************************************/
    /* SELECTOR DE ORDENAMIENTO                                                   */
    /******************************************************************************/
    /******************************************************************************/
    // Como en la clase de la que heredo tiene estos métodos privados, los pongo aca
    // con otro nombre y cambio los estilos utilizando bootstrap.
    /**
     * Genera el HTML que contendra el selector de ordenamiento
     */
    protected function html_selector_ordenamiento()
    {
        $id = $this->_cuadro->get_id_form();
        //Armo el div con el HTML
        echo "<div id='{$id}_selector_ordenamiento' style='display:none;'>";
            echo "<div class='container-fluid ordenamiento-estilo-general'>";
                $this->html_botonera_selector_2();
                //echo "<table class='tabla-0 ei-base ei-form-base ei-ml-grilla' width='100%'>";
                echo "<div class='ordenamiento-estilo-general'>";
                    echo "<table class='table table-condensed table-hover'>";
                        $this->html_cabecera_selector_2();
                        $this->html_cuerpo_selector_2();
            echo '</div>';
        echo '</div>';
    }

    /**
     *  Envia la botonera del selector
     */
    protected function html_botonera_selector_2()
    {
        $objeto_js = $this->_cuadro->get_id_objeto_js();
        echo "<div id='botonera_selector' class='ordenamiento-estilo-general'>";
            echo "<div class='pull-left'>";
            echo bootstrap_form::button_html("{$objeto_js}_subir", "<span class='glyphicon glyphicon-arrow-up'></span>", "onclick='{$objeto_js}.subir_fila_selector();'",
        "", "<","", "button", "", "btn btn-default", true, null, "true");
            echo bootstrap_form::button_html("{$objeto_js}_bajar", "<span class='glyphicon glyphicon-arrow-down'></span>", "onclick='{$objeto_js}.bajar_fila_selector();'",
            "", ">","", "button", "", "btn btn-default", true, null, "true");
            echo '</div>';
        echo '</div>';
    }

    /**
     * Genera la cabecera con los titulos del selector
     */
    protected function html_cabecera_selector_2()
    {
        echo "<thead>
						<th class='ei-ml-columna'>Activar</th>
						<th class='ei-ml-columna'>Columna</th>
						<th class='ei-ml-columna' colspan='2'>Sentido</th>
				</thead>";
    }

    /**
     *  Genera el cuerpo del selector
     */
    protected function html_cuerpo_selector_2()
    {
        $columnas = $this->_cuadro->get_columnas();
        $objeto_js = $this->_cuadro->get_id_objeto_js();

        $cuerpo = '';
        foreach($columnas as $col) {
            if ($col['no_ordenar'] != 1) {
                //Saco el contenedor de la fila y un checkbox para seleccionar.
                $cuerpo .= "<tr id='fila_{$col['clave']}'  onclick=\"$objeto_js.seleccionar_fila_selector('{$col['clave']}');\" class='ei-ml-fila'><td>";
                $cuerpo .= 	\toba_form::checkbox('check_'.$col['clave'], null, '0','ef-checkbox', "onclick=\"$objeto_js.activar_fila_selector('{$col['clave']}');\" ");
                $cuerpo .= "</td><td> {$col['titulo']}</td><td>";

                //Saco el radiobutton para el sentido ascendente
                $id = $col['clave'].'0';
                $cuerpo .=  "<label class='ef-radio' for='$id'><input type='radio' id='$id' name='radio_{$col['clave']}' value='asc'  disabled/>Ascendente</label>";
                $cuerpo .= '</td><td>' ;

                //Saco el radiobutton para el sentido descendente
                $id = $col['clave'].'1';
                $cuerpo .= "<label class='ef-radio' for='$id'><input type='radio' id='$id' name='radio_{$col['clave']}' value='des'  disabled/>Descendente</label>";
                $cuerpo .= '</td></tr>';
            }
        }

        $cuerpo .= "</table></div>";
        $cuerpo .= "<div id='botonera_selector_2' class='ordenamiento-estilo-general'>";
        $cuerpo .= "<div class='pull-right'>";
        $cuerpo .= bootstrap_form::button_html("{$objeto_js}_subir", "<span class='glyphicon glyphicon-ok'></span>", "onclick='$objeto_js.aplicar_criterio_ordenamiento();'",
                "", "O","", "button", "", "btn btn-default", true, null, "true");
        $cuerpo .= "</div></div>";

        echo $cuerpo;
    }

}
