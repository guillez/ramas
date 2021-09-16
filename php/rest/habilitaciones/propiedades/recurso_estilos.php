<?php

use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;

class recurso_estilos implements modelable {
    
    public static function _get_modelos() {
		return [];
	}
    
    protected $modelo;
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_habilitaciones', true );
	}
    
    /**
	 * GET /propiedades
	 *
	 * @notes Retorna un listado de todos los estilos disponibles para las habilitaciones
	 *
	 * @response_type Respuestas
	 * @summary Retorna un listado de todos los estilos disponibles para las habilitaciones
	 *        	
	 * @responses 200 Exito
     * @responses 400 No existen estilos disponibles
	 */
    function get_list() {
        $data = $this->modelo->get_estilos();
        rest::response()->get_list($data);
    }

}