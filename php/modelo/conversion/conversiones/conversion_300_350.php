<?php

require_once('conversion.php');

class conversion_300_350 extends conversion
{
	protected $cambios = array(
        '317', //Creaci�n de estructura de la base de datos.
        '334', //insertar datos iniciales de la base
        '385a', //unidad de gesti�n por defecto
        '333',//migrar usuarios, grupos y encuestas 
        '385b', //unidad de gesti�n por defecto asignada a las entidades
        '318', //secuencias, fk, restricciones, vistas
        '341', //migrar las tablas definidas por el usuario
        '335' //nuevo esquema
	);  
    
}

?>