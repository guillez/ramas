<?php
namespace Kolla\Test\rest\conceptos;

use Kolla\Test\base\kolla_base_test;
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 * @copyright SIU ( Sistemas de Información Universitaria )
 * 
 * Clase base para los casos de test que involucren 'Conceptos'. 
 * Agrupa funciones que son generales a todos los casos de test
 */
class concepto_test extends kolla_base_test {
        
    /**
     * Compara el concepto enviado por parametro con el obtenido por API.
     * @todo agregar la regla de no obtener nada de la base de datos
     * @param Concepto $concepto
     */
	protected function comparar_concepto_api($concepto)
	{
		$result = $this->do_request('/conceptos/' . $concepto['concepto'],  garbage_data::$unidad_gestion, $status = status::$OK,$method = 'GET');
        
		foreach ($concepto as $k => $v) {
			$this->assertEquals($v, $result[$k]);
		}
	}
    /**
     * Compara cada elemento de $resultado con la estructura definida
     * @param Concepto $resultado debe ser el concepto obtenido por WS
     */
    protected function comparar_estructura($resultado)
    {
        $struct = \recurso_conceptos::_get_modelos()['Concepto'];
        foreach ($struct as $key => $value) {
            $this->assertArrayHasKey($key, $resultado);
        }
    }
}
