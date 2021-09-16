<?php
namespace Kolla\Test\conceptos;

use Kolla\Test\rest\conceptos\concepto_test;
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;

/**
 * Description of recurso_get_conceptos
 *
 * @author Paulo Toledo <ptoledo@siu.edu.ar.ar>
 * @vesion 1.0
 */
class recurso_get_conceptoTest extends concepto_test{
    
    /**
     * Obtener listado de conceptos de una Unidad de Gestion  perteneciente al usuario de la sesion
     */
	function test_list_1()
	{
		$result = $this->do_request('/conceptos', garbage_data::$unidad_gestion, [],  $status = 200, $action = "GET");

        $this->assertInternalType('array',$result);    //Valido que lo retornado sea un arreglo
        $this->assertEquals(count(garbage_data::$concepto_initial), count($result));  //Valido que el tamaño sea el mismo
	}
	
    /**
     * Obtener listado de conceptos de una Unidad de Gestion que no pertenece al sistema en general
     */
    function test_list_fail_2()
    {
        $this->do_request('/conceptos', garbage_data::$unidad_gestion_error, [], $status = status::$NOT_FOUND, $action = 'GET');
    }
    
    /**
     * Obtener listado de conceptos sin pasarle como parametro el identificador de la Unidad de Gestion
     */
    function test_list_fail_3()
    {
        $this->do_request('/conceptos', [], [], $status = status::$BAD_REQUEST, $action = 'GET');
    }
    
    /**
     * Obtener listado de conceptos de un sistema que no esta asociado
     * al usuario de la sesion
     */
    function test_list_fail_4()
    {
        $result = $this->do_request('/conceptos', garbage_data::$unidad_gestion_otro, [], $status = status::$OK ,$action = 'GET');

        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que retorne un arreglo vacio
        $this->assertEmpty($result);
    }
  
    /**
     * Obtener un concepto para una Unidad de Gestion pertenenciente
     * al usuario de la sesion
     */
    function test_get_ok_5()
    {
        $result = $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'],  garbage_data::$unidad_gestion, [], $status = status::$OK, $action= 'GET');
        
        //Valido que sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que la estructura recibida sea la del concepto
        $this->comparar_estructura($result);        
    }

    /**
     * Obtener un concepto de una Unidad de Gestion que no pertenece 
     * al sistema en general
     */
    function test_get_fail_6()
    {
        //Valida que la Unidad de Gestion no existe
        $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'],  garbage_data::$unidad_gestion_error,  [],$status = status::$NOT_FOUND, $action = 'GET');
    }
    
    /**
     * Obtener un concepto con un identificador externo que no pertenece
     * a ninguno concepto
     */
    function test_get_fail_7()
    {
        //Valida que el concepto no existe
        $this->do_request('/conceptos/' . garbage_data::$concepto_error['concepto'],  garbage_data::$unidad_gestion_error, [], $status = status::$NOT_FOUND, $action = 'GET');
    }
    
    /**
     * Obtener un concepto  sin pasarle como parametro el identificador
     * de la Unidad de Gestion
     */
    function test_get_fail_8()
    {
        //Valido que el parametro de Unidad de Gestion exista
        $this->do_request('/conceptos/' . garbage_data::$concepto_initial['concepto'],  [],  [],$status = status::$BAD_REQUEST, $action = 'GET');
    }
    
    /**
     * Obtener un concepto sin pasar como parametro ni el identificador
     * externo del concepto ni la Unidad de Gestion
     * @expectedExceptionCode 500
     * @skip Por legibilidad no se implementa para que no muestre el trace
     */
    
    function test_get_fail_9(){
        //Valido que el parametro de Unidad de Gestion exista
        //$this->GETApi('/conceptos/',  [], $method = 'GET');
    }
}
