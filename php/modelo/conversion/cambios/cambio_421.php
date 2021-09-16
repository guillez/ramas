<?php

require_once('cambio.php');

class cambio_421 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 421 : Se setea la FK formulario_habilitado de sge_formulario_habilitado_detalle como deferrable.';
	}

	function cambiar()
	{
        $sql = 'ALTER TABLE sge_formulario_habilitado_detalle
        		DROP CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado;
        		 
				ALTER TABLE sge_formulario_habilitado_detalle 
				ADD CONSTRAINT fk_sge_formulario_habilitado_detalle_sge_formulario_habilitado FOREIGN KEY (formulario_habilitado) 
				REFERENCES sge_formulario_habilitado (formulario_habilitado) deferrable;
				';
        
		$this->ejecutar($sql);
	}
}