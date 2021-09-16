<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_abm_encuestados_a_grupos extends bootstrap_ci
{
	protected $s__filtro_usuarios;
    protected $s__filtro_usuarios_sql_clausulas;
    protected $usuarios;
            
    function relacion()
    {
        return $this->controlador()->relacion();
    }

    //-----------------------------------------------------------------------------------
	//---- encuestados ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__encuestados(toba_ei_cuadro $cuadro)
	{
		return $this->relacion()->tabla('encuestados')->get_filas();
	}

	function evt__encuestados__editar()
	{
        unset($this->s__filtro_usuarios);
        unset($this->s__filtro_usuarios_sql_clausulas);
        $this->set_pantalla('pant_agregar');
	}
    
	//-----------------------------------------------------------------------------------
	//---- filtro_usuarios --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_usuarios(toba_ei_filtro $filtro)
	{
        $filtro->columna('grupo_seleccionado')->set_condicion_fija('es_igual_a', true);
        $filtro->columna('grupo')->set_condicion_fija('es_igual_a', true);
        $filtro->columna('habilitacion')->set_condicion_fija('es_igual_a', true);
        $filtro->columna('formulario_habilitado')->set_condicion_fija('es_igual_a', true);
        $filtro->columna('usuario_grupo_acc')->set_condicion_fija('es_igual_a', true);
                
        if (isset($this->s__filtro_usuarios)) {
            return $this->s__filtro_usuarios;
        }
	}

	function evt__filtro_usuarios__filtrar($datos)
	{
        $this->s__filtro_usuarios = $datos;
        $this->s__filtro_usuarios_sql_clausulas = $this->dep('filtro_usuarios')->get_sql_clausulas();
	}

	function evt__filtro_usuarios__cancelar()
	{
        unset($this->s__filtro_usuarios);
        unset($this->s__filtro_usuarios_sql_clausulas);
	}

	//-----------------------------------------------------------------------------------
	//---- seleccion --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__seleccion(toba_ei_cuadro $cuadro)
	{
        if (isset($this->s__filtro_usuarios)) {
        	
            $datos = kolla_db::consultar($this->get_sql_datos($this->get_where_filtro()));
            
            if (empty($datos)) {
                $this->pantalla()->eliminar_evento('agregar_todos');
                $this->pantalla()->eliminar_evento('accion');
            } else {
                if ($this->relacion()->esta_cargada() && $this->s__filtro_usuarios['grupo_seleccionado']['valor'] == 'S') {
                    $this->pantalla()->evento('accion')->set_etiqueta('&Quitar seleccionados');
                    $this->pantalla()->eliminar_evento('agregar_todos');
                } else {
                    $this->pantalla()->evento('accion')->set_etiqueta('Agregar &marcados');
                }
            }
            return $datos;
        } else {
            $this->pantalla()->eliminar_evento('agregar_todos');
            $this->pantalla()->eliminar_evento('accion');
        }
	}

    function evt__seleccion__seleccion_multiple($datos)
	{
        $this->usuarios = $datos;
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__pant_inicial(toba_ei_pantalla $pantalla)
	{
        $this->controlador()->pantalla()->eliminar_evento('volver');
	}
    
    function get_sql_datos($where)
    {
        //Schema de toba para JOIN
        $schema = toba::instancia()->get_db()->get_schema();
        
        return "SELECT DISTINCT     sge_encuestado.encuestado,
                                    LOWER(sge_encuestado.usuario) AS usuario,
                                    sge_encuestado.apellidos,
                                    sge_encuestado.nombres,
                                    apex_usuario_grupo_acc.nombre AS perfil
                FROM                sge_encuestado
                                        LEFT OUTER JOIN sge_encuestado_titulo ON (sge_encuestado.encuestado = sge_encuestado_titulo.encuestado)
                                        JOIN $schema.apex_usuario_proyecto ON ( sge_encuestado.usuario = apex_usuario_proyecto.usuario AND
                                                                                apex_usuario_proyecto.proyecto = 'kolla')
                                        JOIN $schema.apex_usuario_grupo_acc ON (apex_usuario_proyecto.usuario_grupo_acc = apex_usuario_grupo_acc.usuario_grupo_acc AND
                                                                                apex_usuario_grupo_acc.proyecto ='kolla')
                WHERE               $where
                ORDER BY            LOWER(sge_encuestado.usuario)
                ";
    }
    
    function get_where_filtro()
    {
        if (!isset($this->s__filtro_usuarios_sql_clausulas)) {
            return;
        }
        
        $where = $this->s__filtro_usuarios_sql_clausulas;
        $grupo_seleccionado = $this->s__filtro_usuarios['grupo_seleccionado']['valor'];
        
        unset($where['grupo_seleccionado']);

        if (isset($this->s__filtro_usuarios['habilitacion']['valor']) && isset($this->s__filtro_usuarios['formulario_habilitado']['valor'])) {
            $where['que_respondieron'] = "
                EXISTS (SELECT	1
		                FROM	sge_formulario_habilitado AS sfh
		                    	JOIN sge_formulario_habilitado_detalle AS sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		                    	JOIN sge_respondido_encuestado AS sre ON (sre.formulario_habilitado = sfhd.formulario_habilitado)
		                WHERE	{$where['habilitacion']}
		                AND 	{$where['formulario_habilitado']}
		                AND 	sge_encuestado.encuestado = sre.encuestado)
		            ";
        }

        unset($where['habilitacion']);
        unset($where['formulario_habilitado']);

        if (isset($this->s__filtro_usuarios['grupo']['valor'])) {
            $where_grupo_existente = ' AND EXISTS(	SELECT 	1
                                                    FROM 	sge_grupo_detalle AS g
                                                    WHERE 	sge_encuestado.encuestado = g.encuestado
                                                    AND 	'.$where['grupo'].')';
            unset($where['grupo']);
        } else {
            $where_grupo_existente = '';
        }
        
        if (empty($where)) {
            $where = '1=1';
        } else {
            $where = implode(' AND ', array_values($where));
        }

        $where .= $where_grupo_existente;
        
        if ($this->relacion()->esta_cargada()) {
            $grupo = $this->relacion()->tabla('grupo')->get();
            if ($grupo_seleccionado == 'S') {
                $where_grupo = 'g.grupo = '.kolla_db::quote($grupo['grupo']);
                $op = '';
            } else {
                //Quito los que ya están en el grupo
                $where_grupo = 'g.grupo IN ('.kolla_db::quote($grupo['grupo']).')';
                $op = 'NOT';
            }

            $where .= " AND $op EXISTS(	SELECT 	1
            							FROM 	sge_grupo_detalle AS g
            							WHERE 	sge_encuestado.encuestado = g.encuestado
            							AND 	$where_grupo)";
        }
        
        //Manualmente no es posible agregar usuarios de tipo externo
		$where .= ' AND sge_encuestado.externo = '.kolla_db::quote('N');
        return $where;
    }

    function get_combo_grupos_encuestados()
    {
        $grupo_actual = $this->relacion()->tabla('grupo')->get_columna('grupo');
        $grupo_actual = kolla_db::quote($grupo_actual);
        //$filtro_grupos = $this->controlador()->controlador()->dep('filtro_grupos')->get_sql_where();
        $where = "sge_grupo_definicion.grupo <> $grupo_actual ";

        $ug = $this->controlador()->controlador()->get_ug();
        $ug = kolla_db::quote($ug);
        $where .= "AND sge_grupo_definicion.unidad_gestion = $ug";

        return toba::consulta_php('consultas_usuarios')->get_listado($where);
    }
    
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__accion()
	{
        if (!empty($this->usuarios)) {
			foreach ($this->usuarios as $usuario) {
                if ($this->s__filtro_usuarios['grupo_seleccionado']['valor'] == 'S') {
                    $id_fila = $this->relacion()->tabla('encuestados')->get_id_fila_condicion($usuario);
                    $this->relacion()->tabla('encuestados')->eliminar_fila($id_fila[0]);
                } else {
                    $this->relacion()->tabla('encuestados')->nueva_fila($usuario);
                }
			}
			$this->relacion()->sincronizar();
            $this->relacion()->set_cargado(true);
            unset($this->usuarios);
		}
	}

	function evt__agregar_todos()
	{
        $sql = $this->get_sql_datos($this->get_where_filtro());
        $usuarios = kolla_db::consultar($sql);
        
        if (!empty($usuarios)) {
            foreach ($usuarios as $usuario) {
                $this->relacion()->tabla('encuestados')->nueva_fila($usuario);
            }
            $this->relacion()->sincronizar();
            $this->relacion()->set_cargado(true);
        }
	}

}
?>