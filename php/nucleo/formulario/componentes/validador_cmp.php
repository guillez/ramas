<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validador
 *
 * @author demo
 */
abstract class validador_cmp 
{
	
	protected $clase_js_validacion;
	protected $reglas_js_validacion;

	public function __construct($clase_js, $reglas = '')
    {
		$this->clase_js_validacion = $clase_js;
		$this->reglas_js_validacion = $reglas;
	}
	
	abstract function validar($valor);

	function get_clase_js_validacion()
    {
		if ( !isset($this->clase_js_validacion) ) {
            return array();
        }
		return $this->clase_js_validacion;
	}
	
	function get_atributos_js_validacion()
    {
        return $this->reglas_js_validacion;
	}
}

class validador_requerido extends validador_cmp{
	protected $mensaje = 'El valor es requerido';
	public function __construct() {
		parent::__construct(array("required"));
	}

	function validar($x){
		if (isset($x) && $x!= null && $x != '') return true;
		return $this->mensaje;
	}
}

/**
 * Determina si un componente de opciones tiene al menos 1 rta seleccionada
 */
/*class validador_existe_rta extends validador_cmp{
	protected $mensaje = "Debe seleccionar una opcion";
	
	function __construct() {
 		parent::__construct(null);
	}
	
	function validar($valor){
		$b = validador::validar($valor, validador::TIPO_INT);
		if ($b && (intval($valor) <= $this->max) && (intval($valor) >= $this->min)) return true;
			$this->mensaje;
		}
}*/

class validador_numero_decimal extends validador_cmp{
	protected $mensaje = 'Por favor, ingrese un número válido';
	public function __construct() {
                 $atribs = "maxlength= 9";
		parent::__construct(array("number"), $atribs);
	}

	function validar($x){
		$conpunto = str_replace(',', '.', $x);
		if (is_numeric($conpunto)) {
			if (floatval($conpunto) == $conpunto) {
				return true;
			}
		}
		return $this->mensaje;
	}
}

class validador_numero_telefono extends validador_cmp{
    protected $mensaje = 'Por favor, ingrese un número de teléfono válido, incluyendo código internacional y código regional';
    public function __construct() {
        $atribs = "maxlength= 25";
        parent::__construct(array("val_telefono"), $atribs);
    }

    function validar($x){
        // La función preg_match esta disponible en php 4, php 5 y php 7
        $expresion = '/^[\+]?[0-9]{2,4}[\s]([0-9]{2,5}|\([0-9]{2,5}\))[\s]([0-9][\s,-]?){4,10}$/';
        if( preg_match($expresion, $x) ) {
            return true;
        }

        return $this->mensaje;
    }
}

class validador_numero extends validador_cmp{
	protected $mensaje = 'Por favor, ingrese solo dígitos';
	public function __construct() {
                $atribs = "maxlength= 9";
		parent::__construct(array("digits"), $atribs);
	}

	function validar($valor){
		$b = validador::validar($valor, validador::TIPO_INT);
		if($b !== false) return true;
		return $this->mensaje;
	}
}

class validador_fecha extends validador_cmp{
	protected $mensaje = 'Tipo fecha con formato dd/mm/yyyy';
	
	public function __construct() {
		parent::__construct(array("val_fecha"));
	}
	
	function validar($f){
		//$b = validador:::validar($valor, validador:::TIPO_INT, array('format' => 'd/m/Y')); USAR CUANDO TENGAMOS PHP 5.3+

        /*
		if (ereg("(([0-9]{1,2}-[0-9]{1,2})-([0-9]{4}))", $f)) {
			$array_fecha = explode("-", trim($f));
		} else {
			// chequeo formato dd/mm/yyyy
			if (ereg("(([0-9]{1,2}/[0-9]{1,2})/([0-9]{4}))", $f, $array_fecha)) {
				$array_fecha = explode("/", trim($f));
			} else {
				return false;
			}
		}
        */

        $expresion_1 = '/(([0-9]{1,2}-[0-9]{1,2})-([0-9]{4}))/';

        if (preg_match($expresion_1, $f)) {
            $array_fecha = explode("-", trim($f));
        } else {
            // chequeo formato dd/mm/yyyy
            $expresion_2 = '(([0-9]{1,2}/[0-9]{1,2})/([0-9]{4}))';
            if (preg_match($expresion_2, $f, $array_fecha)) {
                $array_fecha = explode("/", trim($f));
            } else {
                return false;
            }
        }

		
		$dia = $array_fecha[0];
		$mes = $array_fecha[1];
		$anio = $array_fecha[2];
		if(@checkdate($mes, $dia, $anio) === true) return true;
		return $this->mensaje;
	}

}
class validador_edad extends validador_cmp{
	private $min = 0;
	private $max = 100;
	protected $mensaje = "Debe ser un valor numérico entre 0 y 100";
	
