<?php

class importador_usuarios_ws extends importador_ws
{
    protected $actualiza_datos_personales = false;
    protected $agregar_datos_titulos      = false;
    protected $grupo;
    protected $grupo_descripcion;
    protected $grupo_detalle = array();
    protected $grupo_nombre;
    protected $grupo_unidad_gestion;
    protected $cache_usuarios        = array();
    protected $cache_titulos         = array();
    protected $cache_persona_titulos = array();
    protected $actualizados     = 0;
    protected $rechazados       = 0;
    protected $nuevos           = 0;
    protected $incorrectos      = 0;
    protected $agregados_grupo  = 0;
    protected $encuestados      = array();
    protected $seleccion;

    // Arai
    protected $cant_error_arai = 0;
    protected $arai_usuario_y_cuenta = array();
    protected $arai;
            
    function set_seleccion($seleccion)
    {
        $this->seleccion = $seleccion;
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
        $this->grupo_unidad_gestion = $unidad_gestion;
    }
    
    function set_actualiza_datos_personales($actualiza)
    {
        $this->actualiza_datos_personales = $actualiza;
    }
    
    function set_agregar_datos_titulos($actualiza)
    {
        $this->agregar_datos_titulos = $actualiza;
    }
    
    private function _get_encuestado($persona)
    {
        $clave = $this->_get_indice_cache_usuario($persona);
        return $this->cache_usuarios[$clave]['encuestado'];
    }
    
    private function _get_indice_cache_usuario($usuario)
    {
        return trim($usuario['pais_documento']).trim($usuario['tipo_documento']).trim($usuario['nro_documento'].trim($usuario['usuario']));
    }

        private function _actualizar_datos_persona()
    {
        return $this->actualiza_datos_personales || $this->agregar_datos_titulos;
    }
    
    private function _actualizar_datos_personales()
    {
        return $this->actualiza_datos_personales;
    }
    
    private function _agregar_datos_titulos()
    {
        return $this->agregar_datos_titulos;
    }
    
    function get_actualizados()
    {
        return $this->actualizados;
    }
    
    function get_rechazados()
    {
        return $this->rechazados;
    }
    
    function get_nuevos()
    {
        return $this->nuevos;
    }
    
    function get_incorrectos()
    {
        return $this->incorrectos;
    }

    function get_cant_error_arai()
    {
        return $this->cant_error_arai;
    }
    
    function get_agregados_grupo()
    {
        return $this->agregados_grupo;
    }
            
    function importar()
    {
        $this->_conectar();
        $this->_importar_personas();
    }
    
    private function _importar_personas()
    {
        toba::db()->abrir_transaccion();

        $graduados = $this->_get_graduados();

        // Si esta conectado con ARAI se arma una lista que contenga los usuarios ARAI
        // junto con los atributos tipoDocumento y numeroDocumento.
        if (toba::instalacion()->vincula_arai_usuarios()) {
            $this->arai = new usuarios_arai();
            $this->arai->armar_listado_usuarios_arai();
        }
        
        try {
            $this->_sincronizar_datos_institucion();
            $this->_init_grupo();
            
            if (!empty($graduados)) {
                foreach ($graduados as $fila) :
                    $registro = $this->_formatear_registro($fila);
                    if ( $this->_validar_formato($registro) ) {
                        $this->_importar_persona($registro);
                    } else {
                        $this->incorrectos++;  // Esto no debería pasar en teoría
                    }
                endforeach;
            }
            
            $this->_agregar_a_grupo();
            
            toba::db()->cerrar_transaccion();
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            throw $ex;
        } catch (toba_error $ex) {
            toba::db()->abortar_transaccion();
            throw $ex;
        }
    }
    
    private function _get_graduados()
    {
        if ( isset($this->server) ) {
            $this->_eliminar_datos_importados();
            if ( $this->es_soap() ) {
                $datos = $this->get_cliente()->call('ik_graduado', array());
                $this->_validar_response($datos);
            } else {
                $datos = $this->get_cliente()->get_graduados($this->get_ug());
                
            }
            return $datos;   
        } else {
            $where = $this->_get_where_seleccion();
            return kolla::co('consultas_usuarios')->get_datos_int_guarani_persona($where);
        }
    }
    
