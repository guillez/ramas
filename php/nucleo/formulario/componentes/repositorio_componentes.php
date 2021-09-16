<?php

/**
 * Esta clase tiene que tener todos los switch feos que generan indefectiblemente
 * los componentes de la encuesta.
 * Con get_componente() se resuelve la generacion de html y validaciones
 * Con get_procesadores se resuelve el procesamiento del post.
 *
 * @author demo
 */

require_once 'validador_cmp.php'; //tiene todo los validadores

class repositorio_componentes
{
	/*Creo un objetito liviano para mayor facilidad de acceso, que usa las
	 * estructuras estaticas 
	 */
	protected $procesadores;
	
	/**
	 *Deshabilita todo los componentes- Util para mostrar formularios.
	 * @var type 
	 */
	protected static $disabled = false;
	
	public function __construct() {
		$this->procesadores = self::get_procesadores();
	}
	
	public function procesar_post($tipo, $id_post, &$respuestas){
		//if ($tipo != 'label'){
        if (!repositorio_etiquetas::instance()->es_etiqueta($tipo)) {
			$this->procesadores[$tipo]->procesar_post($id_post, $respuestas);
		}
	}
	
	public function get_valor_para_sql($tipo, $respuestas){
		//if ($tipo != 'label') {
        if (!repositorio_etiquetas::instance()->es_etiqueta($tipo)) {
			return $this->procesadores[$tipo]->get_valor_para_sql($respuestas);
		}
	}
	
	public function get_tipo_sp($tipo){
		//if ($tipo != 'label') {
        if (!repositorio_etiquetas::instance()->es_etiqueta($tipo)) {
			return $this->procesadores[$tipo]->get_tipo_sp();
		}
	}
	
	public function to_string($tipo, $respuestas){
		//if ($tipo != 'label') {
        if (!repositorio_etiquetas::instance()->es_etiqueta($tipo)) {
			return $this->procesadores[$tipo]->to_string($respuestas);
		}else throw new Exception("No tiene toString el label");
	}

	protected static $cmp_html = array();
	protected static $procesadores_cmp;

	/**
	 * Genera solo componentes deshabilitados. Util para mostrar formularios
	 * que no se pueden editar.
	 */
	public static function disable_all_components(){
		self::$disabled = true;
	}
	
	public static function get_active_components() {
		return self::$cmp_html;
	}

