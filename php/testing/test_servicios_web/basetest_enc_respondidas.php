<?php

class basetest_enc_respondidas extends toba_test
{

	protected function init($mje, $params, $sistema)
	{
		echo "<br />Inicio Test $mje<br />";
		var_export($params);
		ei_arbol($params);
		$servicio = @new ws_encuestas_respondidas($params, $sistema);
		$array = $servicio->ejecutar();
		return $array;

	}
	protected function end($resultado)
	{
		echo '<br />Resultado: <br />';
		var_export($resultado);
		ei_arbol($resultado);
		echo '<br />Fin Test<br />';
	}

	static function get_descripcion()
	{
		return 'Encuestas Respondidas- Se prueba la implementacion, no el ws';
	}

}
?>