    private function _get_where_seleccion()
    {
        $partes = array("resultado_proceso = 'E'");
        if ( isset($this->seleccion) ) {
            $seleccion = array();
			foreach ($this->seleccion as $persona) {
                $fecha_proceso = toba::db()->quote($persona['fecha_proceso']);
                $usuario       = toba::db()->quote($persona['usuario']);
                $titulo_codigo = toba::db()->quote($persona['titulo_codigo']);
                $seleccion[] = "(fecha_proceso = $fecha_proceso AND usuario = $usuario AND titulo_codigo = $titulo_codigo)";
            }
			$partes[] = '('.implode(' OR ', $seleccion).')';
        }
        if ( isset($this->server) ) {
            $partes[] = '(unidad_gestion IS NULL OR unidad_gestion = '. toba::db()->quote($this->get_ug()) . ')';
        }
        return implode(' AND ', $partes);
    }
    
    private function _formatear_registro($fila)
    {
        if ( !$this->es_soap() ) { // Si es rest lo transformo a posicional porque viene asociativo
            $fila = array_values($fila);
        }

        $registro = array(
            'usuario'           => trim($fila[1]),
            'clave'             => $fila[2],
            'ra_codigo'         => $fila[3],
            'nro_inscripcion'   => $fila[4],
            'apellido'          => $fila[5],
            'nombres'           => $fila[6],
            'pais_documento'    => $fila[7],
            'tipo_documento'    => $fila[8],
            'nro_documento'     => $fila[9],
            'sexo'              => strtoupper($fila[10]),
            'fecha_nacimiento'  => $fila[11],
            'email'             => $fila[12],
            'titulo_codigo'     => $fila[13],
            'colacion_fecha'    => $fila[15],
            'resultado_proceso' => 'N'
        );
        
        if ( isset($this->server) ) {
            $registro['fecha_proceso'] = substr($fila[0],6,4).'-'.substr($fila[0],3,2).'-'.substr($fila[0],0,2);
        } else {
            $registro['grupo']         = $fila[19];
            $registro['fecha_proceso'] = $fila[0];
        }
        
        if ( trim($fila[14]) != '' ) {
            $registro['colacion_codigo'] = $fila[14];
        }
        
        return $registro;
    }
    
    private function _validar_formato($registro)
    {
        // Estos campos deben ser NOT NULL
        $nn = array(
            'usuario',
            'ra_codigo',
            'nro_inscripcion',
            'apellido',
            'nombres',
            'pais_documento',
            'tipo_documento',
            'nro_documento',
            'sexo',
            'fecha_nacimiento',
        );
        
        foreach ($nn as $clave) {
            if (trim($registro[$clave]) == '') {
                return false;
            }
        }
        
        return true;
    }
    
    private function _get_persona($persona)
    {
        $clave = $this->_get_indice_cache_usuario($persona);
        if ( isset($this->cache_usuarios[$clave]) ) {
            return $this->cache_usuarios[$clave];
        }
        $documento_pais     = kolla_db::quote($persona['pais_documento']);
        $documento_tipo     = kolla_db::quote($persona['tipo_documento']);
        $documento_numero   = kolla_db::quote($persona['nro_documento']);
        $usuario            = kolla_db::quote($persona['usuario']);
        
        $sql = "SELECT  *
                FROM    sge_encuestado
                WHERE   sge_encuestado.documento_pais = $documento_pais
				AND 	sge_encuestado.documento_tipo = $documento_tipo
                AND 	sge_encuestado.documento_numero = $documento_numero
                AND 	sge_encuestado.usuario = $usuario
        		";
        
        $res = kolla_db::consultar_fila($sql);
        
        if ($res) {
            $this->cache_usuarios[$clave] = $res;
            return $res;
        }
        
        return false;
    }
    
    private function _importar_persona($persona)
    {
        $datos = $this->_get_persona($persona);
        if ($datos) {
            if ( $this->_actualizar_datos_persona() ) {
                $persona['encuestado'] = $datos['encuestado'];
                $this->_actualizar_persona($persona);
            }
        } else {
            $this->_crear_persona($persona);
        }
    }
    
