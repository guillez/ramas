<?php

require_once('conversion.php');

class conversion_341_342 extends conversion
{
	protected $cambios = array(
        '336', // Tabla para almacenar qui�n fue el usuario que respondi� en nombre de otro
        '341', // Migrar las tablas definidas por el usuario
        '364'  // Incorporar perfiles de datos (tablas nuevas y columnas nuevas en tablas)
	);
}

?>