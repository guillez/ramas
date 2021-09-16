<?php

class basetest_url extends toba_test
{

	protected function init($mje)
	{
		echo "<br />Inicio Test $mje<br />";
	}
	protected function end()
	{
		echo '<br />Fin Test<br />';
	}

	static function get_descripcion()
	{
		return 'Generacion URL';
	}
	
	protected function getPassword($id_hab)
    {
		$sql = "SELECT password_se FROM kolla.sge_habilitacion WHERE habilitacion= $id_hab";
		$res = kolla_db::consultar_fila($sql);
	
		return $res['password_se'];
	}

}
?>