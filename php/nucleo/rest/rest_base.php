<?php

use SIUToba\rest\lib\rest_error;
use SIUToba\rest\rest;

class rest_base
{
	protected $ug, $sistema;

    function _get_sistema()
    {
	    if(!isset($this->sistema)){
		    $usuario = rest::usuario()->get_usuario();
            
		    $sql = "SELECT * FROM sge_sistema_externo WHERE usuario = " . kolla_db::quote($usuario)." AND estado='A'";
		 
		    $sistema = kolla_db::consultar_fila($sql);
            
		    if ( empty($sistema) ) {
			    throw new rest_error(400, 'El sistema no existe o esta dado de baja');
		    }

		    $this->sistema = $sistema['sistema'];

	    }
	    return $this->sistema;

    }

	function _get_ug($requerida = true){
        
		if(!isset($this->ug)){
            
			$ug = rest::request()->get('unidad_gestion', null);
			if ( $ug == null  && $requerida) {
				throw new rest_error(400, 'La unidad de gestión es requerida.');
			}

			$sql = "SELECT * FROM sge_unidad_gestion WHERE unidad_gestion = " . kolla_db::quote($ug);
			$datos = kolla_db::consultar_fila($sql);

			if ( empty($datos) ) {
				throw new rest_error(404, 'La unidad de gestión no existe.');
			}
			$this->ug =  $ug;
		}
		return $this->ug;

	}

	function filtrar_ug_sistema(\SIUToba\rest\lib\rest_filtro_sql $filtro, $alias_ug = 'unidad_gestion', $alias_sis = 'sistema')
    {
		if(!isset($this->ug)) $this->_get_ug();
		$filtro->agregar_campo_simple_local($alias_ug, "unidad_gestion = %s",  $this->ug);
		if(!isset($this->sistema)) $this->_get_sistema();
		$filtro->agregar_campo_simple_local($alias_sis, "sistema = %s", $this->sistema);
	}
    
	function filtrar_sistema(\SIUToba\rest\lib\rest_filtro_sql $filtro, $alias_sis = 'sistema', $campo = 'sistema')
    {
		if(!isset($this->sistema)) $this->_get_sistema();
		$filtro->agregar_campo_simple_local($alias_sis, "$campo = %s", $this->sistema);
	}
    
	function filtrar_ug(\SIUToba\rest\lib\rest_filtro_sql $filtro, $alias_ug = 'unidad_gestion', $campo = 'unidad_gestion')
    {
		if(!isset($this->ug)) $this->_get_ug();
		$filtro->agregar_campo_simple_local($alias_ug, "$campo = %s", $this->ug);
	}

}
