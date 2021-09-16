<?php
include("basetest_url.php");
class test_url_local extends basetest_url
{

	
	function test_encripcion_url()
	{
		$this->init('Test encripción url');
		$id_hab = 3;
		$form = 'FORM2';
		$cui = date("His").rand(1,1000000);
		$url = new url_encuestas($id_hab);
		$pass_hab = $this->getPassword($id_hab);
        
        $hash = url_encuestas::get_token_seguridad($pass_hab, $id_hab, $form, $cui, 'ver_respuestas', 123);
		
		$servidor = 'http://' . $_SERVER['SERVER_NAME'];
		$servidor .= texto_plano($_SERVER["PHP_SELF"]);
		$url_p  = url_encuestas::gen_url($servidor, $id_hab, $hash);
		
		echo '<br />';
		echo "password: $pass_hab /password";
		echo '<br />';
		echo '<br />';
		echo ('La url es: ');
		echo $url_p;

        // El urldecode no debe hacerse cuando la url viene del addres ya que se decodificar por el server
		$param = url_encuestas::decodificar_token_seguridad(urldecode($hash), $pass_hab);

		echo '<br />';
		echo ('Encrypted: <br />');
		var_dump($param);
		$this->end();
	}
	
	function atest_encripcion_url()
	{
		$this->init('Test encripción url');
		$id_hab = 48;
		$elemento = '1';
		$cui = date("His").rand(1,1000000);
		$url = new url_encuestas($id_hab);
		$pass_hab = $url->get_password_existente($id_hab);
		$hash = $url->get_token_seguridad($pass_hab, $id_hab, $elemento, $cui, true);
		
		$servidor = 'http://' . $_SERVER['SERVER_NAME'];
		$servidor .= texto_plano($_SERVER["PHP_SELF"]);
		$url_p  = url_encuestas::gen_url($servidor, $id_hab, $hash);
		echo '<br />';
		echo "password: $pass_hab";
		echo '<br />';
		echo '<br />';
		echo ('El parametro es');
		echo $url_p;
		//echo($hash);

		$param = $url->decodificar_token_seguridad(urldecode($hash));//el urldecode no
		//debe hacerse cuando la url viene del addres ya que se decodificar por el server

		echo '<br />';
		echo ('Encrypted: <br />');
		var_dump($param); // "ey7zu5zBqJB0rGtIn5UB1xG03efyCp+KSNR4/GAv14w="
		$this->end();
	}

	function atest_encripcion_url2()
	{
		$this->init('Test encripción url');
		$id_hab = 179;
		$elemento = '';
		$cui = '558';
		$url = new url_encuestas($id_hab);
		$hash = $url->get_token_seguridad($url->get_password_existente($id_hab), $id_hab, $elemento, $cui, true);
		$servidor = 'http://' . $_SERVER['SERVER_NAME'];
		$servidor .= texto_plano($_SERVER["PHP_SELF"]);
		$url_p  = url_encuestas::gen_url($servidor, $id_hab, $hash);
		echo '<br />';
		echo ('El parametro es');
		echo $url_p . $hash;
		$param = $url->decodificar_token_seguridad(urldecode($hash));

		echo '<br />';
		echo ('Encrypted: <br />');
		var_dump($param); // "ey7zu5zBqJB0rGtIn5UB1xG03efyCp+KSNR4/GAv14w="
		$this->end();
	}
	
	function atest_generacion_url_jmeter()
	{
/*		3
url	ai=kolla||40000112&tm=1
password	276014e50e122b055e7365c3da5b7561*/

		$id_hab = 3;
		$elemento = '1';
		
		$this->init("Datos para pegar en el csv del jmeter. Cheuqear que este habilitada
				la habilitacion $id_hab, con el elemento $elemento, o cambiar estos parametros en el test");

		$url = new url_encuestas($id_hab);
		$pass_hab ='276014e50e122b055e7365c3da5b7561'; // $url->get_password_existente($id_hab);
		$cui = '29';
		
		$a = microtime(true);
		$salt = date("ymdHis");
		for($i = 1; $i < 5000; $i++){
			$cui = $salt.$i;
			$hash = $url->get_token_seguridad($pass_hab, $id_hab, $elemento, $cui, false);
			$url_p  = url_encuestas::gen_url("", $id_hab, $hash);
			//echo '<br />';
			//echo $url_p;
		}
		echo "<br />Tiempo para $i: ".(microtime(true) - $a);
		
		$param = $url->decodificar_token_seguridad(urldecode($hash));//el urldecode no
		//debe hacerse cuando la url viene del addres ya que se decodificar por el server

		echo '<br />';
		echo ('Encrypted: <br />');
		var_dump($param); // "ey7zu5zBqJB0rGtIn5UB1xG03efyCp+KSNR4/GAv14w="
		$this->end();
	}
	

}
?>