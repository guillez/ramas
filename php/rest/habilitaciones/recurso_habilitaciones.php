<?php
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
use SIUToba\rest\lib\rest_validador as regla;

class recurso_habilitaciones implements modelable {
	
	public static function _get_modelos() {
		$habilitacion = array (
				'habilitacion' => array (),
				'fecha_desde' => array (
						'required' => true,
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_DATE => array (
										'format' => 'Y-m-d' 
								) 
						) 
				),
				'fecha_hasta' => array (
						'required' => true,
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_DATE => array (
										'format' => 'Y-m-d' 
								) 
						) 
				),
				'paginado' => array (
						'_validar' => array (
								regla::TIPO_ENUM => array (
										'S',
										'N' 
								) 
						) 
				),
				'anonima' => array (
						'_validar' => array (
								regla::TIPO_ENUM => array (
										'S',
										'N' 
								) 
						) 
				),
				'estilo' => array (
						'_validar' => array (
								regla::TIPO_INT 
						) 
				),
				'password' => array (
						'_mapeo' => 'password_se' 
				),
				'descripcion' => array (
						'required' => true,
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_TEXTO 
						) 
				),
				'texto_preliminar' => array (
						'_validar' => array (
								regla::TIPO_TEXTO 
						) 
				),
				'generar_codigo_recuperacion' => array (
						'_mapeo' => 'generar_cod_recuperacion',
						'_validar' => array (
								regla::TIPO_ENUM => array (
										'S',
										'N' 
								) 
						) 
				),
				'url_imagenes_base' => array (
						'_mapeo' => 'url_imagenes_base' 
				),
				'unidad_gestion' => array (),
				'descarga_pdf' => array (
						'_validar' => array (
								regla::TIPO_ENUM => array (
										'S',
										'N' 
								) 
						) 
				)
		);
		$formulario = array (
				'formulario' => array (
						'_mapeo' => 'formulario_habilitado_externo',
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_TEXTO 
						) 
				),
				'nombre' => array (
						'required' => false,
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_TEXTO 
						) 
				),
				'concepto' => array (
						'_mapeo' => 'concepto_externo',
						'_validar' => array (
								regla::TIPO_ALPHANUM,
								regla::TIPO_LONGITUD => array (
										'min' => 1,
										'max' => 100 
								) 
						) 
				),
				'estado' => array (
						'_validar' => array (
								regla::OBLIGATORIO,
								regla::TIPO_ENUM => array (
										'A',
										'B' 
								) 
						) 
				),
				'detalle' => array (
						'required' => true,
						'type' => 'array',
						'_validar' => array (
								regla::OBLIGATORIO 
						) 
				) 
		);
		$nueva_habilitacion = array(
				'habilitacion' => array( 
						'_validar' => array(
								regla::TIPO_ALPHANUM 
						)
				)
		);
		return array (
				'Habilitacion' => $habilitacion,
				'Formulario' => $formulario,
				'NuevaHabilitacion' => $nueva_habilitacion 
		)
		;
	}
	
	/**
	 *
	 * @var rest_habilitaciones
	 */
	protected $modelo;
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_habilitaciones', true );
	}
	
	/**
	 * GET /habilitaciones/id
	 *
	 * 	Obtiene los datos de una habilitaci�n
	 *
	 *	@param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 *	@response_type Habilitacion
	 *	@summary Obtiene los datos de una habilitaci�n
	 *        	
	 * 	@responses 200 $Habilitacion
	 * 	@responses 404 La Unidad de Gesti�n o la habilitaci�n no existe
	 */
	function get($id_habilitacion) {
		$data = $this->modelo->get ( $id_habilitacion );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /habilitaciones
	 *
	 * Obtiene una lista de habilitaciones
	 *
	 * @param_query $unidad_gestion string [required] Unidad de Gesti�n
	 *        	
	 * @response_type Habilitacion
	 * @summary Obtiene una lista de habilitaciones
	 *        	
	 * @responses 200 array $Habilitacion
	 * @responses 404 La Unidad de Gesti�n o la habilitaci�n no existe
	 */
	function get_list() {
		$datos = $this->modelo->get_list ();
		rest::response ()->get_list ( $datos );
	}
	
	/**
	 * PUT /habilitaciones/id
	 *
	 * @notes Modifica una habilitaci�n.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @param_body $habilitacion Habilitacion [required] Campos de la habilitaci�n
	 *        	
	 * @summary Modifica los datos de una habilitaci�n
	 *        	
	 * @responses 201 Exito- La habilitaci�n se modific� con �xito
	 * @responses 400 Errores de validaci�n
	 * @responses 404 La Unidad de Gesti�n no existe o la habilitaci�n no existe
	 *        	
	 */
	function put($id_habilitacion) {
		$res = $this->modelo->put ( $id_habilitacion, rest::request ()->get_body_json () );
		rest::response ()->put ();
	}
	
	/**
	 * POST /habilitaciones
	 *
	 * Crea una habilitaci�n
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 * @param_body $habilitacion Habilitacion [required] Campos de una habilitaci�n.
	 *        	
	 * @summary Crea una habilitaci�n con datos b�sicos
	 * @response_type Habilitacion
	 *        	
	 * @responses 201 $NuevaHabilitacion
	 * @responses 400 Errores de validaci�n
	 * @responses 404 La Unidad de Gestion no existe
	 *        	
	 */
	function post_list() {
		$res = $this->modelo->post ( rest::request ()->get_body_json () );
		rest::response ()->post ( array (
				'habilitacion' => $res 
		) );
	}
	
	/**
	 * PUT /habilitaciones/id/formularios/id
	 *
	 * @notes Crea un formulario para una habilitaci�n. El Formulario debe tener el atributo concepto
	 *
	 * @summary Crea un formulario para una habilitaci�n y concepto dado
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de gesti�n
	 * @param_body $formulario Formulario [required] Formulario con el detalle de encuestas y elementos
	 *        	
	 * @responses 204 El formulario se cre� correctamente
	 * @responses 400 Errores de validaci�n
	 * @responses 404 La habilitaci�n o la Unidad de Gesti�n no existe
	 */
	function put_formularios($id_habilitacion, $id_formulario) {
		$this->modelo->put_formulario ( $id_habilitacion, $id_formulario, rest::request ()->get_body_json () );
		rest::response ()->put ();
	}
	
	/**
	 * GET /habilitaciones/id/formularios
	 *
	 * Obtiene una lista de los formularios en una habilitaci�n
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @response_type Formulario
	 * @summary Obtiene una lista de los formularios en una habilitaci�n
	 *        	
	 * @responses 200 array $Formulario
	 * @responses 404 No se encontr� la habilitaci�n o la Unidad de Gesti�n no existe
	 */
	function get_formularios_list($id_habilitacion) {
		$data = $this->modelo->get_formularios_list ( $id_habilitacion );
		rest::response ()->get_list ( $data );
	}
	
	/**
	 * GET /habilitaciones/id/formularios/id
	 *
	 * Obtiene un formulario en una habilitaci�n
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @response_type Formulario
	 * @summary Obtiene un formulario en una habilitaci�n
	 *        	
	 * @responses 204 $Formulario
	 * @responses 404 No se encontr� la habilitaci�n o la Unidad de Gesti�n no existe
	 */
	function get_formularios($id_habilitacion, $id_formulario) {
		$data = $this->modelo->get_formularios ( $id_habilitacion, $id_formulario );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /habilitaciones/id/formularios/id/encuestas/id/elementos/id/respuestas
	 *
	 * Obtiene el contenido de la encuesta indicando para cada pregunta el conjunto de respuestas obtenidas y la cantidad de veces que se eligi� cada una.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 * @param_query $limite  integer  Limite para respuestas
	 *       	
	 * @response_type Respuestas
	 * @summary Obtiene el contenido de la encuesta indicando para cada pregunta el conjunto de respuestas obtenidas
	 *        	y la cantidad de veces que se eligi� cada una.
	 *        	
	 * @responses 204 Exito
	 * @responses 404 Alguno de los par�metros no existe
	 * @responses 400 No se pudieron validar los datos ingresados
	 */
	function get_formularios_encuestas_elementos_respuestas_list($id_habilitacion, $id_formulario, $id_encuesta, $id_elemento) {
		$data = $this->modelo->get_formulario_encuesta_elemento_respuestas_detalle ( $id_habilitacion, $id_formulario, $id_encuesta, $id_elemento );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /habilitaciones/id/formularios/id/encuestas/id/elementos/id/bloque/id/pregunta/id/respuestas
	 *
	 * Obtiene el contenido de la encuesta indicando para una pregunta el conjunto de respuestas obtenidas y la cantidad de veces que se eligi� cada una.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 * @param_query $limite  integer  Limite para respuestas
	 *
	 * @response_type Respuestas
	 * @summary Obtiene el contenido de la encuesta indicando para una pregunta el conjunto de respuestas obtenidas
	 *        	y la cantidad de veces que se eligi� cada una.
	 *
	 * @responses 204 Exito
	 * @responses 404 Alguno de los par�metros no existe
	 * @responses 400 No se pudieron validar los datos ingresados
	 */
	function get_formularios_encuestas_elementos_bloque_pregunta_respuestas_list($id_habilitacion, $id_formulario, $id_encuesta, $id_elemento,$id_bloque,$id_pregunta) {
		
		$data = $this->modelo->get_formulario_encuesta_elemento_respuestas_detalle ( $id_habilitacion, $id_formulario, $id_encuesta, $id_elemento ,$id_bloque,$id_pregunta);
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /habilitaciones/id/formularios/id/encuestas/id/elementos/id/bloque/id/pregunta/id/orden/id/respuestas
	 *
	 * Obtiene el contenido de la encuesta indicando para una pregunta el conjunto de respuestas obtenidas y la cantidad de veces que se eligi� cada una.
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 * @param_query $limite  integer  Limite para respuestas
	 *
	 * @response_type Respuestas
	 * @summary Obtiene el contenido de la encuesta indicando para una pregunta el conjunto de respuestas obtenidas
	 *        	y la cantidad de veces que se eligi� cada una.
	 *
	 * @responses 204 Exito
	 * @responses 404 Alguno de los par�metros no existe
	 * @responses 400 No se pudieron validar los datos ingresados
	 */
	function get_formularios_encuestas_elementos_bloque_pregunta_orden_respuestas_list($id_habilitacion, $id_formulario, $id_encuesta, $id_elemento,$id_bloque,$id_pregunta,$orden) {
	
		$data = $this->modelo->get_formulario_encuesta_elemento_respuestas_detalle ( $id_habilitacion, $id_formulario, $id_encuesta, $id_elemento ,$id_bloque,$id_pregunta,$orden);
		rest::response ()->get ( $data );
	}
	
	/**
	 * @todo Se debe agregar el modelo para que aparezca por swagger
	 * GET /habilitaciones/id/formularios/id/encuestas/id/elementos/id/resumen
	 *
	 * Obtiene el contenido de la encuesta indicando para cada pregunta
	 * la cantidad de opciones de respuesta disponibles y la cantidad de esas opciones que fueron elegidas
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gesti�n
	 *        	
	 * @response_type Respuestas
	 * @summary Obtiene el contenido de la encuesta indicando para cada pregunta
	 *        	la cantidad de opciones de respuesta disponibles y la cantidad de esas opciones que fueron elegidas.
	 *        	
	 * @responses 204 Exito
	 * @responses 404 Alguno de los par�metros no existe
	 * @responses 400 No se pudieron validar los datos ingresados
	 */
	function get_formularios_encuestas_elementos_resumen_list($id_habilitacion, $id_formulario, $id_encuesta, $id_elemento) {
		$data = $this->modelo->get_formulario_encuesta_elemento_respuestas_resumen ( $id_habilitacion, $id_formulario, $id_encuesta, $id_elemento );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /habilitaciones/id/formularios/id/respuestas
	 *
	 * @notes Obtiene las respuestas de un formulario en una habilitaci�n
	 *
	 * 	@param_query $codigo_externo string C�digo Externo
	 *	@param_query $unidad_gestion string Unidad de Gesti�n
	 *        	
	 *	@response_type Formulario
	 *  @summary Obtiene las respuestas de un formulario en una habilitaci�n
	 *        	
	 *  @responses 204 Exito
	 *  @responses 404 No se encontr� la habilitaci�n
	 */

	 function get_formulario_respuestas_list($id_habilitacion, $id_formulario) {
	    $data = $this->modelo->get_formulario_respuestas($id_habilitacion, $id_formulario);
	    rest::response()->get($data);
	 }


	/**
	 * PUT /habilitaciones/id/formularios/masivo
	 *
	 * @notes Crea un conjunto de formularios para una habilitaci�n. Se procesan en orden e individualmente.
	 * En caso de errores se retorna un arreglo con la descripcion seg�n el indice *
	 *
	 * @summary Crear formularios masivamente
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de gesti�n
	 * @param_body $formulario array [required] Arreglo de Formulario con el detalle de encuestas y elementos
	 *        	
	 * @responses 204 Los formularios se crearon correctamente
	 * @responses 400 Errores de validaci�n. Al menos un formulario fallo, se adjunta detalle
	 * @responses 404 La habilitaci�n o la Unidad de Gesti�n no existe 
	 */
	function put_formularios_list__masivo($id_habilitacion) {
		$res = $this->modelo->put_formularios_masivo ( $id_habilitacion, rest::request ()->get_body_json () );
		if (empty ( $res )) {
			rest::response ()->put ();
		} else {
			rest::response ()->error_negocio ( array (
					'errores' => $res 
			) );
		}
	}
	
	/**
	 * DELETE /habilitacion/id/formularios/id
	 *
	 * Eliminar un formulario de una habilitaci�n
	 *
	 * @param_query $unidad_gestion string [required] Unidad de Gesti�n
	 *        	
	 * @summary Elimina el formulario si existe
	 *        	
	 * @responses 204 El Formulario se elimino correctamente
	 * @responses 400 La Unidad de Gesti�n/Habilitaci�n/Formulario no existe
	 *        	
	 */
	function delete_formulario($id_habilitacion, $id_formulario) {
		$this->modelo->delete_formulario ( $id_habilitacion, $id_formulario );
		rest::response ()->delete ();
	}
    
    /**
	 * GET /estilos
	 *
	 * Obtiene una lista de estilos
	 *
	 * @response_type Respuestas
	 * @summary Obtiene la lista de estilos disponibles para las habilitaciones
	 *        	
	 * @responses 200 Exito
     * @responses 400 No existen estilos disponibles
	 */
	function get_estilos_list() {
		$data = $this->modelo->get_estilos();
        rest::response()->get($data);
	}
}
