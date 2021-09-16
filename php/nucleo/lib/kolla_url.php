<?php

class kolla_url {
    
    static function get_protocolo($basado_en_host = true, $forzar_seguro = false)
    { 
        $basico = 'http'; 
        
        if ($forzar_seguro || ($basado_en_host &&  self::usa_protocolo_seguro())) { 
            $basico .= 's'; 
        }
        
        $basico .= '://';
        return $basico; 
    }

    static function usa_protocolo_seguro() 
    {
        return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')); 
    }
}
?>
