<?php

class importador_usuarios_archivo 
{
    // Origen de datos
    protected $origen;
    protected $archivo;
    protected $separador;
    protected $personas;
    protected $seleccion;
    // Datos del grupo
    protected $grupo;
    protected $grupo_descripcion;
    protected $grupo_nombre;
    protected $unidad_gestion;
    protected $grupo_detalle = array();
    // Qué se actualiza
    protected $actualiza_datos_personales;
    // Cache para usuarios
    protected $cache_usuarios = array();
    protected $encuestados = array();
    // Contadores
    protected $cant_nuevos           = 0;
    protected $cant_actualizados     = 0;
    protected $cant_error_datos      = 0;
    protected $cant_error_registro   = 0;
    protected $cant_agregados_grupo  = 0;

    // Arai
    protected $arai_usuarios = array();
    protected $cant_error_arai = 0;
    protected $arai_usuario_y_cuenta = array();
    
    function __construct($origen) 
    {
        $this->origen = $origen;
    }
    
    function get_cant_nuevos()
    {
        return $this->cant_nuevos;
    }
    
    function get_cant_actualizados()
    {
        return $this->cant_actualizados;
    }
    
    function get_cant_error_datos()
    {
        return $this->cant_error_datos;
    }
    
    function get_cant_error_registro()
    {
        return $this->cant_error_registro;
    }

    function get_cant_error_arai()
    {
        return $this->cant_error_arai;
    }
    
    function get_cant_agregados_grupo()
    {
        return $this->cant_agregados_grupo;
    }
    
    function set_seleccion($seleccion)
    {
        $this->seleccion = $seleccion;
    }
    
    function set_archivo($archivo)
    {
        $this->archivo = $archivo;
    }
    
    function set_separador($separador)
    {
        $this->separador = $separador;
    }
    
    function set_grupo($grupo)
    {
        $this->grupo = $grupo;
    }
    
    function set_grupo_nombre($nombre)
    {
        $this->grupo_nombre = $nombre;
    }
    
    function set_grupo_descripcion($descripcion)
    {
        $this->grupo_descripcion = $descripcion;
    }
    
    function set_grupo_unidad_gestion($unidad_gestion)
    {
        $this->unidad_gestion = $unidad_gestion;
    }
    
    function set_actualiza_datos_personales($actualiza)
    {
        $this->actualiza_datos_personales = $actualiza;
    }
	
    function importar()
    {
        try {
            toba::db()->abrir_transaccion();
            toba::db()->ejecutar("SET datestyle = 'iso, dmy'");
            $this->_get_personas();
            
            // Si esta conectado con ARAI se arma una lista que contenga los usuarios ARAI
            // junto con los atributos tipoDocumento y numeroDocumento.
            if (toba::instalacion()->vincula_arai_usuarios()) {
                $this->_armar_listado_usuarios_arai();
            }

            foreach ($this->personas as $persona) {
                $this->_importar_persona($persona);
            }
            
            if ($this->cant_error_datos + $this->cant_error_registro != count($this->personas)) {
                $this->_init_grupo();
                $this->_agregar_a_grupo();
            }

            toba::db()->cerrar_transaccion();
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->agregar('Ocurrió un error al intentar importar usuarios.');
            throw $ex;
        }
    }
    
    private function _get_personas()
    {
        if ( $this->origen == 'int_persona') {
            $where = $this->_get_where_seleccion();
            $sql = "SELECT * FROM int_persona WHERE $where";
            $this->personas = kolla_db::consultar($sql);
        } else {
            $this->_eliminar_datos_importados();
            $contenido_archivo = file_get_contents($this->archivo);
            if ( empty($contenido_archivo) ) {
                throw new toba_error(toba::mensajes('archivo_vacio'));
            }
            $this->personas = str_getcsv($contenido_archivo, "\n");
        }
    }
    
