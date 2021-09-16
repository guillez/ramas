<?php
namespace Kolla\Test\conceptos;

use Kolla\Test\rest\conceptos\concepto_test;
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;

/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 */
class put_conceptoTest extends concepto_test{
    
    /**
     * Crear un concepto con valores correctos
     */
    function test_put_ok_1()
    {
        $new = $this->do_request(  '/conceptos/' . garbage_data::$concepto_individual['concepto'],  
                                garbage_data::$unidad_gestion, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_individual, // Datos del Concepto
                                $status = status::$CREATED, 
                                $method = 'PUT'
                            );
       $this->assertInternalType('array',$new); // Valido que sea un
       $this->assertArrayHasKey('concepto', $new);// Valido que tenga el id del concepto
    }
    
    /**
     * Crear un concepto cuyo id ya existe (modificarlo) por lo que se actualice el mismo
     */
    function test_put_ok_2()
    {
        $new = $this->do_request(  '/conceptos/' . garbage_data::$concepto_individual['concepto'],  
                                garbage_data::$unidad_gestion, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_modificado, // Datos del Concepto
                                $status = status::$NO_CONTENT, 
                                $method = 'PUT'
                            );
        $this->assertEquals("", $new);
        
        
    }
    
    /**
     * Crear un concepto sin pasarle como parametro la unidad de gestion
     */
    function test_put_fail_3()
    {
        $this->do_request(  '/conceptos/' . garbage_data::$concepto_individual['concepto'],  
                                [],// Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_individual, // Datos del Concepto
                                $status = status::$BAD_REQUEST, 
                                $method = 'PUT'
                            );
    }
    
    /**
     * Crear un cocepto sin pasarle como parametro el id del concepto
     */
    function test_put_fail_4()
    {
        $this->markTestSkipped("Skip for legibilidad");
        $this->do_request(  '/conceptos/',  
                                [],// Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_individual, // Datos del Concepto
                                status::$ERROR_SERVER, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto (que no existe) sin pasarle como parametro las descripcion del concepto
     */
    function test_put_fail_5()
    {
        $this->do_request(  '/conceptos/'. garbage_data::$concepto_incompleto['concepto'],  
                                garbage_data::$unidad_gestion,// Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_incompleto, // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto sin pasarle ningun parametro
     */
    function test_put_fail_6()
    {
        $this->do_request(  '/conceptos/'. garbage_data::$concepto_individual['concepto'],  
                                [],// Unidad de Gestion a la cual se asociara al concepto
                                [], // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto pasando como parametro una Unidad de Gestion que no existe en el sistema
     */
    function test_put_fail_9()
    {
        $this->do_request(  '/conceptos/' . garbage_data::$concepto_individual['concepto'],  
                                garbage_data::$unidad_gestion_error, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_individual, // Datos del Concepto
                                status::$NOT_FOUND, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto cuyo id ya existe (modificarlo) pero no esta asociada a la unidad de gestion
     */
    function test_put_ok_10()
    {
         $this->do_request(  '/conceptos/' . garbage_data::$concepto_modificado['concepto'],  
                            garbage_data::$unidad_gestion_otro, // Unidad de Gestion a la cual se asociara al concepto
                            garbage_data::$concepto_modificado, // Datos del Concepto
                            status::$CREATED, 
                            'PUT'
                        );
         //No se valida los resultados retornado porque fueron verificados en el primer test.
    }
    
    /**
     * Crear un concepto pasando como parametro un tipo de id  para la Unidad Gestion incorrecto
     */
    function test_put_fail_11()
    {
        $this->do_request(  '/conceptos/' . garbage_data::$concepto_individual['concepto'],  
                                garbage_data::$unidad_gestion_invalido, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_individual, // Datos del Concepto
                                status::$NOT_FOUND, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto cuyo id sobrepase el limite de caracteres
     */
    function test_put_fail_12()
    {
        $this->do_request(  '/conceptos/' . garbage_data::$concepto_overflow['concepto'],  
                                garbage_data::$unidad_gestion, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_overflow, // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );
    }
    
    /**
     * Crear un concepto pasando como parametro un tipo de id para la Unidad Gestion incorrecto
     */
    function test_put_fail_13(){
        /*$this->do_request(  '/conceptos/' . garbage_data::$concepto_initial['concepto'],  
                                garbage_data::$unidad_gestion_otro, // Unidad de Gestion a la cual se asociara al concepto
                                garbage_data::$concepto_initial_modificado, // Datos del Concepto
                                status::$BAD_REQUEST, 
                                'PUT'
                            );*/
    }
    
    /**
     * Crear tres conceptos correctamente con la misma unidad de gestion
     */
    function test_put_ok_14()
    {
        $result = $this->do_request('/conceptos/masivo', garbage_data::$unidad_gestion, garbage_data::$conceptos, status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Enviar como parametro un arreglo vacio de Conceptos
     */
    function test_put_ok_15()
    {
        $result = $this->do_request('/conceptos/masivo', garbage_data::$unidad_gestion, [], status::$NO_CONTENT, 'PUT');
        $this->assertEmpty($result);
    }
    
    /**
     * Crear tres conceptos cuyo identificador de Unidad de Gestion no exista
     */
    function test_put_fail_16()
    {
        $result = $this->do_request('/conceptos/masivo', garbage_data::$unidad_gestion_invalido, garbage_data::$conceptos, status::$NOT_FOUND, 'PUT');
        
        $this->assertInternalType('array',$result); // Valido que sea un
        $this->assertEquals(count($result), count(garbage_data::$conceptos));//Valido que ningun concepto se haya modificado/insertado        
    }
    
    /**
     * Crear tres conceptos donde uno Concepto tenga al menos un dato incorrecto
     */
    function test_put_fail_17()
    {
        $result = $this->do_request('/conceptos/masivo', garbage_data::$unidad_gestion, garbage_data::$conceptos_invalido, status::$BAD_REQUEST, 'PUT');
        
         $this->assertInternalType('array',$result); // Valido que sea un
         $this->assertEquals(count($result), 1);//Valido que ningun concepto se haya modificado/insertado        
    }
    
    /**
     * Crear tres conceptos sin pasarle como parametro el id de la Unidad de Gestion
     */
    function test_put_fail_18()
    {
        $this->do_request('/conceptos/masivo', [], garbage_data::$conceptos, status::$BAD_REQUEST, 'PUT');
    }
    
    /**
     * Crear tres conceptos pasando como parametro un arreglo 
     * de conceptos mal formados.
     */
    function test_put_fail_19()
    {
        $result = $this->do_request('/conceptos/masivo', garbage_data::$unidad_gestion, garbage_data::$conceptos_invalido_todos, status::$BAD_REQUEST, 'PUT');
        
         $this->assertInternalType('array',$result); // Valido que sea un arreglo
         $this->assertArrayHasKey('errores', $result);// Valido que tenga los errores
         $this->assertEquals(count($result["errores"]), count(garbage_data::$conceptos_invalido_todos));//Valido que ningun concepto se haya modificado/insertado  
    }
    
    function test_beforeTearDown()
    {
        $sql = " DELETE FROM sge_concepto WHERE concepto<>102;";
        \toba::db()->consultar($sql);
    }
    
    
    
  
}
