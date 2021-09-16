<?php

namespace ext_bootstrap\componentes;

use SIUToba\rest\bootstrap;
use ext_bootstrap\componentes\tools\bootstrap_translate;

/**
 * Clase que extiende al menu de toba CSS. Permite armar el arbol del menu
 * vertical consultando los distintos items que toba retorna.
 *
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 *
 * Un item del menú, cuando se recupera desde toba tiene la estrutura:
 * [
 * 	'padre' => string
 * 	'carpeta' => int
 * 	'proyecto' => string
 * 	'item' => string
 * 	'nombre' => string
 * 	'orden' => string
 * 	'imagen' => string
 * 	'imagen_recurso_origen' => string
 * 	'es_primer_nivel' => boolean
 * 	'js' => string
 * ]
 */
class menu extends \toba_menu_css
{
	private    $items_map = array(); //Arreglo asociativo por id de Item
	private    $tree;
	protected  $items; //Items del menu

	protected  $hoja  = "<li class='{activo} leaf'><a href='#' tabindex='32767' onclick='{js}'><i class='fa fa-circle-o'></i>{nombre}</a></li>";
	protected  $padre = "<li class='{activo} treeview'>
							<a href='#'>
								<i class='fa fa-folder'></i>
								<span> {nombre}</span>
								<span class='pull-right-container'>
									<i class='fa fa-angle-left pull-right'></i>
								</span>
							</a>
							<ul class='treeview-menu'>";
	protected $config_menu;
	protected $operacion_actual;

	/**
	 * Constructor que inicializa las distintas estructuras que se utilizan
	 * durante el ciclo de vida de la clase
	 * @param array $config arreglo con la configuración del menu ( params.php )
	 */
	function __construct($config)
    {
		parent::__construct();
		$this->items = \toba::proyecto()->get_items_menu(); // Obtiene los items del menú
		$this->buildMap();
		$this->tree = $this->buildTree($this->items);
		$this->config_menu = $config;
		$this->operacion_actual = \toba::solicitud()->get_id_operacion();
	}

	/**
	 * Función que permite renderizar el menú completo
	 * {@inheritDoc}
	 * @see toba_menu_css::mostrar()
	 */
	function mostrar()
    {
		$section = $this->renderPreMenu();

		foreach ($this->tree as $item)
			$section .= $this->renderTree($item);

		$section .= $this->renderPostMenu();

		echo $section;
	}

	/**
	 * A partir de un item padre, se retorna el subarbol de dicho item.
	 * @param integer $padre identificador del item
	 */
	function get_items($padre)
    {
		$items = [];
		foreach ($this->items as $item){
			if ($item['padre'] == $padre){
				$items[] = $this->items_map[$item['item']];
			}
		}
		return $items;
	}

	function get_item($id_item)
    {
		return $this->items_map[$id_item];
	}

	function get_path_item($id_item) {}

	/**
	 * A partir de los items del menú, se contruye un arreglo asociativo
	 * donde la clave es el atributo 'item' que corresponde al id del
	 * elemento.
	 * Permite recuperar un item cualquiera del arbol en orden constante
	 * Complejidad: O(n)
	 */
	private function buildMap()
    {
		foreach ($this->items as $item) {
			$this->items_map[$item['item']] = $item;
		}
	}

	/**
	 * Con los items almacenados, comienza a inicializar la estructura
	 * de arbol de los menú. Para luego poder aplicar algoritmo de recorrido
	 * en profundidad
	 * @param array $elements
	 * @param int $parentId identificador del padre actual
	 * @return unknown[][]|unknown[][][]
	 */
	private function buildTree(array $elements, $parentId = 1)
    {
		$branch = array();

		foreach ($elements as $element) {
			if ($element['padre'] == $parentId) {
				$children = $this->buildTree($elements, $element['item']);
				$element['children'] = $children; // No hace falta ordenarlos porque ya vienen ordenados por padre y por orden
				$branch[] = [ 'id' => $element['item'],'padre'=>$element['padre'],'orden'=> $element['orden'],'hijos'=> $element['children']];
			}
		}

		return $branch;
	}

