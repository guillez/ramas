<?php
namespace ext_bootstrap\componentes\efs;

class bootstrap_ef_fieldset extends \toba_ef_fieldset
{
	function get_input()
	{
		if(! $this->fin){
			echo "<fieldset title='{$this->etiqueta}'>";
			if (trim($this->etiqueta) != ''){
				echo "<legend>{$this->etiqueta}</legend>";
			}//if
		} else {
			echo "</fieldset>";
		}//if externo
	}
}