<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_lista_publicas extends bootstrap_ci
{	
    protected $s__filtro;


	//---- listado -----------------------------------------------------------------------

	function conf__listado(toba_ei_cuadro $cuadro)
    {
        if (isset($this->s__filtro)) {
            $habilitacion = $this->s__filtro['habilitacion'];
            $datos = toba::consulta_php('consultas_formularios')->get_formularios_habilitados_habilitacion($habilitacion);
            foreach ($datos as $key => $form_hab) {
                $datos[$key]['url_encuesta'] = $this->armar_acceso($form_hab['habilitacion'], $form_hab['formulario_habilitado']);
            }
            $cuadro->set_datos($datos);
        }
	}

    private function armar_acceso($habilitacion, $formulario_habilitado){
        return toba_http::get_nombre_servidor().toba_parametros::get_redefinicion_parametro('kolla', 'url', false).'/responder?h='.$habilitacion.'&f='.$formulario_habilitado;
    }

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

    function conf__formulario()
    {
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }


	function evt__formulario__filtrar($datos)
	{
        if (isset($datos)) {
            $this->s__filtro = $datos;
        }
    }

    function evt__formulario__cancelar()
    {
	    unset($this->s__filtro);
    }

}
?>