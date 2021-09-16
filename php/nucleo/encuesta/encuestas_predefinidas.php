<?php

class encuestas_predefinidas
{
    private static $instancia = NULL;
    private $encuestas = array();
   
    private function __construct()
    {
        $this->encuestas = toba::consulta_php('consultas_encuestas')->get_encuestas_predefinidas();
    }
    
    public static function get_instancia()
    {
        if (is_null(self::$instancia)) {
            self::$instancia = new encuestas_predefinidas();
        }

        return self::$instancia;
    }
    
    public function get_encuestas()
    {
        return $this->encuestas;
    }
    
}

?>