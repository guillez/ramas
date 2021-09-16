<?php
use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
class recurso_tipo_elementos implements modelable {
	
	public static function _get_modelos() {
		$tipo_elemento = [ 
				'tipo_elemento' => [	'_mapeo' => 'tipo_elemento_externo',
												'type' => 'string',
												'_validar' => [	regla::OBLIGATORIO,
																		regla::TIPO_ALPHANUM,
																		regla::TIPO_LONGITUD => [	'min' => 1,	'max' => 100	]
																	] 
				],
				'descripcion' => [	'required' => true,
											'type' => 'string',
											'_validar' => [	regla::TIPO_TEXTO		] 
				],
				'unidad_gestion' => [	'type'=>'string', 'required' => true	] 
		];
		
		$tipoElementoAct = $tipo_elemento;
		unset ( $tipoElementoAct ['unidad_gestion'] );
		
		$new_tipo_elemento = [	'tipo_elemento' => [	'type' => 'string',	'_validar' => [	regla::TIPO_ALPHANUM	]	]	];
		
		return array (
				'TipoElemento' => $tipo_elemento,
				'TipoElementoAct' => $tipoElementoAct,
				'NuevoTipoElemento' => $new_tipo_elemento 
		);
	}
	
	/**
	 *
	 * @var rest_tipo_elementos
	 */
	protected $modelo;
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_tipo_elementos', true );
	}
	
	
	/**
	 * GET /tipo_elementos
	 *
	 * @notes Retorna un listado de los tipos de elementos para una Unidad de Gestión dada
	 *
	 * @param_query $unidad_gestion string [required] Unidad de Gestión
	 *
	 * @responses 200 array {"$ref": "TipoElemento"}
	 * @responses 404 La Unidad de Gestión o el tipo de elemento no existe
	 */
	function get_list() {
		$datos = $this->modelo->get_list ();
		rest::response ()->get_list ( $datos );
	}
	
	/**
	 * GET /tipo_elementos/id
	 *
	 * @notes Retorna el tipo de elemento cuyo id es el enviado como parámetro
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @responses 200 {"$ref": "TipoElemento"}
	 * @responses 404 La Unidad de Gestión o el tipo de elemento no existe
	 */
	function get($id_tipo_elemento) {
		$datos = $this->modelo->get ( $id_tipo_elemento );
		rest::response ()->get ( $datos );
	}
	
	
	
	/**
	 * PUT /tipo_elementos/id_tipo_elemento
	 *
	 * @notes Crea o modifica los datos de un tipo de elemento. El id lo envía el cliente.
	 * Si es modificación y se envia el id en la url y en el body, se modifica el id
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	{"$ref": "
	 * @param_body $tipo_elemento TipoElementoAct [required] Campos del elemento para crear o modificar
	 *        	
	 *        	
	 * @responses 201 {"$ref": "NuevoTipoElemento" } 
	 * @responses 204 Exito - El tipo de elemento se modificó correctamente
	 * @responses 400 Errores de validación
	 * @responses 404 La Unidad de Gestión no existe
	 *        	
	 */
	function put($id_tipo_elemento) {
		$res = $this->modelo->put ( $id_tipo_elemento, rest::request ()->get_body_json () );
		
		if ($res == 0) { // Creación
			rest::response ()->post ( array (
					'tipo_elemento' => $id_tipo_elemento 
			) );
		} else { // Modificación
			rest::response ()->set_data ( '' );
			rest::response ()->set_status ( 204 );
		}
	}
	
	
	/**
	 * PUT /tipo_elementos
	 *
	 * @notes Crea o modifica Tipos de Elemento de forma masiva. Toma como parámetros del body un arreglo de tipos de elementos. El id
	 * del mismo se toma del campo tipo_elemento.
	 * <br/> No es transaccional, los tipos de elementos que se crean exitosamente no se revierten si hay errores en otros.
	 *
	 * @param_query $unidad_gestion	string [required] ID Unidad de Gestión
	 *        	
	 * @param_body  array [required] Arreglo de Tipos de Elementos
	 *        	
	 *        	
	 * @responses 204 Exito - Todos los tipos de elementos fueron creados/actualizados
	 * @responses 400 Exito - Un arreglo con los errores de los tipos de elementos con errores.
	 * @responses 404 La Unidad de Gestión no existe
	 *        	
	 *        	
	 */
	function put_list__masivo() {
		$res = $this->modelo->put_masivo ( rest::request ()->get_body_json () );
		if (empty ( $res )) {
			rest::response ()->put ();
		} else {
			rest::response ()->error_negocio ( array (
					'errores' => $res 
			) );
		}
	}
	
	
	/**
	 * DELETE /tipo_elementos/id
	 *
	 * Eliminar el tipo de elemento
	 *
	 * @param_query $unidad_gestion string [required] Unidad de Gestión
	 *        	
	 * @summary Elimina el tipo de elemento si existe
	 *        	
	 * @responses 204 El tipo de elemento se eliminó correctamente
	 * @responses 404 La Unidad de Gestión o el tipo de elemento no existe
	 * @responses 500 No se pudo eliminar el tipo elemento (razón: integridad)
	 *        	
	 */
	function delete($id_tipo_elemento) {
		$this->modelo->delete ( $id_tipo_elemento );
		rest::response ()->delete ();
	}
	
	
}