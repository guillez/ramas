<?php
namespace ext_bootstrap\componentes\efs;

class bootstrap_ef_barra_divisora extends \toba_ef_barra_divisora
{
	function get_input()
	{
		echo "<div class='divisor text-right'> <label>{$this->etiqueta}</label></div>";
		
	}
}