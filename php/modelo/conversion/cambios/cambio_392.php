<?php

require_once('cambio.php');

class cambio_392 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 392 : Consumo de servicios web por unidad de gestión';
    }
    
	function cambiar()
	{
        //SE AGREGA LA UNIDAD DE GESTION
        $sqls = array();
        $sqls[] = "ALTER TABLE sge_ws_conexion ADD COLUMN unidad_gestion Varchar";
        $sqls[] = "CREATE INDEX ifk_sge_ws_conexion_sge_unidad_gestion ON sge_ws_conexion (unidad_gestion)";
        $sqls[] = "ALTER TABLE      sge_ws_conexion 
                    ADD CONSTRAINT  fk_sge_ws_conexion_sge_unidad_gestion FOREIGN KEY (unidad_gestion) 
                    REFERENCES      sge_unidad_gestion (unidad_gestion)";
        
        //SE AGREGA UN CAMPO PARA REGISTRAR QUÉ TIPO DE SERVICIO ES
        $sqls[] = "ALTER TABLE sge_ws_conexion ADD COLUMN ws_tipo Char(4)";
        //SETEAR LOS WS EXISTENTES CON EL TIPO EN SOAP
        $sqls[] = "UPDATE sge_ws_conexion SET ws_tipo='soap' WHERE ws_tipo IS NULL";
        $sqls[] = "ALTER TABLE sge_ws_conexion ALTER COLUMN ws_tipo SET DEFAULT 'rest'";
        $sqls[] = "ALTER TABLE sge_ws_conexion ALTER COLUMN ws_tipo SET NOT NULL";
        $sqls[] = "ALTER TABLE sge_ws_conexion ADD CONSTRAINT ck_sge_ws_conexion_ws_tipo CHECK (ws_tipo IN ('rest', 'soap'))";
        
        $this->ejecutar($sqls);
	}

} 
