<?php

// use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
use SIUToba\rest\lib\rest_validador as regla;

class recurso_encuestas implements modelable {
//class recurso_encuestas extends rest_base {
	
	protected $modelo;

	public static function _get_modelos() {
		$encuesta = [
				'encuesta' => [ 'required' => true, 	'type' => 'integer', 'format' => 'int32',	'_validar' => [	regla::OBLIGATORIO, 	regla::TIPO_INT ]	],
				'nombre' 	=> [ 'required' => true, 	'type' => 'string', 		'_validar' => [	regla::OBLIGATORIO,  regla::TIPO_TEXTO ],		'_mapeo' => 'encuesta_nombre', 	 ],
				'estado' 	=> [ 'required' => true, 	'type' => 'string', 	'_validar' => [ regla::OBLIGATORIO, 	regla::TIPO_TEXTO, regla::TIPO_LONGITUD => [	'min' => 1, 'max' => 1]  ] ],
				'descripcion' => [ '_mapeo' => 'encuesta_descripcion', 'type' => 'string',  '_validar' => [ 	regla::TIPO_TEXTO ] ],
				'texto_preliminar' =>  [ 'type' => 'string', '_validar' => [ 	regla::TIPO_TEXTO ] ],
				'implementada' => [ 'type' => 'string', 'required' => true,  	'_validar' => [ regla::OBLIGATORIO,  regla::TIPO_TEXTO,  regla::TIPO_LONGITUD => [ 	'min' => 1,  'max' => 1 ] ] ],
				'unidad_gestion' => [ 'type' => 'string', '_validar' => [	regla::TIPO_TEXTO ] ],
				'detalle' => [  'type' => 'array' , 'items' => [ "\$ref" => "BloqueSD"  ] ]
		];
		
		$encuesta_sin_detalle = array_slice($encuesta, 0, 5); 
		
		$encuesta_completa = $encuesta;
		$encuesta_completa['detalle'] =  [  'type' => 'array' , 'items' => [ "\$ref" => "Bloque"  ] ];
		
		$bloque = array (
				'bloque' 	=> [ 'required' => true, 	'type' => 'integer', 'format' => 'int32',  '_validar' => [ regla::OBLIGATORIO,  regla::TIPO_INT ] ],
				'nombre' 	=> [ 'required' => true, 'type' => 'string',  	'_validar' => [ regla::OBLIGATORIO, regla::TIPO_TEXTO, regla::TIPO_LONGITUD => [ 'min' => 1,  'max' => 255] ], '_mapeo' => 'bloque_nombre', ],
				'descripcion' => ['type' => 'string',  '_mapeo' => 'bloque_descripcion',  '_validar' => [ regla::TIPO_TEXTO,  	regla::TIPO_LONGITUD => [ 'max' => 255 ] ] ],
				'orden' =>  [ 'required' => true,   'type' => 'integer', 'format' => 'int32', 	'_validar' => [ regla::OBLIGATORIO, regla::TIPO_INT ] , '_mapeo'	 => 'bloque_orden' ],
				'detalle' =>  [ 'type' => 'array',  'items' => [ "\$ref" => "Pregunta"  ]  ] 
		);
		
		$bloque_sin_detalle = array_slice($bloque, 0, 4); 
		
		$bloque_pregunta = $bloque;
		$bloque_pregunta['detalle'] =  [ 'type' => 'array',  'items' => [ "\$ref" => "PreguntaSD"  ]  ] ;
		
		$pregunta = [
				'pregunta' => [ 'required' => true, 	'type' => 'integer', 'format' => 'int32',  	'_validar' => [ regla::OBLIGATORIO,  regla::TIPO_INT ] ],
				'nombre' 	=> [ '_mapeo' => 'pregunta_nombre', 'type' => 'string', 'required' => true, '_validar' => [ regla::OBLIGATORIO, 	regla::TIPO_TEXTO, regla::TIPO_LONGITUD => [ 'min' => 1, 	'max' => 512 ] ] ],
                'componente' => [ 'required' => true, 'type' => 'string', '_validar' => [ regla::OBLIGATORIO, 	regla::TIPO_TEXTO ] ],
				'descripcion_resumida' => [ 	'required' => true, 'type' => 'string', '_validar' => [regla::OBLIGATORIO,  regla::TIPO_TEXTO,	regla::TIPO_LONGITUD => [ 'min' => 1, 'max' => 30 ] ] ],
				'es_libre ' => [ 'type' => 'string'],
				'es_multiple'  => [ 'type' => 'string'],
				'obligatoria' => [ 'type' => 'string'],
				'pregunta_orden_bloque' => [ '_mapeo' => 'pregunta_orden', 	'type' => 'integer', 'format' => 'int32', '_validar' => [ regla::TIPO_INT ] ],
				'detalle' =>  [ 'type' => 'array',  'items' => [ "\$ref" => "Respuesta"  ]  ]
		];
		
		$pregunta_bloque = [ 
				'bloque_nombre' => ['type' => 'string',  '_validar' => [ 	regla::TIPO_TEXTO ] ],
				'pregunta_nombre' => ['type' => 'string',  '_validar' => [ 	regla::TIPO_TEXTO ] ],
				'componente' => [ 'type' => 'string', '_validar' => [ regla::TIPO_TEXTO ]  ],
				'obligatoria' => [  'type' => 'string', '_validar' => [ regla::TIPO_TEXTO ] ]
		];
		
		$encuesta_detalle = $encuesta_sin_detalle;
		$encuesta_detalle ["detalle"] =[ 'type' => 'array', 'items' => [ "\$ref" => "BloquePregunta" ] ];
		
		$respuesta = [ 
								"respuesta" =>[ 	'type' => 'integer', 'format' => 'int32' ],
								"respuesta_valor" =>  [ 'type' => 'string']
		];
		$encuesta_preguntas =  array_slice($encuesta, 0, 5);
		$encuesta_preguntas ["preguntas"] =[ 'type' => 'array', 'items' => [ "\$ref" => "PreguntasBloque" ] ];
		
		
		return array (	
				'Encuesta' => $encuesta,
				'Bloque' => $bloque,
				'Pregunta' => $pregunta,
				'EncuestaSD' => $encuesta_sin_detalle,
				'BloqueSD' => $bloque_sin_detalle,
				'PreguntaSD' => array_slice($pregunta, 0, 8),
				'EncuestaDetalle' => $encuesta_detalle,
				'PreguntasBloque' => $pregunta_bloque,
				'EncuestaPreguntas' => $encuesta_preguntas,
				'EncuestaCompleta' => $encuesta_completa,
				'Respuesta' => $respuesta,
				'BloquePregunta' => $bloque_pregunta
		);
	}
	
