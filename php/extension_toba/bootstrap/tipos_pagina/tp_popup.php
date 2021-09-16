<?php

use ext_bootstrap\tipos_pagina\tp_basico;

class tp_popup extends tp_basico{
	
	function __construct(){
		$this->clase_contenido = 'container';
		parent::__construct();
	}
	
	function pre_contenido(){
		$pre = "<section class='content-header'>
					{$this->get_breadcrumb()}
				</section>
				<section class='col-md-8 col-md-offset-2'>
				";
		echo $pre;
	}
	function post_contenido(){
	
		//cierre de los tags en el pre_contenido();
		echo "		
				</section>
			</div>";
	}
	
	protected function ingreso_header(){
		return "";
	}
	
	function get_breadcrumb(){
		$item = $this->menu->get_item($this->config['menu']['inicio']['id']);
		if (!isset($item['js'])){
			$item['js'] = 'return toba.ir_a_operacion("'.$item['proyecto'].'", "'.$item['item'].'", false)';
		}
	
		return "<ol class='breadcrumb'>
					<li><a><i class='glyphicon glyphicon-map-marker'></i> Popup </a></li>
					<li><a> {$this->titulo_item()} </a></li>
				</ol>";
	}
	
	function pie(){
	
		$scripts = $this->footer_scripts();
			
		echo "			$scripts
					</body>
				</html>";
	}
}