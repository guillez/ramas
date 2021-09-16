<?php

require_once('conversion.php');

class conversion_312_342 extends conversion
{
    protected $cambios = array(
        '317', //Creación de estructura de la base de datos.
        '334', //insertar datos iniciales de la base
        '255', //migrar usuarios, grupos y encuestas 
        '318', //secuencias, fk, restricciones, vistas
        '341', //migrar las tablas definidas por el usuario
        '335'  //nuevo esquema
	);

}
?>