    private function _eliminar_datos_importados()
    {
        $where = $this->_get_where_seleccion();
        $sql = "DELETE FROM int_persona WHERE $where";
        kolla_db::ejecutar($sql);
    }
    
    private function _get_where_seleccion()
    {
        $partes = array("resultado_proceso = 'E'");
        if ( isset($this->seleccion) ) {
            $partes[]  = 'persona IN ('.implode(', ', toba::db()->quote($this->seleccion)).')';   
        }
        return implode(' AND ', $partes);
    }
    
    private function _init_grupo()
    {
        if ( $this->origen == 'archivo') {
            if ( isset($this->grupo_nombre) ) {
                $this->_alta_grupo();
            } else {
                $this->_cargar_definicion_grupo();
            }   
        }
    }
    
    private function _alta_grupo()
    {
        $grupo = array(
            'nombre'            => $this->grupo_nombre,
            'descripcion'       => $this->grupo_descripcion,
            'unidad_gestion'    => $this->unidad_gestion,
            'estado'            => 'A',
            'externo'           => 'N'
        );
        $sql = sql_array_a_insert('sge_grupo_definicion', $grupo);
        $sql = substr($sql, 0, -1);
        $sql .= " RETURNING grupo";
        
        $res = kolla_db::consultar_fila($sql);
        $this->grupo = $res['grupo'];
    }
    
    private function _cargar_definicion_grupo()
    {
        $grupo = kolla_db::quote($this->grupo);
        $sql = "SELECT * FROM sge_grupo_detalle WHERE grupo = $grupo";
        $res = kolla_db::consultar($sql);
        $this->grupo_detalle = rs_convertir_asociativo($res, array('encuestado'), 'encuestado');
    }

    private function _importar_persona($persona)
    {
        if ( $persona = $this->_get_formato_datos_persona($persona) ) {
            $this->_upsert_persona($persona);
        }
    }
    