	function __construct() {
		$this->modelo = kolla::rest ( 'rest_encuestas', true );
	}
	
	/**
	 * GET /encuestas
	 *
	 * @notes Retorna un listado de encuestas disponibles para una Unidad de Gestión dada
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @response_type array
	 *        	
	 * @responses 200 array {"$ref": "EncuestaSD "}
	 * @responses 404 La Unidad de Gestión no existe
	 */
	function get_list() {
		$datos = $this->modelo->get_list ();
		rest::response ()->get ( $datos );
	}
	
	/**
	 * GET /encuestas/{$encuesta}
	 *
	 * @notes Retorna una encuesta con sus preguntas
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @response_type array
	 *        	
	 * @responses 200 {"$ref": "EncuestaPreguntas "}
	 * @responses 404 La Unidad de Gestión o la encuesta no existe
	 */
	function get($encuesta) {
		$datos = $this->modelo->get ( $encuesta );
		rest::response ()->get ( $datos );
	}

	/**
	 * GET /encuestas/id/bloques
	 *
	 * @notes Retorna el listado de bloques de una encuesta dada
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 *        	
	 * @responses 200 {"$ref": "Encuesta"}
	 * @responses 400 No se pudo validar la encuesta
	 * @responses 404 La Unidad de Gestion o la encuenta no existe
	 */
	function get_bloques_list($encuesta) {
		$data = $this->modelo->get_bloques_list ( $encuesta );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /encuestas/id/bloques/id/preguntas
	 *
	 * @notes Retorna el listado de preguntas incluidas en un bloque de una encuesta dada
	 *
	 * @param_query $unidad_gestion string [required] ID Unidad de Gestión
	 *        	
	 * @response_type Encuesta
	 *        	
	 * @responses 200 {"$ref": "EncuestaDetalle"}
	 * @responses 400 No se pudo validar la encuesta
	 * @responses 404 La Unidad de Gestion o la encuesta o el bloque no existe
	 */
	function get_bloques_preguntas_list($encuesta, $bloque) {
		$data = $this->modelo->get_bloques_preguntas_list ( $encuesta, $bloque );
		rest::response ()->get ( $data );
	}
	
	/**
	 * GET /encuestas/id/bloques/id/preguntas/id/respuestas
	 *
	 * @notes Retorna el listado de preguntas incluidas en un bloque con de una encuesta dada junto con las opciones de respuesta
	 *
	 * @param_query $unidad_gestion string [required] Unidad de Gestión
	 *        	
	 * @response_type Encuesta
	 *        	
	 * @responses 200 {"$ref": "EncuestaCompleta"}
	 * @responses 400 No se pudo validar la encuesta
	 * @responses 404 La Unidad de Gestion o la encuesta o el bloque o la pregunta no existe
	 */
	function get_bloques_preguntas_respuestas_list($encuesta, $bloque, $pregunta) {
		$data = $this->modelo->get_bloques_preguntas_respuestas_list ( $encuesta, $bloque, $pregunta );
		rest::response ()->get ( $data );
	}
}