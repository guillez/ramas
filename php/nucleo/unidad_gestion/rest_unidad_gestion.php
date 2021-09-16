<?php
use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;

class rest_unidad_gestion extends rest_base
{
	/**
	 * @var co_unidad_gestion
	 */
	protected $modelo;
	
	
	function __construct()
	{
		$this->modelo = kolla::co('co_unidad_gestion');
	}
	
	public function get_list()
	{
		$this->_get_sistema();
		return $this->modelo->get_list ();
	}
	
	public function get ($id_unidad)
	{
		$this->_get_sistema();
		return $this->modelo->get_ug( $id_unidad );
	}
}