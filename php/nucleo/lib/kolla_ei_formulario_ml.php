<?php

/**
 * Esta clase agrega métodos utiles para el uso del formulario multilinea.
 * 
 * @author Germán Lodovskis
 * @category Extension Toba
 * @version 1.0.0
 */

class kolla_ei_formulario_ml extends \bootstrap_ml_formulario
{
	/**
	 * No permite que el usuario pueda ordenar las filas en el cliente
	 */
    function desactivar_ordenamiento_filas()
	{
	    $this->_info_formulario['filas_ordenar'] = false;
    }
    
}