	public static function get_componente($tipo) 
    {
		$validadores = null;
		$atributos   = array();
		$clases      = array();
		
		if( self::$disabled ) {
            $atributos['disabled']='';
        }

        // Hago esto, que no es para nada prolijo, pero en realidad no me interesa
        // tener un componente para las etiquetas, entonces cualquiera de estas
        // que sea tratada como un 'label'.
        if ( repositorio_etiquetas::instance()->es_etiqueta($tipo) ) {
            $tipo = 'label';
        }

		if ( !isset(self::$cmp_html[$tipo]) ) {
			switch ($tipo) {
				case 'label':// las preguntas de "ETIQUETA" no tienen respuesta.
					$clases[] = 'ef_label';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'localidad': // LOCALIDAD
					$clases[] = 'ef_localidad';
					self::$cmp_html[$tipo] = new kolla_cp_localidad($validadores, $atributos, $clases);
					break;
				case 'texto': // TEXTO LIBRE
					$validadores = new validador_texto(validador::RENGLON_MAX_LENGTH);
					$atributos['type'] = "'text'";
					$atributos['size'] = validador::RENGLON_MAX_LENGTH; /* , */
					$clases[] = 'ef_editable span12';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'texto_numeroentero': // TEXTO LIBRE NUMERICO LARGO
					$validadores = new validador_numero();
					$atributos['type'] = "'text'";
					$atributos['size'] = 15;
					$clases[] = 'ef_editable_numero';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'texto_numeroedad': // TEXTO LIBRE NUMERICO EDAD
					$validadores = new validador_edad();
					$atributos['type'] = "'text'";
					$atributos['size'] = 5;
					$clases[] = 'ef_editable_numero_edad';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'texto_numeroanio': // TEXTO LIBRE NUMERICO AÑO
					$validadores = new validador_anio();
					$atributos['type'] = "'text'";
					$atributos['size'] = 5;
					$clases[] = 'ef_editable_numero_anio';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'texto_numerodecimal': // TEXTO LIBRE DECIMAL
					$validadores = new validador_numero_decimal();
					$atributos['type'] = "'text'";
					$atributos['size'] = 15;
					$clases[] = 'ef_editable_numero_decimal';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
                case 'texto_numerotelefono': // TEXTO LIBRE TELEFONO
                    $validadores = new validador_numero_telefono();
                    $atributos['type'] = "'text'";
                    $atributos['size'] = 25;
                    $atributos['placeholder'] = "'Formato válido: +54 (011) 1234-5678'";
                    $clases[] = 'ef_editable';
                    self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
                    break;
				case 'texto_fecha': // TEXTO LIBRE FECHA
					$validadores = new validador_fecha();
					$atributos['type'] = "'text'";
					$atributos['size'] = 10;
					$clases[] = 'ef_editable_fecha';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'texto_mail': // TEXTO LIBRE MAIL
					$validadores = new validador_mail();
					$atributos['type'] = "'text'";
					$atributos['size'] = 50;
					$clases[] = 'ef_editable_mail span12';
					self::$cmp_html[$tipo] = new kolla_cp_input($validadores, $atributos, $clases);
					break;
				case 'textarea': // TEXTO LIBRE AREA
					$validadores = new validador_texto(validador::AREA_MAX_LENGTH);
					$atributos['rows'] = 10;
					$atributos['cols'] = 50;
					$atributos['maxlength'] = validador::AREA_MAX_LENGTH;
					$clases[] = 'ef_editable_textarea span12';
					self::$cmp_html[$tipo] = new kolla_cp_textarea($validadores, $atributos, $clases);
					break;
				case 'combo': // COMBO
					$clases[] = 'ef_combo';
					$clases[] = 'inline';
					self::$cmp_html[$tipo] = new kolla_cp_combo($validadores, $atributos, $clases);
					break;
				case 'radio': // RADIO BUTTONS
					$atributos['type'] = "'radio'";
					$clases[] = 'ef_radio inline'; 
					self::$cmp_html[$tipo] = new kolla_cp_radio($validadores, $atributos, $clases);
					break;
				case 'check': // CHECKS (RESPUESTAS MULTIPLES)
					$clases[] = 'ef_check';
					self::$cmp_html[$tipo] = new kolla_cp_check($validadores, $atributos, $clases);
					break;
				case 'list': // LISTAS (RESPUESTAS MULTIPLES)
					$clases[] = 'ef_list';
					self::$cmp_html[$tipo] = new kolla_cp_list($validadores, $atributos, $clases);
					break;
                case 'fecha_calculo_anios': // FECHA CON CALCULO DE AÑOS (PREGUNTA COMPUESTA)
					$validadores = new validador_fecha();
					$atributos['type'] = "'text'";
					$atributos['size'] = 10;
					$clases[] = 'ef_editable_fecha';
                    $clases[] = 'dinamico_fecha_edad';
					self::$cmp_html[$tipo] = new kolla_cp_fecha_calculo_anios($validadores, $atributos, $clases);
					break;
                case 'localidad_y_cp': // LOCALIDAD Y CÓDIGO POSTAL
					$clases[] = 'ef_localidad';
                    self::$cmp_html[$tipo] = new kolla_cp_localidad_y_cp($validadores, $atributos, $clases);
					break;
                case 'combo_dinamico': // COMBO
					$clases[] = 'ef_combo';
					$clases[] = 'inline';
                    $clases[] = 'dinamico_localidad';
					self::$cmp_html[$tipo] = new kolla_cp_combo_dinamico($validadores, $atributos, $clases);
					break;
                case 'combo_autocompletado': // COMBO_EDITABLE - AUTOCOMPLETADO
                    $clases[] = 'select_search';
                    self::$cmp_html[$tipo] = new kolla_cp_combo_autocompletado($validadores, $atributos, $clases);
                    break;
				default:
                                    echo "Componente invalido- skipping '$tipo'";
                                return;
                    
			}
		}
        
		return self::$cmp_html[$tipo];
	}
	
	static function get_procesadores() {
		if (!isset(self::$procesadores_cmp)) {
			$proc_texto = new procesador_componentes_texto();
			$proc_telefono = new procesador_componentes_telefono();
			$proc_mult = new procesador_componentes_multiple();
			$proc_opc = new procesador_componentes_opciones();
			self::$procesadores_cmp = array(
				'texto' => $proc_texto,
				'texto_numeroentero' => $proc_texto,
				'texto_numeroedad' => $proc_texto,
				'texto_numeroanio' => $proc_texto,
				'texto_numerodecimal' => $proc_texto,
                'texto_numerotelefono' => $proc_telefono,
				'texto_fecha' => $proc_texto,
				'texto_mail' => $proc_texto,
				'textarea' => $proc_texto,
				'localidad' => $proc_texto,
                'localidad_y_cp' => $proc_texto,
				'combo' => $proc_opc,
                'combo_dinamico' => $proc_opc,
				'radio' => $proc_opc,
				'check' => $proc_mult,
				'list' => $proc_mult,
                'fecha_calculo_anios' => $proc_texto,
                'combo_autocompletado' => $proc_opc,
			);
		}
		return self::$procesadores_cmp;
	}

}

class procesador_componentes_texto {

