<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\http\vista_raw;
use SIUToba\rest\lib\rest_error_interno;

include_once('nucleo/formulario/formulario_controlador_config.php');
include_once('nucleo/formulario/vista/builder_pdf.php');

class recurso_respondidos implements modelable
{
	/**
	 *
	 * @var rest_formularios_habilitados_respondidos
	 */
	protected $modelo;
    
	function __construct()
    {
		$this->modelo = kolla::rest ('rest_formularios_habilitados_respondidos', true);
	}
	
	public static function _get_modelos()
    {
		$formulario_habilitado_respondido = [
				'formulario_habilitado' => [
						'type' => 'string',
						'_validar' =>[
								regla::OBLIGATORIO
						]
				],
				'respondido_formulario' => [
						'type' => 'string',
						'_validar' =>[
								regla::OBLIGATORIO
						]
				],
				
		];
	
		return ['FormularioHabilitadoRespondido' => $formulario_habilitado_respondido];
	}

    /**
	 * GET /formularios_habilitados/id/respondidos/id/pdf
	 *
	 * 	Obtiene el PDF correspondiente a un formulario habilitado y código externos
     *
     *  @param_query $habilitacion string [required] ID de la Habilitación
     *  @param_query $unidad_gestion               string [required] ID de la Unidad de Gestión
     * 
	 *	@summary Obtiene el PDF correspondiente a un formulario respondido
	 *        	
	 * 	@responses 200 OK
	 * 	@responses 404 El formulario habilitado o id de respondido indicados no existen
      * @responses 500 Hubo un error inesperado
	 */
    function get_pdf ($id_formulario_habilitado, $id_respondido)
    {
        try {
            $datos = $this->modelo->get_pdf($id_formulario_habilitado, $id_respondido);

            if ($datos !== false) {
                $tipo_salida = (string) rest::request()->get('tipo_salida', 'pdf');
                $vista = new vista_raw(rest::response());
                $vista->set_content_type('application/'.$tipo_salida);
                rest::app()->set_vista($vista);
                rest::response()->get_list($datos);
            } else {
                rest::response()->not_found('No se encontró el formulario respondido solicitado.');
            }
        } catch(rest_error_interno $e) {
			//recursos_tools::loggear_error($e, rest::app(), rest::request()->params());
			rest::response()->error_negocio(array($e->getMessage()));
		} catch (error $e) {
			//recursos_tools::loggear_error($e, rest::app(), rest::request()->params());
            rest::response()->error_negocio(array($e->getMessage()), 500);
		} catch (\Exception $e) {
			//recursos_tools::loggear_error($e, rest::app(), rest::request()->params());
            rest::response()->error_negocio(array($e->getMessage()), 500);
		}

	}
    
}