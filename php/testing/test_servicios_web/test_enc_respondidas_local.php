<?php
include("basetest_enc_respondidas.php");
class test_enc_respondidas_local extends basetest_enc_respondidas
{

	function test_encuestas_respondidas1()
	{
		$params = array (
				'respuestas_ya_marcadas' => array (
						
						1 => array ( 0 => 30, 1 => '__fdsfsdfsdfsd', 2 => '1', ),
						2 => array ( 0 => 5, 1 => 'komomom', 2 => '4', ), 
						3 => array ( 0 => 5, 1 => 'ffdsf', 2 => '4', ), 
						4 => array ( 0 => 5, 1 => 'gfdgdfg', 2 => '5', ), 
						0 => array ( 0 => 5, 1 => 'gdfhnnhmj', 2 => '7', ),
						),
										
				'respuestas_inexistentes' => array (
						0 => array ( 0 => 5, 1 => 'gdfhnnhmj', 2 => '1', ), 
						 ), );

		$mje = 'con los 3 parámetros, y existe un resultado';
		$sistema = 1;
		$r = $this->init($mje, $params, $sistema);
		$this->end($r);

	}

	function atest_encuestas_respondidas2()
	{
		$params = array(
				'habilitacion' => 5,
				'sistema' => 1,
		);
		$mje = 'solo habilitacion';
		$r = $this->init($mje, $params);
		$this->end($r);
	}

	function atest_encuestas_respondidas3()
	{
		$params = array(
				'habilitacion' => 5,
				'formulario' => 'fisica1',
				'sistema' => 1,
		);
		$mje = 'con habilitacion y elemento';
		$r = $this->init($mje, $params);
		$this->end($r);
	}



}
?>