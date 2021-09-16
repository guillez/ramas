<?php
class cuadro_recuperacion extends toba_ei_cuadro
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
    /*
     * Modificacion en PHP a los vinculos de las FILAS
     */
	function conf_evt__ver($evento, $fila)
    {
		$id_enc = $this->datos[$fila]['formulario_encabezado'];
	    $id_form = $this->datos[$fila]['formulario_habilitado'];
	    $evento->vinculo()->agregar_parametro('formulario_habilitado', $id_form);
	    $evento->vinculo()->agregar_parametro('encabezado_formulario', $id_enc);
    }

}
?>