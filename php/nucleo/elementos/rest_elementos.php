<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;
use SIUToba\rest\lib\rest_validador;

class rest_elementos extends rest_base
{
    
    /**
     * @var co_elementos
     */
    protected $modelo;
    
    /**
     * @var act_elementos
     */
    protected $modelo_act;
    
    function __construct() 
    {
        $this->modelo     = kolla::co('co_elementos');
        $this->modelo_act = kolla::abm('act_elementos');
    }
    
    protected function get_modelo($nombre)
	{
		$modelos = recurso_elementos::_get_modelos();
		return $modelos[$nombre];
	}
    
    /**
     * Retorna los datos de un elemento
     */
    function get($id_elemento)
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);
	    $filtro->agregar_campo_simple_local('elemento', 'elemento_externo = %s', $id_elemento);

	    $rs = $this->modelo->get_listado_rest($filtro->get_sql_where());

        if ( !$rs ) {
            throw new rest_error(404, 'El elemento no existe');
        }
        //toba::logger()->error($rs);
        $campos = $this->get_modelo('Elemento');
        //toba::logger()->error($campos);

		return rest_hidratador::hidratar_fila($campos, $rs[0]);
    }
    
    function get_list()
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);

        //$limit = $filtro->get_sql_limit(20);
        $order = $filtro->get_sql_order_by('+sge_elemento.elemento_externo');
		$where = $filtro->get_sql_where();

		$rs = $this->modelo->get_listado_rest($where, $order);
        
		return rest_hidratador::hidratar($this->get_modelo('Elemento'), $rs);
    }
    
    /**
     * Internamente el actualizador solo modifica la descripcion del elemento
     */
    function put($id_elemento, $data)
    {        
	    $unidad_gestion = $this->_get_ug();
	    $sistema = $this->_get_sistema();

        $spec = $this->get_modelo('ElementoAct');
        
	    rest_validador::validar($data, $spec, true);
	    $modelo = rest_hidratador::deshidratar_fila($data, $spec);
        
        $modelo['unidad_gestion'] = $unidad_gestion;
		$modelo['sistema'] = $sistema;

		return $this->modelo_act->crear_o_actualizar_elemento($id_elemento, $modelo);
    }
    
    function put_masivo($elementos)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();

		$spec = $this->get_modelo('Elemento');

		$resultados = array();
		foreach ($elementos as $indice => $elemento) {
			try {
				rest_validador::validar($elemento, $spec, false); // Tiene que mandar todo!
				$modelo = rest_hidratador::deshidratar_fila($elemento, $spec);

				$modelo['unidad_gestion'] = $unidad_gestion;
				$modelo['sistema'] = $sistema;
				$this->modelo_act->crear_o_actualizar_elemento($modelo['elemento_externo'], $modelo);
			} catch (rest_error $e) {
				$resultados[] = array('indice' => $indice, 'error' => $e->getMessage(), 'detalle' => $e->get_datalle());
			}
		}
		return $resultados;
	}

	function delete($id_elemento)
    {
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
		$this->get($id_elemento); // Sino ya tiro un 404
		$this->modelo_act->eliminar_elemento($id_elemento, $unidad_gestion, $sistema);
	}
    
}