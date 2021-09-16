<?php 
namespace Kolla\Test\base;

use SIUToba\rest\http\respuesta_rest;
use SIUToba\rest\seguridad\rest_usuario;
use Kolla\Test\data\garbage_data;
use Kolla\Test\helpers\status;



/**
 * @author Paulo Toledo <ptoledo@siu.edu.ar>
 * @version 1.0.0
 * 
 * Clase que encapsula los comportamientos para realizar las peticiones de Web Service.
 * Se utiliza como interfaz entre los Test Suites y quien provee los servicios.
 */
class kolla_base_test extends kolla_suite_test{
	
	/**
	 * Realiza el set up inicial para los casos de test. Inicializa la aplicación junto
	 * con las librerias REST que permiten brindar los servicios, se crea un usuario de prueba y
	 * se hace un mock del autenticador para la aplicación.
	 */
	public function setUp()
	{
		parent::setUp();
		$this->app = null; //regenero todo lo relacionado al server;
		$app = $this->setupRest();
		$user = new rest_usuario();
		$user->set_usuario(garbage_data::$usuario);
		$this->mock_autenticador($user, $app);
	}

	/**
	 * Función que permite realizar una petición hacia un Servicio Web.
	 * En caso que enviar el parametro "status" se realiza un control de la respuesta,
	 * @param string $path URI del Servicio Web que se quiere consultar
	 * @param array $params_get arreglo de parametros que se envian cuando el $action es GET.
	 * @param array $params_post arreglo de parametros que se envian el body de la petición.
	 * @param integer $status entero que representa un código de estado HTTP.
	 * @param string $action Nombre de acción que se quiere realizar {"GET", "POST", "DELETE", "PUT"}
	 * @param array $headers Arreglo de headers adicionales que se envian en la petición
	 */
	public function do_request($path, $params_get = [] , $params_post = [] , $status = 200 , $action = "POST", $headers = [])
	{
		$response = $this->ejecutar($action, $path, $params_get, $params_post,$headers);
		$datos = $this->controlar_respuesta($status, $response);
		return $datos; 
	}
	
	/**
	 * Permite realizar un control sobre la respuesta frente a una petición HTTP.
	 * Si el parametro $status se establece se realiza un control sobre el estado de la petición
	 * frente al estado que se estaba esperando.
	 * @param integer $status entero que representa un código de estado HTTP.
	 * @param respuesta_rest $response
	 */
	private function controlar_respuesta($status, $response)
	{
		$datos = $response->get_data();
		$status_response = $response->get_status();
		if ($status_response > 500) {
			print_r($datos);
		}
		if ($stauts) {
			$this->assertEquals($stauts, $response->get_status());
		}
		return $datos;
	}
	
}
?>