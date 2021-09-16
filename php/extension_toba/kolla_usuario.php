<?php

class kolla_usuario extends toba_usuario_basico
{
	protected $doc_tipo;
	protected $doc_numero;
	
	//---- API agregada de usuario KOLLA ------------------------------------------
	
	function get_doc_tipo()
	{
		return $this->doc_tipo;
	}
	
	function get_doc_numero()
	{
		return $this->doc_numero;
	}

    /**
     * @param $long
     * @return string
     * Se crea una copia de la función de toba que genera una clave aleatoria
     * Se estaban generando caracteres fuera de los esperados
     */

    function kolla_generar_clave_aleatoria($long)
    {
        $str = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789-._~';
        for($cad='',$i=0;$i<$long;$i++) {
            $cad .= substr($str,rand(0,(strlen($str)-1)),1);
        }
        return $cad;
    }
	
}

?>