<?php

require_once('conversion.php');

class conversion_352_353 extends conversion
{
	protected $cambios = array(
        '428', // Se agrega en la definici�n de Grupo el campo para la Unidad de Gesti�n
        '434', // Estructura de tablas para Preguntas Dependientes
        '436', // Modificaci�n en tabla para Reportes Exportados
		'458', // Modificaci�n en tabla para las Habilitaciones
	);
}

?>