<?php

class ci_lista_pendientes extends ci_navegacion_por_ug
{	
    protected $id_usuario;
    protected $datos_cuadro;
    
    //-----------------------------------------------------------------------------------
    //---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
	function conf()
	{
        $this->id_usuario   = toba::usuario()->get_id();
        $this->datos_cuadro = $this->dep('cuadro')->get_datos();
	}
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__seleccion(toba_ei_pantalla $pantalla)
	{
        if (!(toba::consulta_php('consultas_usuarios')->es_admin_actual() || toba::consulta_php('consultas_usuarios')->es_gestor_actual())) {
			$pantalla->eliminar_dep('form_unidad_gestion');
		}
	}
    
    //---- filtro -----------------------------------------------------------------------
	
	function conf__filtro(toba_ei_filtro $filtro)
	{
        $filtro->columna('vigente')->set_condicion_fija('es_igual_a', true);
        
		if (isset($this->s__filtro)) {
			$filtro->set_datos($this->s__filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}
    
	//---- cuadro -----------------------------------------------------------------------
	
	function conf__cuadro(toba_ei_cuadro $cuadro) 
	{
		$vigente = isset($this->s__filtro) ? $this->s__filtro['vigente']['valor'] : true;
        
        if (toba::consulta_php('consultas_usuarios')->es_admin_actual() || toba::consulta_php('consultas_usuarios')->es_gestor_actual()) {
			$this->set_ug();
            $unidades_gestion = array(kolla_db::quote($this->s__ug));
		} else {
            $unidades_gestion = toba::consulta_php('consultas_usuarios')->get_unidades_gestion_usuario($this->id_usuario);
            $unidades_gestion = kolla_db::quote(kolla_arreglos::aplanar_matriz_sin_nulos($unidades_gestion));
        }

        // Esto es porque si un usuario "encuesta" no tiene grupo asignado, la UG viene vacía
        // y falla la siguiente consulta.
        if (!empty($unidades_gestion))
        {
            $datos = toba::consulta_php('consultas_usuarios')->get_formularios_para_contestar($this->id_usuario, $vigente, $unidades_gestion);
        } else
        {
            $datos = array();
        }

		$cuadro->set_datos($datos);
		
		/*
		 * En caso de que el usuario sea guest, directamente se elimina el evento
         * para imprimir. Lo que se hace es dejar sólo dicho evento para el momento
         * en que el usuario finalice la encuesta.
		 */
		
		if (toba::consulta_php('consultas_usuarios')->es_guest_actual()) {
			$cuadro->eliminar_evento('pdf');
			$cuadro->eliminar_columnas(array('anonima'));
		}
        
        if (!$vigente) {
            $cuadro->eliminar_evento('seleccion');
        }
	}
	
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