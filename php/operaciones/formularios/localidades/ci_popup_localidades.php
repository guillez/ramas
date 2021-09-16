<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_popup_localidades extends bootstrap_ci
{
	protected $s__seleccion;
	protected $s__filtro;
	
	private function get_tabla() 
	{
		return $this->dependencia('datos');
	}
	
	//-------------------------------------------------------------------
	//--- Dependencias
	//-------------------------------------------------------------------


	//-- FORMULARIO

	function conf__seleccion($componente)
	{
		// por el momento no hace nada, en algún momento tendria que recuperar
		// si existiera alguna localidad cargada!
		if(isset($this->s__seleccion)){
			$t = $this->get_tabla();
			$componente->ef('bloque')->set_solo_lectura();
			$t->cargar($this->s__seleccion);
			return $t->get();
		}
	}

	function evt__seleccion__modificacion($datos)
	{
		// envio al formulario el codigo y descripción de la localidad.
		if(isset($this->s__seleccion)){
			$t = $this->get_tabla();
			$t->set($datos);
			$t->sincronizar();
			$this->resetear();
		}
	}

	function evt__seleccion__cancelar()
	{
		// cierro el popup
		$this->resetear();		
	}
	
}
?>