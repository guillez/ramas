<?php 
//Inicializo el arreglo para darle el orden que figura en el wsdl.
class ws_habilitar_mjes {
	
	public $info = array(
			'operacion' => '',
			'encuesta' => '',
			'estilo' => '',
			'paginado' => '',
			'anonima' => '',
			//'multiple' => '',
			'debug' => '',
			'formularios' => '',
			'elementos' => ''
	);
	
	private $mjes_log = array(
			'operacion' => array(
					operacion_log::codigo_creacion => 'No existe el parámetro "habilitacion", se crea una nueva. ID = ',
					operacion_log::codigo_modificar_limitado => 'La encuesta solo se modifica en forma limitada que que posee respuestas :- ',
					operacion_log::codigo_modificar_libre => 'La encuesta no posee respuestas. Modificación libre.'
			),
			'estilo' => array(
					estilo_log::codigo_defecto => 'Se establece estilo por defecto',
					estilo_log::codigo_seleccionado => 'Se selecciona el estilo:- ',
			),
			'anonima' => array(
					anonima_log::codigo_defecto => 'Se establece anonima por defecto (N)',
					anonima_log::codigo_deshabilitado => 'Se establece anonima = N',
					anonima_log::codigo_habilitado => 'Se establece anonima = S'
			),
			'paginado' => array(
					paginado_log::codigo_defecto => 'Se establece paginado por defecto (N)',
					paginado_log::codigo_deshabilitado => 'Se establece paginado = N',
					paginado_log::codigo_habilitado => 'Se establece paginado = S'
			),
		/*	'multiple' => array(
					multiple_log::codigo_defecto => 'No se encontró alcance. Se establece multiple = N',
					multiple_log::codigo_deshabilitado => 'No se encontraron items. Se establece multiple = N',
					multiple_log::codigo_habilitado => 'Se encontraron items. Se establece multiple = S'
			),*/
			'formularios' => array(
					formulario_log::codigo_creacion => 'Se creo un nuevo formulario -',
					formulario_log::codigo_actualizacion => 'El formulario ya existe, se actualiza - ', //no se usa por ahora
					formulario_log::codigo_eliminacion => 'El formulario fue dado de baja en la habilitación -'
			),
			'elementos' => array(
					items_log::codigo_creacion => 'Elemento inexistente, se crea uno nuevo -  Id: ',
					items_log::codigo_actualizacion => 'El elemento ya existe, se actualiza - Id: ',
			),
			'encuesta' => array(
					encuesta_log::codigo_defecto_bug => 'Bug. La encuesta no tiene valor por defecto-',
					encuesta_log::codigo_seleccion => 'Se selecciona la encuesta: - '
			),
			'debug' => array(
					debug_log::codigo_defecto => 'Debug deshabilitado por defecto. ',
					debug_log::codigo_habilitado => 'Debug habilitado. No se guardara en la base de datos - Se aborta la transaccion. ',
					debug_log::codigo_deshabilitado => 'Debug deshabilitado',
			),
	);
	
	private  $errores = array(
			'indefinido' => 'Este error es un bug.',
			'error_autenticacion' => 'Error de autenticación sistema.',
			'estilo_inexsistente' => 'El estilo no existe. Debe ser numérico. Puede ser nulo y se proporciona el valor por defecto.',
			'error_alcance' => 'Hubo un error en la creación del alcance.',
			'error_form' => 'Hubo un error en la creación de los formularios.',
			'error_item' => 'Hubo un error en la creación del item : - ',
			'error_formato_id_encuesta' => 'El id de encuesta no es numérico.',
			'encuesta_inexistente' => 'La encuesta no existe.',
			'encuesta_no_activa' => 'La encuesta no se encuentra Activa.',
			'encuesta_no_implementada' => 'La encuesta no se encuentra Implementada.',
			'encuesta_no_ug' => 'La unidad de gestión no tiene acceso a esta encuesta.',
			'fecha_nula' => 'Las fechas no pueden ser nulas.',
			'error_formato_fecha' => 'Las fechas deben tener formato YYYY-mm-dd.',
			'error_orden_fechas' => 'La Fecha Hasta es anterior a la Fecha Desde.',
			'error_modificacion_fechas' => 'El cambio de fechas solicitadas no cumple con las restricciones necesarias.',
			'habilitacion_superpuesta' => 'La encuesta que quiere habilitar ya se encuentra habilitada para ese rango de fechas.',
			'error_grupos' => 'Se produjo un error al asociar los grupos de usuarios con la habilitación',
			'error_password' => 'No se pudo generar el password para la habilitación.',
			'error_formato_id_habilitacion' => 'El id de la habilitación no es numérico.',
			'error_modif_habilitacion_no_ini' => 'Error al modificar habilitación (no iniciada). Corroborar que la habilitación existe.',
			'error_modif_habilitacion_ini' => 'Error al modificar habilitación (ya iniciada).',
			'error_indefinido' => ' ',
			'error_mod_form' => 'Error al modificar un formulario.',
			'error_form_no_modificable' => 'El formulario especificado no se puede modificar.',
			'ug_inexsistente' => 'La unidad de gestión no existe.',
	);
	