	function procesar_post($id_post, &$respuestas) {
        $respuestas[0]['respuesta_valor'] = (isset($_POST[$id_post]) && trim($_POST[$id_post]) != "")?$_POST[$id_post]:null;
	}
	function get_valor_para_sql($respuestas){
		return $respuestas[0]['respuesta_valor']; //quotea el sp 
	}
	
	function to_string($respuestas){ //NO PUEDE CAMBIAR PORQUE SE USA PARA EL HASH
		return quote($respuestas[0]['respuesta_valor']);
	}
	
	function get_tipo_sp(){
		return "lib";
	}

}

class procesador_componentes_telefono {

    function procesar_post($id_post, &$respuestas) {
        $respuestas[0]['respuesta_valor'] = (isset($_POST[$id_post]) && trim($_POST[$id_post]) != "")?$_POST[$id_post]:null;
    }

    function get_valor_para_sql($respuestas){
        // Le doy formato uniforme al numero de telefono para que se almacenen como especificó Marco
        // en el ticket #17315
        $original = $respuestas[0]['respuesta_valor'];

        // Separo SOLO por los tres primeros espacios
        $split = preg_split('/[\s]/', $original, 3);
        $internacional = $split[0];
        $area = $split[1];
        $numero = $split[2];

        // Elimino los espacios y guiones del numero
        $caracteres = array(" ", "-");
        $numero_sanitizado = str_replace($caracteres, "", $numero);

        // Armo el numero final
        $respuestas[0]['respuesta_valor'] = $internacional . " " . $area . " " . $numero_sanitizado;

        return $respuestas[0]['respuesta_valor']; //quotea el sp
    }

    function to_string($respuestas){ //NO PUEDE CAMBIAR PORQUE SE USA PARA EL HASH
        return quote($respuestas[0]['respuesta_valor']);
    }

    function get_tipo_sp(){
        return "lib";
    }

}

class procesador_componentes_opciones {

	function procesar_post($id_post, &$respuestas) {
		if (!isset($_POST[$id_post]))
        {
            foreach ($respuestas as &$rta) {
                    unset($rta['sel']);
            }
            return;    
        }//si no hay post, no "des-seteo" tampoco!!.
		$id = $_POST[$id_post];

        foreach ($respuestas as &$rta) {
			if (($id != null) && $rta['respuesta'] == $id) {
				$rta['sel'] = 'S';
			} else {
                if (isset($rta['sel']))
                    unset($rta['sel']);
            }
		}
	}
    
	function get_valor_para_sql($respuestas){
		foreach($respuestas as $respuesta){
			if ((isset($respuesta['sel']) && $respuesta['sel'] == 'S')){
                return (int)$respuesta['respuesta'];
            }
		}
		return null;
	}
	
	function to_string($respuestas){ //NO PUEDE CAMBIAR PORQUE SE USA PARA EL HASH
		foreach($respuestas as $respuesta){
			if ((isset($respuesta['sel']) && $respuesta['sel'] == 'S'))
			return $respuesta['respuesta'];
		}
		return 'null';
	}
	
	function get_tipo_sp(){
		return "tab";
	}

}

class procesador_componentes_multiple {

	function procesar_post($id_post, &$respuestas) 
    {
		if ( !isset($_POST[$id_post]) ) {
			//return; //si no hay post, no "des-seteo" tampoco.
            // Porqué no? > Rodrigo
            foreach ($respuestas as &$rta) {
                unset($rta['sel']);
            }
            return;
        }
        
		$array_post = $_POST[$id_post];
        
        if ( !is_array($array_post) ) {//es ''.
            $array_post = array();
        }
		foreach ($respuestas as &$rta) {
			if (in_array($rta['respuesta'], $array_post)) {
				$rta['sel'] = 'S';
			} else if ( isset($rta['sel']) )
				unset($rta['sel']);
		}
	}
    
	function get_valor_para_sql($respuestas)
    {
		$separador = ''; //es este la primera vez.
		$valor = "{";
		foreach($respuestas as $respuesta){
			if ((isset($respuesta['sel']) && $respuesta['sel'] == 'S')){
				$valor .= $separador . (int)$respuesta['respuesta'] ;
				$separador = ', ';
			}
		}
		$valor .=  "}";
		return $valor;
	}
	
	function to_string($respuestas){ //NO PUEDE CAMBIAR PORQUE SE USA PARA EL HASH
		$separador = ''; //es este la primera vez.
		$valor = "{";
		foreach($respuestas as $respuesta){
			if ((isset($respuesta['sel']) && $respuesta['sel'] == 'S')){
				$valor .= $separador . $respuesta['respuesta'] ;
				$separador = ', ';
			}
		}
		$valor .=  "}";
		return $valor;
	}
	
	function get_tipo_sp()
    {
		return "mul";
	}

}
?>