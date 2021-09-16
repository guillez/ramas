<?php

require_once('conversion.php');

class conversion_310_350 extends conversion
{
	protected $cambios = array(
        '317', //Creación de estructura de la base de datos.
        '334', //insertar datos iniciales de la base
        '385a', //unidad de gestión por defecto
        '337',//migrar usuarios, grupos y encuestas 
        '385b', //unidad de gestión por defecto asignada a las entidades
        '318', //secuencias, fk, restricciones, vistas
        '341', //migrar las tablas definidas por el usuario
        '335' //nuevo esquema
	);  
    
}

?>