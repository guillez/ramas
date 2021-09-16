<?php
	
	echo toba_js::abrir();
	echo "
			var opciones = {'width': 1000, 'scrollbars' : 1, 'height': 600, 'resizable': 1};
			abrir_popup('usuarios', '".URL_WIKIKOLLA."', opciones);
		";
	echo toba_js::cerrar();

?>