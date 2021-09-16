<?php

/**
 * User: glodovskis
 * Date: 27/03/20
 */

class nombre_archivo
{
    /**
     * Call this method to get singleton
     */
    public static function instancia()
    {
        static $instance = false;
        
        if ($instance === false) {
            // Late static binding (PHP 5.3+)
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct() {}

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

    public function get_nombre($encuesta)
    {
        $dia     = kolla_fecha::get_hoy_parte('dia');
        $mes     = kolla_fecha::get_hoy_parte('mes') - 1;
        $anio    = kolla_fecha::get_hoy_parte('anio');
        $hora    = kolla_fecha::get_hoy_parte('hora');
        $minuto  = kolla_fecha::get_hoy_parte('minuto');
		$segundo = kolla_fecha::get_hoy_parte('segundo');
        
        return $dia.'_'.$mes.'_'.$anio.'_'.$hora.'_'.$minuto.'_'.$segundo.'_create_encuesta_'.$encuesta.'.txt';
    }
    
    public function get_nombre_tabla_asociada($encuesta)
    {
        $dia     = kolla_fecha::get_hoy_parte('dia');
        $mes     = kolla_fecha::get_hoy_parte('mes') - 1;
        $anio    = kolla_fecha::get_hoy_parte('anio');
        $hora    = kolla_fecha::get_hoy_parte('hora');
        $minuto  = kolla_fecha::get_hoy_parte('minuto');
		$segundo = kolla_fecha::get_hoy_parte('segundo');
        
        return $dia.'_'.$mes.'_'.$anio.'_'.$hora.'_'.$minuto.'_'.$segundo.'_encuesta_'.$encuesta;
    }
    
}
