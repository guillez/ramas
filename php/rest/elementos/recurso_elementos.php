<?php
use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
class recurso_elementos implements modelable {

	public static function _get_modelos() {
		$elemento = [
				'elemento'=> [ 
					'_mapeo'	=> 'elemento_externo',
					'required'	=> true,
					'type'		=> 'string',
					'_validar'	=> [
								regla::OBLIGATORIO,
								regla::TIPO_LONGITUD =>	['min' => 1, 'max' => 100] 
							] 
					],
				'descripcion'	=> [
					'required'	=> true,
					'type'		=> 'string',
					'_validar'	=> [
								regla::OBLIGATORIO
							]
					],
				'url_img' => [
					'required'	=> true,
					'type'		=> 'string',
					'_validar' 	=> [
								regla::TIPO_LONGITUD	=> ['max' => 127]
							]
					],
				'unidad_gestion' => [
					'required' 	=> true,
					'type'		=> 'string'
					] 
			];

		$new_elemento = [ 
				'elemento' =>  ['type'	=> 'string']
				];
		
		$elementoAct = $elemento;
		unset ( $elementoAct ['unidad_gestion'] );
		
		return [
			'Elemento' => $elemento,
			'ElementoAct' => $elementoAct,
			'NuevoElemento' => $new_elemento 
		];
	}
	
	/**
	 *
	 * @var rest_elementos
	 */
	protected $modelo;
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_elementos', true );
	}
	
	/**
	 * GET /elementos
	 *
	 * @notes Retorna un listado de elementos para una Unidad de Gestión dada
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *
	 * @responses 200 array {"$ref": "Elemento"}
	 * @responses 404 La Unidad de Gestión no existe
	 */
	function get_list() {
		$datos = $this->modelo->get_list ();
		rest::response ()->get_list ( $datos );
	}
	
	/**
	 * GET /elementos/id
	 *
	 * @notes Retorna el elemento cuyo id es el enviado por parámetro
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	   	
	 * @responses 200 {"$ref": "Elemento"}
	 * @responses 404 La Unidad de Gestión o el elemento no existe
	 */
	function get($id_elemento) {
		$datos = $this->modelo->get ( $id_elemento );
		rest::response ()->get ( $datos );
	}
	
	/**
	 * PUT /elementos/id_elemento
	 *
	 * @notes Crea o modifica los datos de un elemento. El id lo envía el cliente;
	 * Si es modificación y se envía el id en la url y en el body, se modifica el id
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @param_body $elemento ElementoAct [required] Campos del elemento para crear o modificar
	 *        	
	 * @responses 201 {"$ref": "NuevoElemento"}
	 * @responses 204 El elemento se modificó correctamente
	 * @responses 400 Errores de validación
	 * @responses 404 La Unidad de Gestión no existe
	 *        	
	 */
	function put($id_elemento) {
		$res = $this->modelo->put ( $id_elemento, rest::request ()->get_body_json () );
		
		if ($res == 0) { // Creación
			rest::response ()->post ( array (
					'elemento' => $id_elemento 
			) );
		} else { // Modificación
			rest::response ()->set_data ( '' );
			rest::response ()->set_status ( 204 );
		}
	}
	
	/**
	 * PUT /elementos
	 *
	 * @notes Crea o modifica elementos de forma masiva. Toma como parámetros del body un arreglo de elementos. El id
	 * del mismo se toma del campo elemento.
	 * <br/> No es transaccional, los elementos que se crean exitosamente no se revierten si hay errores en otros.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @param_body $elementos array [required] Arreglo de Elementos
	 *        	        	
	 * @responses 204 Todos los elementos fueron creados/actualizados
	 * @responses 400 Un arreglo con los errores de los elementos que no se guardaron.
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
	 * DELETE /elementos/id_elemento
	 *
	 * @notes Elimina el elemento cuyo id es el enviado por parámetro
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	        	
	 * @responses 204 El elemento se eliminó correctamente
	 * @responses 404 La Unidad de Gestión o el elemento no existe
	 * @responses 500 Error al intentar eliminar el elemento
	 *        	
	 */
	function delete($id_elemento) {
		$this->modelo->delete ( $id_elemento );
		rest::response ()->delete ();
	}
	
}
