<?php
namespace Kolla\Test\rest\tipo_elemento;

use Kolla\Test\base\kolla_base_test;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class tipo_elemento_test extends kolla_base_test{
    
    /**
     * Compara cada componente de $resultado con la estructura definida
     * @param Elemento $resultado debe ser el elemento obtenido por WS
     */
    protected function comparar_estructura($resultado)
    {
        $struct = \recurso_tipo_elementos::_get_modelos()['TipoElemento'];
        foreach ($struct as $key => $value) {
            $this->assertArrayHasKey($key, $resultado);
        }
    }
}
