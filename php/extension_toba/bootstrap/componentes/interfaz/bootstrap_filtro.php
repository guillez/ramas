<?php

use ext_bootstrap\componentes\interfaz\bootstrap_form;
use ext_bootstrap\componentes\botones\bootstrap_evento_usuario;

class bootstrap_filtro extends toba_ei_filtro{
	
	protected function cargar_lista_eventos()
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
	
	protected function crear_columnas()
	{
		$this->_columnas = array();
		$efs = array();
		$parametros_efs = array();
		foreach ($this->_info_filtro_col as $fila) {
			$clase = 'ext_bootstrap\\componentes\\filtro_columnas\\bootstrap_filtro_columna_'.$fila['tipo'];
			$this->_columnas[$fila['nombre']] = new $clase($fila, $this);
			$efs[$fila['nombre']] = $this->_columnas[$fila['nombre']]->get_ef();
			$parametros = $fila;
			if (isset($parametros['carga_sql']) && !isset($parametros['carga_fuente'])) {
				$parametros['carga_fuente']=$this->_info['fuente'];
			}
			$parametros_efs[$fila['nombre']] = $parametros;
		}
		//--- Se registran las cascadas porque la validacion de efs puede hacer uso de la relacion maestro-esclavo
		$this->_carga_opciones_ef = new toba_carga_opciones_ef($this, $efs, $parametros_efs);
		$this->_carga_opciones_ef->registrar_cascadas();
	}
	
	function generar_html()
	{
		//Genero la interface
		echo "\n\n<!-- ***************** Inicio EI filtro (	".	$this->_id[1] ." )	***********	-->\n\n";
		//Campo de sincroniacion con JS
		echo bootstrap_form::hidden($this->_submit, '');
		echo bootstrap_form::hidden($this->_submit.'_implicito', '');
		$ancho = '';
		if (isset($this->_info_filtro["ancho"])) {
			$ancho = convertir_a_medida_tabla($this->_info_filtro["ancho"]);
		}
		echo "<div class='panel panel-default'>";
		echo $this->get_html_barra_editor();
		echo "<div class='panel-heading'>";
		$this->generar_html_barra_sup(null, true,"");
		echo "</div>";
		echo "<div class='panel-body form-horizontal'>";
		$this->generar_formulario();
		echo "</div>";
		echo "</div>";
		$this->_flag_out = true;
	}
	
	protected function generar_formulario()
	{
		$this->_carga_opciones_ef->cargar();
		$this->_rango_tabs = toba_manejador_tabs::instancia()->reservar(100);
		$this->_colspan = 0;
		if (isset($this->_colapsado) && $this->_colapsado) {
			$estilo .= "display:none;";
		}
		//Campo de comunicacion con JS
		echo bootstrap_form::hidden("{$this->objeto_js}_listafilas",'');
		echo bootstrap_form::hidden("{$this->objeto_js}__parametros", '');
		//echo "<div class='ei-cuerpo ei-filtro-base' id='cuerpo_{$this->objeto_js}' style='$estilo'>";
		$this->generar_layout('');
	}
	
	protected function generar_layout($ancho)
	{
		$this->generar_formulario_cuerpo();
		if ($this->botonera_abajo()) {
			$this->generar_botones();
		}
	}
	
	protected function generar_formulario_cuerpo()
	{
		foreach ($this->_columnas as $nombre_col => $columna) {
			$this->analizar_visualizacion_columna ($columna);
			if ($columna->es_visible()) {
				$estilo_fila = "";
			} else {
				$estilo_fila = "style='display:none;'";
			}
			$clase_etiqueta = !$columna->es_obligatorio() ? 'opcional':'';
			$marca_obligatoriedad = $columna->es_obligatorio() ? ' (*)':'';
			$row = "<div class='form-group' $estilo_fila id='{$this->objeto_js}_fila$nombre_col' onclick='{$this->objeto_js}.seleccionar(\"$nombre_col\")'>";
			$row.= "	<label class='col-sm-3 control-label $clase_etiqueta'>";
			$row.=			 $this->generar_vinculo_editor($nombre_col);
			$row.=			 $columna->get_etiqueta().$marca_obligatoriedad;
			$row.= "	</label>";
			//-- Condición
			if ($columna->get_cant_condiciones() > 1) { // si no tiene Toba pone un hidden y se ve feo
				$row.= "	<div class='col-sm-2'>";
				$row.= 			$columna->get_html_condicion();
				$row.= "	</div>";
			}
		
			//-- Valor
			$row.= "	<div class='col-sm-6'>";
			echo $row;
							$columna->get_html_valor();
			$row = "	</div>";
			
			//Si es obligatoria no se puede borrar
			if (!$columna->es_solo_lectura() && !$columna->es_obligatorio()) {
				$span = "<span class='glyphicon glyphicon-trash'></span>";
				$row .= "<div class='col-sm-1'>";
				$row .= bootstrap_form::button_html("{$this->objeto_js}_eliminar$nombre_col", $span,
				"onclick='{$this->objeto_js}.seleccionar(\"$nombre_col\");{$this->objeto_js}.eliminar_seleccionada();'",
				$this->_rango_tabs[0]++, null, 'Elimina la fila');
				$row .= "</div>";
			} 
				
			$row.= "</div>";
			echo $row;
				
		}
	}
	
	function generar_botones($clase = '', $extra='')
	{
		$extra .= $this->get_botonera_manejo_filas();			//Lo coloco aca porque sino debo redefinir toda la ventana superior
		
		$clase_grupo = count($this->_eventos_usuario_utilizados)>1?'btn-group':'';
		echo "<div class='btn-toolbar' role='toolbar' aria-label='botonera'>";
		//----------- Generacion
		if ($this->hay_botones()) {
			echo "<div class='btn-group pull-left' role='group'>";
			echo $extra;
			echo "</div>";
			echo "<div class='$clase_grupo pull-right' role='group'>";
			$this->generar_botones_eventos();
			echo "</div>";
		} elseif ($extra != '') {
			echo $extra;
		}
		
		echo "</div>";
	}
	protected function get_botonera_manejo_filas()
	{
		$salida = '';
		$salida = "<div class='form-inline ' id='botonera_{$this->objeto_js}'>";
		
		$texto = toba_recurso::imagen_toba('nucleo/agregar.gif', true);
		$opciones = array(apex_ef_no_seteado => '');
		foreach ($this->_columnas as $columna) {
			$opciones[$columna->get_nombre()] = $columna->get_etiqueta();
		}
		$salida .= "<label class='control-label opcional'>Agregar Filtro</label>";
		$onchange = "onchange='{$this->objeto_js}.crear_fila()'";
		$salida .= bootstrap_form::select("{$this->objeto_js}_nuevo", null, $opciones, 'form-control', $onchange);
		$salida .="</div>\n";
		return $salida;
	}
	
	
	
}