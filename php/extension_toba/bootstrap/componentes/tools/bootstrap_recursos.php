<?php
namespace ext_bootstrap\componentes\tools;

class bootstrap_recursos {
	
	static function ayuda($tecla, $ayuda='', $clases_css='', $delay_ayuda=1000)
	{
		$ayuda_extra = '';
        $access = '';
		$a = 'data-toggle="tooltip" data-placement="top" ';
		
		$texto = '';
		
		if ($tecla !== null) {
			$ayuda_extra = "[alt + shift + $tecla]";
			$access = "accesskey='$tecla'";
		}
		
		if ( $ayuda != '' )
			$texto .= $ayuda;
		
		if ( $ayuda_extra != '')
			$texto .= ' '. $ayuda_extra;
		
		$a .= " title='$texto' " . $access;
		
			
		if ($clases_css != "") {
			$a .= " class='$clases_css'";
		}
		return $a;
	}
	
}