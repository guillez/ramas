<?php

//include_once('servicios_web/kolla/constantes_servicios.php');

class basetest_servicios_web extends toba_test
{
	protected $opciones;
	protected $proxy; //servidor
	protected $servicio; //
	
	protected function call($mje, $action, $params)
	{
			echo "<br />Inicio Test $mje<br />";
			$this->opciones = array(
					'useWSA'	=> true,
					//'seguro'    => false, No sirve, si hay un certif se hacer seguro
					'action'	=>	"http://siu.edu.ar/kolla/habilitaciones/$action"
					);
			$this->servicio = toba::servicio_web('habilitaciones', $this->opciones);
			$mensaje = new toba_servicio_web_mensaje($params);
			$this->client = $this->servicio->wsf();
			try{
				$respuesta = $this->servicio->request($mensaje);
				return $respuesta->get_array();
			}catch(Exception $e){
				echo $e->getMessage();
			}
			return array();
	}
	
	protected function end()
	{		
		echo('<br /><textarea>');
		print_r('Request\n' . $this->client->getLastRequest() .'\n fin request.');
		print_r('\nResponse\n' . $this->client->getLastResponse() .'\n FIN');
		echo('</textarea>');
		echo '<br />Fin Test<br />';
	}
		
	static function get_descripcion()
	{
		return 'Servicios web - mediante un cliente ws';
	}
	
	
}
?>