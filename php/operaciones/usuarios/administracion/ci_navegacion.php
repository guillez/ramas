<?php

use ext_bootstrap\componentes\bootstrap_ci;

require __DIR__."/../../../../vendor/siu-toba/framework/proyectos/toba_usuarios/php/lib/rest_arai_usuarios.php";

class ci_navegacion extends bootstrap_ci
{
	protected $s__filtro;
    protected $s__perfiles;
            
    function ini__operacion()
	{
		toba::solicitud()->set_autocomplete(false); // Evita que el browser quiera guardar la clave de usuario
        $this->s__perfiles = toba::usuario()->get_perfiles_funcionales();
	}
    
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__guardar()
	{
		$encuestado = $this->relacion()->tabla('sge_encuestado')->get_columna('encuestado');
		if (!isset($encuestado)) {
			$usuario = $this->relacion()->tabla('sge_encuestado')->get_columna('usuario');
			if (toba::consulta_php('consultas_usuarios')->existe_usuario($usuario)) {
				$mje_error = $this->get_mensaje('existe_usuario');
				throw new toba_error($mje_error);
			}
		}
		
		try {
            $this->relacion()->persistidor()->desactivar_transaccion();
            toba::db()->abrir_transaccion();
			$this->relacion()->sincronizar();

			// Se realiza el guardado de ARAI
            if (toba::instalacion()->vincula_arai_usuarios()) {
                $usuario_arai = $this->dep('editor')->get_usuario_arai();
                if (isset($usuario_arai)) {
                    $datos = $this->dep('editor')->datos('apex_usuario')->get();

                    gestion_arai::sincronizar_datos($datos['usuario'], $usuario_arai);
                }
            }

            toba::db()->cerrar_transaccion();
		} catch (toba_error $e) {
			toba::logger()->info($e->getMessage());
            toba::db()->abortar_transaccion();
			throw new toba_error($e->getMessage());
		}
		
        $this->dep('editor')->resetear_ug();
		$this->relacion()->resetear();
		$this->set_pantalla('seleccionar');
	}

	function evt__cancelar()
	{
		$this->relacion()->resetear();
		$this->dep('editor')->resetear();
		$this->set_pantalla('seleccionar');
	}

	function evt__agregar()
	{
		$this->set_pantalla('editar');
	}

	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__filtro__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	function conf__filtro(toba_ei_filtro $filtro)
	{
        $filtro->columna('apellidos')->set_condicion_fija('contiene', true);
        $filtro->columna('nombres')->set_condicion_fija('contiene', true);
        $filtro->columna('usuario')->set_condicion_fija('contiene', true);
        $filtro->columna('usuario_grupo_acc')->set_condicion_fija('es_igual_a', true);
        
		if (isset($this->s__filtro)) {
			$filtro->set_datos($this->s__filtro);
		}
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro)) {
			$datos = toba::consulta_php('consultas_usuarios')->get_lista_encuestados($this->get_where());
			$cuadro->set_datos($datos);
		}
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->relacion()->cargar($seleccion);	
		$this->set_pantalla('editar');
	}
	
	function conf_evt__cuadro__seleccion(toba_evento_usuario $evento, $fila)
	{
        $datos = $this->dep('cuadro')->get_datos();
        if (in_array('gestor', $this->s__perfiles)) {
            if (in_array($datos[$fila]['nombre_grupo'], array('admin', 'gestor'))) {
                $evento->anular();
            }
        }
	}
    
    /**
     * @return toba_datos_relacion
     */
    function relacion()
    {
        return $this->dep('datos');
    }
    
    function get_where()
    {
        return $this->dep('filtro')->get_sql_where();
    }

}
?>