<?php
namespace Kolla\Test\rest\tipo_elemento;

use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;


/**
 * Description of delete_tipo_elementoTest
 *
 * @author ptoledo
 */
class delete_tipo_elementoTest extends tipo_elemento_test {
    /**
     * Eliminar un tipo elemento que existe y que esta asociado
     * a la Unidad de Gestion del usario
     */
    function test_delete_ok_1()
    {
        $result = $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_initial["tipo_elemento"], garbage_data::$unidad_gestion, [], status::$NO_CONTENT, 'DELETE');
         //Valido que el resultado sea una cadena vacia
        $this->assertEquals("", $result);
    }
    
     /**
     * Eliminar un tipo elemento sin pasarle como parametro el  
     * identificador de Unidad de Gestion
     */
    function test_delete_fail_2()
    {
        $this->do_request('/elementos/' . garbage_data::$tipo_elemento_initial["tipo_elemento"], [], [], status::$BAD_REQUEST, 'DELETE');
    }
    
    /**
     * Eliminar un tipo elemento sin pasarle el identificador externo 
     * pero si la Unidad de Gestion
     * @skip Por legibilidad no se implementa para que no muestre el trace
     */
    function test_delete_fail_3()
    {
       // $this->do_request('/tipo-elemento/', garbage_data::$unidad_gestion, array(), status::$NO_CONTENT, 'DELETE');		
    }
    
    /**
     * Eliminar un tipo elemento sin pasarle ni el identificador 
     * externo del elemento ni el de la Unidad de Gestion
     * @skip Por legibilidad no se implementa para que no muestre el trace
     */
    function test_delete_fail_4()
    {
        // $this->do_request('/tipo-elemento/', [], [], status::$NO_CONTENT, 'DELETE');		
    }
    
    /**
     * Eliminar un elemento que no existe en el sistema en general
     */
    function test_delete_fail_5()
    {
        $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_error["tipo_elemento"], garbage_data::$unidad_gestion, [], status::$NOT_FOUND, 'DELETE');
    }
    
    
    /**
     * Eliminar un tipo elemento que existe pero que no pertenece
     * al sistema del usuario de la sesion
     */
    function test_delete_fail_6()
    {
        $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_otro['tipo_elemento'], garbage_data::$unidad_gestion_otro, [], status::$NOT_FOUND, 'DELETE');
    }
    
    /**
     * Eliminar un elemento que existe en la Unidad de Gestion
     * pero esta referenciado por otras entidades
     */
    function test_delete_fail_7()
    {
        $this->do_request('/tipo-elementos/' . garbage_data::$tipo_elemento_initial['tipo_elemento2'], garbage_data::$unidad_gestion, [], status::$ERROR_SERVER, 'DELETE');
        
    }
}
