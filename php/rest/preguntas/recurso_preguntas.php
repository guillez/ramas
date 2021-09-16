
<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_validador as regla;
use SIUToba\rest\lib\modelable;

class recurso_preguntas implements modelable
{

    protected $modelo;

    public static function _get_modelos()
    {
    }

    function __construct()
    {
        $this->modelo = kolla::rest('rest_preguntas', true);
    }


    /**
     * GET /preguntas/{id}/respuestas
     *
     * @notes Retorna las opciones de respuesta de una pregunta en una encuesta
     *
     * @param_query $unidad_gestion string [required] ID Unidad de Gestión
     *
     * @response_type array
     *
     * @responses 404 La Unidad de Gestión o la pregunta no existe
     */
    function get_respuestas_list($id)
    {
        $datos = $this->modelo->get_respuestas_list($id);
        rest::response()->get($datos);
    }

}

