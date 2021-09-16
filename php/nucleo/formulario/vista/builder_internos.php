<?php

include_once('builder_base.php');

class builder_internos extends builder_base
{
	protected $flag_crear_barra_progreso = false;
    protected $con_header = true;

	//es igual a los externos por ahora -
	function crear_encabezado_formulario($nombre_form, $texto_preliminar=null, $url_action_post, $puede_guardar)
	{
        $title = $nombre_form; //parametro al header..
        $estilo = $this->plantilla_css;
        include ('nucleo/formulario/vista/header.php');
		
        if ($this->con_header) {
            $inst = toba::consulta_php('consultas_mgi')->get_institucion();
            $nombre_institucion = empty($inst) ? 'No definida' : $inst[0]['nombre'];
            $logo = toba::proyecto()->get_www('img/logo_institucion.jpg');
            $us = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario(toba::usuario()->get_id());
            //--- Logo
            $ini = new toba_ini(toba_proyecto::get_path().'/proyecto.ini');
            $header = "<header class='header-sin-padding row'>
                             <div class='col-xs-4 text-center'>
                                <div class='col-xs-2 col-md-4'>
                                    {$this->mostrar_logo()}
                                </div>
                                <div class='col-xs-10 col-md-8 kolla_cab_titulo'> 
                                    Versión ".toba::proyecto()->get_version()."
                                </div>
                            </div>"; 

            //--- Nombre institución
            $header .= 		"<div class='col-xs-4 text-center'>
                                <div class='kolla_cab_titulo'>Institución</div>
                                <div class='header-word-wrap'> $nombre_institucion</div>
                            ";
            //--- Logo institución
            if (file_exists($logo['path'])) {
                $header .="<div>".toba_recurso::imagen($logo['url'], null, '34px').'</div>';
            }
            $header .= "</div>";//cierro div datos institución
            $nombre_usuario = texto_plano(ucwords(isset($us) ? $us['nombres'].' '.$us['apellidos'] : ''));
            $tipo_usuario = texto_plano(toba::usuario()->get_id());
            //--- Usuario
            //$header .= "<div class='col-xs-3 col-xs-push-1 text-center'>
            $header .= "<div class='col-xs-4 text-center'>
                            <div class='kolla_cab_titulo'>Usuario</div>
                            <div class='header-word-wrap'> $nombre_usuario ( $tipo_usuario )</div>
                        </div>";
            $header .='</header>';//encabezado
            echo $header;
        }
		
		parent::crear_encabezado_formulario($nombre_form, $texto_preliminar, $url_action_post, $puede_guardar);
	}

    public function crear_barra_progreso()
    {
    	// Como la barra de progreso debe estar despues de los botones de guardar y terminar,
		// levanto el flag para que se cree una vez que sea hace el cierre del formulario parcial.
		// Es decir, entre medio del cierre de formulario de la clase "builder_base" y el que hace
		// la clase actual (builder_internos).
        $this->flag_crear_barra_progreso = true;
    }
    
    public function set_mostrar_header($con_header)
    {
    	$this->con_header = $con_header;
    }
	
	public function crear_cierre_formulario()
	{
		parent::crear_cierre_formulario();

		// Creo la barra de progreso en caso de que sea necesario
		if ($this->flag_crear_barra_progreso) {
           	echo "<script type='text/javascript' src='js/progressbar.min.js'></script>
				<script type='text/javascript' src='js/EstadoPreguntas.js'></script>
           		<script type='text/javascript' src='js/BarraProgreso.js'></script>
           		<script type='text/javascript' src='js/IniciadorBarraProgreso.js'></script>";

           	echo "<div id='contenedor-barra' class='navbar navbar-default navbar-fixed-bottom' style='min-height: 0px; padding-top: 3px;'>
					<div id='barra-progreso' style='margin: auto; width: 200px; height: 40px;'>
					</div>
				  </div>";
		}

		echo '</body></html>';
	}
	
	function mostrar_logo()
	{
		// Link a la operación "Inicio".
		$proyecto = toba::proyecto()->get_id();
		$item = '2';
		$js = "return toba.ir_a_operacion(\"$proyecto\", \"$item\", false)";
		$img = toba_recurso::imagen_proyecto('logo-kolla-iso.png', true, '35', '35');
		return "<div class='kolla_logo'><a  href='#' onclick='$js'>$img</a></div>";
	}
}

?>
