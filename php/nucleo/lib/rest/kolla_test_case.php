<?php
use SIUToba\rest\http\request_memoria;
use SIUToba\rest\rest;
use SIUToba\rest\seguridad\rest_usuario;

class kolla_test_case extends \PHPUnit_Extensions_Database_TestCase {

	/**
	 * @var rest
	 */
	protected $app;
	protected $autenticador;
	protected $autorizador;

	protected function ejecutar($metodo, $ruta, $get = array(), $post = array(), $headers = array())
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

	protected function setupRest()
	{
		if(!isset($this->app)){
			$tr = new \toba_rest();
			$app = $tr->instanciar_libreria_rest();
			$tr->configurar_libreria_rest($app);
			$this->app = $app;
		}
		return $this->app;
	}

    
	protected function mock_autenticador(rest_usuario $user, rest $app)
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

	protected function mock_autorizador($autorizar, rest $app)
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

	protected function mock_vista_no_escribir(rest $app)
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

    /******************************************************************************/
    /*                  Configuracion de DBUnit                                   */
    /******************************************************************************/
    
    protected function getConnection() {
        
        $pdo = new PDO("pgsql:host=localhost;dbname=toba_2_6", 'postgres', "postgres");
        $connection = $this->createDefaultDBConnection($pdo, "kolla_test");
        
        return $connection;
    }
    
    protected  function getSetUpOperation() {

        return new PHPUnit_Extensions_Database_Operation_Composite(
                array(
                    new PHPUnit_Extensions_Database_Operation_Replace(),
                    //PHPUnit_Extensions_Database_Operation_Factory::DELETE()
                
                )
            );
    }
    
    protected function getDataSet() {
       $rt = $this->createFlatXMLDataSet(toba::proyecto()->get_path()."/test/seeds/seed.xml");
       return $rt;
        
    }
    
   

    

}