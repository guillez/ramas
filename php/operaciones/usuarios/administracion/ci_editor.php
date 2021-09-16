<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_editor extends bootstrap_ci
{
    const clave_falsa = 'xS34Io9gF2JD'; //La clave no se envia al cliente

    protected $datos_titulos_temp;
    protected $grupos;
    protected $s__ug = null;
    protected $s__usuario_arai = null;
    

    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    
    /**
     * @return toba_datos_relacion
     */
    function relacion()
    {
        return $this->controlador()->relacion();
    }

    /**
     * @param string $tabla
     * @return toba_datos_tabla
     */
    function tabla($tabla)
    {
        return $this->relacion()->tabla($tabla);
    }

    function resetear()
    {
		unset($this->s__ug);
    }
    
    function resetear_ug()
    {
		unset($this->s__ug);
    }

    function get_grupos_acceso($proyecto = null)
    {
        if ($this->relacion()->esta_cargada()) {
            $encuestado = $this->tabla('sge_encuestado')->get();
            $externo = ($encuestado['externo'] == 'S');
        } else {
            $externo = false;
        }
        
        $partes = array("proyecto = 'kolla'");
		// Solo se carga el grupo de acceso externo si se está visualizando datos de un externo
        if ($externo) {
            $partes[] = "usuario_grupo_acc = 'externo'";
        } else {
            $partes[] = "usuario_grupo_acc != 'externo'";
        }
        $perfiles = toba::usuario()->get_perfiles_funcionales();
      
        // Solo se cargan los grupos de acceso Administrador y Gestor si el usuario actual es Administrador
        if (!in_array('admin', $perfiles)) {
            $partes[] = "usuario_grupo_acc != 'admin' AND usuario_grupo_acc != 'gestor'";
        }

        $where = implode(' AND ', $partes);
		$sql = "SELECT 	proyecto,
                        usuario_grupo_acc,
                        nombre,
                        descripcion
                FROM 	apex_usuario_grupo_acc
                WHERE 	$where";
        
		return toba::db('toba')->consultar($sql);
    }

    //-----------------------------------------------------------------------------------
    //---- form_usuario -----------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    
    function conf__form_usuario(toba_ei_formulario $form)
    {
        // Si esta vinculado con ARAI entonces no ingreso clave
        if (toba::instalacion()->vincula_arai_usuarios()) {
            $form->desactivar_efs(array('clave'));
        } else {
            if ($form->existe_ef('barra_arai') && $form->existe_ef('usuario_arai')) {
                $form->desactivar_efs(array('barra_arai', 'usuario_arai'));
            }
            
        }

        if ($this->relacion()->esta_cargada()) {
            $form->ef('usuario')->set_solo_lectura(true);
            $form->ef('usuario_perfil_datos')->set_solo_lectura(true);
            
            // Si esta vinculado con ARAI la modificiación de la "persona ARAI" no se hace desde kolla.
            // Debe ser realizado (en caso de ser necesario) por el administrador de ARAI.
            if (toba::instalacion()->vincula_arai_usuarios() && $form->existe_ef('barra_arai') && $form->existe_ef('usuario_arai')) {
                $form->desactivar_efs(array('barra_arai', 'usuario_arai'));
            }
            
            // Recupero info para visualizar
            $usuario    = $this->tabla('apex_usuario')->get();
            $encuestado = $this->tabla('sge_encuestado')->get();
            
            // Esto funciona sólo si el usuario tiene un único perfil funcional
            $usuario_proyecto = $this->tabla('apex_usuario_proyecto')->get_filas(array('proyecto' => 'kolla'));
            $acceso = current($usuario_proyecto);
            
            // Merge para visualizar todo
            $datos = array_merge(empty($usuario)    ? array() : $usuario,
                                 empty($encuestado) ? array() : $encuestado,
                                 empty($acceso)     ? array() : $acceso);
            $datos['clave'] = self::clave_falsa;
            
            if ($encuestado['externo'] == 'S') {
                $form->set_solo_lectura(array('usuario_grupo_acc', 'usuario'));
                if ($form->existe_ef('clave')) {
                    $form->desactivar_efs(array('clave'));
                }
            }
            
            if ($acceso['usuario_grupo_acc'] == 'guest' || $acceso['usuario_grupo_acc'] == 'externo' || $acceso['usuario_grupo_acc'] == 'encuesta') {
                $form->set_solo_lectura(array('usuario_grupo_acc'));
            }

            //Si el perfil del usuario es encuestado, administrador o gestor se muestra Último Registro de Actividad
            //Acá se controlaba no mostrar datos de conexión en caso de que sea usuario anónimo ("guest" para toba)
            //if ($acceso['usuario_grupo_acc'] != 'externo' && $acceso['usuario_grupo_acc'] != 'guest') {
            //Según el ticket #17029 se tenía que mostrar, entonces la condición queda así:
            if ($acceso['usuario_grupo_acc'] != 'externo') {
                $conexion = toba::consulta_php('consultas_usuarios')->get_ultima_conexion($encuestado['usuario']);
                $fecha = $conexion ? new DateTime($conexion['ingreso']) : '< Sin datos >';
                $datos['ultima_actividad'] = $conexion ? $fecha->format("d/m/Y H:i:s") : $fecha;
                $form->ef('ultima_actividad')->set_solo_lectura(true);
            } else {
                if ($form->existe_ef('ultima_actividad')) {
                    $form->desactivar_efs('ultima_actividad');
                }
            }
        } else {
            if ($form->existe_ef('ultima_actividad')) {
                $form->desactivar_efs('ultima_actividad');
            }
        }

        $form->set_datos(isset($datos) ? $datos : array());
    }
    
    function evt__form_usuario__modificacion($datos)
    {
        if (in_array($datos['usuario_grupo_acc'], array('guest', 'externo'))) {
            $datos['guest']  = 'S';
            $datos['nombre'] = $datos['usuario_grupo_acc'];
        } else {
            $datos['nombre'] = $datos['nombres'].' '.$datos['apellidos'];
        }
        
        if (isset($datos['clave'])) {
            if ($datos['clave'] == self::clave_falsa) {
                unset($datos['clave']);	
            } else { // Chequeamos que la composicion de la clave sea valida
                $largo_clave = kolla::co('co_toba')->get_largo_pwd();
                toba_usuario::verificar_composicion_clave($datos['clave'], $largo_clave);
            }   
        }
		
        if (!isset($datos['autentificacion'])) {
            $datos['autentificacion']  = apex_pa_algoritmo_hash;
        }
        
        $this->validar_usuario($datos);
        $this->tabla('apex_usuario')->set($datos);
        
        if (isset($datos['clave'])) { // Así no se guarda más el campo en sge_encuestado
            unset($datos['clave']);
        }
        
        $this->tabla('sge_encuestado')->set($datos);
        $condicion = array('proyecto' => 'kolla');
        
        // Gestion de Perfiles de Datos
        $id = $this->tabla('apex_usuario_proyecto_perfil_datos')->get_id_fila_condicion($condicion);
        
        if (isset($datos['usuario_perfil_datos'])) {
            $fila = array(
                'proyecto'              => 'kolla',
                'usuario'               => $datos['usuario'],
                'usuario_perfil_datos'  => $datos['usuario_perfil_datos']
            );
            
			if (empty($id)) {
				$this->tabla('apex_usuario_proyecto_perfil_datos')->nueva_fila($fila);
			} else {
				$this->tabla('apex_usuario_proyecto_perfil_datos')->modificar_fila($id[0], $fila);
			}
		} else if (!empty($id)) {
			//-- Si por pantalla no viene nada pero esta en la tabla hay que borrarlo
			$this->tabla('apex_usuario_proyecto_perfil_datos')->eliminar_fila($id[0]);
		}
        
        // Gestion de Perfiles de Funcionales
        $accesos  = $this->tabla('apex_usuario_proyecto')->get_filas($condicion);
        $perfiles = array();
        foreach ($accesos as $perfil) {
            $perfiles[$perfil['usuario_grupo_acc']] = $perfil['usuario_perfil_datos'];
        }
        
        $datos['proyecto'] = 'kolla';
        
        if (empty($accesos)) {
            
            // Si no hay datos de proyecto cargados en el dt, entonces se trata de un alta
            $this->tabla('apex_usuario_proyecto')->nueva_fila($datos);
            
            if ($datos['usuario_grupo_acc'] == 'admin') {
                $datos['proyecto'] = 'toba_usuarios';
                $this->tabla('apex_usuario_proyecto')->nueva_fila($datos);
            }
		} elseif (!array_key_exists($datos['usuario_grupo_acc'], $perfiles)) {
            
            // Si el perfil seleccionado en el form no está entre los del usuario
			$this->tabla('apex_usuario_proyecto')->eliminar_filas();
            $this->tabla('apex_usuario_proyecto')->nueva_fila($datos);

            if ($datos['usuario_grupo_acc'] == 'admin') {
                $datos['proyecto'] = 'toba_usuarios';
                $this->tabla('apex_usuario_proyecto')->nueva_fila($datos);
            }
		}

        if (isset($datos['usuario_arai'])) {
            $largo_clave =  toba_parametros::get_largo_pwd(null);

            // seteo los datos de arai-usuarios
            $datos = gestion_arai::completar_datos_usuario($datos, $largo_clave);
            $this->s__usuario_arai = $datos['usuario_arai'];
        }
    }
    
    function validar_usuario($datos)
    {
        if (strpos($datos['usuario'], ' ')) {
			throw new toba_error('El Nombre de usuario no puede contener espacios.');
		}
        
        if (substr(ltrim($datos['usuario']), 0, 3) == 'ue_') {
            throw new toba_error('El Nombre de usuario no puede tener el prefijo "ue_", ya que el mismo queda reservado para los usuarios externos que crea Kolla.');
        }
        
        $usuario_proyecto = $this->tabla('apex_usuario_proyecto')->get_filas(array('proyecto' => 'kolla'));
        
        if (!empty($usuario_proyecto)) {
            if ($usuario_proyecto[0]['usuario_grupo_acc'] ==  'admin' && $datos['usuario_grupo_acc'] ==  'gestor') {
                throw new toba_error('No es posible modificar el <b>Perfil de Acceso</b> de Administrador a Gestor.');
            } elseif ($usuario_proyecto[0]['usuario_grupo_acc'] ==  'admin' && $datos['usuario_grupo_acc'] ==  'guest') {
                throw new toba_error('No es posible modificar el <b>Perfil de Acceso</b> de Administrador a Anónimo.');
            } elseif ($usuario_proyecto[0]['usuario_grupo_acc'] ==  'gestor' && $datos['usuario_grupo_acc'] ==  'admin') {
                throw new toba_error('No es posible modificar el <b>Perfil de Acceso</b> de Gestor a Administrador.');
            } elseif ($usuario_proyecto[0]['usuario_grupo_acc'] ==  'gestor' && $datos['usuario_grupo_acc'] ==  'guest') {
                throw new toba_error('No es posible modificar el <b>Perfil de Acceso</b> de Gestor a Anónimo.');
            }
        }
    }
    
    function ajax__get_acceso($parametro, toba_ajax_respuesta $respuesta)
	{
        $usuario_proyecto = $this->tabla('apex_usuario_proyecto')->get_filas(array('proyecto' => 'kolla'));
        
        if (empty($usuario_proyecto)) {
            $estructura = array('acceso_anterior' => 'NO_DEFINIDO');
            $respuesta->set($estructura);
        } else {
            $estructura = array('acceso_anterior' => $usuario_proyecto[0]['usuario_grupo_acc']);
            $respuesta->set($estructura);
        }
	}
	
	//---- Asociacion a Titulos ---------------------------------------------------------
	
	//-----------------------------------------------------------------------------------
	//---- ml_titulos ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__ml_titulos(toba_ei_formulario_ml $form_ml)
	{	
		if (isset($this->datos_titulos_temp)) {
			$form_ml->set_datos($this->datos_titulos_temp);		
		} else {
            $datos = $this->tabla('sge_encuestado_titulo')->get_filas();
            $form_ml->set_datos($datos);
		}
	}

	function evt__ml_titulos__modificacion($datos)
	{
		try {
			$this->validar_datos_titulos($datos);
			$this->tabla('sge_encuestado_titulo')->procesar_filas($datos);
		} catch (toba_error $e) {
			$this->datos_titulos_temp = $datos;
			throw $e;
		}
	}

	function validar_datos_titulos($titulos)
	{
		$this->validar_titulos_repetidos($titulos);
		$this->validar_fecha_y_anio($titulos);
	}
	
	function validar_titulos_repetidos($titulos)
	{
		$array_titulos = kolla_arreglos::aplanar_matriz_sin_nulos($titulos, 'titulo');
		
		if (count(array_unique($array_titulos)) < count($array_titulos)) {
			throw new toba_error($this->get_mensaje('control_repetidos', array('Títulos')));
		}
	}
	
	function validar_fecha_y_anio($titulos)
	{
		foreach ($titulos as $titulo) {
			if ($titulo['apex_ei_analisis_fila'] != 'B') {
				$fecha = $titulo['fecha'];
				if ($titulo['anio'] < kolla_fecha::get_parte($fecha, 'año')) {
					throw new toba_error($this->get_mensaje('control_anio_titulo'));
				}
			}
		}
	}

    //-----------------------------------------------------------------------------------
	//---- GRUPOS -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    //-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__grupos(toba_ei_pantalla $pantalla)
	{
        $usuario_proyecto = $this->tabla('apex_usuario_proyecto')->get_filas();
        $acceso = $usuario_proyecto[0]['usuario_grupo_acc'];
        
        if ($acceso == 'encuesta') {
            $pantalla->tab('titulos')->activar();
        } else {
            $pantalla->tab('titulos')->desactivar();
        }
        
        if ($acceso == 'guest') {
        	$pantalla->tab('grupos')->desactivar();
        } else {
        	$pantalla->tab('grupos')->activar();
        }
	}
    
	//-----------------------------------------------------------------------------------
	//---- cuadro_grupos ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_grupos(toba_ei_cuadro $cuadro)
	{
        if (!isset($this->s__ug)) {
            $ugs = kolla::co('consultas_ug')->get_unidad_gestion_combo();
            $this->s__ug = $ugs[0]['unidad_gestion'];
        }
        
        $encuestado = $this->tabla('sge_encuestado')->get();
        $grupos = kolla::co('consultas_usuarios')->get_grupos_usuario_por_ug($encuestado['encuestado'], $this->s__ug);
        
        foreach ($grupos as $key => $value) {
            $utilizado = kolla::co('consultas_usuarios')->es_grupo_utilizado_en_formulario($value['grupo']);
            $grupos[$key]['habilitado'] = $utilizado['utilizado'] ? 'Si' : 'No';
        }
        
        $cuadro->set_datos($grupos);
	}
    
    function evt__cuadro_grupos__seleccion_multiple($datos)
	{
        $this->grupos = $datos;
	}

	//-----------------------------------------------------------------------------------
	//---- form_grupos ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_grupos(toba_ei_formulario $form)
	{
        if ($this->relacion()->esta_cargada()) {
            $usuario = $this->tabla('apex_usuario')->get();
            $datos = array('usuario' => $usuario['usuario']);
            $datos = isset($this->s__ug) ? array_merge($datos, array('unidad_gestion' => $this->s__ug)) : $datos;
        } else {
            $datos = array();
        }
        
        $form->set_datos($datos);
        $form->ef('usuario')->set_solo_lectura(true);
	}

	function evt__form_grupos__modificacion($datos)
	{
        $this->s__ug = $datos['unidad_gestion'];
	}

    function evt__form_grupos__recargar($datos)
    {
        $this->evt__form_grupos__modificacion($datos);
    }
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function evt__cancelar()
    {
        $this->resetear();
        $this->controlador()->relacion()->resetear();
        $this->controlador()->set_pantalla('seleccionar');
    }
    
	function evt__eliminar()
	{
        if (!empty($this->grupos)) {
            $encuestado = $this->tabla('sge_encuestado')->get();
            foreach ($this->grupos as $grupo) {
                act_usuarios::eliminar_encuestado_de_grupo($encuestado['encuestado'], $grupo['grupo']);
			}
			unset($this->grupos);
		}
	}
    
    //-----------------------------------------------------------------------------------
	//---- Auxiliares -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function datos($tabla)
    {
        return $this->controlador->dep('datos')->tabla($tabla);
    }

	public function get_usuario_arai()
    {
        return $this->s__usuario_arai;
    }

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__eliminar = function()
		{
            if (this.dep('cuadro_grupos').get_ids_seleccionados('seleccion_multiple').length == 0) {
                alert('Debe seleccionar al menos un grupo.');
                return false;
            }

            return true;
		}
		";
	}

}
?>