<?php

require_once('cambio.php');

class cambio_385 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 385 : Incorporación de Unidad de Gestión por defecto para el sistema';
    }
    
    function cambiar()
    {
	$sql = "SELECT EXISTS (SELECT 1 FROM sge_unidad_gestion WHERE unidad_gestion = '0') AS existe";
	$res = $this->consultar_fila($sql);
	if ( !$res['existe'] ) {
	        $sql = "INSERT INTO sge_unidad_gestion (unidad_gestion, nombre) VALUES (0, 'Unidad de Gestión Predeterminada')";
		$this->ejecutar($sql);
	}        
        // Se asigna la unidad de gestión por defecto a todo entidad existente en la base que aún no tenga una unidad de gestión
        $sqls = array();
        $sqls[] = "UPDATE sge_encuesta_atributo SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_concepto          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_elemento          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_pregunta          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_respuesta         SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        
        $this->ejecutar($sqls);
    }

} 