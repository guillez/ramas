<?php
namespace ext_bootstrap\componentes;

use ext_bootstrap\componentes\interfaz\bootstrap_ei_pantalla;


class bootstrap_ci extends \toba_ci{
	
	
	/**
	 * @todo sobre-escribir metodo 
	 * @todo revisar la creación dinamica de las clases
	 */
	function pantalla()
	{
		if (! isset($this->_pantalla_servicio)) {
		
			//$this->_log->debug( $this->get_txt() . "Pantalla de servicio: '{$this->_pantalla_id_servicio}'", 'toba');
			$id_pantalla = $this->get_id_pantalla();
			if(!isset($id_pantalla)) {
				//Se esta consumiendo la pantalla antes de la configuracion,
				//y sin un set_pantalla de por medio: utilizo la misma pantalla de los eventos.
				$id_pantalla = $this->_pantalla_id_eventos;
			}
			$info_pantalla = $this->get_info_pantalla($id_pantalla);
			$obj_pantalla = $this->get_info_objetos_asoc_pantalla($id_pantalla);
			$evt_pantalla = $this->get_info_eventos_pantalla($id_pantalla);
			$info = array('_info' => $this->_info,
					'_info_ci' => $this->_info_ci,
					'_info_eventos' => $this->_info_eventos,
					'_info_ci_me_pantalla' => $this->_info_ci_me_pantalla);
			$info['_info_pantalla'] = $info_pantalla;
			$info['_objetos_pantalla'] = $obj_pantalla;
			$info['_eventos_pantalla'] = $evt_pantalla;
			$info['_const_instancia_numero'] = 0;
			if (isset($info_pantalla['subclase_archivo'])) {
				$pm = \toba::puntos_montaje()->get_por_id($info_pantalla['punto_montaje']);
				$path = $pm->get_path_absoluto().'/'.$info_pantalla['subclase_archivo'];
								require_once($path);
			}
			
			$clase = 'bootstrap_ei_pantalla';
			if (isset($info_pantalla['subclase'])) {
				$clase = $info_pantalla['subclase'];
			}
			$this->_pantalla_servicio = new bootstrap_ei_pantalla($info, $this->_submit, $this->objeto_js);
			$this->_pantalla_servicio->set_controlador($this, $id_pantalla);
			$this->_pantalla_servicio->pre_configurar();
			//Se le pasan las notificaciones
			foreach ($this->_notificaciones as $notificacion) {
				$this->_pantalla_servicio->agregar_notificacion($notificacion['mensaje'], $notificacion['tipo']);
			}
			$this->_notificaciones = array();
		}
		return $this->_pantalla_servicio;
	}
	
	/**
	 * Construye una dependencia y la asocia al componente actual
	 *
	 * @param unknown_type $identificador
	 * @param unknown_type $parametros
	 * @return unknown
	 * @ignore
	 */
	function cargar_dependencia($identificador)
	{
		if(!isset($this->_indice_dependencias[$identificador])){
			throw new \toba_error_def("OBJETO [cargar_dependencia]: No EXISTE una dependencia asociada al indice [$identificador].");
		}
		$posicion = $this->_indice_dependencias[$identificador];
		$clase = $this->_info_dependencias[$posicion]['clase'];
		$clave['proyecto'] = $this->_info_dependencias[$posicion]['proyecto'];
		$clave['componente'] = $this->_info_dependencias[$posicion]['objeto'];
		$this->_dependencias[$identificador] = \toba_constructor::get_runtime( $clave, $clase );
	}
}