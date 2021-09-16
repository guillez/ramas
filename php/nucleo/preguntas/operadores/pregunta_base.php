<?php

abstract class pregunta_base
{
    protected $_condiciones = array();
            
    function __construct()
    {
        $this->ini();
    }
    
    abstract function ini();
    
    function agregar_condicion($id, pregunta_condicion $condicion)
	{
		$this->_condiciones[$id] = $condicion;
	}
    
    function get_condicion($condicion)
    {
        return $this->_condiciones[$condicion];
    }
            
    function get_condiciones_combo()
    {
        $condiciones = array();
        foreach ($this->_condiciones as $id => $condicion)
        {
            $condiciones[] = array('condicion' => $id, 'etiqueta' => $condicion->get_etiqueta());
        }
        return $condiciones;
    }
}