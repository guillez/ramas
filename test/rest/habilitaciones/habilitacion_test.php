<?php
namespace Kolla\Test\rest\habilitaciones;

use Kolla\Test\base\kolla_base_test;

/**
 * Description of habilitacion_test
 *
 * @author ptoledo
 */
class habilitacion_test extends kolla_base_test{
    
    /**
     * Compara cada componente de $resultado con la estructura definida
     * @param Habilitacion $resultado debe ser el elemento obtenido por WS
     */
    protected function comparar_estructura($resultado,$model='Habilitacion')
    {
        $struct = \recurso_habilitaciones::_get_modelos()[$model];
        foreach ($struct as $key => $value) {
            $this->assertArrayHasKey($key, $resultado);
        }
    }
    
}
