<?php
namespace Kolla\Test\rest\elementos;

use Kolla\Test\base\kolla_base_test;
use Kolla\Test\helpers\status;
use Kolla\Test\data\garbage_data;

/**
 *
 * @author ptoledo
 */
class elemento_test extends kolla_base_test{
   
    protected function comparar_elemento_api($elemento)
	{
		$result = $this->do_request('/elementos/' . $elemento['elemento'], garbage_data::$unidad_gestion, [], status::$OK, "GET");

		foreach ($elemento as $k => $v) {
			$this->assertEquals($v, $result[$k]);
		}
	}
    
    /**
     * Compara cada componente de $resultado con la estructura definida
     * @param Elemento $resultado debe ser el elemento obtenido por WS
     */
    protected function comparar_estructura($resultado)
    {
        $struct = \recurso_elementos::_get_modelos()['Elemento'];
        foreach ($struct as $key => $value) {
            $this->assertArrayHasKey($key, $resultado);
        }
    }
}
