<?php

abstract class cache
{
	abstract function get_tipo();
	abstract function existe($id);
	abstract function guardar($id, $datos);
	abstract function buscar($id);
	abstract function eliminar($id);
        
        abstract function disponible();
	
}
?>