<?php
include("nucleo/servicios_web/kolla/ws_habilitar.php");
/**
 * Extender esta clase con un nombre que empiece con test_  y con metodos que empiecen con test_
 * para que sean detectados por el motor de testing de toba. 
 * No subirlo al repositorio. (Les podriamos agregar un ignore) 
 * @author alejandro
 *
 */
class basetest_habilitaciones extends toba_test
{
	protected function init($mje, $params, $sistema)
	{
		echo "<br />Inicio Test $mje<br />";
		$servicio = new ws_habilitar($params, $sistema);
		$array = $servicio->ejecutar();
		return $array;

	}
	protected function end($resultado)
	{
		echo '<br />El resultado de la operacion es: <br />';
		ei_arbol($resultado);
	}

	static function get_descripcion()
	{
		return 'Habilitacion Encuesta- se prueba la implementacion, no el ws';
	}

	
}
?>