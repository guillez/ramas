<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;
use SIUToba\rest\lib\rest_validador;

class rest_tipo_elementos extends rest_base
{
    
    /**
     * @var co_tipo_elementos
     */
    protected $modelo;
    
    /**
     * @var act_tipo_elementos
     */
    protected $modelo_act;
    
    function __construct() 
    {
        $this->modelo     = kolla::co('co_tipo_elementos');
        $this->modelo_act = kolla::abm('act_tipo_elementos');
    }
    
    protected function get_modelo($nombre)
	{
		$modelos = recurso_tipo_elementos::_get_modelos();
		return $modelos[$nombre];
	}
    
    /**
     * Retorna los datos de un tipo de elemento
     */
    function get($id_tipo_elemento)
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);
	    $filtro->agregar_campo_simple_local('tipo_elemento', 'tipo_elemento_externo = %s', $id_tipo_elemento);

	    $rs = $this->modelo->get_listado_rest($filtro->get_sql_where());

        if ( !$rs ) {
            throw new rest_error(404, 'El tipo de elemento no existe');
        }
        //toba::logger()->error($rs);
        $campos = $this->get_modelo('TipoElemento');
        //toba::logger()->error($campos);

		return rest_hidratador::hidratar_fila($campos, $rs[0]);
    }
    
    function get_list()
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);

        //$limit = $filtro->get_sql_limit(20);
        $order = $filtro->get_sql_order_by('+sge_tipo_elemento.tipo_elemento_externo');
		$where = $filtro->get_sql_where();

		$rs = $this->modelo->get_listado_rest($where, $order);
        
		return rest_hidratador::hidratar($this->get_modelo('TipoElemento'), $rs);
    }
    
    /**
     * Internamente el actualizador solo modifica la descripcion del tipo de elemento
     */
    function put($id_tipo_elemento, $data)
    {        
	    $unidad_gestion = $this->_get_ug();
	    $sistema = $this->_get_sistema();

        $spec = $this->get_modelo('TipoElementoAct');
        
	    rest_validador::validar($data, $spec, true);
	    $modelo = rest_hidratador::deshidratar_fila($data, $spec);
        
        $modelo['unidad_gestion'] = $unidad_gestion;
		$modelo['sistema']        = $sistema;

		return $this->modelo_act->crear_o_actualizar_tipo_elemento($id_tipo_elemento, $modelo);
    }
    
    function put_masivo($tipo_elementos)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();

		$spec = $this->get_modelo('TipoElemento');

		$resultados = array();
		foreach ($tipo_elementos as $indice => $tipo_elemento) {
			try {
				rest_validador::validar($tipo_elemento, $spec, false); // Tiene que mandar todo!
				$modelo = rest_hidratador::deshidratar_fila($tipo_elemento, $spec);

				$modelo['unidad_gestion'] = $unidad_gestion;
				$modelo['sistema']        = $sistema;
				$this->modelo_act->crear_o_actualizar_tipo_elemento($modelo['tipo_elemento_externo'], $modelo);
			} catch (rest_error $e) {
				$resultados[] = array('indice' => $indice, 'error' => $e->getMessage(), 'detalle' => $e->get_datalle());
			}
		}
		return $resultados;
	}

	function delete($id_tipo_elemento)
    {
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
		$this->get($id_tipo_elemento); // Sino ya tiro un 404
		$this->modelo_act->eliminar_tipo_elemento($id_tipo_elemento, $unidad_gestion, $sistema);
	}
    
}