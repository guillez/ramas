<?php
namespace Kolla\Test\base;

use SIUToba\rest\http\request_memoria;
use SIUToba\rest\rest;
use SIUToba\rest\seguridad\rest_usuario;

class kolla_suite_test extends \PHPUnit_Extensions_Database_TestCase
{

	/**
	 * @var \toba_rest $app instancia de Toba rest
	 */
	private $app; 
	/**
	 * @var unknown $autenticador
	 */
	private $autenticador;
	private $autorizador;
	
	public function setupRest()
	{
		if(!isset($this->app)){
			$tr = new \toba_rest();
			$app = $tr->instanciar_libreria_rest();
			$tr->configurar_libreria_rest($app);
			$this->app = $app;
		}
		return $this->app;
	}
	
	public  function ejecutar($metodo, $ruta, $get = array(), $post = array(), $headers = array())
	{
		if(strpos($ruta, '?') !== false){
			throw new \Exception("Pasar los parametros del get en el tercer parámetro");
		}
		$host = \toba_rest::url_rest() . $ruta;
		$app = $this->setupRest();
		$this->mock_vista_no_escribir($app);
		$mock_request = new request_memoria($metodo, $host, $get, $post, $headers);
		$app->request = $mock_request;
		$app->procesar();
		return $app->response();
	}
	
	public function mock_autenticador(rest_usuario $user, rest $app)
	{
		$this->autenticador = $this->getMockBuilder('SIUToba\rest\seguridad\proveedor_autenticacion')
		->disableOriginalConstructor()
		->getMockForAbstractClass();
	
		$this->autenticador
		->expects($this->any())
		->method('get_usuario')
		->will($this->returnValue($user));
		$app->autenticador = $this->autenticador;
	}
	
	public function mock_autorizador($autorizar, rest $app)
	{
		$this->autorizador = $this->getMockBuilder('SIUToba\rest\seguridad\proveedor_autorizacion')
		->disableOriginalConstructor()
		->getMockForAbstractClass();
		$this->autorizador
		->expects($this->any())
		->method('tiene_acceso')
		//->with($this->equalTo($usuario))
		->will($this->returnValue($autorizar));
		$app->autorizador = $this->autorizador;
	}
	
	public function mock_vista_no_escribir(rest $app)
	{
		$vista = $this->getMockBuilder('SIUToba\rest\http\vista_json')
		->disableOriginalConstructor()
		->getMock();
		$vista
		->expects($this->once())
		->method('escribir')
		->will($this->returnValue(''));
		$app->vista = $vista;
	}
	
	
	/**
	 * Establece la configuración para realizar la conexión frente a la base de datos
	 * que utiliza PHPDbUnit para la ejecución de los casos de test
	 */
	protected function getConnection() {
	
		$pdo = new \PDO("pgsql:host=localhost;dbname=toba_2_6", 'postgres', "postgres");
		$connection = $this->createDefaultDBConnection($pdo, "kolla_test");
		return $connection;
	}
	
	/**
	 * Función que establece la clase de tratado frente a los datos que existen actualmente
	 * en la base de datos. 
	 * @uses Actualmente se utiliza la operación replace ya que es más rapida que otras.
	 * @see http://dbunit.sourceforge.net/components.html
	 */
	protected function getSetUpOperation() {
	
		return 
					new \PHPUnit_Extensions_Database_Operation_Composite(
																												array(
																															new \PHPUnit_Extensions_Database_Operation_Replace(),
																														)
																											);
	}
	
	/**
	 * Función que especifica el path del archivo de donde se obtendran los datos
	 * que se utilizarán como semilla al momento de inicializar la base de datos.
	 * @return PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet
	 */
	protected function getDataSet() {
		$rt = $this->createFlatXMLDataSet(\toba::proyecto()->get_path()."/test/data/seed.xml");
		return $rt;
	
	}
}