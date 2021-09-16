<?php
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;
use Kolla\Test\rest\elementos\elemento_test;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class get_elementoTest extends elemento_test{
    
    /**
     * Obtener listado de elementos de una Unidad de Gestion 
     * perteneciente al usuario de la sesion
     */
    function test_list_ok_1()
    {
		$result = $this->do_request('/elementos', garbage_data::$unidad_gestion,[], status::$OK,"GET");

		//Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que el tamaño sea el mismo
        $this->assertEquals(count(garbage_data::$concepto_initial), count($result));
        
        foreach ($result as $key => $value)
        {
            $this->comparar_estructura($value);
        }
	}
    
    /**
     * Obtener listado de elementos de una Unidad de Gestion que no 
     * pertenece al sistema en general
     */
    function test_list_fail_2()
    {
        $this->do_request('/elementos', garbage_data::$unidad_gestion_error,[], status::$NOT_FOUND,"GET");
    }
    
    /**
     * Obtener listado de elementos sin pasarle como parametro el 
     * identificador de la Unidad de Gestion
     */
    function test_list_fail_3()
    {
        $this->do_request('/elementos', [],[],status::$BAD_REQUEST, "GET");
    }
    
    /**
     * Obtener listado de elementos de un sistema que no esta asociado
     * al usuario de la sesion
     */
    function test_list_fail_4()
    {
        $result = $this->do_request('/elementos', garbage_data::$unidad_gestion_otro,[], status::$OK,"GET");

        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que retorne un arreglo vacio
        $this->assertEmpty($result);
    }

    /**
     * Obtener un elemento para una Unidad de Gestion pertenenciente
     * al usuario de la sesion
     */
	function test_get_ok_5()
	{
		$result = $this->do_request('/elementos/' . garbage_data::$elemento_initial["elemento"], garbage_data::$unidad_gestion,[], status::$OK,"GET");
        
        //Valido que sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que la estructura recibida sea la del concepto
        $this->comparar_estructura($result);    
	}
    /**
     * Obtener un elemento de una Unidad de Gestion que no pertenece 
     * al sistema en general
     */
    function test_get_fail_6()
    {
        $this->do_request('/elementos/' . garbage_data::$elemento_initial["elemento"], garbage_data::$unidad_gestion_invalido,[], status::$NOT_FOUND,"GET");
    }
    
    /**
     * Obtener un elemento con un identificador externo que no pertenece
     * a ninguno concepto
     */
    function test_get_fail_7()
    {
        $this->do_request('/elementos/' . garbage_data::$elemento_error["elemento"], garbage_data::$unidad_gestion,[], status::$NOT_FOUND,"GET");
    }
    
    /**
     * Obtener un elemento sin pasarle como parametro el identificador
     * de la Unidad de Gestion
     */
    function test_get_fail_8()
    {
        $this->do_request('/elementos/' . garbage_data::$elemento_initial["elemento"], [], [],status::$BAD_REQUEST, "GET"); 
    }
}