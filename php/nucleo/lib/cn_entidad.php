<?php
/**
 * Entidades Generica
 */
class cn_entidad extends toba_cn
{
	protected $hay_errores = false;
	protected $errores;
	
	/**
	 * Valida los datos
	 */
	function validar_datos($datos=array())
	{
		return true;
	}	

	/**
	 * Retorna un elemento de persistencia de la entidad
	 */
	function tabla($tabla)
	{
		return $this->dep('datos')->tabla($tabla);
	}
	
	function es_nueva()
	{
		return !$this->dep('datos')->esta_cargada();	
	}

	/**
	 * Acumula mensajes a enviar a la pantalla
	 */	
	function set_error($descripcion_corta, $parms=null, $tipo=null)
	{
		$mensaje = $this->get_mensaje($descripcion_corta, $parms);
		$this->hay_errores = true;
		$this->errores[]['mensaje'] = $mensaje;
	}
	
	function get_mensaje_error($separador='\n')
	{
		return implode($separador, aplanar_matriz($this->errores, 'mensaje'));
	}

	function get_errores()
	{
		return $this->errores;
	}

	function hay_errores()
	{
		return $this->hay_errores;	
	}
	
}
?>