	/**
	 * Función que retorna el codigo previo al renderizado del menu
	 * @return string
	 */
	private function renderPreMenu()
    {
		$js_previo = '';

		if (\toba::memoria()->get_celda_memoria_actual_id() != 'paralela') {
			$js_previo .= \toba_js::abrir();
			$js_previo .= '
				function on_menu_set_popup_on(e) {
				   	var id = (window.event) ? event.keyCode : e.keyCode;
					if (id == 16) {
						toba.set_menu_popup(true);
					}
				}
				function on_menu_set_popup_off(e) {
				   	var id = (window.event) ? event.keyCode : e.keyCode;
					if (id == 16) {
						toba.set_menu_popup(false);
					}
				}
				agregarEvento(document, "keyup", on_menu_set_popup_off);
				agregarEvento(document, "keydown", on_menu_set_popup_on);
			';
			$js_previo .= \toba_js::cerrar();
			echo $js_previo;
		}

		//$silueta = \toba_recurso::imagen_proyecto('../bt-assets/img/silueta.png', false);
		$perfil_usuario = implode('/', \toba::usuario()->get_perfiles_funcionales());

        if ($perfil_usuario == 'guest') {
            $nombre_usuario = \toba::usuario()->get_id();
        } else {
            $nombre_usuario = \toba::usuario()->get_nombre();
        }

        // Vinculo a la operación que permite modificar la imagen de pefil
        // (se utiliza en la imagen de perfil del menu)
        $vinculo = '';
        if ($this->existe_usuario_encuesta(\toba::usuario()->get_id())) {
            $vinculo = \toba::vinculador()->get_url('kolla', 44000002, [], array('menu' => 0, 'celda_memoria' => '0123456'));
        }
        $flag = \toba::memoria()->get_dato_instancia("flag_imagen_perfil");

        $image_html_source = "<img src=\"". \toba_recurso::imagen_proyecto("../bt-assets/img/silueta.png", false) . "&dummy=" . "" . "\"class='img-responsive img-circle menu_imagen_perfil_usuario' alt='Imagen de perfil'>";

        if ( !isset($flag) || $flag )
        {
            // Se verifica si se subió una imagen de perfil o utilizo el default
            $datos_usuario   = \toba::consulta_php('consultas_usuarios')->get_datos_encuestado_x_usuario_sin_documento(\toba::usuario()->get_id());

            if (isset ($datos_usuario['imagen_perfil_nombre']))
            {
                $usuario = \toba::usuario()->get_id();
                $sql = "SELECT  encode(sge_encuestado.imagen_perfil_bytes,'base64')
                FROM    sge_encuestado
                WHERE   sge_encuestado.usuario = '{$usuario}'";
                $output = \kolla_db::consultar_fila($sql);
                $imgData = $output['encode'];
                $image_html_source = "<img src= \"data:image/png;base64," . $imgData . "\" class='img-responsive img-circle menu_imagen_perfil_usuario' alt='Imagen de perfil'>";
            }

            \toba::memoria()->set_dato_instancia("flag_imagen_perfil", false);
            \toba::memoria()->set_dato_instancia("imagen_perfil", $image_html_source);

        } else
        {
            $image_html_source = \toba::memoria()->get_dato_instancia("imagen_perfil");
        }

        return '<aside class="main-sidebar">
  				<section class="sidebar">	<!-- Sidebar user panel -->
					<div class="user-panel">
						<div class="pull-left image">
						    <a href=' . $vinculo . '>'
                                . $image_html_source .
                            '</a>
						</div>
						<div style="font-family: Helvetica, Arial, sans-serif;
                                    font-weight: bold;
                                    font-size: 12px;
                                    color: #625e5e;
                                    padding-top: 4px;
                                    padding-left: 55px;">
							'.bootstrap_translate::instance()->translate($nombre_usuario).'
						</div>
                        <div style="font-family: Helvetica, Arial, sans-serif;
                                    font-weight: bold;
                                    font-style: italic;
                                    font-size: 11px;
                                    color: #999999;
                                    padding-left: 55px;">
							'.bootstrap_translate::instance()->translate($perfil_usuario).'
						</div>
					</div>
					<!-- search form -->
					<form action="#" method="get" class="sidebar-form">
						<div class="input-group">
							<input type="text" id="search-input" class="form-control" placeholder="Buscar..." onkeyup="buscar_menu(this.value)">
							<span class="input-group-btn">
								<button type="submit" name="search" id="search-btn" class="btn btn-flat">
									<i class="fa fa-search"></i>
							    </button>
							</span>
						</div>
					</form>
					<ul class="sidebar-menu">';
	}

