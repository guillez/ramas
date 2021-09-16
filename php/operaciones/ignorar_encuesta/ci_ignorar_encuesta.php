<?php

class ci_ignorar_encuesta extends toba_ci
{	
	protected $s__mail;
	protected $s__encuestado;
	protected $s__habilitacion; 
	protected $s__usuario;
	protected $s__encuesta;
	
	function ini() 
	{	
		$hash = toba::memoria()->get_parametro('ignorar');
		
		if (isset($hash) && ($hash!='')) {
			$where = "hash='".$hash."'";
			$logs_envio = toba::consulta_php('consultas_mgn')->get_datos_logs_envio($where);
			if (count($logs_envio) > 0) {
				$this->s__mail = $logs_envio[0]['mail'];
				$this->s__encuestado = $logs_envio[0]['encuestado'];
				$where = "mail='".$this->s__mail."'";
				$datos_mail = toba::consulta_php('consultas_mgn')->get_datos_mail($where);
				$this->s__habilitacion = $datos_mail[0]['habilitacion'];
			} else {
				//toba::vinculador()->navegar_a('kolla', '200000004', null, null, null);
                toba::vinculador()->navegar_a('kolla', '12000094', null, null, null);
			}
		} 
	}
	
	function resetear () 
	{
		unset($this->s__mail);
		unset($this->s__encuestado);
		unset($this->s__habilitacion); 
		unset($this->s__usuario);
		unset($this->s__encuesta);
		$this->dependencia('datos')->resetear();
		toba::vinculador()->navegar_a('kolla', '200000004', null, null, null);
	}
	
	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{	
		if (isset($this->s__encuestado) && isset($this->s__habilitacion)) {
			//obtener el nombre de la encuesta
			$encuesta = toba::consulta_php('consultas_encuestas')->get_habilitaciones("habilitacion ='".$this->s__habilitacion."'");
			$this->s__encuesta = $encuesta[0]['encuesta'];			
			//obtener los datos (nombre y apellido) del encuestado
			$encuestado = toba::consulta_php('consultas_encuestas')->get_encuestado(null,$this->s__encuestado);
			$this->s__usuario = $encuestado[0]['usuario'];
			$datos = array('nombre_encuestado' => $encuestado[0]['apellidos'].', '.$encuestado[0]['nombres'], 'nombre_encuesta' => $encuesta[0]['nombre']);
			$form->set_datos($datos);
		} else {
			toba::vinculador()->navegar_a('kolla', '200000004', null, null, null);
		}
	}

	function evt__formulario__confirmar($datos)
	{
		if (isset($datos)) {
			$fecha = date('Y-m-d');
			$motivo = quote($datos['motivo']);
			
			$fila = array(	'usuario'		=> $this->s__usuario, 
							'encuesta'		=> $this->s__encuesta, 
							'habilitacion'	=> $this->s__habilitacion, 
							'fecha'			=> $fecha, 
							'descripcion'	=> $motivo);
			try {
				$this->dependencia('datos')->nueva_fila($fila);
				$this->dependencia('datos')->sincronizar();
				$this->dependencia('datos')->resetear();
				toba::notificacion()->agregar('Registro guardado. Muchas Gracias.', 'info');
			} catch (toba_error $e) {
				toba::notificacion()->agregar($e->get_mensaje(), 'warning');
			}
		}
		$this->resetear();
	}

}
?>