<?php


class recurso_habilitacionesTest extends kolla_rest_test_case
{

	protected $formularios = [
		[
			//formulario con nombre toma ese nombre
			"formulario"            => "f01",
			"nombre"                => "form1",
			"concepto"              => "01",
			"estado"                => "A",
			"detalle"               => [
				[
					'encuesta' => 1,
					'elemento' => 'e01',
                    'tipo_elemento' => 'TIPO'
				],
				[
					'encuesta' => 2,
					'elemento' => null
				],
				[
					'encuesta' => 3
				]
			],
		],
		[
			//formulario sin nombre toma el nombre del concepto
			"formulario"            => "f02",
			"concepto"              => "c02",
			"estado"                => "B",
			"detalle"               => [
				[
				'encuesta' => 1,
				'elemento' => 'e02'
				]
			],
		],
		[
			"formulario"            => "f03",
			"detalle"               => [
				[
				'encuesta' => 1
				]
			],
		]
	];

	function testCrearHabilitacion()
	{
		$estado = 201;

		$result = $this->POSTApi('/habilitaciones', $this->unidad_gestion, $this->habilitacion, $estado);
		$this->assertArrayHasKey('habilitacion', $result);
		return $result['habilitacion'];
	}

	function testErroresCrear(){
		$estado = 400;
		//espero que devuelva el arreglo de errores
		$result = $this->POSTApi('/habilitaciones', $this->unidad_gestion, array('cualquier' => 'cosa'), $estado);
		$this->assertArrayHasKey('mensaje', $result);
		$this->assertArrayHasKey('descripcion', $result);
		$this->assertArrayHasKey('detalle', $result);
	}

	function testErroresCrear2(){
		$estado = 400;
		//espero que devuelva el arreglo de errores
		$datos = $this->habilitacion;
		unset($datos['fecha_desde']);
		$result = $this->POSTApi('/habilitaciones', $this->unidad_gestion, $datos, $estado);
	}

	/**
	 * @depends testCrearHabilitacion
	 */
	function testGet($id_habilitacion)
	{
		$estado = 200;
		$result = $this->GETApi('/habilitaciones/' . $id_habilitacion, $this->unidad_gestion, $estado);

		$this->assertArrayHasKey('password', $result);
		$this->assertEquals($id_habilitacion, $result['habilitacion']);

		foreach ($this->habilitacion as $key => $value) {
			$this->assertEquals($value, $result[$key]);
		}
		return $result;
	}

	/**
	 * @depends testGet
	 */
	function testGetProtegeUG($habilitacion)
	{
		$ug_trucha = array('unidad_gestion' => '0'); //es otra existente
		$estado = 404;
		$result = $this->GETApi('/habilitaciones/' . $habilitacion['habilitacion'], $ug_trucha, $estado);

	}

	function testGetList()
	{
		$estado = 200;
		$result = $this->GETApi('/habilitaciones', $this->unidad_gestion, $estado);

	}
	/**
	 * @depends testCrearHabilitacion
	 */
	function testGet_formularios_List($id_habilitacion)
	{
		$estado = 200;
		$ruta = '/habilitaciones/' . $id_habilitacion .'/formularios';
		$result = $this->GETApi($ruta, $this->unidad_gestion, $estado);
//
//		$this->assertEquals($id_habilit<acion, $result['habilitacion']);
//
//		foreach ($this->habilitacion as $key => $value) {
//			$this->assertEquals($value, $result[$key]);
//
	}
	/**
	 * @depends testCrearHabilitacion
	 */
	function testPut($id_habilitacion)
	{
		$estado = 204;
		$ruta = '/habilitaciones/' . $id_habilitacion;
		$params = array("paginado" => "N", 'fecha_desde' => '2012-10-10');
		$result = $this->POSTApi($ruta, $this->unidad_gestion, $params, $estado, 'PUT');

		$estado = 200;
		$result = $this->GETApi($ruta, $this->unidad_gestion, $estado);

		$this->assertEquals('N', $result['paginado']);
		$this->assertEquals('2012-10-10', $result['fecha_desde']);
	}

	/**
	 * @depends testGet
	 */
	function testPut_formularios($habilitacion)
	{
		$id_habilitacion = $habilitacion['habilitacion'];
		$password = $habilitacion['password'];
		//no andan porque no se guarda la ug en la habilitacion creo
		$this->probar_formulario($this->formularios[0], $id_habilitacion);
		$this->generar_url($id_habilitacion, $password, $this->formularios[0]['formulario']);

		$this->probar_formulario($this->formularios[1], $id_habilitacion);
		$this->generar_url($id_habilitacion, $password, $this->formularios[1]['formulario']);

		$this->probar_formulario($this->formularios[2], $id_habilitacion);
		$this->generar_url($id_habilitacion, $password, $this->formularios[2]['formulario']);
	}

	/**
	 * @depends testCrearHabilitacion
	 */
	function testPut_formularios__masivo($id_habilitacion)
	{
		$estado = 204;
		$ruta = '/habilitaciones/' . $id_habilitacion ."/formularios/masivo";

		$forms = $this->formularios; //les pongo otros ids
		foreach ($forms as &$f) {
			$f['formulario'] = 'masivo_' . $f['formulario'];
		}

		$result = $this->POSTApi($ruta, $this->unidad_gestion, $forms, $estado, 'PUT');
	}

	/**
	 * @depends testCrearHabilitacion
	 */
	function testDelete_formulario($id_habilitacion)
	{
		$this->assertTrue(true);

	}


	protected function probar_formulario($form, $id_habilitacion)
	{
		$estado = 204;
		$ruta = '/habilitaciones/' . $id_habilitacion ."/formularios/" . $form['formulario'];
		$result = $this->POSTApi($ruta, $this->unidad_gestion, $form, $estado, 'PUT');

		$estado = 200;
		$result = $this->GETApi($ruta, $this->unidad_gestion, $estado);
		if(isset($form['nombre'])){
			$this->assertEquals($form['nombre'], $result['nombre']);
		}
		if(isset($form['estado'])){
			$this->assertEquals($form['estado'], $result['estado']);
		}else{
			$this->assertEquals('A', $result['estado']);
		}
		if(isset($form['concepto'])){
			$this->assertEquals($form['concepto'], $result['concepto']);
		}else {
			$this->assertNull($result['concepto']);
		}

		foreach ($form['detalle'] as $id => $det) {
			$this->assertEquals($det['encuesta'], $result['detalle'][$id]['encuesta']);
			if(empty($det['elemento']) || is_null($det['elemento'])){
				$this->assertNull($result['detalle'][$id]['elemento']);
			}else{
				$this->assertEquals($det['elemento'], $result['detalle'][$id]['elemento']);
			}
		}
	}

	protected function generar_url($hab, $password, $form){
		$cui = date("His").rand(1,1000000);

		$hash = url_encuestas::get_token_seguridad($password, $hab, $form, $cui);

		$servidor = 'http://localhost/kolla';
		// $servidor = "http://ec-149.zenplus.com.ar/kolla/3.2/aplicacion.php";
		echo " \n ########### url formulario $form ###########\n";
		echo url_encuestas::gen_url($servidor, $hab, $hash);
	}
}