	/**
	 * Implementación de algoritmo de recorrido de arbol en profundidad a partir
	 * de la estructura armada previdamente
	 * @param array $item es una hoja del arbol dentro de la estructura
	 * @return void|string|string
	 */
	private function renderTree($item)
    {
		$objeto = $this->items_map[$item['id']]; // Recupero el objeto ( O(1) )

		if ($item['hijos'] == NULL){// Es una hoja
			return $this->renderItem($objeto);
		} else {
			//Si es el item de ayuda no lo muestro y va directamente en la cabecera
			if (isset($this->config_menu['ayuda']) && $this->config_menu['ayuda']['id'] == $objeto['item'])
				return;
			$clase = $this->es_padre_activo($item) ? 'active':'';
			$subarbol = strtr($this->padre, ['{activo}'=>$clase,'{nombre}'=> $objeto['nombre']]);
			foreach ($item['hijos'] as $hijo)
				$subarbol .=$this->renderTree($hijo);
			$subarbol .= "</ul> </li>";
			return $subarbol;
		}
	}

	/**
	 * Retorna los tags finales perteneciente a la sección
	 * del menú
	 * @return string
	 */
	private function renderPostMenu()
    {
		return '</ul>
				  </section<!-- /.sidebar -->
				</aside>';
	}

	/**
	 * Renderiza un hoja en particular del arbol. Se hace una verificación sobre los
	 * items Inicio y Salir para ver si se quiere mostrar dentro del menu o directamente
	 * se muestra en el header de la página.
	 * @param array $obj
	 * @return void|string
	 */
	private function renderItem($obj)
    {
		if (!isset($obj['js'])){ // Seteo el JS para navegación
			$obj['js'] = 'return toba.ir_a_operacion("'.$obj['proyecto'].'", "'.$obj['item'].'", false)';
		}

		//Si es el item del inicio y no debo mostrarlo retorno directamente
		if (isset($this->config_menu['inicio']) && !$this->config_menu['inicio']['mostrar'] && $this->config_menu['inicio']['id'] == $obj['item'])
			return;
		//Si es el item para cerrar sesión, no lo muestro porque va directamente en la cabecera
		if (isset($this->config_menu['salir'])  && $this->config_menu['salir']['id'] == $obj['item'])
			return;

		//Veo si el item pertenece a la operación actual
		$activo = (isset($this->operacion_actual) && $this->operacion_actual == $obj['item'])?'active':'';

		return strtr($this->hoja,['{activo}'=>$activo,'{js}'=>$obj['js'],'{nombre}'=>$obj['nombre']]);
	}

	private function es_padre_activo($item)
    {
		$variable = false;
		if ($item['hijos'] == null){// Es una hoja
			if($item['id'] ==  $this->operacion_actual)
				return true;
			else
				return false;
		} else {
			foreach ($item['hijos'] as $hijo)
				$variable = $variable || $this->es_padre_activo($hijo);
		}
		return $variable;
	}

	function get_path_actual()
    {
		//Evito que sea la operación de inicio porque siempre va a estar en el breadcrumbs
		if($this->operacion_actual != $this->config_menu['inicio']['id']) {
			$path = []; //base
			$to_return = [];
			$hoja_actual = $this->items_map[$this->operacion_actual]; // Obtengo el objecto de la operación actual
			$path[] = $hoja_actual;

			//escalo en jerarquia hasta el inicio
			while (isset($hoja_actual['padre']) && $hoja_actual['padre'] != '1'){
				$hoja_actual = $this->items_map[$hoja_actual['padre']];
				$path []= $hoja_actual;
			}

			$path = array_reverse($path);
			foreach ($path as $item) {
				if(!$item['carpeta']) {
					if (!isset($hoja_actual['js'])) {
						$item['js'] = 'return toba.ir_a_operacion("'.$item['proyecto'].'", "'.$item['item'].'", false)';
					}
					$to_return[] = "<li><a href='#' onclick='$item[js]' >$item[nombre]</a></li>";
				} else {
					$to_return[] = "<li><a>$item[nombre]</a></li>";
				}
			}

			return implode('', $to_return);
		}
	}

    /**
     * @param $usuario Nombre del usuario logeado en sistema.
     * @return bool retorna un valor booleano correspondiente a si existe dicho usuario en la tabla sge_encuesta.
     * @throws \toba_error
     */
	protected function existe_usuario_encuesta($usuario) {
        $resultado = \toba::consulta_php('consultas_usuarios')->get_datos_encuestado_x_usuario_sin_documento($usuario);

        return ($resultado !== false && count($resultado) > 0 );
}

}