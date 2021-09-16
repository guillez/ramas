<?php

/**
 * Esta clase contiene el puente hacia TOBA. Si se quisiese utilizar con otro
 * framework hay que implementar estos objetos (con la misma interfaz).
 *
 * @author demo
 */
class kolla 
{
	static private $rest_php;
    static private $abm_php;

    /**
     * @return toba_db 
     */
    static function db()
    {
       return toba::db();
    }
	
    /**
     * @return toba_logger
     */
    static function logger()
    {
        return toba::logger();
    }
	
    /**
     * @return toba_memoria
     */
	static function memoria()
    {
		return toba::memoria();
	}
    
    static function co($clase_consulta) 
	{
		return toba::consulta_php($clase_consulta);
	}
    
    /**
     * Retorna modelo para REST
     * regenerar: sirve para asegurarse de que no se cachea nada en tests unitarios
     */
    static function rest($clase, $regenerar = false)
	{
		if (!isset(self::$rest_php[$clase]) || $regenerar ) {
			self::$rest_php[$clase] = new $clase();
		}
		return self::$rest_php[$clase];
	}
    
    /**
     * Retorna una clase de actualizacion
     */
    static function abm($clase)
	{
		if (!isset(self::$abm_php[$clase])) {
			self::$abm_php[$clase] = new $clase();
		}
		return self::$abm_php[$clase];
	}
    
}