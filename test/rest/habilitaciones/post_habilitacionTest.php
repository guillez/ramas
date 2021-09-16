<?php



require_once 'test/seeds/garbage_data.php';
require_once 'test/seeds/status.php';
class post_habilitacionTest extends habilitacion_test
{
	/**
	 * Creación de habilitación con valores correctos y valor de UG correcto
	 */
	function test_post_ok_1()
	{
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, \garbage_data::$habilitacion_individual, \status::$CREATED);
		
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('habilitacion', $result); // verifico que haya devuelto el id de la habilitacion
		$this->assertInternalType('int',$result["habilitacion"]);// verifico que sea una cadena 
	}
	
	/**
	 * Envió valor correcto de habiltiacion y un valor de UG  que no exista en BD
	 */
	function test_post_fail_2()
	{
		$this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion_error, \garbage_data::$habilitacion_individual, \status::$NOT_FOUND);
	}

	/**
	 * Envió valor correcto de habiltiacion y un valor de UG  que pertenezca a otro usuario
	 */	
	function test_post_ok_3()
	{
		$this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion_otro, \garbage_data::$habilitacion_individual, \status::$CREATED);
	}
	
	/**
	 * Envió valor correcto de habiltiacion y un valor de UG  negativo
	 */	
	function test_post_fail_4()
	{
		$this->POSTApi('/habilitaciones', ['unidad_gestion'=>-1], \garbage_data::$habilitacion_individual, \status::$NOT_FOUND);
	}
	  
	
	/**
	 * Enviar una habilitacion vacia y un correcto valor de UG
	 */
	function test_post_fail_5()
	{
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, [], \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(3, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya primer componente tenga la clave incorrecta y un valor correcto de UG
	 */
	function test_post_fail_6()
	{
		$habilitacion = ['feha_desde'=>'2016-02-01', 'fecha_hasta'=>'2016-02-28','descripcion'=>'Primer habilitacion'];
		
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(2, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya segunda componente tenga la clave incorrecta y un valor correcto de UG
	 */
	function test_post_fail_7()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01', 'fecha_hata'=>'2016-02-28','descripcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(2, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya tercera componente tenga la clave incorrecta y un valor correcto de UG
	 */
	function test_post_fail_8()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01', 'fecha_hasta'=>'2016-02-28','descrispcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(2, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya primer componente tenga el valor incorrecto y un valor correcto de UG
	 */
	function test_post_fail_9()
	{
		$habilitacion = ['fecha_desde'=>'01/01/2016', 'fecha_hasta'=>'2016-02-28','descripcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya segunda componente tenga el valor incorrecto y un valor correcto de UG
	 */
	function test_post_fail_10()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01', 'fecha_hasta'=>'28/02/2016','descripcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	}
	
	/**
	 * Enviar una habilitacion cuya tercera componente tenga el valor incorrecto y un valor correcto de UG
	 */
	function test_post_fail_11()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01', 'fecha_hasta'=>'2016-02-28','descripcion'=>[]];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
		
	}
	
	function test_post_fail_12()
	{
		$habilitacion = ['fecha_hasta'=>'2016-02-28','descripcion'=>'primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	
	}
	
	function test_post_fail_13()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01','descripcion'=>'primera habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	
	}
	
	
	function test_post_fail_14()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-01', 'fecha_hasta'=>'2016-02-28'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('detalle', $result); // verifico que haya devuelto un arreglo con los errores
		$this->assertEquals(1, count($result["detalle"])); // Verifico que retorne los 3 atributos faltantes
	
	}
	
	/**
	 * Test para comprobación entre fechas, la fecha desde debe ser menor o igual a la fecha hasta y un valor correcto de UG
	 */
	function test_post_fail_15()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-28', 'fecha_hasta'=>'2016-02-01','descripcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('descripcion', $result); // verifico que haya devuelto un arreglo con los errores
	}
	
	/**
	 * Test para comprobación de fecha, en este caso, verificar que sea un fecha correcta
	 */
	function test_post_fail_16()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-30', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	
		$this->assertInternalType('array',$result);//verifico que sea un arrego
		$this->assertArrayHasKey('descripcion', $result); // verifico que haya devuelto un arreglo con los errores
	}
	
	/**
	 * Validación del atributo 'paginado'
	 */
	function test_post_fail_17()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','paginado'=>'si'];
		
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);

	}
	
	/**
	 * Validación del atributo 'paginado' 2
	 */
	function test_post_fail_18()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','paginado'=>1];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	
	}
	
	/**
	 * Validación del atributo 'paginado' 3
	 */
	function test_post_fail_19()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','paginado'=>'no'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	
	}
	
	/**
	 * Validación del atributo 'paginado' 4
	 */
	function test_post_fail_20()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','paginado'=>'n'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	
	}
	
	/**
	 * validación del atributo 'anonima'
	 */
	function test_post_fail_21()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','anonima'=>'si'];
		
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	}
	
	/**
	 * validación del atributo 'anonima' 2
	 */
	function test_post_fail_22()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','anonima'=>'s'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	}
	
	/**
	 * validación del atributo 'anonima' 3
	 */
	function test_post_fail_23()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','anonima'=>'no'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	}
	
	/**
	 * validación del atributo 'anonima' 4
	 */
	function test_post_fail_24()
	{
		
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','anonima'=>'n'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	}
	
	/**
	 * Se ingresa un valor correcto del atributo 'Estilo'
	 */
	function test_post_fail_25()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','estilo'=>'0'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$CREATED);
	}
	
	/**
	 * Se ingresa un valor que no exista del atributo 'Estilo'
	 */
	function test_post_fail_26()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','estilo'=>'5'];
		
		
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion,\status::$BAD_REQUEST);
	
	}
	
	/**
	 * Se ingresa un valor negatico del atributo 'Estilo'
	 */
	function test_post_fail_27()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','estilo'=>'-1'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		
	}
	
	/**
	 * Se ingresa un valor de tipo diferente correcto del atributo 'Estilo'
	 */
	function test_post_fail_28()
	{
	
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','estilo'=>'cuatro'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
		
	}
	
	/**
	 * Se ingresa un valor correcto del atributo 'generar_codigo_recuperacion'
	 */
	function test_post_fail_29()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','generar_codigo_recuperacion'=>'S'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$CREATED);
	}
	
	/**
	 * Se ingresa un valor que no exista del atributo 'generar_codigo_recuperacion'
	 */
	function test_post_fail_30()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','generar_codigo_recuperacion'=>'U'];
	
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion,\status::$BAD_REQUEST);
		
	
	}
	
	/**
	 * Se ingresa un valor que sobrepase la longitud del atributo 'generar_codigo_recuperacion'
	 */
	function test_post_fail_31()
	{
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','generar_codigo_recuperacion'=>'ASDASDASDSADSAd'];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	}
	
	/**
	 * Se ingresa un valor de tipo diferente correcto del atributo 'generar_codigo_recuperacion'
	 */
	function test_post_fail_32()
	{
	
		$habilitacion = ['fecha_desde'=>'2016-02-27', 'fecha_hasta'=>'2016-03-25','descripcion'=>'Primer habilitacion','generar_codigo_recuperacion'=>-1];
	
		$result = $this->POSTApi('/habilitaciones', \garbage_data::$unidad_gestion, $habilitacion, \status::$BAD_REQUEST);
	
	}
	
	
	/**
	 * Caso de test que permite realizar un rollback a la configuración inicial
	 */
	function test_deleteAll()
	{
		$sql = "DELETE FROM sge_habilitacion WHERE habilitacion<>100";
		\toba::db()->consultar($sql);
	}
}