<?php

require_once('conversion.php');

class conversion_352_353 extends conversion
{
	protected $cambios = array(
        '428', // Se agrega en la definición de Grupo el campo para la Unidad de Gestión
        '434', // Estructura de tablas para Preguntas Dependientes
        '436', // Modificación en tabla para Reportes Exportados
		'458', // Modificación en tabla para las Habilitaciones
	);
}

?>