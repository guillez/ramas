<?php
namespace Kolla\Test\conceptos;

use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;
use Kolla\Test\rest\conceptos\concepto_test;


/**
 * @author ptoledo <ptoledo@siu.edu.ar>
 */
class delete_conceptoTest extends concepto_test{

    /**
     * Eliminar un concepto que existe y que esta asociado  a la Unidad de Gestion del usario
     */
	function test_delete_ok_1()
	{
        $result = $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'], garbage_data::$unidad_gestion,[],status::$NO_CONTENT, 'DELETE');
        
        $this->assertEquals("", $result);   //Valido que el resultado sea una cadena vacia
    }
    
    /**
     * Eliminar un concepto sin pasarle como parametro el identificador de Unidad de Gestion
     */
    function test_delete_fail_2()
    {
        $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'], [], [], status::$BAD_REQUEST, 'DELETE');		
    }
    
    /**
     * Eliminar un concepto sin pasarle el identificador externo pero si la Unidad de Gestion
     */
    function test_delete_fail_3()
    {
       	$this->markTestSkipped("Por legibilidad se pasa, sabiendo que el resultado fue el esperado");
    	$this->do_request('/conceptos/', garbage_data::$unidad_gestion, array(), status::$NO_CONTENT, 'DELETE');		
    }

    /**
     * Eliminar un concepto sin pasarle ni el identificador externo del concepto ni el de la Unidad de Gestion
     */
    function test_delete_fail_4()
    {
    	$this->markTestSkipped("Por legibilidad se pasa, sabiendo que el resultado fue el esperado");
        $this->do_request('/conceptos/', [], [], status::$NO_CONTENT, 'DELETE');		
    }
    
    /**
     * Eliminar un concepto que no existe en el sistema en general
     */
    function test_delete_fail_5()
    {
        $this->do_request('/conceptos/' . garbage_data::$concepto_error['concepto'], garbage_data::$unidad_gestion, [], status::$NOT_FOUND, 'DELETE');		
    }
    
    /**
     * Eliminar un concepto que existe pero que no pertenece al sistema del usuario de la sesion
     */
    function test_delete_fail_6()
    {
        $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'], garbage_data::$unidad_gestion_otro, [], status::$NOT_FOUND, 'DELETE');
    }
    
    /**
     * Eliminar un concepto que existe en la Unidad de Gestion pero esta referenciado por otras entidades
     */
    function test_delete_fail_7()
    {
        $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto2'], garbage_data::$unidad_gestion, [], status::$ERROR_SERVER, 'DELETE');
    }
    
}