    private function _actualizar_persona($persona)
    {
        $persona = $this->_formatear_datos($persona);
        
        if ( $persona ) {
            $persona['encuestado'] = $this->_get_encuestado($persona);
            // Actualiza datos personales si así lo indicó el usuario.
            if ( $this->_actualizar_datos_personales() ) {
                $encuestado = $this->_get_datos_encuestado($persona, true);
                $where = array(
                    'documento_pais'    => $persona['pais_documento'],
                    'documento_tipo'    => $persona['tipo_documento'],
                    'documento_numero'  => $persona['nro_documento'],
                    'usuario'           => $persona['usuario']
                );
                $sql = sql_array_a_update('sge_encuestado', $encuestado, $where);
                kolla_db::ejecutar($sql);
                $this->_agregar_encuestado($persona['encuestado']);
                $this->actualizados++;
            }
            // Actualiza datos de títulos si así lo indicó el usuario.
            if ( $this->_agregar_datos_titulos() ) {
                $this->_alta_titulo($persona);
            }
        }
    }
    
    private function _crear_persona($persona)
    {
        $persona = $this->_formatear_datos($persona);
        
        if ($persona) {
            $grupo = $persona['grupo'];
            unset($persona['grupo']);
            $encuestado = $this->_crear_encuestado($persona);
            $this->_crear_usuario($persona);
            $persona['encuestado'] = $encuestado;
            $this->_agregar_encuestado($persona['encuestado']);
            $this->_alta_titulo($persona);
            $this->nuevos++;

            // En caso de ser una instalación vinculada con ARAI, se asocia la cuenta a un usuario ARAI
            if (toba::instalacion()->vincula_arai_usuarios()) {
                gestion_arai::sincronizar_datos($persona['usuario'],
                    $this->arai_usuario_y_cuenta[$persona['usuario']]);
            }
            
            if (!isset($this->server)) {
                $datos = array('grupo' => $grupo, 'encuestado' => $encuestado);
                $sql = sql_array_a_insert('sge_grupo_detalle', $datos);
                kolla_db::ejecutar($sql);
            }
        }
    }
    
    private function _alta_titulo($persona)
    {
        if ( !$this->_existe_titulo_persona($persona) ) {
            $titulo = array(
                'encuestado' => $persona['encuestado'],
                'titulo'     => $persona['titulo'],
                'fecha'      => $persona['colacion_fecha'],
                'anio'       => $persona['colacion_anio']
            );
            $sql = sql_array_a_insert('sge_encuestado_titulo', $titulo);
            kolla_db::ejecutar($sql);
            $clave = array(
                'titulo'                => $persona['titulo'],
                'responsable_academica' => $persona['responsable_academica']
            );
            if ( !$this->_existe_titulo_ra($clave)) {
                $sql = sql_array_a_insert('mgi_titulo_ra', $clave);
                kolla_db::ejecutar($sql);
            }   
        }
    }
    
    private function _crear_encuestado($persona)
    {
        $encuestado = $this->_get_datos_encuestado($persona);
        
        $sql = sql_array_a_insert('sge_encuestado', $encuestado);
        $sql = substr($sql, 0, -1);
        $sql .= " RETURNING encuestado";
        $res = kolla_db::consultar_fila($sql);
        
        return $res['encuestado'];
    }
    
    private function _crear_usuario($persona)
    {
        $usuario = $persona['usuario'];
        $nombre  = $persona['nombres'].' '.$persona['apellido'];
        
        act_toba::agregar_usuario($usuario, $nombre, 'encuesta', $persona['email']);
    }
    
