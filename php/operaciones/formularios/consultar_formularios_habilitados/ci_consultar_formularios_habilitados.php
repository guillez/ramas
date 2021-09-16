<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_consultar_formularios_habilitados extends bootstrap_ci{
	
    protected $s__filtro;
    
    //-----------------------------------------------------------------------------------
    //---- formulario -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__formulario(toba_ei_cuadro $cuadro)
    {
        if(isset($this->s__filtro)) {
            $datos = toba::consulta_php('consultas_habilitaciones')->get_lista_formularios($this->s__filtro);
            if (isset($datos[0])) {
                $nombre_form = $datos[0]['nombre_form'];
                $cuadro->set_titulo("Formulario: ".$nombre_form);                
            }            
            return $datos;
        }
    }
	
    function conf__filtro()
    {
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }
	
    function evt__filtro__filtrar($datos)
    {
        if (isset($datos)) {
            $this->s__filtro = $datos;
        }
    }
	
    function evt__filtro__cancelar() 
    {
        unset($this->s__filtro);
    }

}

?>