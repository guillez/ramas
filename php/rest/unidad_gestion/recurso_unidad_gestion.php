<?php

use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;

class recurso_unidad_gestion implements modelable {
	
	/**
	 *
	 * @var rest_unidad_gestion
	 */
	protected $modelo;
    
	function __construct() {
		$this->modelo = kolla::rest ('rest_unidad_gestion', true);
	}
	
	public static function _get_modelos() {
		$unidad_gestion = [
				'unidad_gestion' => [
						'type' => 'string',
						'_validad' =>[
								regla::OBLIGATORIO
						]
				],
				'nombre' => [
						'type' => 'string',
						'_validad' =>[
								regla::OBLIGATORIO
						]
				],
				
		];
	
		return ['UnidadGestion' => $unidad_gestion];
	}
	
	/**
	 * GET /unidad_gestion
	 *
	 * @notes Retorna un listado de todas las unidades de gestion 
	 *
	 * @summary Retorna un listado de todas las unidades de gestion 
	 * @response_type UnidadGestion
	 *
	 * @responses 200 array $UnidadGestion
	 * @responses 404 
	 */	
	public function get_list()
	{
		$datos = $this->modelo->get_list ();
		rest::response ()->get_list ( $datos );
	}
	
	/**
	 * GET /unidad_gestion/id
	 *
	 * @notes Retorna la unidad de gestion {id}
	 *
	 * @summary Devuelve datos de una Unidad de Gestion
	 * @response_type UnidadGestion
	 *
	 * @responses 200 $UnidadGestion
	 * @responses 404 
	 */
	function get($id_unidad) {
		$datos = $this->modelo->get( $id_unidad );
		rest::response ()->get ( $datos );
	}
}