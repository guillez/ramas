<?php

namespace Kolla\Test\rest\tipo_elemento;

use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class get_tipo_elementoTest  extends tipo_elemento_test{
    /**
     * Obtener listado de tipos de elementos de una Unidad de Gestion 
     * perteneciente al usuario de la sesion
     */
    function test_get_ok_1(){
        $result = $this->do_request('/tipo-elementos', garbage_data::$unidad_gestion, [], status::$OK, "GET");

		//Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que el tamaño sea el mismo
        $this->assertEquals(count(garbage_data::$tipo_elemento_initial), count($result));
        
        foreach ($result as $key => $value)
        {
            $this->comparar_estructura($value);
        }
    }

    
    /*
    * Obtener listado de tipo elementos de una Unidad de Gestion que no 
    * pertenece al sistema en general
    */
    function test_list_fail_2()
    {
        $this->do_request('/tipo-elementos', garbage_data::$unidad_gestion_error, [], status::$NOT_FOUND, "GET");
    }
    
    /**
     * Obtener listado de tipo de elementos sin pasarle como parametro el 
     * identificador de la Unidad de Gestion
     */
    function test_list_fail_3()
    {
        $this->do_request('/tipo-elementos', [], [],status::$BAD_REQUEST, "GET");
    }
    
    /**
     * Obtener listado de tipo elementos de un sistema que no esta asociado
     * al usuario de la sesion
     */
    function test_list_fail_4()
    {
        $result = $this->do_request('/tipo-elementos', garbage_data::$unidad_gestion_otro, [], status::$OK, "GET");

        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que retorne un arreglo vacio
        $this->assertEmpty($result);
    }
    
    /**
     * Obtener un tipo de elemento para una Unidad de Gestion pertenenciente
     * al usuario de la sesion
     */
    function test_get_ok_5()
	{
		$result = $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_initial["tipo_elemento"], garbage_data::$unidad_gestion, [], status::$OK, "GET");
        
        //Valido que sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que la estructura recibida sea la del concepto
        $this->comparar_estructura($result);    
	}
    
    /**
     * Obtener un tipo elemento de una Unidad de Gestion que no pertenece 
     * al sistema en general
     */
    function test_get_fail_6()
    {
        $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_initial["tipo_elemento2"], garbage_data::$unidad_gestion_invalido, [], status::$NOT_FOUND, "GET");
    }
    
    /**
     * Obtener un tipo elemento con un identificador externo que no pertenece
     * a ninguno concepto
     */
    function test_get_fail_7()
    {
        $this->do_request('/elementos/' . garbage_data::$tipo_elemento_error["tipo_elemento"], garbage_data::$unidad_gestion, [], status::$NOT_FOUND, "GET");
    }
    
    /**
     * Obtener un tipo elemento sin pasarle como parametro el identificador
     * de la Unidad de Gestion
     */
    function test_get_fail_8()
    {
        $this->do_request('/elementos/' . garbage_data::$tipo_elemento_initial["tipo_elemento"], [], [], status::$BAD_REQUEST, "GET"); 
    }
    
}
