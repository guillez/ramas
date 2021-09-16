<?php

//include_once('servicios_web/kolla/constantes_servicios.php');
include("basetest_servicios_web.php");
class test_servicios_web_local extends basetest_servicios_web
{
	
	function test_encuestasDisponibles()
	{
		$params = array('a'=>0);
		$respuesta = $this->call('Test encuestas disponibles', 'encuestasDisponibles', $params);
		ei_arbol($respuesta);
		$this->end();
	}
	
	function atest_encuestasRespondidas()
	{ //outdated
		$datos = array();
		$datos['habilitacion'] = 6;
		$datos['cui'] = '555';
		$datos['elemento'] = '087';
		$respuesta= $this->call('Test encuestas respondidas', 'encuestasRespondidas', $datos);
		ei_arbol($respuesta);
		$this->end();
		//$this->assertTrue(!empty($rta), "No hay respuestas");
	}

	function atest_de_parametros1()
	{
		$elementos = array( 
                    array('id_ex' => 'id_it5', 'dsc' => 'Mr. Guilty', 'url' => "http://img.printfection.com/14/58244/c68GQ.jpg"),
					array('id_ex' => 'id_it6', 'dsc' => 'Fisica 1'),
                    array('id_ex' => 'id_it7', 'dsc' => 'Jordan Michael', 'url' => "http://upload.wikimedia.org/wikipedia/commons/b/b3/Jordan_Lipofsky.jpg"),
                    array('id_ex' => 'id_it8', 'dsc' => 'Echarry Pablo', 'url' => "http://upload.wikimedia.org/wikipedia/commons/3/3f/Pabloecharri.JPG"),
                    
                    
		);
		$params = array(
				//  'habilitacion'=> 99,
				'debug' => 'S',
		//		'encuesta' => 2,
				'fecha_desde' => '2010-05-11',
				'fecha_hasta' => '2019-09-20',
				'paginado' => 'N',
				'estilo' => '2',
				'anonima' => 'N',
				'elementos'   => $elementos,
				'formularios' => array(
					array(
						'id_ex' => 'fisica1',
						'dsc' => 'Evaluación de la catedra de Fisica 1 2012',
						'enc_elemento' => array(
									array(1, 'id_it5'), //encabezado
									array(2, 'id_it6'),
                                    array(3, 'id_it7'),
								//	array(7, null),
									) 
						),
					)
		);
		ei_arbol($params);
		$rta = $this->call('Test habilitacion', 'habilitar', $params);
		ei_arbol($rta);
		$this->end();
		/*	
		$this->assertTrue($info_op->codigo == operacion_log::codigo_creacion, "Operacion debe ser creacion");
		$this->assertTrue($log->estilo->codigo == estilo_log::codigo_seleccionado,
				'Estilo debe ser seleccionado (ver si existe 1 en la tabla)');
		$this->assertTrue($log->encuesta->codigo == encuesta_log::codigo_seleccion,
				'Encuesta no existe (ver que exista una con id=1)');
		$this->assertTrue($log->paginado->codigo == paginado_log::codigo_habilitado, "Paginado deberia ser si");
		$this->assertTrue($rta['anonima']['codigo'] == 2, "Anonima debe ser si");
		 $this->assertTrue($rta['multiple']['codigo'] == 2, "Multiple debe ser si");
		$this->assertTrue($rta['elementos'][0]['codigo'] == 0, "El elementos debe ser nuevo (se asume que no está creado)");
		$this->assertTrue($rta['debug']['codigo'] == 0, "El modo debug debe estar habilitado");
		$this->assertTrue($rta->id_hab > 0, 'Debe devolver una habilitacion');
		$this->assertTrue($rta->url != '', 'Debe devolver una url');*/
	
	}
	function atest_de_parametros2()
	{
		$datos = array();
		$datos['anonima'] = 'N';
		$datos['debug'] = 'S'; 
		$datos['encuesta'] = '2';
		$datos['fecha_desde'] = '2010-05-11';
		$datos['fecha_hasta'] = '2019-09-20';
		$datos['estilo'] = 2;
		ei_arbol($datos);
		$rta = $this->call('Test encuestas respondidas', 'habilitar', $datos);
		ei_arbol($rta);
		$this->end();
		
	}
	
	function atest_de_parametros3(){
		$datos= array(
			'debug'		=> 'S', 
			'encuesta'    => 1,
			'fecha_desde' => '2019-05-11',
			'fecha_hasta' => '2019-09-20',
		);
		ei_arbol($datos);
		$rta = $this->call('Test encuestas respondidas', 'habilitar', $datos);
		ei_arbol($rta);
		$this->end();
		
	}
	
	
	/* OJO que no tiene seteado el debug este metodo
	*/	function /*test_*/ de_parametros4(){
		//echo ("Se asume que existe una encueta con id=1 (sino modificar), y un estilo = 0");
		$opciones = array(
		'action' =>	"http://siu.edu.ar/kolla/habilitaciones/habilitar");
		$servicio = toba::servicio_web('habilitaciones', $opciones);
	
		$elementos = array( array('id_externo' => '977', 'descripcion' => 'mi_elemento97'),
						array('id_externo' => 'po17', 'descripcion' => 'mi_elementopo1')
			  );
		
		$datos= array(
			//  'habilitacion'=> 99,
		//	  'debug'		=> 'N', 
			  'encuesta'    => 1,
	          'fecha_desde' => '2019-05-11',
	          'fecha_hasta' => '2019-09-20',
	          'paginado'    => 'S',
	          'estilo'      => '1',
	          'anonima'     => 'S',
			  'concepto'     => 
					 array( 'id_externo'  => 'jkop7',
							'descripcion'  => 'mi_concepto_',
							'elementos' => $elementos
							),
			  );

		try{
			//$opciones = null;
			$respuesta = $servicio->request(new toba_servicio_web_mensaje($datos));
			$rta = $respuesta->get_array();
			$this->assertTrue($rta['operacion']['codigo'] == 0, "Operacion debe ser creacion");
			$this->assertTrue($rta['estilo']['codigo'] == 1, "Estilo debe ser seleccionado (ver si existe 1 en la tabla)");
			$this->assertTrue($rta['encuesta']['codigo'] == 1, "Encuesta no existe (ver que exista una con id=1)");
			$this->assertTrue($rta['paginado']['codigo'] == 2, "Paginado deberia ser si");
			$this->assertTrue($rta['anonima']['codigo'] == 2, "Anonima debe ser si");
			$this->assertTrue($rta['multiple']['codigo'] == 2, "Multiple debe ser si");
			$this->assertTrue($rta['elementos'][0]['codigo'] == 0, "El elementos debe ser nuevo (se asume que no está creado)");
			$this->assertTrue($rta['elementos'][1]['codigo'] == 0, "El elementos debe ser nuevo (se asume que no está creado)");
			$this->assertTrue($rta['concepto']['codigo'] == 0, "El concepto debe ser nuevo (se asume que no está creado)");
			
		}catch(Exception $e){
			echo $e->getMessage();
			
		}	
		
	}

	
	
}
?>