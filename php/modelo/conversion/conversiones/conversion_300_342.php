<?php

require_once('conversion.php');

class conversion_300_342 extends conversion
{
	protected $cambios = array(
		'317', //Creación de estructura de la base de datos
        '334', //insertar datos iniciales de la base
        '333', //migrar usuarios, grupos y encuestas
        '339', //registrar conexión a Guarani 2
        '318', //secuencias, fk, restricciones, vistas
        '341', //migrar las tablas definidas por el usuario
        '335'  //apuntar al nuevo esquema de BD
	);
}

?>