    private function _get_formato_datos_persona($persona)
    {
        if ( $this->origen == 'archivo' ) {
            $grupo   = null;
            $persona = explode($this->separador, $persona);

            if ( count($persona) != 11 ) { // Contabilizo el registro mal formado
                $this->cant_error_registro++;
                return false;
            }

            $persona = array(
                'usuario'           => trim($persona[0]),
                'clave'             => trim($persona[1]),
                'autentificacion'   => trim($persona[2]),
                'documento_pais'    => trim($persona[5]),
                'documento_tipo'    => trim($persona[6]),
                'documento_numero'  => trim($persona[7]),
                'apellidos'         => trim($persona[3]),
                'nombres'           => trim($persona[4]),
                'email'             => trim($persona[10]),
                'sexo'              => strtoupper(trim($persona[8])),
                'fecha_nacimiento'  => trim($persona[9]),
            );
            
        if (trim($persona['clave']) == '') {
                //$persona['clave'] = toba_usuario::generar_clave_aleatoria(8);
            }
        } else {
            $grupo                       = $persona['grupo'];
            $persona['documento_pais']   = $persona['pais_documento'];
            $persona['documento_tipo']   = $persona['tipo_documento'];
            $persona['documento_numero'] = $persona['nro_documento'];
            $persona['fecha_nacimiento'] = $persona['fecha_nac'];
        }
        
        $errores = array();
        
        $fecha_nacimiento = DateTime::createFromFormat('d/m/Y', $persona['fecha_nacimiento']);
        
        if ( !$this->es_campo_valido($persona['usuario']) ) {
            $errores[] = 'Falta el campo usuario';
        } else {
            if ( !validador::validar_usuario($persona['usuario']) ) {
                $errores[] = 'Campo usuario supera máxima longitud permitida. ('.validador::USUARIO_MAX_LENGTH.' caracteres)';
            }
        }

        if ( !$this->es_campo_valido($persona['apellidos']) ) {
            $errores[] = 'Falta el campo apellido';
        }
        if ( !$this->es_campo_valido($persona['nombres']) ) {
            $errores[] = 'Falta el campo nombres';
        }
        if ( !$this->es_campo_valido($persona['documento_pais']) || !validador::validar_pais_documento($persona['documento_pais'])) {
            $errores[] = 'País de documento incorrecto';
        }
        if ( !$this->es_campo_valido($persona['documento_tipo']) || !validador::validar_tipo_documento($persona['documento_tipo'])) {
            $errores[] = 'Tipo de documento incorrecto';
        }
        if ( !$this->es_campo_valido($persona['documento_numero']) ) {
            $errores[] = 'Falta el campo número de documento';
        }
        if ( !$this->es_campo_valido($persona['sexo']) ||  !validador::validar_sexo($persona['sexo']) ) {
            $errores[] = 'Sexo incorrecto (debe ser M o F)';
        }
        if ( !filter_var($persona['email'], FILTER_VALIDATE_EMAIL) ) {
            $errores[] = 'El mail es incorrecto';
        }
        if ( !$fecha_nacimiento ) {
            $errores[] = 'La fecha de nacimiento es incorrecta';
        }

        // Si es una instalación vinculada con ARAI, se busca coincidencia con los datos $persona['documento_tipo']
        // y $persona['documento_numero']. Notar que tiene que existir una única coincidencia para que se considere
        // para asociar la cuenta de Kolla a la persona ARAI.
        if (toba::instalacion()->vincula_arai_usuarios()) {
            $identificador = $this->_existe_usuario_kolla_en_arai($persona['documento_tipo'] ,
                                                                  $persona['documento_numero']);
            $coincidencias = count($identificador);

            if ($coincidencias == 0) {
                $errores[] = 'No se encuentra un usuario de ARAI con el mismo tipo y número de documento.';
                $this->cant_error_arai++;
            } elseif ($coincidencias == 1) {
                if (count($errores) == 0) {
                    $this->arai_usuario_y_cuenta[$persona['usuario']] =  $identificador[0];
                }
            } else {
                $errores[] = 'Existe más de un usuario ARAI con el mismo tipo y número de documento.';
                $this->cant_error_arai++;
            }
        }
        
        if ( empty($errores) ) {
            $persona['guest']   = 'N';
            $persona['externo'] = 'N';
            
            if ( $this->origen == 'int_persona') { // Tengo el dato de la persona
                $sql = 'DELETE FROM int_persona WHERE persona = ' . $persona['persona'];
                kolla_db::ejecutar($sql);
                
                unset($persona['persona']);
                unset($persona['pais_documento']);
                unset($persona['tipo_documento']);
                unset($persona['nro_documento']);
                unset($persona['resultado_proceso']);
                unset($persona['resultado_descripcion']);
                unset($persona['fecha_nac']);
            }
            
            return $persona;
        } else {
            $persona['resultado_proceso']     = 'E';
            $persona['resultado_descripcion'] = implode(', ', $errores);
            $persona['grupo']                 = is_null($grupo) ? $this->grupo : $grupo;
            // Datos formateados para que entren en int_persona (!)
            $persona['pais_documento']        = $persona['documento_pais'];
            $persona['tipo_documento']        = $persona['documento_tipo'];
            $persona['nro_documento']         = $persona['documento_numero'];
            $persona['fecha_nac']             = $persona['fecha_nacimiento'];
            // Datos que no van
            unset($persona['documento_pais']);
            unset($persona['documento_tipo']);
            unset($persona['documento_numero']);
            unset($persona['fecha_nacimiento']);

            if ( $this->origen == 'archivo') {
                $sql = sql_array_a_insert('int_persona', $persona);
            } else {
                $where = array('persona' => $persona['persona']);
                $sql = sql_array_a_update('int_persona', $persona, $where);
            }
            kolla_db::ejecutar($sql);
            
            $this->cant_error_datos++;
            return false;
        }
    }
    
