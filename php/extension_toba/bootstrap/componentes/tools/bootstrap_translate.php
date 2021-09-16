<?php
namespace ext_bootstrap\componentes\tools;

class bootstrap_translate{
	/**
	 *
	 * @var Singleton
	 */
	private static $instance;

	private $diccionario;
	
	public function __construct(){
		
		$this->diccionario = require(__DIR__.'/../../config/dictionary.php');
	}
	
	
	static function instance(){
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function translate($to_translate){
		if (array_key_exists($to_translate, $this->diccionario)){
			return $this->diccionario[$to_translate];
		}
		return $to_translate;
	}
	
}