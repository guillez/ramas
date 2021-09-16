<?php
namespace Kolla\Test\habilitaciones;

use Kolla\Test\data\garbage_data;
use Kolla\Test\rest\habilitaciones\habilitacion_test;
use Kolla\Test\helpers\status;


/**
 * TestSuite que permite realizar testeo de los diferentes parametros que recibe el
 * servicio web. En esta clase se realizan casos de test para la operaci�n que permite
 * la eliminaci�n de un formulario dentro de una habilitaci�n para una unidad de gesti�n.
 * 
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 * @example DELETE /habilitacion/{id_habilitacion}/formularios/{id_formulario}
 */
class delete_habilitacionTest extends habilitacion_test
{

	/**
	 * Eliminaci�n correcta de un formulario
	 */
	function test_delete_ok_1()
	{
		
		$result = $this->do_request('/habilitaciones/'.garbage_data::$habilitacion_initial["habilitacion"].'/formulario/'.garbage_data::$formulario_initial["formulario"],
				garbage_data::$unidad_gestion,
				[],
				status::$NO_CONTENT, 'DELETE');
	}
	
	/**
	 * 
	 */
	function test_delete_fail()
	{
		
	}
	
}