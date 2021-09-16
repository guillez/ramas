<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;
use SIUToba\rest\lib\rest_validador;

class rest_conceptos extends rest_base
{
    /**
     * @var co_conceptos
     */
    protected $modelo;
    
    /**
     * @var act_conceptos
     */
    protected $modelo_act;
    
    function __construct() 
    {
        $this->modelo     = kolla::co('co_conceptos');
        $this->modelo_act = kolla::abm('act_conceptos');
    }
    
    protected function get_modelo($nombre)
	{
		$modelos = recurso_conceptos::_get_modelos();
		return $modelos[$nombre];
	}
    
    /**
     * Retorna los datos de un concepto
     */
    function get($id_concepto)
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);
	    $filtro->agregar_campo_simple_local('concepto', 'concepto_externo = %s', $id_concepto);

	    $rs = $this->modelo->get_listado_rest($filtro->get_sql_where());

        if ( !$rs ) {
            throw new rest_error(404, 'El concepto no existe');
        }

        $campos = $this->get_modelo('Concepto');

		return rest_hidratador::hidratar_fila($campos, $rs[0]);
    }
    
    function get_list()
    {
        $filtro = new rest_filtro_sql();
	    $this->filtrar_ug_sistema($filtro);
        
        $limit = $filtro->get_sql_limit(20);
        $order = $filtro->get_sql_order_by('+sge_concepto.concepto_externo');
		$where = $filtro->get_sql_where();
		$rs = $this->modelo->get_listado_rest($where, $order, $limit);
        
		return rest_hidratador::hidratar($this->get_modelo('Concepto'), $rs);
    }

	/**
	 * Internamente el actualizador solo modifica la descripcion del concepto
	 */
	function put($id_concepto, $data)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
        
        $spec = $this->get_modelo('ConceptoAct');

        rest_validador::validar($data, $spec, true);
        $modelo = rest_hidratador::deshidratar_fila($data, $spec);

        $modelo['unidad_gestion'] = $unidad_gestion;
		$modelo['sistema'] = $sistema;

		return $this->modelo_act->crear_o_actualizar_concepto($id_concepto, $modelo);
	}

	function put_masivo($conceptos)
	{
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();

		$spec = $this->get_modelo('Concepto');

		$resultados = array();
		foreach ($conceptos as $indice => $concepto) {
			try {
				rest_validador::validar($concepto, $spec, false); //tiene que mandar todo!
                $modelo = rest_hidratador::deshidratar_fila($concepto, $spec);
                
                $modelo['unidad_gestion'] = $unidad_gestion;
				$modelo['sistema'] = $sistema;
                $this->modelo_act->crear_o_actualizar_concepto($modelo['concepto_externo'], $modelo);
			} catch(rest_error $e) {
				$resultados[] = array('indice' => $indice, 'error' => $e->getMessage(), 'detalle' => $e->get_datalle());
			}
		}
		return $resultados;
	}

	function delete($id_concepto)
    {
		$unidad_gestion = $this->_get_ug();
		$sistema = $this->_get_sistema();
		
		$this->get($id_concepto); // Sino ya tiro un 404
		$this->modelo_act->eliminar_concepto($id_concepto, $unidad_gestion, $sistema);
	}
    
}