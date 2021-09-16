<?php

class ci_nav_importar_alumnos extends toba_ci
{
	protected $datos_form;
	protected $datos_inst;
	protected $convertidos  = 0;
	protected $rechazados	= 0;
	protected $actualizados = 0;
    
    function conf()
    {
        if ( !$this->hay_usuarios_procesados() ) {
            $this->pantalla()->eliminar_dep('form_totales');
        }
    }
	
	//---- formulario -------------------------------------------------------------------
	
	function conf__formulario(toba_ei_formulario $form)
	{
		$institucion = $this->cn()->get_institucion_local();
		
		if ( empty($institucion) || ($institucion[0]['institucion_araucano'] == null) ) {
			$this->pantalla('pant_inicial')->evento('procesar')->desactivar();
			$form->set_solo_lectura();
			toba::notificacion()->error($this->get_mensaje('institucion_inexistente'));
		}
		
		if ( isset($this->datos_form) ) {
			$form->set_datos($this->datos_form);
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->datos_form = $datos;
	}
	
	//---- form_totales -----------------------------------------------------------------
	
	function conf__form_totales(toba_ei_formulario $form)
	{
		if ( isset($this->datos_form) ) {
			if ( $this->hay_usuarios_procesados() ) {
				$form->set_datos(
                        array(
                            'convertidos'   => $this->convertidos,
                            'actualizados'  => $this->actualizados,
							'rechazados'    => $this->rechazados
                        ));
			}
		}
	}
	
	function hay_usuarios_procesados()
	{
		return !($this->convertidos == 0 && $this->actualizados == 0 && $this->rechazados == 0);
	}
	
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
		$usuarios 	  = $this->cn()->get_usuarios();
       
		$estado_clave = $this->datos_form['estado_clave'];
		$algoritmo 	  = $this->datos_form['algoritmo'];
		
		// Se recorren aquellos usuarios no importados de ingeniería relevamiento, y se los va convirtiendo a encuestados o bien actualizándolos.

		foreach ($usuarios as $usuario) :
			// Inicializaciones y consultas
			$resultado_descripcion 	 = '';
			$resultado_procesar 	 = 'E';
			$importado				 = false;
			$fila_convertida 		 = false;
			$existe_ra 				 = false;
            $corresponde_institucion = false;
			$existe_usuario 		 = toba::consulta_php('consultas_usuarios')->existe_usuario($usuario['usuario']);
			$existe_usuario_dni 	 = toba::consulta_php('consultas_usuarios')->get_encuestado_por_documento($usuario['tipo_documento'], $usuario['numero_documento']);
			$hay_ra 				 = $this->es_campo_definido($usuario, 'arau_ua');
			
			if ( $hay_ra ) {
				$arau = toba::consulta_php('consultas_mgi')->get_instituciones_araucano_ra($usuario['arau_ua']);
				$existe_ra = !empty($arau);
				if ( $existe_ra ) {
					$institucion = $this->cn()->get_institucion_local();
					$corresponde_institucion = $institucion[0]['institucion_araucano'] == $arau[0]['institucion_araucano'];
				}
			}
			
			$existe_pais = $this->es_campo_definido($usuario, 'pais_documento') ? toba::consulta_php('consultas_mug')->get_paises('pais = '.$usuario['pais_documento']) : true;
			$no_esta_en_condiciones = 	($hay_ra && !$existe_ra) ||
										(!empty($existe_usuario_dni) && !$existe_usuario) ||
										(empty($existe_usuario_dni) && $existe_usuario) ||
										(!$corresponde_institucion) ||
										(!$existe_pais);
			
			if ( $no_esta_en_condiciones ) {
				$resultado_descripcion .= (!empty($existe_usuario_dni) && !$existe_usuario) ? 'Ya existe otro usuario con ese documento de identidad.' : '';
				$resultado_descripcion .= (empty($existe_usuario_dni) && $existe_usuario) ? 'El usuario existe pero no coincide el documento de identidad.' : '';
				$resultado_descripcion .= ($hay_ra && !$existe_ra) ? 'No se registra la responsable académica en el sistema.' : '';
				$resultado_descripcion .= (!$corresponde_institucion) ? 'Los datos de la responsable académica no corresponden a la institución local definida.' : '';
				$resultado_descripcion .= (!$existe_pais) ? 'El código de país no es un código válido.' : '';
				$this->rechazados++;
			} else {
				if ( !$existe_usuario ) {
					$fila_convertida = $this->cn()->actualizar_usuario($usuario, $estado_clave, $algoritmo, true);
					if ($fila_convertida) {
						$resultado_descripcion .= 'Registro CONVERTIDO con éxito.';
						$resultado_procesar 	= 'O';
						$importado 				= true;
						$this->convertidos++;
					} else {
						$resultado_descripcion .= 'falló la inserción en la base de datos.';
						$this->rechazados++;
					}
				} else {
					$fila_convertida = $this->cn()->actualizar_usuario($usuario, $estado_clave, $algoritmo, false);
					if ($fila_convertida) {
						$resultado_descripcion .= 'Registro ACTUALIZADO con éxito.';
						$resultado_procesar 	= 'W';
						$fila_actualizada 		= true;
						$importado 				= true;
						$this->actualizados++;
					} else {
						$resultado_descripcion .= 'falló la actualización en la base de datos.';
						$this->rechazados++;
					}
				}
			}
			
			//Actualizo los resultados del proceso en ingeniería relevamiento
			$ingenieria_relevamiento = array();
			$ingenieria_relevamiento['importado'] 			  = $importado ? 'S' : 'N';
			$ingenieria_relevamiento['resultado_proceso'] 	  = $resultado_procesar;
			$ingenieria_relevamiento['resultado_descripcion'] = $resultado_descripcion;
			$this->cn()->actualizar_ingenieria_relevamiento($usuario, $ingenieria_relevamiento);
		endforeach;
		
		if ( empty($usuarios) ) {
			//Se emite mensaje de error: no hay personas para procesar
			toba::notificacion()->agregar($this->get_mensaje('importacion_sin_usuarios'), 'error');
		} else {
			//Se emite mensaje de información: importación ok
			toba::notificacion()->agregar($this->get_mensaje('importacion_ok'), 'info');
		}
	}
	
	function es_campo_definido($usuario, $campo)
	{
		return !(($usuario[$campo] == null) || ($usuario[$campo] == ''));
	}

}
?>