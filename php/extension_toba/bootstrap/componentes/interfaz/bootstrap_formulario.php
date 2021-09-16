<?php
use ext_bootstrap\componentes\botones\bootstrap_evento_usuario;
use ext_bootstrap\componentes\efs\bootstrap_ef_html;

class bootstrap_formulario extends toba_ei_formulario{
	
	protected $clase_formulario = "panel panel-default";
	
	function get_consumo_javascript()
	{
		$consumo = parent::get_consumo_javascript();
		//$consumo[] = '../../kolla/bt-assets/js/bt_formulario';
        $consumo[] = '../../'.toba_recurso::url_proyecto().'/bt-assets/js/bt_formulario';
        
		return $consumo;
	}
	
	protected function cargar_lista_eventos()
	{
		foreach ($this->_info_eventos as $info_evento) {
			$e = new bootstrap_evento_usuario($info_evento, $this);
			$this->_eventos_usuario[ $e->get_id() ] = $e;				//Lista de eventos
			$this->_eventos_usuario_utilizados[ $e->get_id() ] = $e;	//Lista de utilizados
			if( $e->es_implicito() ){
				\toba::logger()->debug($this->get_txt() . " IMPLICITO: " . $e->get_id(), 'toba');
				$this->_evento_implicito = $e;
			}
		}
	}
	
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
	
	function generar_html()
	{
		//Genero la interface
		echo "\n\n<!-- ***************** Inicio EI FORMULARIO (	".	$this->_id[1] ." )	***********	-->\n\n";
		//Campo de sincroniacion con JS
		echo toba_form::hidden($this->_submit, '');
		echo toba_form::hidden($this->_submit.'_implicito', '');
		
		echo "<div class='{$this->clase_formulario}'>";
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
		
		if ( $tiene_titulo || trim($this->_info["descripcion"])!="" ){
			
			echo "	<div class='panel-heading'>";
			parent::generar_html_barra_sup(null, true,"ei-form-barra-sup");
			echo "	</div>\n";
		}
	}
	
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
		//--- La carga de efs se realiza aqui para que sea contextual al servicio
		//--- ya que hay algunos que no lo necesitan (ej. cascadas)
		$this->_carga_opciones_ef->cargar();
		$this->_rango_tabs = toba_manejador_tabs::instancia()->reservar(250);

		$colapsado = (isset($this->_colapsado) && $this->_colapsado) ? "display:none;" : "";
	
		echo "<div class='form-horizontal panel-body' style='$colapsado' id='cuerpo_{$this->objeto_js}'>"; //Comienza el formulario
		$this->generar_layout();
	
		$hay_colapsado = false;
		foreach ($this->_lista_ef_post as $ef){
			if (! $this->_elemento_formulario[$ef]->esta_expandido()) {
				$hay_colapsado = true;
				break;
			}
		}
		if ($hay_colapsado) {
			$img = toba_recurso::imagen_skin('expandir_vert.gif', false);
			$colapsado = "style='cursor: pointer; cursor: hand;' onclick=\"{$this->objeto_js}.cambiar_expansion();\" title='Mostrar / Ocultar'";
			echo "<div class='ei-form-fila ei-form-expansion'>";
			echo "<img id='{$this->objeto_js}_cambiar_expansion' src='$img' $colapsado>";
			echo "</div>";
		}
		if ($this->botonera_abajo()) {
			$this->generar_botones('col-md-12');
		}
		echo "</div>\n"; // Fin de formulario
	}
	
	protected function get_html_ef($ef, $ancho_etiqueta=null, $con_etiqueta=true)
	{
		$salida = '';
		if (! in_array($ef, $this->_lista_ef_post)) {
			//Si el ef no se encuentra en la lista posibles, es probable que se alla quitado con una restriccion o una desactivacion manual
			return;
		}
		$clase = $this->get_estilo_ef().' col-md-12';
		$estilo_nodo = "";
		$id_ef = $this->_elemento_formulario[$ef]->get_id_form();
		
		if (! $this->_elemento_formulario[$ef]->esta_expandido()) {
			$clase .= ' ei-form-fila-oculta';
			$estilo_nodo = "display:none";
		}
		if (isset($this->_info_formulario['resaltar_efs_con_estado'])
				&& $this->_info_formulario['resaltar_efs_con_estado'] && $this->_elemento_formulario[$ef]->seleccionado()) {
				$clase .= ' ei-form-fila-filtrada';
        }
        $es_fieldset = ($this->_elemento_formulario[$ef] instanceof toba_ef_fieldset);
        if (! $es_fieldset) {							//Si es fieldset no puedo sacar el <div> porque el navegador cierra visualmente inmediatamente el ef.
            $salida .= "<div class='$clase' style='$estilo_nodo' id='nodo_$id_ef'>\n";
        }
        if ($this->_elemento_formulario[$ef]->tiene_etiqueta() && $con_etiqueta) {
            $salida .= $this->get_etiqueta_ef($ef, $ancho_etiqueta);
            $clase = ($this->_elemento_formulario[$ef] instanceof bootstrap_ef_html)?"col-md-10":"col-md-5";
            $salida .= "<div id='cont_$id_ef' class='$clase'>\n";
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
    
    protected function get_estilo_ef()
    {
        return 'form-group';
    }
	
	protected function get_etiqueta_ef($ef, $ancho_etiqueta=null)
	{
		$estilo = 'control-label ';
		$custom = $this->_elemento_formulario[$ef]->get_estilo_etiqueta();
		$estilo .= ($custom == '')?'col-sm-2':$custom;
		$marca ='';
	
		if ($this->_elemento_formulario[$ef]->es_obligatorio()) {
			$marca .= '(*)';
		} else {
			$estilo .= ' opcional';
		}
		
		$desc='';
		if (!isset($this->_info_formulario['expandir_descripcion']) || ! $this->_info_formulario['expandir_descripcion']) {
			$desc = $this->_elemento_formulario[$ef]->get_descripcion();
			if ($desc !=""){
				$desc = toba_parser_ayuda::parsear($desc);
				$desc = "<span class='glyphicon glyphicon-pushpin' data-toggle='tooltip' data-placement='top' title='$desc'></span>";
			}
		}
		$id_ef = $this->_elemento_formulario[$ef]->get_id_form();
		$editor = $this->generar_vinculo_editor($ef);
		$etiqueta = $this->_elemento_formulario[$ef]->get_etiqueta();
		if(trim($etiqueta) == '')
			return "";
		
		return "<label class='$estilo' for='$id_ef' >$editor $desc $etiqueta $marca </label>\n";
	}
	
}
?>