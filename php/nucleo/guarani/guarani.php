<?php

class guarani 
{
    protected $cliente;
    protected $url;
    protected $auth_tipo;
    protected $auth_usuario;
    protected $auth_password;
            
    function __construct($url, $usuario, $password, $tipo='Basic')
    {
        $this->url              = $url;
        $this->auth_usuario     = $usuario;
        $this->auth_password    = $password;
        $this->auth_tipo        = $tipo;
    }
    
    public function get_graduados($ua)
	{

            $response = $this->get_cliente_rest()
                    ->get('graduado',
                            array('query' => array('ua' => $ua))
                    );

            $this->validar_response($response, 200, __FUNCTION__);
            //return rest_decode($response->json());
            return rest_decode($response->getBody()->__toString());
	}
    
    public function get_titulo($ua)
    {
        $response = $this->get_cliente_rest()
			->get('titulo',				
                                array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_carrera($ua)
    {
        $response = $this->get_cliente_rest()
			->get('carrera',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_institucion($ua)
    {
        $response = $this->get_cliente_rest()
			->get('institucion',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_respacad($ua)
    {
        $response = $this->get_cliente_rest()
			->get('respacad',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_carrera_titulo($ua)
    {
        $response = $this->get_cliente_rest()
			->get('carrera_titulo',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_respacad_carrera($ua)
    {
        $response = $this->get_cliente_rest()
			->get('respacad_carrera',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    public function get_respacad_titulo($ua)
    {
        $response = $this->get_cliente_rest()
			->get('respacad_titulo',
				array('query' => array('ua' => $ua),)
			);
        $this->validar_response($response, 200, __FUNCTION__);
        //return rest_decode($response->json());
        return rest_decode($response->getBody()->__toString());
    }
    
    /**
	 * @return \Guzzle\Service\Client
	 */
	protected function get_cliente_rest()
	{
            if( !isset($this->cliente) ) {
                $opciones = array(
                'to'            => $this->url,
                'auth_tipo'     => $this->auth_tipo,
                'auth_usuario'  => $this->auth_usuario,
                'auth_password' => $this->auth_password
                );
            
                $cliente = toba::servicio_web_rest('guarani', $opciones);
		        $this->cliente = $cliente->guzzle();
		        $this->cliente = $cliente->guzzle();
            }
            return $this->cliente;
	}

	protected function validar_response($response, $status, $desc_error)
    {
        if ( $response->getStatusCode() != $status) {
                toba::logger()->error('Error en '. $desc_error . ". Se esperaba $status y se obtuvo {$response->getStatusCode()}");
                toba::logger()->var_dump(rest_decode($response->json()));
                throw new toba_error($this->get_mensaje_descripcion($response));
        }
    }
    
    public function get_mensaje_descripcion($guzzle_res)
	{
		$response = rest_decode($guzzle_res->json());
		if ( isset($response['descripcion']) ) {
			$mje = $response['descripcion'];
		} else {
			if ( isset($response['errores']) && isset($response['errores'][0]['error']) ) {
				$mje = $response['errores'][0]['error']; //muestro el primer error
			} else {
				$mje = 'Error en la comunicación con Guarani';
			}
		}
		return $mje;
	}
}
