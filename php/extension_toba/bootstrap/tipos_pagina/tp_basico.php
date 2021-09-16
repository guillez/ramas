<?php

namespace ext_bootstrap\tipos_pagina;

use ext_bootstrap\componentes\menu;
use toba_app_launcher;
use kolla_app_launcher;

class tp_basico extends \toba_tp_normal
{
	protected $config;
	protected $clase_contenido = "wrapper";

	protected $arai_flag = false;

	function __construct(){
		
		parent::__construct();
		$this->config = require(__DIR__.'/../config/params.php');
		$this->clase_encabezado = "";
		$this->menu = new menu($this->config['menu']);
	}
	
	protected function cabecera_html(){
		$favicon = \toba_recurso::imagen_proyecto('favicon.ico');
		echo "	<!DOCTYPE html>
				<html>
				<head>
					<!--<meta charset='utf-8'>-->
					<meta charset='ISO-8859-1'>
					<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
					<link rel='icon' href='$favicon'  >
					<title>".$this->titulo_pagina()."</title>".
					$this->plantillas_css().
					"<!--[if lt IE 9]>
					<script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
					<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
					<![endif]-->";
					
					\toba_js::cargar_consumos_basicos();
					
		echo "	</head>"
				;
		;
	}
	
	protected function comienzo_cuerpo(){
		
		$this->comienzo_cuerpo_basico();
	}
	
	protected function comienzo_cuerpo_basico(){
		$cerrar = \toba_recurso::imagen_toba('nucleo/cerrar_ventana.gif', false);
		$wait = \toba_recurso::imagen_toba('wait.gif');
		
				
		$header = $this->ingreso_header();
		
		echo "<body class='hold-transition sidebar-mini '>";
		echo "<script>
				var colap = localStorage.getItem('colapsado');
				if( colap == 1 ){
					$('body').addClass('sidebar-collapse')
				}
			  </script>";
		\toba_js::cargar_consumos_globales(array('basicos/tipclick'));
		echo "<!-- jQuery 2.2.3 -->
				<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
				<!-- Bootstrap 3.3.6 -->
				<script src='assets/bootstrap/js/bootstrap.min.js'></script>
				";
		
		$this->incluir_override_js();
		$basic = "
					{$this->getModalHtml()}
					<div id='div_toba_esperar' class='div-esperar' style='display:none'>
						
					</div>
					{$this->getModalEspera()}
				  <div class='{$this->clase_contenido}'>
				  $header";
		echo $basic;
	}
	
	private function incluir_override_js(){
		$files = $this->config["assets"]['js'];
		
		foreach ($files as $file){
			echo \toba_js::incluir(\toba::proyecto()->get_www($file)['url']);
		}
	}
	private function getModalHtml(){
		return '<div class="modal fade" tabindex="-1" role="dialog" id="modal_notificacion">
  					<div class="modal-dialog" role="document">
    					<div class="modal-content" >      						
      						
				    	</div><!-- /.modal-content -->
				  	</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->';
	}
	
	
	private function getModalEspera(){
		$logo = \toba_recurso::imagen_proyecto($this->config['logos']['espera'], false);
		return '<div class="modal fade" tabindex="-1" role="dialog" id="modal_espera">
  					<div class="modal-dialog" role="document">
    					<div class="modal-content">
							<div class="modal-header">
								<div class="row">
									<img src="'.$logo.'"  class="center-block"/>
						        </div>
							</div>
							<div class="modal-body">
					        	<div class="row">
                      				<div class="progress">
  										<div class="progress-bar progress-bar-striped active progress-bar-danger" role="progressbar" aria-valuenow="45" aria-valuemin="2" aria-valuemax="100" style="width: 100%">
	    									<span class="sr-only">45% Complete</span>
	  									</div>
									</div>
	                			</div>
								<div class="row text-center">
									<h4 class="">Procesando. Por favor aguarde...</h4>
								</div>
							</div>
					    </div><!-- /.modal-content -->
				  	</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->';
	}
	