    private function _upsert_persona($datos_persona)
    {
        $persona = $this->_get_persona($datos_persona);
        if ( $persona ) {
            if ( $this->actualiza_datos_personales ) {
                //LOS DATOS QUE SE DEBE USAR PARA ACTUALIZAR SON LOS QUE VIENEN DEL ARCHIVO,
                //NO LOS OBTENIDOS DE LA PERSONA EXISTENTE EN LA BASE
                $datos_persona['encuestado'] = $persona['encuestado'];
                $this->_actualizar_persona($datos_persona);
                $this->cant_actualizados++;
            } else {
                //se agrega al arreglo de encuestados para que luego sea incorporado al grupo
                $this->_agregar_encuestado($persona['encuestado']);
            }
        } else {
            $grupo = $datos_persona['grupo'];
            unset($datos_persona['grupo']);
            $encuestado = $this->_crear_encuestado($datos_persona);
            $this->_agregar_encuestado($encuestado);
            $this->_crear_usuario($datos_persona);

            // En caso de ser una instalación vinculada con ARAI, se asocia la cuenta a un usuario ARAI
            if (toba::instalacion()->vincula_arai_usuarios()) {
                gestion_arai::sincronizar_datos($datos_persona['usuario'],
                                                $this->arai_usuario_y_cuenta[$datos_persona['usuario']]);
            }
            
            if ($this->origen == 'int_persona') {
                $datos = array('grupo' => $grupo, 'encuestado' => $encuestado);
                $sql = sql_array_a_insert('sge_grupo_detalle', $datos);
                kolla_db::ejecutar($sql);
            }
            
            $this->cant_nuevos++;
        }
    }
    
    private function _actualizar_persona($persona)
    {
        $encuestado = $persona['encuestado'];
        
        //Identificación del encuestado en el esquema de Kolla
        $where_sge_encuestado = array(
            'documento_pais'    => $persona['documento_pais'],
            'documento_tipo'    => $persona['documento_tipo'],
            'documento_numero'  => $persona['documento_numero'],
            'usuario'           => $persona['usuario']
        );
        
        //Identificación del usuario en el esquema de Toba
        $where_apex_usuario = array(
            'usuario'           => $persona['usuario']
        );

        $clave = null;
        $autentificacion = null;
        $forzar_cambio_pwd = 0;
        if (isset($persona['clave']) && $persona['clave'] != '') {
            if (!isset($persona['autentificacion']) || $persona['autentificacion']=='') {
                //se pide actualizar la clave, viene plana, se debe encriptar primero
                toba_usuario::set_clave_usuario($persona['clave'], $persona['usuario']);
                $forzar_cambio_pwd = 1;
            } else {
                //si se informó clave y autentificación, se pide actualizarla y ya viene encriptada con el método informado
                $sql = 'UPDATE apex_usuario
					SET		clave = :clave ,
					autentificacion = :autenticacion '
                    .'  WHERE  usuario = :usuario ;';
                $id = toba::instancia()->get_db()->sentencia_preparar($sql);
                toba::instancia()->get_db()->sentencia_ejecutar($id, array('clave' => $persona['clave'],  'autenticacion' => $persona['autentificacion'], 'usuario'=>$persona['usuario']));
            }
        }
        //si no hay clave informada, no se actualiza el dato

        //Datos que no van en la actualización
        unset($persona['documento_pais']);
        unset($persona['documento_tipo']);
        unset($persona['documento_numero']);
        unset($persona['usuario']);
        unset($persona['encuestado']);
        unset($persona['clave']);
        unset($persona['autentificacion']);
        unset($persona['grupo']);

        //Actualizo esquema de Kolla
        $sql_sge_encuestado = sql_array_a_update('sge_encuestado', $persona, $where_sge_encuestado);
        kolla_db::ejecutar($sql_sge_encuestado);
        
        //Datos a actualizar en el esquema de Toba
        $datos_apex_usuario = array(
            'nombre' => $persona['nombres'].' '.$persona['apellidos'],
            'email'  => $persona['email'],
            'forzar_cambio_pwd' => $forzar_cambio_pwd
        );

        //Actualizo esquema de Toba
        $schema_toba = toba::instancia()->get_db()->get_schema();
        $sql_apex_usuario = sql_array_a_update("$schema_toba.apex_usuario", $datos_apex_usuario, $where_apex_usuario);
        kolla_db::ejecutar($sql_apex_usuario);

        //Agrego el encuestado
        $this->_agregar_encuestado($encuestado);
    }
    
