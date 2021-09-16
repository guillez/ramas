<?php

class paso_act_migrar extends paso_actualizar_migrar
{
	protected $relevamiento_ing = 'relevamiento_ing';
	protected $htmlchild_ = 2; //en que hijo inserto al form de ing
	function get_id()
	{
		/*$partes = explode('_', get_class($this));
		return $partes[2];*/
		return "migrar";
	}
	
//Todo esto es un copy paste con paso_configuracion!! no separo en otro objeto para
//no agregar problemas con los include y el autoload, ni crear dependencias entre las acciones	
	
	//OVERRIDE
	function procesar()
	{ 
		parent::procesar();
		
		if (!empty($_POST)) {
			if ($this->tiene_errores()) return;
			inst::logger()->grabar('Evaluando hablitacion del menu de Ingenierías');
			
			//Se llama despues del post_procesar del manejadro de negocio
			$path_final = str_replace('\\', '/', $_SESSION['path_instalacion']);
			
			if (isset($_POST[$this->relevamiento_ing ]) &&($_POST[$this->relevamiento_ing ])) {
				//$this->habilitar_menu_ingenierias($path_final);
			} else {
				//$this->deshabilitar_menu_ingenierias($path_final);
			}
		}
	}

	/**
	 * Hay que copiar los metadatos compilados del menu del administrador y hacer uno con el menu y otro sin el menu
	 * de ingenierias. (es borrar lo que involucra al item 148 (menu ing).
	 * @param unknown_type $path_final
	 */
	private function deshabilitar_menu_ingenierias($path_final)
	{
		$origen = $path_final."/aplicacion/metadatos_compilados/kolla/ing_deshabilitada.php";
		$destino = $path_final."/aplicacion/metadatos_compilados/gene/toba_mc_gene__grupo_admin.php";
		$this->reemplazar_archivo($origen, $destino);
		inst::logger()->grabar("El menu de ingenierías se ha deshabilitado.");
	}
	
	/**
	 * Hay que copiar los metadatos compilados del menu del administrador y hacer uno con el menu y otro sin el menu
	 * de ingenierias. (es borrar lo que involucra al item 148 (menu ing).
	 * @param string $path_final
	 */
	private function habilitar_menu_ingenierias($path_final)
	{
		$origen = $path_final."/aplicacion/metadatos_compilados/kolla/ing_habilitada.php";
		$destino = $path_final."/aplicacion/metadatos_compilados/gene/toba_mc_gene__grupo_admin.php";
		$this->reemplazar_archivo($origen, $destino);
		inst::logger()->grabar("El menu de ingenierías se ha habilitado.");
	}

	private function reemplazar_archivo($origen, $destino)
	{
		inst::logger()->grabar('copiando: '. $origen);
		inst::logger()->grabar('reemplazando: '. $destino);
		$s = file_get_contents($origen);
		
		if ($s === false) {
			die('Recordar incluir los archivos mc_menu_activado y mc_menu_desactivado');
		}
		
		$fp = fopen($destino, 'w') or die('Recordar incluir los archivos mc_menu_activado y mc_menu_desactivado');
		fwrite($fp, $s);
		fclose($fp);
	}
	
	//Override
	//El paso original usa un template. Como no se puede extender lo copio aca
	function generar()
	{
		parent::generar();
		$relevamiento_ing = "$this->relevamiento_ing";
		$hijo = "$this->htmlchild_";
		$checked = ((isset($_POST[$this->relevamiento_ing ]) && ($_POST[$this->relevamiento_ing ])) ? 'checked' : '');
		echo "
		<script type='text/javascript'>
		 function createDivRelevamiento() { 
            var divTag = document.createElement('div'); 
            divTag.id = 'relevamiento_ing'; 
            divTag.innerHTML = \"<h2>Relevamiento de Ingenier&iacute;as</h2>\"+
			\"<span class='aclaracion'> Habilita en el men&uacute; de operaciones las funciones para el relevamiento de ingenier&iacute;as del Ministerio de Educaci&oacute;n</span>\"+
	   		\"<table id='configurar_smtp'>\"+
			\"<tr><td class='label'><label for='relev_ing'>Activar en menú</label></td><td colspan=3>\"+
			\"<input id=$relevamiento_ing name=$relevamiento_ing type='checkbox' $checked >\"+
			\"</td></tr></table>\"+
			\"\";
		document.getElementById('main').children[$hijo].appendChild(divTag);
        }
        createDivRelevamiento(); 
		</script>
		";
	}

}

?>