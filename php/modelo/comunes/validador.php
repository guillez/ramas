<?php
/**
 * Validador con métodos estáticos para hacer validaciones de parámetros. 
 */
class validador
{
    const TIPO_ALPHA    = 1;
    const TIPO_ALPHANUM = 2;
    const TIPO_INT		= 3;
    const TIPO_DATE		= 4;
    const TIPO_TIME		= 5;
    const TIPO_CUSTOM	= 6;
    const TIPO_MAIL		= 7;
    const TIPO_TEXTO	= 8;

    const TIPO_ARRAY_ALPHA		= 101;
    const TIPO_ARRAY_ALPHANUM	= 102;
    const TIPO_ARRAY_INT		= 103;
    const TIPO_ARRAY_DATE		= 104;
    const TIPO_ARRAY_TIME		= 105;
    const TIPO_ARRAY_CUSTOM		= 106;
    const TIPO_ARRAY_MAIL		= 107;
    const TIPO_ARRAY_TEXTO		= 108;

    const MAIL_MAX_LENGTH    = 127;
    const RENGLON_MAX_LENGTH = 50;
    const AREA_MAX_LENGTH    = 4096;
	
    const SEXO_FEMENINO	 = 'F';
    const SEXO_MASCULINO = 'M';
    
    const USUARIO_MAX_LENGTH = 60;

    static function es_valido($valor, $tipo = self::TIPO_ALPHA, $options = array())
    {
        $valor = self::validar($valor, $tipo, $options);
        return $valor !== false;
    }
     
    /**
     * Retorna falso si no valida, o sino el valor validado.
     * @param type $valor
     * @param type $tipo
     * @param type $options
     * @return type
     */
    static function validar($valor, $tipo = self::TIPO_ALPHA, $options = array())
    {
        $filter_options = array();
        $flags = '';

        if (empty($valor) && $valor !== 0) {
            if (isset($options['allowempty']) && $options['allowempty']) {
                    return $valor;
            } else if (isset($options['default'])) {
                    return $options['default'];
            }
        }

        switch ($tipo) {
            case ($tipo >= self::TIPO_ARRAY_ALPHA):
                return self::validar_array($valor, $tipo % 100);
                break;
            case self::TIPO_ALPHA:
                $filter = FILTER_VALIDATE_REGEXP;
                $filter_options = array('regexp' => "/^[a-zA-Z]+$/");
                break;
            case self::TIPO_ALPHANUM:
                $filter = FILTER_VALIDATE_REGEXP;
                $filter_options = array('regexp' => "/^[a-zA-Z0-9]+$/");
                break;
            case self::TIPO_INT:
                $all_digits = ctype_digit($valor);
                return ($all_digits) ? $valor : false;
            case self::TIPO_MAIL:
                $filter = FILTER_VALIDATE_EMAIL;
                if (strlen($valor) > self::MAIL_MAX_LENGTH) {
                        return false;
                }
                break;
            case self::TIPO_TEXTO:
                return $valor;
                break;
            case self::TIPO_DATE:
                $date = date_parse_from_format($options['format'], $valor);
                if ($date['error_count'] == 0) {
                        if (checkdate($date['month'], $date['day'], $date['year'])) {
                                return $valor;
                        }
                }
                return false;
            case self::TIPO_TIME:
                $date = date_parse_from_format($options['format'], $valor);
                if ($date['error_count'] == 0) {
                        if (self::checktime($date['hour'], $date['minute'], $date['second'])) {
                                return $valor;
                        }
                }
                return false;
            case self::TIPO_CUSTOM:
                $filter = FILTER_VALIDATE_REGEXP;
                $format = $options['format'];
                $filter_options = array('regexp' => "/$format$/");
                break;
        }

        return filter_var($valor, $filter, array('options' => $filter_options, 'flags' => $flags));
    }

    static function checktime($hour, $minute, $seconds = 0) 
    {
    	if ($hour > -1 && $hour < 24 && $minute > -1 && $minute < 60 && $seconds > -1 && $seconds <60) { 
        	return true; 
        } 

        return false;
    }

    protected static function validar_array($valores, $tipo)
    {
        foreach ($valores as $valor) {
        	$rs = self::validar($valor, $tipo);
            if ($rs === false) {
            	return false;
            }
        }
        return $valores;
    }

    /**
     * Retorna true si la fecha tiene el formato dd/mm/aaaa, y falso en caso contrario.
     */
	static function validar_fecha($fecha)
	{
		if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fecha)) {
			$array_fecha = explode('/', $fecha);
			return checkdate($array_fecha[1], $array_fecha[0], $array_fecha[2]);
		}
		
		return false;
	}
	
	static function validar_email($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	static function validar_sexo($sexo)
	{
		return strtoupper($sexo) == self::SEXO_FEMENINO || strtoupper($sexo) == self::SEXO_MASCULINO;
	}
	
	static function validar_pais_documento($pais_documento)
	{
		$paises_documentos_validos = kolla_arreglos::aplanar_matriz_sin_nulos(toba::consulta_php('consultas_mug')->get_paises(), 'pais');
		return in_array($pais_documento, $paises_documentos_validos);
	}
	
	static function validar_tipo_documento($tipo_documento)
	{
		$tipos_de_documentos_validos = kolla_arreglos::aplanar_matriz_sin_nulos(toba::consulta_php('consultas_encuestas')->get_tipodoc(), 'documento_tipo');
		return in_array($tipo_documento, $tipos_de_documentos_validos);
	}
	
    //valida que el id de usuario sea válido
    static function validar_usuario($usuario)
	{
        return (strlen($usuario) <= self::USUARIO_MAX_LENGTH);
	}
}
?>