    private function _crear_encuestado($persona)
    {
        unset($persona['autentificacion']);
        $sql = sql_array_a_insert('sge_encuestado', $persona);
        $sql = substr($sql, 0, -1);
        $sql .= " RETURNING encuestado";
        $res = kolla_db::consultar_fila($sql);
        
        return $res['encuestado'];
    }
    
    private function _crear_usuario($persona)
    {
        $usuario = $persona['usuario'];
        $nombre  = $persona['nombres'].' '.$persona['apellidos'];
        $atributos['autentificacion'] = $persona['autentificacion'];
        $atributos['clave'] = $persona['clave'];

        act_toba::agregar_usuario($usuario, $nombre, 'encuesta', $persona['email'], $atributos);
    }
    
    private function _agregar_encuestado($encuestado)
    {
        if ( !isset($this->encuestados[$encuestado]) ) {
            $this->encuestados[$encuestado] = $encuestado;
        }
    }
	
	function es_campo_valido($campo)
	{
        if ( $this->origen == 'archivo') {
            return (strcasecmp($campo, 'null') != 0) && $campo != '\'\'' && $campo != '""' && $campo != '';   
        } else {
            return !is_null(trim($campo));
        }
	}
    
    private function _get_persona($persona)
    {
        $clave = $this->_get_indice_cache_usuario($persona);
        if ( isset($this->cache_usuarios[$clave]) ) {
            return $this->cache_usuarios[$clave];
        }
        $documento_pais     = kolla_db::quote($persona['documento_pais']);
        $documento_tipo     = kolla_db::quote($persona['documento_tipo']);
        $documento_numero   = kolla_db::quote($persona['documento_numero']);
        $usuario            = kolla_db::quote($persona['usuario']);
        
        $sql = "SELECT  *
                FROM    sge_encuestado
                WHERE   documento_pais = $documento_pais
                AND     documento_tipo = $documento_tipo
                AND     documento_numero = $documento_numero
                AND     usuario = $usuario
                ";
        
        $res = kolla_db::consultar_fila($sql);
        if ( $res ) {
            $this->cache_usuarios[$clave] = $res;
            return $res;
        } else {
            return false;
        }
    }
    
    private function _get_indice_cache_usuario($usuario)
    {
        return trim($usuario['documento_pais']).trim($usuario['documento_tipo']).trim($usuario['documento_numero'].trim($usuario['usuario']));
    }
    
    private function _agregar_a_grupo()
    {
        if ( $this->origen == 'archivo' ) {
            $datos = array(
                'grupo' => $this->grupo
            );
            foreach ($this->encuestados as $encuestado) {
                if ( !in_array($encuestado, $this->grupo_detalle) ) {
                    $datos['encuestado'] = $encuestado;
                    $sql = sql_array_a_insert('sge_grupo_detalle', $datos);
                    kolla_db::ejecutar($sql);
                    $this->cant_agregados_grupo++;
                }
            }   
        }
    }

    /**
     * Método para armar el listado de usuarios ARAI con sus atributos tipoDocumento y numeroDocumento.
     * @throws toba_error
     */
    protected function _armar_listado_usuarios_arai()
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
    protected function _existe_usuario_kolla_en_arai($tipo_documento, $nro_documento)
    {
        $identificador = null;
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