	function barra_superior(){
		echo "<div> <div>";
	}
	
	
	
	function pre_contenido(){
		
		$pre = "<div class='content-wrapper'>
					<section class='content-header'>
				      {$this->get_breadcrumb()}
				    </section>
					<section class='content row'>
						<div class='col-md-12'>
				";
		echo $pre;
	}	
	
	function get_breadcrumb(){
		$item = $this->menu->get_item($this->config['menu']['inicio']['id']);
		if (!isset($item['js'])){
			$item['js'] = 'return toba.ir_a_operacion("'.$item['proyecto'].'", "'.$item['item'].'", false)';
		}
		
		return "<ol class='breadcrumb'>
					<li><a href='#' onclick='$item[js]' ><i class='glyphicon glyphicon-map-marker'></i> Inicio </a></li>
					{$this->menu->get_path_actual()}
				</ol>";
	}
	function post_contenido(){
		
		//cierre de los tags en el pre_contenido();
		echo "		</div>
				</section>
			</div>";
	}
	
	function pie(){
		
		$scripts = $this->footer_scripts();
		
		echo "<footer class='main-footer'>";
		if ( \toba_editor::modo_prueba() ) {
			$item = \toba::solicitud()->get_datos_item('item');
			$accion = \toba::solicitud()->get_datos_item('item_act_accion_script');
			echo "<div class='row centrar-text-xs'>";
			\toba_editor::generar_zona_vinculos_item($item, $accion);
			echo "</div>";
		}
		echo "	<div class='row centrar-text-xs'>
					<div class='pull-right hidden-xs'> 				
						<b>Versión</b> ". \toba::proyecto()->get_version() ."
					</div>
						Desarrollado por <strong> <a href='http://www.siu.edu.ar'class='footer_skin'> SIU</a>.</strong> 2005 - ".date("Y")."
					</div>
			  </footer>
			  <div class='control-sidebar-bg'></div>
			</div> <!-- content-wrapper -->
			$scripts
		 </body>
		 </html>";
	}
	
	protected function ingreso_header(){
	    $this->arai_flag = false;

		$logo = \toba_recurso::imagen_proyecto('../bt-assets/img/logo-kolla-iso2.png', false);
		$nombre = \toba_recurso::imagen_proyecto('../bt-assets/img/kolla_nombre.png', false);
        $link = 'return toba.ir_a_operacion("kolla", "2", false)';
        
		$head = "<header class='hidden-xs main-header'>
	    			<div class='logo'>
						<span class='logo-mini'>
                            <a href='#' onclick='$link'>
                                <img alt='Siu' src='$logo' class='img-responsive'>
                            </a>
						</span>
						<!-- logo for regular state and mobile devices -->
                        <a href='#' onclick='$link'>
                            <img alt='Brand' src='$nombre' class='img-responsive'>
                        </a>
					</div>
					<nav class='navbar navbar-static-top'>
						<a href='#' class='sidebar-toggle fix-burger' data-toggle='offcanvas' role='button'>
							<span class='sr-only'>Toggle navigation</span>
						</a>
						<div class='col-md-9 col-xs-8 hidden-xs'>
						{$this->custom_cabecera()}
						</div>
						<!-- Navbar Right Menu -->
						<div class='navbar-nav-arreglado navbar-custom-menu'>
							<ul class='nav navbar-nav'>
								<li class='dropdown notifications-menu'>".
                                    $this->get_ayuda().
								"</li>
								<li>".
									$this->get_exit().
								"</li>
							</ul>
						</div>
					</nav>
				</header>
				<!-- Cambio totalmente el maquetado para tamaños menores a XS #18005 -->
				<header class='visible-xs main-header'>
					<nav class='navbar navbar-static-top'>
						<div class='col-sss-offset-1 col-sss-10 hidden-sssm logo-xs'>		   
                            <a href='#' onclick='$link'>
                                <img alt='Brand' src='$nombre' class='img-responsive'>
                            </a>
					    </div>
					    <div class='col-sss-2 hamburguesa-xs col-sssm-2'>
						    <a href='#' class='sidebar-toggle fix-burger' data-toggle='offcanvas' role='button'>
							<span class='sr-only'>Toggle navigation</span>
						</a>
                        </div>
                        <div class='hidden-sss col-sssm-6'>		   
                            <a href='#' onclick='$link'>
                                <img alt='Brand' src='$nombre' class='img-responsive logo-xs2'>
                            </a>
					    </div>
						<!-- Navbar Right Menu -->
						<div class='col-sss-8 col-sssm-4 navbar-nav-arreglado navbar-custom-menu'>
							<ul class='nav navbar-nav'>
								<li class='dropdown notifications-menu'>".
                                    $this->get_ayuda().
                                "</li>
								<li>".
                                    $this->get_exit().
                                "</li>
							</ul>
						</div>
					</nav>
				</header>";
		return $head;
	}
	
