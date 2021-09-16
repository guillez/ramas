<?php
include("basetest_habilitaciones.php");
class test_habilitaciones_local extends basetest_habilitaciones
{

	static function get_descripcion()
	{
		return 'Habilitacion Encuesta- se prueba la implementación, no el ws';
	}

	function atest_vacios(){
		$params = array (
		  'fecha_desde' => '2012-12-17',
		  'fecha_hasta' => '2012-12-31',
		  'anonima' => 'S',
		  'habilitacion' => 33,
		  'elementos' => 
		  array (  ),
		  'formularios' => 
		  array (	  ),
		);
		$mje = 'crear una habilitacion nueva';
		$r = $this->init($mje, $params, 1, 'ge_sistema_externo');
		
		$this->end($r);
	}
	
	function test_habilitar_nuevo()
	{
		$id_form1 = 'fisica1';
		
		$elementos = array( 
                    array('id_ex' => 5, 'dsc' => 'Otra genérica'),
                    array('id_ex' => 'id_it6', 'dsc' => 'Fisica 1-modif'),
                    //array('id_ex' => 'id_it7', 'dsc' => 'Jordan Michael', 'url' => "http://upload.wikimedia.org/wikipedia/commons/b/b3/Jordan_Lipofsky.jpg"),
                    //array('id_ex' => 'id_it8', 'dsc' => 'Echarry Pablo', 'url' => "http://upload.wikimedia.org/wikipedia/commons/3/3f/Pabloecharri.JPG"),
                    
                    
		);
		$params = array(
			 //   'habilitacion'=> 16,
			//	'debug' => 's',
				'encuesta' => 104,
				'fecha_desde' => '2014-08-01',
				'fecha_hasta' => '2015-08-01',
		//		'paginado' => 'N',
		//		'estilo' => '2',
				'anonima' => 'N',
				'elementos'   => $elementos,
                                'unidad_gestion' => 'BIOLOGIA',
				'formularios' => array(
					array(
						'id_ex' => $id_form1,
					//	'eliminar' => '',
						'dsc' => 'Otra genérica',
						'enc_elemento' => array(
									array(104, null), //encabezado
 									//array(102, 'null'),
//                                     array(3, ''),
								//	array(7, null),
									) 
						),
/*					array(
						'id_ex' => 'fisica5',
						//'eliminar' => '',
						'dsc' => 'Segundo',
						'enc_elemento' => array(
								array(101, 'id_it8'), //encabezado
								array(104, 'id_it6'),
								array(103, 'id_it8'),
								)
								//	array(7, null),
						),
*/					),
		);
		$mje = 'crear una habilitacion nueva';
		$r = $this->init($mje, $params, 1);
		
		$this->end($r);
		
		$id_hab = $r['id_hab'];
		$pass_hab = $r['password'];
		$form = $id_form1;
		$cui = date("His").rand(1,1000000);
		
        $hash = url_encuestas::get_token_seguridad($pass_hab, $id_hab, $form, $cui);
		
		$servidor = 'http://' . $_SERVER['SERVER_NAME'];
		$servidor .= texto_plano($_SERVER["PHP_SELF"]);
	   // $servidor = "http://ec-149.zenplus.com.ar/kolla/3.2/aplicacion.php";
		echo url_encuestas::gen_url($servidor, $id_hab, $hash);
		
		
		
	}
	
	function atest_habilitar1()
	{
		$elementos = array( array('id_ex' => '1', 'dsc' => 'mi_elemento1'),
				array('id_ex' => '2', 'dsc' => 'mi_elemento2')
		);
		$params = array(
				//  'habilitacion'=> 99,
				'debug' => 'N',
				'encuesta' => 2,
				'fecha_desde' => '2019-05-11',
				'fecha_hasta' => '2019-09-20',
				'paginado' => 'N',
				'estilo' => '2',
				'anonima' => 'N',
				'concepto' => array(
						'id_ex' => 'concepto',
						'dsc' => 'mi_concepto_',
						'elemento' => $elemento
				),
		);
		$mje = 'crear una habilitacion nueva';
		$r = $this->init($mje, $params, 1, 'ge_sistema_externo');
	
		$this->end($r);

	}
	function atest_habilitar2()
	{
		$elemento = array('id_ex' => '977', 'dsc' => 'mi_elemento97');
		$params = array(
				//  'habilitacion'=> 99,
				'debug' => 'S',
				'encuesta' => 1,
				'fecha_desde' => '2019-05-11',
				'fecha_hasta' => '2019-09-20',
				'paginado' => 'S',
				'estilo' => '1',
				'anonima' => 'S',
				'concepto' => array(
						'id_ex' => 'jkop7',
						'dsc' => 'mi_concepto_',
						'elemento' => $elemento
				),
		);
		$mje = 'con 1 solo elemento';
		$r = $this->init($mje, $params, 1, 'ge_sistema_externo');
	
		$this->end($r);
	}

}
?>