<?php

require_once('conversion.php');

class conversion_351_352 extends conversion
{
	protected $cambios = array(
        '393', //Campo tipo_elemento_externo para la tabla sge_tipo_elemento
        '413', //Se quita obligatoriedad del campo sge_encuestado.clave
		'421', //Se setea la FK formulario_habilitado de sge_formulario_habilitado_detalle como deferrable
        '423'  //Modificar el nombre de la unidad de gestión 0
	);
}

?>