	protected function plantillas_css(){
		
		$files = $this->config['assets']['css'];
		$tags = "";
		foreach ($files as $file){
			$tags .= "<link rel='stylesheet' href='assets/$file'>";
		}
		return $tags;
	}
	
	
	protected function footer_scripts(){
		return "
				
				<!-- AdminLTE App -->
				<script src='bt-assets/js/app.js'></script>
				<script >
				  $(document).ready(function(){
				    $.AdminLTE.layout.activate();
				  })
				</script>		
		";
	}
	
	protected function custom_cabecera(){
			
	}
	
	/**
	 * Lista cada uno de los items pertencientes al menu de ayuda.
	 * @todo Agregar la imagen si tuviera
	 * @return string
	 */
	private function get_ayuda(){
		if ( isset($this->config['menu']['ayuda']) && !$this->config['menu']['ayuda']['mostrar'] )
			return "";
		$items = $this->menu->get_items($this->config['menu']['ayuda']['id']);

		//<li class='header'>Centro de Ayuda</li>
		$list = "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>
					<span class='glyphicon glyphicon-book' aria-hidden='true'> </span> Ayuda
				</a>
				<ul class='dropdown-menu menu-ayuda-fix'>
					<li>
						<ul class='menu'>";
							foreach ($items as $item){
								if (!isset($item['js'])){
									$item['js'] = 'return toba.ir_a_operacion("'.$item['proyecto'].'", "'.$item['item'].'", false)';
								}
								$list .="<li>
											<a href='#' tabindex='32767' onclick='$item[js]' >
												<i class='fa $item[imagen] text-red'></i>
												$item[nombre]
											</a>
										</li>";
							}
		$list .="		</ul>
					</li>
				</ul>";
		return $list;
	}
	
	private function get_exit(){
	    if (!\toba::instalacion()->vincula_arai_usuarios()) {
            if ( isset($this->config['menu']['salir']) && !$this->config['menu']['salir']['mostrar'] )
                return "";
            $item = $this->menu->get_item($this->config['menu']['salir']['id']);
            if (!isset($item['js'])){
                $item['js'] = 'return toba.ir_a_operacion("'.$item['proyecto'].'", "'.$item['item'].'", false)';
            }
            return "<a href='#' onclick='$item[js]'> 
					<span class='glyphicon glyphicon-log-out' aria-hidden='true'></span>   
					$item[nombre]
				</a>";
        } else {
            if ( isset($this->config['menu']['salir']) && !$this->config['menu']['salir']['mostrar'] )
                return "";

            if ($this->arai_flag)
                return "";

            $salida = "";
            $salida .= "<li class='arai-applauncher'>";
            $salida .= kolla_app_launcher::instancia()->get_html_app_launcher();
            $salida .= "</li>";

            $this->arai_flag = true;

            return $salida;
        }
	}
	
}
?>