<?php
namespace Kolla\Test\habilitaciones;

use Kolla\Test\rest\habilitaciones\habilitacion_test;
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;


/**
 * Description of get_habilitacionTest
 *
 * @author ptoledo
 */
class get_habilitacionTest extends habilitacion_test
{
    /***************************************************/
    /*              Listado de Habilitaciones         			    */
    /***************************************************/
    function test_ok_1()
    {
        $result = $this->do_request('/habilitaciones', garbage_data::$unidad_gestion);
        
        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que el tamaño sea el mismo
        $this->assertEquals(1, count($result));       
    }
    
    function test_fail_2()
    {
        $this->do_request('/habilitaciones', garbage_data::$unidad_gestion_error,  status::$NOT_FOUND);
    }
    
    function test_fail_3()
    {
        $this->do_request('/habilitaciones', [],  status::$BAD_REQUEST);
    }
    
    function test_fail_4()
    {
        $result = $this->do_request('/habilitaciones', garbage_data::$unidad_gestion_otro,  status::$OK);
        
        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$result);
        //Valido que el tamaño sea el mismo
        $this->assertEmpty($result);   
    }
    
    /***************************************************/
    /*             Habilitaciones                      */
    /***************************************************/
    
    function test_fail_5()
    {
        $object = $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"],
                                garbage_data::$unidad_gestion,  
                                status::$OK);
        //Valido que lo retornado sea un arreglo
        $this->assertInternalType('array',$object);
        $this->comparar_estructura($object);
    }
    
    function test_fail_6()
    {
        $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"],
                        garbage_data::$unidad_gestion_error,  
                        status::$NOT_FOUND);
        
    }
    
     function test_fail_7()
    {
        $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"],
                        [],  
                        status::$BAD_REQUEST);
        
    }
    
    function test_fail_8()
    {
        $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"],
                        garbage_data::$unidad_gestion_otro,  
                        status::$NOT_FOUND);
        
    }
    
    function test_fail_9()
    {
        $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"],
                       	garbage_data::$unidad_gestion,  
                        status::$NOT_FOUND);
        
    }
    
    function test_fail_10()
    {
        $this->markTestSkipped();
        $this->do_request('/habilitaciones/',
                        garbage_data::$unidad_gestion,  
                        status::$ERROR_SERVER);
    }

    function test_fail_11()
    {
        $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion2"],
                    garbage_data::$unidad_gestion_otro,  
                    status::$NOT_FOUND);
        
    }
    
    function test_fail_12()
    {
        $this->markTestSkipped();
        $this->do_request('/habilitaciones/',
                        [],  
                        status::$ERROR_SERVER);
    }
    
    /***************************************************/
    /*              Listado de Formularios             */
    /***************************************************/
    /**
     * Ingresar un valor alfanumerico correcto de habilitacion y no ingresar valor de UG
     */
    function test_fail_13()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        [],
                        status::$BAD_REQUEST);
    }
    
    /**
     * Ingresar un valor alfanumerico correcto de habilitacion e 
     * ingresar un valor de UG inexistente
     */
    function test_fail_14()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        garbage_data::$unidad_gestion_error,
                        status::$NOT_FOUND);
    }
    /**
     * Ingresar un valor alfanumerico correcto de habilitacion y 
     * un valor de UG de otro usuario
     */
    function test_fail_15()
    {
       $result = $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        garbage_data::$unidad_gestion_otro,
                        status::$OK);
       $this->assertEmpty($result);   
    }
    
    /**
     * Ingresar un valor alfanumerico correcto de habilitacion y
     * un valor vacio de UG
     */
    function test_fail_16()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        ['unidad_gestion'=>''],
                        status::$BAD_REQUEST);
    }
    /**
     * Ingresar un valor alfanumerico correcto de habilitacion y 
     * un valor negativo de UG
     */
    function test_fail_17()
    {
        $this->do_request(  '/habilitaciones/'.\garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        ['unidad_gestion'=>-1],
                        status::$NOT_FOUND);
    }
    
    /**
     * Ingresar un valor incorrecto de habilitacion y un valor nulo de UG
     */
    function test_fail_18()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                        ['unidad_gestion'=>null],
                        status::$BAD_REQUEST);
    }
    
    /**
     * Ingresar un valor incorrecto de habilitacion y un valor inexistente de UG
     */
    function test_fail_19()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                        garbage_data::$unidad_gestion_error,
                       status::$NOT_FOUND);
    }
    /**
     * Ingresar un valor incorrecto de habilitacion y un valor 
     * de UG de otro usuario
     */
    function test_fail_20()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                       garbage_data::$unidad_gestion_error,
                       status::$NOT_FOUND);
    }
    
    /**
     * Ingresar un valor incorrecto de habilitacion y un valor de 
     * cadena vacia para UG
     */
    function test_fail_21()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                        ['unidad_gestion'=>''],
                        status::$BAD_REQUEST);
    }
    
    /**
     * Ingresar un valor incorrecto de habilitacion y un valor negativo de UG
     */
    function test_fail_22()
    {
        $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                        ['unidad_gestion'=>-1],
                        status::$NOT_FOUND);
    }
    
    /**
     * Ingresar un valor correcto de habilitacion y un valor correcto de UG
     */
    function test_ok_23()
    {
        $result = $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$OK);
        foreach ($result as $formulario) {
            $this->comparar_estructura($formulario,'Formulario');
        }
    }
    
    /**
     * Ingresar un valor de habilitacion que sea un string de solo numero y 
     * un valor correcto de UG
     */
    function test_ok_24()
    {
        $result = $this->do_request(  '/habilitaciones/'."1555465".'/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$OK);
        $this->assertEmpty($result);   
    }
    
    /**
     * Ingresar un valor negativo de habilitacion y un valor correcto de UG
     */
    function test_ok_25()
    {
        $result = $this->do_request(  '/habilitaciones/'."-1".'/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$OK);
        $this->assertEmpty($result);   
    }
    
    /**
     * Ingresar un valor de habilitacion perteneciente a otro usuario y 
     * un valor correcto de UG
     */
    function test_ok_26()
    {
        $result = $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion2"].'/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$OK);
        $this->assertEmpty($result);   
    }
    /**
     * Ingresar un valor de habilitacion que no exista en BD y un valor correcto de UG
     */
    function test_ok_27()
    {
        $result = $this->do_request(  '/habilitaciones/'.garbage_data::$habilitacion_error["habilitacion"].'/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$OK);
        $this->assertEmpty($result);   
    }
    /**
     * No ingresar un valor de habilitacion y un valor correcto de UG
     */
    function test_ok_28()
    {
    	$this->markTestSkipped();
        $result = $this->do_request(  '/habilitaciones/formularios', 
                        garbage_data::$unidad_gestion,
                        status::$BAD_REQUEST);
          
    }
}
