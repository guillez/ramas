<?php

use SIUToba\rest\rest;

class rest_formularios_habilitados_respondidos extends rest_base
{
    /**
     * @var co_formularios_respondidos
     */
    protected $modelo;


    function __construct()
    {
        $this->modelo = kolla::co('co_formularios_respondidos');
    }

    public function get_pdf($form_hab_externo, $codigo_externo)
    {
        $habilitacion   = rest::request()->get('habilitacion');
        $unidad_gestion = $this->_get_ug();

        return $this->modelo->get_pdf($form_hab_externo, $codigo_externo, $habilitacion, $unidad_gestion);
    }

}