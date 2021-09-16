<?php

class ci_responder_por_encuestado extends ci_navegacion_por_ug
{
	protected $s__filtro;
	protected $s__usuario;
	
    //-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function conf__filtro(toba_ei_filtro $filtro)
	{
        try {
			toba::instalacion()->get_datos_smtp();
		} catch (toba_error $e) {
			$filtro->evento('filtrar')->desactivar();
			toba::notificacion()->error('Necesita predeterminar una conexión SMTP en Configuración > Configuración de Mails.');
		}
        
		$filtro->columna('usuario')->borrar_condicion('es_distinto_de');
		$filtro->columna('usuario')->borrar_condicion('no_contiene');
		$filtro->columna('documento_numero')->set_condicion_fija('es_igual_a', true);
		$filtro->columna('nombres')->borrar_condicion('es_distinto_de');
		$filtro->columna('nombres')->borrar_condicion('no_contiene');
		$filtro->columna('apellidos')->borrar_condicion('es_distinto_de');
		$filtro->columna('apellidos')->borrar_condicion('no_contiene');
		
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

	//-----------------------------------------------------------------------------------
	//---- usuarios ---------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__usuarios(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
			$cuadro->set_datos(toba::consulta_php('consultas_usuarios')->get_lista_encuestados_filtro($this->dep('filtro')->get_sql_where()));
		}
	}

	function evt__usuarios__seleccion($seleccion)
	{
		$this->s__usuario = $seleccion['usuario'];
		$this->set_pantalla('pant_encuestas');
	}
	
	//-----------------------------------------------------------------------------------
	//---- encuestas --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf_evt__encuestas__pdf(toba_evento_usuario $evento, $fila)
	{
		$datos = $this->dep('encuestas')->get_datos();
        
        if ($datos[$fila]['anonima'] == 'N') {
            $respondio = toba::consulta_php('consultas_usuarios')->ya_respondio($datos[$fila]['usuario_encuestado'], $datos[$fila]['formulario']);
            if (!$respondio) {
                $evento->anular();
            }
        } else {
            $evento->anular();
        }
	}
	
	function conf__encuestas(toba_ei_cuadro $cuadro)
	{
        $this->set_ug();
		$datos = toba::consulta_php('consultas_usuarios')->get_formularios_para_contestar($this->s__usuario, true, array(kolla_db::quote($this->s__ug)));
		$usuario = toba::consulta_php('consultas_usuarios')->get_encuestado_por_usuario($this->s__usuario);
        
		$cuadro->set_titulo('Formularios habilitados para contestar por '.$usuario['apellidos'].', '.$usuario['nombres']);
		
		if (!empty($datos)) {
			$cuadro->set_datos($datos);
		} else {
			$cuadro->set_eof_mensaje('No existen habilitaciones para el usuario seleccionado');
		}
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cancelar()
	{
		unset($this->s__usuario);	
		$this->set_pantalla('pant_usuarios');
	}	

}

?>