<?php
use SIUToba\rest\rest;
use SIUToba\rest\lib\modelable;
use SIUToba\rest\lib\rest_validador as regla;

class recurso_habilitaciones_completas implements modelable {

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
     * GET /habilitaciones-completas/id
     *
     * 	Obtiene los datos de una habilitación junto con sus formularios habilitados y las preguntas
     *  que componen las encuestas de los formularios.
     *
     *	@param_query $unidad_gestion string [required] ID Unidad de Gestión
     *
     *	@response_type habilitacion-completa
     *	@summary Obtiene los datos de una habilitación junto con sus formularios habilitados y las preguntas
     *  que componen las encuestas de los formularios.
     *
     * 	@responses 200 $Habilitacion
     * 	@responses 404 La Unidad de Gestión o la habilitación no existe
     */
    function get($id_habilitacion) {
        //$data = $this->modelo->get ( $id_habilitacion );
        $data = $this->modelo->get_habilitaciones_completas ( $id_habilitacion );
        rest::response ()->get ( $data );
    }
}