    /**
     * Validación de datos de usuario
     */
    private function _formatear_datos($persona)
    {
        $errores = array();
        
        $fecha_nacimiento = DateTime::createFromFormat('d/m/Y', $persona['fecha_nacimiento']);
        $colacion_fecha   = DateTime::createFromFormat('d/m/Y', $persona['colacion_fecha']);
        
        if ( !$fecha_nacimiento ) {
            $errores[] = 'La fecha de nacimiento es incorrecta';
        }
        if ( !$colacion_fecha ) {
            $errores[] = 'La fecha de colación es incorrecta';
        }
        if ( !filter_var($persona['email'], FILTER_VALIDATE_EMAIL) ) {
            $errores[] = 'El mail es incorrecto';
        }
        $titulo = trim($persona['titulo_codigo']);
        if (!empty($titulo)) {
            if (!($titulo = $this->_get_titulo($persona['titulo_codigo']))) {
                $errores[] = 'El título no existe';
            }
        }
        $ra = trim($persona['ra_codigo']);
        if (!empty($ra)) {
            if (!($ra = $this->_get_ra($persona['ra_codigo']))) {
                $errores[] = 'La responsable académica no existe';
            }
        }

        // Si es una instalación vinculada con ARAI, se busca coincidencia con los datos $persona['documento_tipo']
        // y $persona['documento_numero']. Notar que tiene que existir una única coincidencia para que se considere
        // para asociar la cuenta de Kolla a la persona ARAI.
        if (toba::instalacion()->vincula_arai_usuarios()) {
            $identificador = $this->arai->existe_persona_en_arai_usuarios ($persona['tipo_documento'] ,
                $persona['nro_documento']);
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
        
        $grupo = isset($this->server) ? null : $persona['grupo'];
        
        if ( empty($errores) ) {
            $persona['clave']                   = toba_usuario::generar_clave_aleatoria(8);
            $persona['fecha_nacimiento']        = $fecha_nacimiento->format('Y-m-d');
            $persona['colacion_fecha']          = $colacion_fecha->format('Y-m-d');
            $persona['colacion_anio']           = $colacion_fecha->format('Y');
            $persona['titulo']                  = isset($titulo['titulo']) ?  $titulo['titulo'] : null;
            $persona['responsable_academica']   = $ra['responsable_academica'];
            
            if ( !isset($this->server) ) {
                $fecha_proceso = toba::db()->quote($persona['fecha_proceso']);
                $usuario       = toba::db()->quote($persona['usuario']);
                $titulo_codigo = toba::db()->quote($persona['titulo_codigo']);
                $sql = "DELETE
                        FROM    int_guarani_persona
                        WHERE   int_guarani_persona.fecha_proceso = $fecha_proceso
                        AND 	int_guarani_persona.usuario = $usuario
                        AND 	int_guarani_persona.titulo_codigo = $titulo_codigo";
                kolla_db::ejecutar($sql);
            }
            
            return $persona;
        } else {
            // Guardo los que no se pudieron procesar por error de datos
            $persona['grupo']                 = is_null($grupo) ? $this->grupo : $grupo;
            $persona['resultado_proceso']     = 'E';
			$persona['resultado_descripcion'] = implode(', ', $errores);
            $_persona = $persona;
            unset($_persona['encuestado']);
            
            if ( isset($this->server) ) {
                $sql = sql_array_a_insert('int_guarani_persona', $_persona);
            } else {
                $where = array(
                    'fecha_proceso' => $_persona['fecha_proceso'],
                    'usuario'       => $_persona['usuario'],
                    'titulo_codigo' => $_persona['titulo_codigo'],
                );
                $sql = sql_array_a_update('int_guarani_persona', $_persona, $where);
            }
            
            kolla_db::ejecutar($sql);
            $this->rechazados++;
            
            return false;
        }
    }
    
    private function _get_titulo($codigo)
    {
        if ( isset($this->cache_titulos[$codigo]) ) {
            return $this->cache_titulos[$codigo];
        }
        
        $sql = 'SELECT  *
                FROM    mgi_titulo
                WHERE   mgi_titulo.codigo = '.kolla_db::quote($codigo);
        
        $res = kolla_db::consultar_fila($sql);
        
        if ($res) {
            $this->cache_titulos[$codigo] = $res;
            return $res;
        }
        
        return false;
    }
    
    private function _get_ra($codigo)
    {
        if ( isset($this->cache_ra[$codigo]) ) {
            return $this->cache_ra[$codigo];
        }
        
        $sql = 'SELECT  *
                FROM    mgi_responsable_academica
                WHERE   mgi_responsable_academica.codigo = '.kolla_db::quote($codigo);
        
        $res = kolla_db::consultar_fila($sql);
        
        if ($res) {
            $this->cache_ra[$codigo] = $res;
            return $res;
        }
        
        return false;
    }
    
    private function _existe_titulo_ra($titulo)
    {
        if ( isset($this->cache_titulo_ra[$titulo['titulo'].$titulo['responsable_academica']]) ) {
            return true;
        }
        $clave = array(
            'titulo'                => $titulo['titulo'],
            'responsable_academica' => $titulo['responsable_academica']
        );
        
        $existe = abm::existen_registros('mgi_titulo_ra', $clave);
        
        if ($existe) {
            $this->cache_titulo_ra[$titulo['titulo'].$titulo['responsable_academica']] = true;
            return true;
        }
        
        return false;
    }
    
    private function _get_datos_encuestado($persona, $es_actualizacion=false)
    {
        $encuestado = array(
            'apellidos'         => $persona['apellido'],
            'nombres'           => $persona['nombres'],
            'email'             => $persona['email'],
            'sexo'              => $persona['sexo'],
            'fecha_nacimiento'  => $persona['fecha_nacimiento'],
            'guest'             => 'N'
        	);
        
        if (!$es_actualizacion) {
            $encuestado['documento_pais']   = $persona['pais_documento'];
            $encuestado['documento_tipo']   = $persona['tipo_documento'];
            $encuestado['documento_numero'] = $persona['nro_documento'];
            $encuestado['usuario']          = $persona['usuario'];
        }
        
        return $encuestado;
    }
    
    private function _existe_titulo_persona($persona)
    {
        if ( isset($this->cache_persona_titulos[$persona['encuestado']]) && 
             in_array($persona['titulo'], $this->cache_persona_titulos[$persona['encuestado']])) {
            return true;
        }
        $clave = array(
            'encuestado'=> $persona['encuestado'],
            'titulo'    => $persona['titulo']
        );
        $existe = abm::existen_registros('sge_encuestado_titulo', $clave);
        if ($existe) {
            if ( isset($this->cache_persona_titulos[$persona['encuestado']]) ) {
                $this->cache_persona_titulos[$persona['encuestado']][] = $persona['titulo'];
            } else {
                $this->cache_persona_titulos[$persona['encuestado']] = array($persona['titulo']);
            }
            return true;
        }

        return false;
    }
    
    private function _alta_grupo()
    {
        $grupo = array(
                'nombre'	=> $this->grupo_nombre,
                'descripcion'	=> $this->grupo_descripcion,
                'estado'	=> 'A',
                'externo'	=> 'N',
        	'unidad_gestion'=> $this->grupo_unidad_gestion
            );
        $sql = sql_array_a_insert('sge_grupo_definicion', $grupo);
        $sql = substr($sql, 0, -1);
        $sql .= " RETURNING grupo";
        
        $res = kolla_db::consultar_fila($sql);
        $this->grupo = $res['grupo'];
    }
    
    private function _agregar_encuestado($encuestado)
    {
        if ( !isset($this->encuestados[$encuestado]) ) {
            $this->encuestados[$encuestado] = $encuestado;
        }
    }
    
    private function _agregar_a_grupo()
    {
        if ( isset($this->server) ) {
            $datos = array(
                'grupo' => $this->grupo
            );
            foreach ($this->encuestados as $encuestado) {
                if ( !in_array($encuestado, $this->grupo_detalle) ) {
                    $datos['encuestado'] = $encuestado;
                    $sql = sql_array_a_insert('sge_grupo_detalle', $datos);
                    kolla_db::ejecutar($sql);
                    $this->agregados_grupo++;
                }
            }   
        }
    }
    
    private function _cargar_definicion_grupo()
    {
        $grupo = kolla_db::quote($this->grupo);
        $sql = "SELECT * FROM sge_grupo_detalle WHERE grupo = $grupo";
        $res = kolla_db::consultar($sql);
        $this->grupo_detalle = rs_convertir_asociativo($res, array('encuestado'), 'encuestado');
    }
    
    private function _eliminar_datos_importados()
    {
        $where = $this->_get_where_seleccion();
        $sql = "DELETE 
                FROM    int_guarani_persona
                WHERE   $where
            ";
        kolla_db::ejecutar($sql);
    }
    
    private function _sincronizar_datos_institucion()
    {
        if ( isset($this->server) ) {
            // Mantengo actualizado los datos de la institución
            $institucion = new importador_institucion_ws($this->get_conexion());
            $institucion->importar();   
        }
    }
    
    private function _init_grupo()
    {
        if ( isset($this->server) ) {
            if ( isset($this->grupo_nombre) ) {
                $this->_alta_grupo();
            } else {
                $this->_cargar_definicion_grupo();
            }   
        }
    }
}