	/**
	 *	Se encarga de manejar los errores de acuerdo al arreglo errores, y mantiene
	 *  consistencia de formato para enviarlos al cliente.
	 * @param type $codigo el codigo de error segun el arreglo errores
	 * @param string $params mensaje adicional a enviar en el error
	 * @throws toba_error_servicio_web envia el error en forma de excepcion al cliente
	 */
	public function throw_error($codigo, $params ='')
	{
		toba::logger()->debug('ERROR:- ' . $codigo . ": " . $this->errores[$codigo] . $params);
		throw new toba_error_servicio_web($this->errores[$codigo] . " - " . $params, $codigo);
	}
	
	/**
	 * Se encarga de manejar el arreglo info que se envia como respuesta
	 * al cliente. Usar esta funcion para enviar inforamcion al cliente.
	 * @param type $key la categoria del mensaje
	 * @param type $code el codigo dentro de la categoria
	 * @param type $str  cadena adicional a anexar al mensaje
	 */
	function log_info($key, $code = 0, $str = '', $id = '')
	{
		//('filas_form', $id_form, $encuesta, $item);
		if($key== 'filas_form'){
			$this->info['formularios'][$code]['forms'][] = "$str;$id";
			toba::logger()->debug($key . ': ' . $str .";". $id . " [$code]");
			return;
		}
		$mje = $this->mjes_log[$key][$code] . $str;
		if($key == 'formularios'){
			$this->info['formularios'][$id] = array(
					'codigo' => $code,
					'dsc' => $mje,
					'id' => $id
			);
		}elseif ($key == 'elementos') {
			$this->info[$key][] = array(
					'codigo' => $code,
					'dsc' => $mje,
					'id' => $id
			);
		} else {
			$this->info[$key] = array(
					'codigo' => $code,
					'dsc' => $mje,
					'id' => $id
			);
		}
		toba::logger()->debug($key . ': ' . $mje . " [$code]");
	}
}
class operacion_log
{
	const codigo_creacion = 'creacion';
	const codigo_modificar_limitado = 'mod_limitada';
	const codigo_modificar_libre = 'mod_libre';
}

class encuesta_log
{
	const codigo_defecto_bug = 'bug';
	const codigo_seleccion = 'seleccion';
}

class estilo_log
{
	const codigo_defecto = 'defecto';
	const codigo_seleccionado = 'seleccion';
}

class paginado_log
{
	const codigo_defecto = 'defecto';
	const codigo_habilitado = 'habilitado';
	const codigo_deshabilitado = 'deshabilitado';
}

class anonima_log
{
	const codigo_defecto = 'defecto';
	const codigo_habilitado = 'habilitado';
	const codigo_deshabilitado = 'deshabilitado';
}

/*class multiple_log
{
	const codigo_defecto = 'defecto';
	const codigo_habilitado = 'habilitado';
	const codigo_deshabilitado = 'deshabilitado';
}*/

class debug_log
{
	const codigo_defecto = 'defecto';
	const codigo_habilitado = 'habilitado';
	const codigo_deshabilitado = 'deshabilitado';
}
class formulario_log
{
	const codigo_creacion = 'creacion';
	const codigo_actualizacion = 'actualizacion';
	const codigo_eliminacion = 'eliminacion';
}
class items_log
{
	const codigo_creacion = 'creacion';
	const codigo_actualizacion = 'actualizacion';
}

?>
