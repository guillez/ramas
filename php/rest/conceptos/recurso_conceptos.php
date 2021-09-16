<?php
	
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
use SIUToba\rest\lib\rest_validador as regla;

class recurso_conceptos implements modelable {
	
	public static function _get_modelos() {
		$concepto = [
								'concepto'			=> [		'_mapeo' 	=> 	'concepto_externo',
																	'required'	=>	true,
																	'type'		=>	'string',
																	'_validar' 	=> 	[	regla::OBLIGATORIO,
																								regla::TIPO_LONGITUD => [		'min'		=>	1,		'max'	=> 100	]
																							]
															],
								'descripcion'		=> [		'required'	=>	true,
																	'type'		=>	'string',
																	'_validar' 	=> 	[	regla::TIPO_TEXTO,
																								regla::OBLIGATORIO
																							] 
															],
								'unidad_gestion' => [
																		'required'	=>	true,
																		'type'		=>	'string'
																]
							];
		$conceptoAct = $concepto;
		unset ( $conceptoAct ['unidad_gestion'] );
		
		$new_concepto =  [ 	'concepto' => [	'required'	=>	true,	'type'	=>	'string' ]	];
		
		return [
						'Concepto' => $concepto,
						'ConceptoAct' => $conceptoAct,
						'NuevoConcepto' => $new_concepto 
					];
	}
	
	/**
	 * @var rest_conceptos
	 */
	protected $modelo;
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_conceptos', true );
	}
	
	/**
	 * GET /conceptos
	 *
	 * @notes Retorna un listado de conceptos para una Unidad de Gesti�n dada
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *
	 * @response_type Concepto
	 *
	 * @responses 200 array {"$ref": "Concepto"}
	 * @responses 404 La Unidad de Gestion no existe
	 */
	function get_list() {
		$datos = $this->modelo->get_list ();
		rest::response ()->get_list ( $datos );
	}
	
	
	/**
	 * GET /conceptos/id
	 *
	 * @notes Retorna el concepto cuyo id es el enviado como par�metro
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @response_type Concepto
	 *        	
	 * @responses 200 {"$ref": "Concepto"}
	 * @responses 404 La Unidad de Gesti�n o el concepto no existe.
	 */
	function get($id_concepto) {
		$datos = $this->modelo->get ( $id_concepto );
		rest::response ()->get ( $datos );
	}
	
	
	/**
	 * PUT /conceptos/id
	 *
	 * @notes Crea o modifica la descripci�n de un concepto. El id lo env�a el cliente.
	 * Si es modificaci�n y se env�a el id en la url y en el body, se modifica el id
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @param_body $concepto ConceptoAct [required] Campos del concepto para crear o modificar
	 *        	
	 *        	
	 * @responses 201 {"$ref": "NuevoConcepto"}
	 * @responses 204 El concepto se modific� correctamente
	 * @responses 400 Errores de validaci�n
	 * @responses 404 La Unidad de Gesti�n no existe
	 *        	
	 */
	function put($id_concepto) {
		$res = $this->modelo->put ( $id_concepto, rest::request ()->get_body_json () );
		
		if ($res == 0) { // Creaci�n
			rest::response ()->post ( array (
					'concepto' => $id_concepto 
			) );
		} else { // Modificaci�n
			rest::response ()->set_data ( '' );
			rest::response ()->set_status ( 204 );
		}
	}
	
	/**
	 * PUT /conceptos
	 *
	 * @notes Crea o modifica conceptos de forma masiva. Toma como par�metros del body un arreglo de conceptos. El id
	 * del mismo se toma del campo concepto.
	 * <br/> No es transaccional, los conceptos que se crean exitosamente no se revierten si hay errores en otros.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @param_body $conceptos array [required] Arreglo de Conceptos
	 *        	
	 *        	
	 * @responses 204  Todos los conceptos fueron creados/actualizados
	 * @responses 400 Un arreglo con los errores de los conceptos con errores
	 * @responses 404 La Unidad de Gesti�n no existe
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
	 * DELETE /conceptos/id
	 *
	 * @notes Elimina un concepto en caso de existir y que no este referenciado por otros modelos.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @responses 204 El concepto se elimin� correctamente
	 * @responses 404 La Unidad de Gesti�n o el concepto no existe.
	 * @responses 500 Error al intentar eliminar el concepto
	 *        	
	 */
	function delete($id_concepto) {
		$this->modelo->delete ( $id_concepto );
		rest::response ()->delete ();
	}
	
	
}