	public function __construct() {
        $config = "min='$this->min' max ='$this->max' maxlength = 3"; //el range no anda bien
		parent::__construct(null, $config);
	}

	function validar($valor){
		$b = validador::validar($valor, validador::TIPO_INT);
		if($b !== false && $valor >= $this->min && $valor <= $this->max){
			return true;
		}
		return $this->mensaje;
	}
}

class validador_mail extends validador_cmp{
	protected $mensaje = "Por favor, ingrese un mail";
	public function __construct() {
                $atribs = "maxlength= 50";
		parent::__construct(array("email"), $atribs);
	}

	function validar($email)
	{
		$mail_correcto = validador::validar($email, validador::TIPO_MAIL);
		if($mail_correcto) return true;
		return $this->mensaje;
	}
}

class validador_texto extends validador_cmp{
	
	protected $mensaje = 'La longitud no puede ser mayor a ';
	protected $max = 0;
	public function __construct($maxl) {
		$this->max = $maxl;
		$config = " maxlength = $maxl ";
		parent::__construct(array("val_texto"), $config);
	}

	function validar($valor){
		if (strlen($valor) <= $this->max) return true;
		return $this->mensaje . $this->max;
	}
}

class validador_anio extends validador_cmp{
	private $min = 1900;
	private $max = 9000;
	protected $mensaje = "Debe ser un valor numérico entre 1900 y 9000";
	
	function __construct() {
        $config = "min='$this->min' max ='$this->max' maxlength = 4"; //el range no anda bien
		parent::__construct(null, $config);
	}
	
	function validar($valor){
		$b = validador::validar($valor, validador::TIPO_INT);
		if ($b && (intval($valor) <= $this->max) && (intval($valor) >= $this->min)) return true;
			$this->mensaje;
		}
}

	//------------------------------------------------------------------------------
	//-- Procesos de validaciones -- NO SE USA MAS
	//------------------------------------------------------------------------------

	function validar_pregunta($pregunta, $valor, $check_obligatorio)
	{
		$preguntaValida = true;
		$pregunta_nombre = $pregunta['nombre'];
		$componente = $pregunta['componente'];
		$es_obligatorio = $pregunta['obligatoria'];

		if (is_array($valor)) {
			$len = count($valor);
		} else {
			$len = strlen($valor);
		}

		//--- Validacion de obligatorios
		if ($check_obligatorio) {
			if (($es_obligatorio == 'S') && ($len == 0)) {
				$this->agregar_mensaje("Error en pregunta obligatoria:\"" . $pregunta_nombre);
				$preguntaValida = false;
			}
		}

		if ($len > 0) {
			if ((($componente == 'texto_fecha') || ($componente == 'fecha_calculo_anios')) && (!$this->validar_fecha($valor))) {
				$preguntaValida = false;
                $descripcion = 'Tipo fecha con formato dd/mm/yyyy';
			} elseif (($componente == 'texto_numeroentero') && (!$this->validar_entero($valor))) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico entero de 1 a 99999999';
			} elseif (($componente == 'texto_numeroedad') && (!$this->validar_entero($valor))) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico entero de 1 a 100';
			} elseif (($componente == 'texto_numeroedad') && (intval($valor) < 1) && (intval($valor) > 100)) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico entero de 1 a 100';
			} elseif (($componente == 'texto_numeroanio') && (!$this->validar_entero($valor))) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico entero de 1000 a 9000';
			} elseif (($componente == 'texto_numeroanio') && (intval($valor) < 1000) && (intval($valor) > 9000)) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico entero de 1000 a 9000';
			} elseif (($componente == 'texto_numerodecimal') && (!$this->validar_decimal($valor))) {
				$preguntaValida = false;
				$descripcion = 'Tipo numérico decimal';
			} elseif (($componente == 'texto_mail') && (!$this->validar_email($valor))) {
				$preguntaValida = false;
				$descripcion = 'Tipo alfanumérico con formato de correo electrónico';
			} elseif ((($componente == 'texto') || ($componente == 'textarea')) && (strlen($valor) > 1000)) {
				$preguntaValida = false;
				$descripcion = 'Tipo alfanumérico con máximo de 1000 caracteres';
			}
		}

		if (!$preguntaValida) {
			$this->agregar_mensaje("Error en pregunta:\"" . $pregunta_nombre . "\"(" . $descripcion . ")");
		}

		return $preguntaValida;
	}
?>