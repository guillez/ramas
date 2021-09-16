<?php

class usuarios_arai
{
    // Arai
    protected $arai_usuarios = array();
    protected $cant_error_arai = 0;
    protected $arai_usuario_y_cuenta = array();

    function __construct()
    {
    }

    public function get_usuarios() {
        return $this->arai_usuarios;
    }

    /**
     * Método para armar el listado de usuarios ARAI con sus atributos tipoDocumento y numeroDocumento.
     * @throws toba_error
     */
    public function armar_listado_usuarios_arai()
    {
        $this->arai_usuarios = manejador_rest_arai_usuarios::instancia()->get_usuarios();

        $i = 0;
        // En este caso eran necesarias dos llamadas de WS
        /*foreach ($this->arai_usuarios as $user) {
            $atributo = manejador_rest_arai_usuarios::instancia()->get_atributo($user['identificador'], "numeroDocumento");
            $this->arai_usuarios[$i]['numeroDocumento'] = $atributo[0];

            $atributo = manejador_rest_arai_usuarios::instancia()->get_atributo($user['identificador'], "tipoDocumento");
            $this->arai_usuarios[$i]['tipoDocumento'] = $atributo[0];

            $i++;
        }*/

        // Con este otro método resuelvo lo mismo en una única llamada de WS
        foreach ($this->arai_usuarios as $user) {
            $atributos = manejador_rest_arai_usuarios::instancia()->get_atributos($user['identificador']);
            $this->arai_usuarios[$i]['numeroDocumento'] = $atributos['numeroDocumento'];
            $this->arai_usuarios[$i]['tipoDocumento'] = $atributos['tipoDocumento'];

            $i++;
        }
    }

    /**
     * Método para buscar entre la lista de usuarios de ARAI, coincidencia con un tipo y nro. de documento de
     * una cuenta de Kolla. Se buscan TODAS las coincidencias, puede darse el caso de que haya más de una. Como
     * salida se devuelve un arreglo con los identificadores ARAI correspondientes a las coincidencias encontradas.
     * @param $tipo_documento
     * @param $nro_documento
     * @return array|null Si el arreglo es vacío no se encontró coincidencia.
     *                    Si el arreglo tiene un único valor, es el identificador de la persona econtrada.
     *                    Si el arreglo tiene más de un valor, existe más de un usuario ARAI con el tipo y nro. doc.
     */
    public function existe_persona_en_arai_usuarios($tipo_documento, $nro_documento)
    {
        $cantidad = count($this->arai_usuarios);
        $identificador = array();

        for ($i = 0; $i < $cantidad; $i++ ) {
            //todo en esta comparacion asumo que arai guarda el tipoDocumento de la misma manera que kolla
            if ( ($this->arai_usuarios[$i]['tipoDocumento'] == $tipo_documento)
                &&
                ($this->arai_usuarios[$i]['numeroDocumento'] == $nro_documento) ) {
                $identificador[] = $this->arai_usuarios[$i]['identificador'];
            }
        }

        return $identificador;
    }

}