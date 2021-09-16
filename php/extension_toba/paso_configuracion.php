<?php

class paso_configuracion extends paso_instalar_configuracion
{
	protected $relevamiento_ing = 'relevamiento_ing';
	protected $htmlchild_ = 2; //en que hijo inserto al form de ing
	
	
	function get_id()
	{
	/*	$partes = explode('_', get_class($this));
		return $partes[2];
		*/
		return "configuracion";
	}
//Todo esto es un copy paste con paso_act_migrar!! no separo en otro objeto para
//no agregar problemas con los include y el autoload, ni crear dependencias entre las acciones	
	//OVERRIDE
	function procesar()
	{
		parent::procesar();
		inst::logger()->grabar("Evaluando hablitacion del menu de Ingenierías");
		//var_dump($_POST);
		//Se llama despues del post_procesar del manejadro de negocio
		$path_final = str_replace('\\', '/', $_SESSION['path_instalacion']);
		if (isset($_POST[$this->relevamiento_ing ]) && ($_POST[$this->relevamiento_ing ])) {
			$this->habilitar_menu_ingenierias($path_final);
		}
		else {
			$this->deshabilitar_menu_ingenierias($path_final);
		}
	}
	/**
	 * Hay que copiar los metadatos compilados del menu del administrador y hacer uno con el menu y otro sin el menu
	 * de ingenierias. (es borrar lo que involucra al item 148 (menu ing).
	 * @param unknown_type $path_final
	 */
	private function deshabilitar_menu_ingenierias($path_final){
		$origen = $path_final."/aplicacion/metadatos_compilados/kolla/ing_deshabilitada.php";
		$destino = $path_final."/aplicacion/metadatos_compilados/gene/toba_mc_gene__grupo_admin.php";
		$this->reemplazar_archivo($origen, $destino);
		inst::logger()->grabar("El menu de ingenierías se ha deshabilitado.");
	}
	/**
	 * Hay que copiar los metadatos compilados del menu del administrador y hacer uno con el menu y otro sin el menu
	 * de ingenierias. (es borrar lo que involucra al item 148 (menu ing).
	 * @param unknown_type $path_final
	 */
	private function habilitar_menu_ingenierias($path_final){
		$origen = $path_final."/aplicacion/metadatos_compilados/kolla/ing_habilitada.php";
		$destino = $path_final."/aplicacion/metadatos_compilados/gene/toba_mc_gene__grupo_admin.php";
	
		$this->reemplazar_archivo($origen, $destino);
		inst::logger()->grabar("El menu de ingenierías se ha habilitado.");
	}

	private function reemplazar_archivo($origen, $destino){
		inst::logger()->grabar("copiando: ". $origen);
		inst::logger()->grabar("reemplazando: ". $destino);
		$s = file_get_contents($origen);
		if($s === false){
			die("Recordar incluir los archivos mc_menu_activado y mc_menu_desactivado");
		}
		$fp = fopen($destino, 'w')  or die("Recordar incluir los archivos mc_menu_activado y mc_menu_desactivado");
		fwrite($fp, $s);
		fclose($fp);
	}
	
//Override
	//El paso original usa un template. Agrego por DOM mis modificaciones
	function generar(){
		parent::generar();
		$relevamiento_ing = "$this->relevamiento_ing";
		$hijo = "$this->htmlchild_";
		$checked = ((isset($_POST[$this->relevamiento_ing ]) && ($_POST[$this->relevamiento_ing ]))?'checked': '');
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

    //se sobreescribe la generación del conf para personalizar la sección corresopndiente al proyecto Kolla
    function generar_conf_apache()
    {
        $ini_instalacion = new inst_ini($this->path_instalacion.'/instalacion.ini');
        $ini_instancia = new inst_ini($this->path_instancia.'/instancia.ini');
        $destino = $this->path_instalacion.'/toba.conf';

        //-- Nucleo
        $origen = INST_DIR.'/lib/var/toba.conf';
        if (! copy($origen, $destino)) {
            $this->set_error('sin_toba_conf', "No fue posible copiar el archivo '$origen' hacia '$destino'");
            return;
        }

        $path_final = str_replace('\\', '\\\\', inst::configuracion()->get_dir_dest_toba());
        $editor = new editor_archivos();
        $editor->agregar_sustitucion( '|__toba_dir__|', $path_final);
        $editor->agregar_sustitucion( '|__toba_alias__|', $ini_instalacion->get_datos_entrada('url'));
        $editor->procesar_archivo($destino);

        //-- Proyectos
        $proyectos = inst::configuracion()->get_proyectos_final_instancia();
        foreach ($proyectos as $id_proyecto) {
            $datos_proyecto = $ini_instancia->get_datos_entrada($id_proyecto);
            //--- Se agrega el proyecto al archivo
            $template = file_get_contents(INST_DIR.'/lib/var/proyecto.conf');
            $editor = new editor_texto();
            $editor->agregar_sustitucion( '|__toba_dir__|', $path_final);
            if (isset($datos_proyecto['path'])) {
                $path = $datos_proyecto['path'];
            } else {
                $path = $path_final ."/proyectos/$id_proyecto";
            }
            $editor->agregar_sustitucion( '|__proyecto_dir__|', $path);
            $editor->agregar_sustitucion( '|__proyecto_alias__|', $datos_proyecto['url']);
            $editor->agregar_sustitucion( '|__proyecto_id__|', $id_proyecto);
            $editor->agregar_sustitucion( '|__instancia__|', inst::configuracion()->get_nombre_instancia());
            $editor->agregar_sustitucion( '|__instalacion_dir__|', $this->path_instalacion);

            //se agrega la regla personalizada por Kolla
            if ($id_proyecto == 'kolla') {
                $regla = "RewriteRule ^responder(.*)$ publica.php$1 [L]";
                $editor->agregar_sustitucion('|__regla_urlcorta_publicas__|', $regla);
            }

            $salida = $editor->procesar( $template );

            file_put_contents($destino, $salida, FILE_APPEND);
        }
    }
}

?>