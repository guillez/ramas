<?php

use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;
use Kolla\Test\rest\elementos\elemento_test;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class put_elementoTest extends elemento_test{
    /**
     * Crear un elemento con valores correctos
     */
    function test_put_ok_1()
    {
        $result = $this->do_request(   '/elementos/' . garbage_data::$elemento_individual['elemento'], 
                                    garbage_data::$unidad_gestion, 
                                    garbage_data::$elemento_individual, 
                                    status::$CREATED, 
                                    'PUT');
       $this->assertInternalType('array',$result); // Valido que sea un
       $this->assertArrayHasKey('elemento', $result);// Valido que tenga el id del concepto
    }
    
    /**
     * Crear un elemento cuyo id ya existe (modificarlo)
     *  por lo que se actualice el mismo
     */
    function test_put_ok_2()
    {
        $new = $this->do_request(  '/elementos/' . garbage_data::$elemento_individual['elemento'], 
                                garbage_data::$unidad_gestion, 
                                garbage_data::$elemento_modificado, 
                                status::$NO_CONTENT, 
                                'PUT'); 
        $this->assertEquals("", $new);
    }
    
    /**
     * Crear un elemento sin pasarle como parametro la unidad de gestion
     */
    function test_put_fail_3()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_individual['elemento'], 
                        [], 
                        garbage_data::$elemento_individual, 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
     * Crear un elemento sin pasarle como parametro el id del elemento
     */
    function test_put_fail_4()
    {
        $this->markTestSkipped();
        $this->do_request( '/elementos/', 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$elemento_individual, 
                        status::$ERROR_SERVER, 
                        'PUT');
    }
    
    /**
     * Crear un elemento (que no existe) sin pasarle 
     * como parametro las descripcion del elemento
     */
    function test_put_ok_5()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_incompleto['elemento'], 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$elemento_incompleto, 
                        status::$CREATED, 
                        'PUT');
    }
    
    /**
     * Crear un elemento (que existe) sin pasarle 
     * como parametro las descripcion del elemento
     */
    function test_put_fail_5_1()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_incompleto2['elemento'], 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$elemento_incompleto2, 
                        status::$NO_CONTENT, 
                        'PUT');
    }
    /**
     * Crear un elemento sin pasarle ningun parametro
     */
    function test_put_fail_6()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_incompleto2['elemento'], 
                        [], 
                        [], 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    /**
    * Crear un elemento sin pasarle los datos
    */
    function test_put_ok_6_1()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_incompleto['elemento'], 
                        garbage_data::$unidad_gestion, 
                        [], 
                        status::$NO_CONTENT, 
                        'PUT');
    }
            
    /**
     * Crear un elemento pasando como parametro una Unidad de Gestion que no existe en el sistema
     */
    function test_put_fail_9()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_individual['elemento'], 
                        garbage_data::$unidad_gestion_invalido, 
                        garbage_data::$elemento_individual, 
                        status::$NOT_FOUND, 
                        'PUT');
    }
    
    /*
     * Crear un elemento cuyo id ya existe (modificarlo) pero no 
     * esta asociada a la unidad de gestion
     */
    function test_put_ok_10()
    {
        $this->do_request( '/elementos/' . garbage_data::$elemento_individual['elemento'], 
                        garbage_data::$unidad_gestion_otro, 
                        garbage_data::$elemento_individual, 
                        status::$CREATED, 
                        'PUT');
    }
    
    /**
     * Crear un elemento pasando como parametro un tipo de id 
     * para la Unidad Gestion incorrecto
     */
    function test_put_fail_11(){
        $this->do_request(  '/elementos/' . garbage_data::$elemento_individual['elemento'],  
                                garbage_data::$unidad_gestion_invalido, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$elemento_individual, // Datos del Concepto
                                status::$NOT_FOUND, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un elemento cuyo id sobrepase el limite de caracteres
     */
    function test_put_fail_12()
    {
        $this->do_request(  '/elementos/' . garbage_data::$elemento_overflow['elemento'],  
                                garbage_data::$unidad_gestion, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$elemento_overflow, // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );
    }
    
    /**
     * Crear tres elementos correctamente con la misma unidad de gestion
     */
    function test_put_ok_14()
    {
        $result = $this->do_request('/elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$elementos, status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Enviar como parametro un arreglo vacio de elementos
     */
    function test_put_ok_15()
    {
        $result = $this->do_request('/elementos/masivo', garbage_data::$unidad_gestion, [], status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Crear tres elementos cuyo identificador de Unidad de Gestion no exista
     */
    function test_put_fail_16()
    {
        $result = $this->do_request('/elementos/masivo', garbage_data::$unidad_gestion_invalido, garbage_data::$elementos, status::$NOT_FOUND, 'PUT');
        
        $this->assertInternalType('array',$result); // Valido que sea un
        $this->assertEquals(count($result), count(garbage_data::$elementos));//Valido que ningun concepto se haya modificado/insertado        
    }
    
    /**
     * Crear tres elementos donde un Elemento tenga al menos un dato incorrecto
     */
    function test_put_fail_17()
    {
        $result = $this->do_request('/elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$elementos_invalidos, status::$BAD_REQUEST, 'PUT');
        $this->assertInternalType('array',$result); // Valido que sea un
        $this->assertEquals(count($result), 1);//Valido que ningun concepto se haya modificado/insertado  
    }
    
    /**
     * Crear tres elementos sin pasarle como parametro el id de la Unidad de Gestion
     */
    function test_put_fail_18()
    {
        $this->do_request('/elementos/masivo', [], garbage_data::$elementos, status::$BAD_REQUEST, 'PUT');
        
    }
    
    function test_put_fail_19()
    {
        $result = $this->do_request('/elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$elementos_invalidos_todos, status::$BAD_REQUEST, 'PUT');
    
        $this->assertInternalType('array',$result); // Valido que sea un arreglo
        $this->assertArrayHasKey('errores', $result);// Valido que tenga los errores
        $this->assertEquals(count($result["errores"]), count(garbage_data::$elementos_invalidos_todos));//Valido que ningun concepto se haya modificado/insertado  
    }
    function test_beforeTearDown()
    {
        $sql = "DELETE FROM sge_elemento WHERE elemento<>102";
        \toba::db()->consultar($sql);
    }

}