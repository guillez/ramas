<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;
use SIUToba\rest\lib\rest_validador;

class rest_preguntas extends rest_base
{
    protected $modelo;

    function __construct()
    {
        $this->modelo = kolla::co('co_preguntas');
    }

    protected function get_modelo($nombre)
    {
        $modelos = recurso_preguntas::_get_modelos();
        return $modelos[$nombre];
    }


    public function get_respuestas_list ($pregunta)
    {
        $ug = $this->_get_ug();
        $this->_get_sistema();

        //obtener datos

        $sql = "SELECT * 
                FROM respuestas_pregunta($pregunta)
                pregunta ( 
                        pregunta integer,                   	--1
                        pregunta_nombre character varying(4096),--2
                        componente_numero integer,          	--3
                        componente character varying,       	--4
                        opciones_multiples text,            	--5
                        respuesta_codigo integer,           	--6
                        valor_tabulado character varying,   	--7
                        respuesta_orden smallint,				--8
                        tabla_asociada character varying,       --9
                        tabla_asociada_codigo character varying, --10
                        tabla_asociada_descripcion character varying --11
                    );";
        $datos = kolla::db()->consultar($sql);

        return $datos;
    }

}
