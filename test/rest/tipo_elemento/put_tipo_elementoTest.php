<?php
namespace Kolla\Test\rest\tipo_elemento;

use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class put_tipo_elementoTest  extends tipo_elemento_test{
    /**
     * Crear un tipo elemento con valores correctos
     */
    function test_put_ok_1()
    {
        $result = $this->do_request(   '/tipo-elementos/' . garbage_data::$tipo_elemento_individual["tipo_elemento"], 
                                    garbage_data::$unidad_gestion, 
                                    garbage_data::$tipo_elemento_individual, 
                                    status::$CREATED, 
                                    'PUT');
       $this->assertInternalType('array',$result); // Valido que sea un
       $this->assertArrayHasKey('tipo_elemento', $result);// Valido que tenga el id del concepto
    }
    
    /**
     * Crear un tipo elemento cuyo id ya existe (modificarlo)
     *  por lo que se actualice el mismo
     */
    function test_put_ok_2()
    {
        $new = $this->do_request(  '/tipo-elementos/' . garbage_data::$tipo_elemento_individual['tipo_elemento'], 
                                garbage_data::$unidad_gestion, 
                                garbage_data::$tipo_elemento_modificado, 
                                status::$NO_CONTENT, 
                                'PUT'); 
        $this->assertEquals("", $new);
    }
    
     /**
     * Crear un tipo elemento sin pasarle como parametro la unidad de gestion
     */
    function test_put_fail_3()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_individual['tipo_elemento'], 
                        [], 
                        garbage_data::$tipo_elemento_individual, 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
     * Crear un tipo elemento sin pasarle como parametro el id
     */
    function test_put_fail_4()
    {
        $this->markTestSkipped();
        $this->do_request( '/tipo-elementos/', 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$tipo_elemento_individual, 
                        status::$ERROR_SERVER, 
                        'PUT');
    }
    
    /**
     * Crear un tipo elemento (que no existe) sin pasarle 
     * como parametro las descripcion del tipo de elemento
     */
    function test_put_fail_5()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_incompleto['tipo_elemento'], 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$tipo_elemento_incompleto, 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
     * Crear un tipo elemento (que existe) sin pasarle 
     * como parametro las descripcion del tipo elemento
     */
    function test_put_fail_5_1()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_incompleto2['tipo_elemento'], 
                        garbage_data::$unidad_gestion, 
                        garbage_data::$tipo_elemento_incompleto2, 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
     * Crear un tipo elemento sin pasarle ningun parametro
     */
    function test_put_fail_6()
    {
        $this->do_request( '/elementos/' . garbage_data::$tipo_elemento_incompleto2['tipo_elemento'], 
                        [], 
                        [], 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
    * Crear un tipo elemento sin pasarle los datos
    */
    function test_put_fail_6_1()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_incompleto['tipo_elemento'], 
                        garbage_data::$unidad_gestion, 
                        [], 
                        status::$BAD_REQUEST, 
                        'PUT');
    }
    
    /**
     * Crear un tipo elemento pasando como parametro una Unidad de Gestion que no existe en el sistema
     */
    function test_put_fail_9()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_individual['tipo_elemento'], 
                        garbage_data::$unidad_gestion_invalido, 
                        garbage_data::$tipo_elemento_individual, 
                        status::$NOT_FOUND, 
                        'PUT');
    }
    
    /*
     * Crear un tipo elemento cuyo id ya existe (modificarlo) pero no 
     * esta asociada a la unidad de gestion
     */
    function test_put_ok_10()
    {
        $this->do_request( '/tipo-elementos/' . garbage_data::$tipo_elemento_individual['tipo_elemento'], 
                        garbage_data::$unidad_gestion_otro, 
                        garbage_data::$tipo_elemento_individual, 
                        status::$CREATED, 
                        'PUT');
    }
    
     /**
     * Crear un tipo elemento pasando como parametro un tipo de id 
     * para la Unidad Gestion incorrecto
     */
    function test_put_fail_11(){
        $this->do_request(  '/tipo-elementos/' . garbage_data::$tipo_elemento_individual['tipo_elemento'],  
                                garbage_data::$unidad_gestion_invalido, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$tipo_elemento_individual, // Datos del Concepto
                                status::$NOT_FOUND, 
                                'PUT'
                            );
    }
    
     /**
     * Crear un tipo elemento cuyo id sobrepase el limite de caracteres
     */
    function test_put_fail_12()
    {
        $this->do_request(  '/tipo-elementos/' . garbage_data::$tipo_elemento_overflow['tipo_elemento'],  
                                garbage_data::$unidad_gestion, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$tipo_elemento_overflow, // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );
    }
    
    /**
     * Crear tres tipo elementos correctamente con la misma unidad de gestion
     */
    function test_put_ok_14()
    {
        $result = $this->do_request('/tipo-elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$tipo_elementos, status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Enviar como parametro un arreglo vacio de tipo elementos
     */
    function test_put_ok_15()
    {
        $result = $this->do_request('/tipo-elementos/masivo', garbage_data::$unidad_gestion, [], status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Crear tres tipo elementos cuyo identificador de Unidad de Gestion no exista
     */
    function test_put_fail_16()
    {
        $result = $this->do_request('/tipo-elementos/masivo', garbage_data::$unidad_gestion_invalido, garbage_data::$tipo_elementos, status::$NOT_FOUND, 'PUT');
        
        $this->assertInternalType('array',$result); // Valido que sea un
        $this->assertEquals(count($result), count(garbage_data::$tipo_elementos));//Valido que ningun concepto se haya modificado/insertado        
    }
    
    /**
     * Crear tres tipo elementos donde un Elemento tenga al menos un dato incorrecto
     */
    function test_put_fail_17()
    {
        $result = $this->do_request('/tipo-elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$tipo_elementos_invalidos, status::$BAD_REQUEST, 'PUT');
        $this->assertInternalType('array',$result); // Valido que sea un
        $this->assertEquals(count($result), 1);//Valido que ningun concepto se haya modificado/insertado  
    }
    
    /**
     * Crear tres tipo elementos sin pasarle como parametro el id de la Unidad de Gestion
     */
    function test_put_fail_18()
    {
        $this->do_request('/tipo-elementos/masivo', [], garbage_data::$elementos, status::$BAD_REQUEST, 'PUT');
        
    }
    
    function test_put_fail_19()
    {
        $result = $this->do_request('/tipo-elementos/masivo', garbage_data::$unidad_gestion, garbage_data::$tipo_elementos_invalidos_todos, status::$BAD_REQUEST, 'PUT');
    
        $this->assertInternalType('array',$result); // Valido que sea un arreglo
        $this->assertArrayHasKey('errores', $result);// Valido que tenga los errores
        $this->assertEquals(count($result["errores"]), count(garbage_data::$tipo_elementos_invalidos_todos));//Valido que ningun concepto se haya modificado/insertado  
    }
    
    function test_beforeTearDown()
    {
        $sql = "DELETE FROM sge_tipo_elemento WHERE tipo_elemento<>102";
        \toba::db()->consultar($sql);
    }
}
