<?php

class admin_instancia
{
	static private $instanciacion;
	protected $id_instancia;
	protected $base;
	
	private function __construct()
	{
		$this->id_instancia = toba::instancia()->get_id();
		$datos = toba_instancia::get_datos_instancia($this->id_instancia);
		$this->base = $datos['base'];
	}

	function db()
	{
		return toba_dba::get_db($this->base);
	}
	
	static function ref($recargar=false)
	{
		if (!isset(self::$instanciacion) || $recargar ) {
			self::$instanciacion = new admin_instancia();	
		}
		return self::$instanciacion;	
	}	
}
?>