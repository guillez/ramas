<?php
class ci_completar_in_site extends ci_navegacion
{
    protected $id_usuario;
    protected $datos_cuadro;
    
    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
	function conf()
	{
        $this->id_usuario = toba::usuario()->get_id();
        $this->datos_cuadro = $this->dep('cuadro')->get_datos();
	}
    
    
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    //---- cuadro -----------------------------------------------------------------------
    
    function conf__cuadro(toba_ei_cuadro $cuadro) 
	{
		$datos = toba::consulta_php('consultas_usuarios')->get_formularios();
        $cuadro->set_datos($datos);
	}
    
    /*function evt__cuadro__seleccion($seleccion)
	{
        toba::memoria()->set_dato_operacion('clave_fila', $seleccion);
        $this->s__seleccion = $seleccion;
        $this->set_pantalla('edicion');
	}*/
    
    function conf_evt__cuadro__pdf(toba_evento_usuario $evento, $fila)
	{
        if ($this->datos_cuadro[$fila]['anonima'] == 'N') {
            $respondio = toba::consulta_php('consultas_usuarios')->ya_respondio($this->id_usuario, $this->datos_cuadro[$fila]['formulario']);
            
            if (!$respondio) {
                $evento->desactivar();
            } else {
                $evento->activar();
            }
        } else {
            $evento->desactivar();
        }
	}

    function conf_evt__cuadro__seleccion(toba_evento_usuario $evento, $fila)
	{
        $formulario = $this->datos_cuadro[$fila]['formulario'];
        $anonima    = $this->datos_cuadro[$fila]['anonima'] == 'N' ? false : true;
        $respondio  = toba::consulta_php('consultas_usuarios')->ya_respondio($this->id_usuario, $formulario, $anonima);
        
        if ($respondio) {
            $evento->desactivar();
        } else {
            $evento->activar();
        }
	}
    
    function conf_evt__cuadro__ver(toba_evento_usuario $evento, $fila)
	{
        $formulario = $this->datos_cuadro[$fila]['formulario'];
        $anonima    = $this->datos_cuadro[$fila]['anonima'] == 'N' ? false : true;
        $respondio  = toba::consulta_php('consultas_usuarios')->ya_respondio($this->id_usuario, $formulario, $anonima);
        
        if (!$respondio) {
            $evento->desactivar();
        } else {
            $evento->activar();
        }
	}
	
}
?>