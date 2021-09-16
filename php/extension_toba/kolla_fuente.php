<?php

require_once('nucleo/lib/admin_instancia.php');

class kolla_fuente extends toba_fuente_datos
{
	function get_db($reusar=true)
	{
		return admin_instancia::ref()->db();
	}		
}

?>